<?php

namespace App\Traits;

use App\Models\Activity;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

trait LogsActivity
{
	public static function bootLogsActivity(): void
	{
		static::created(function ($model) {
			self::logActivity($model, 'created');
		});

		static::updated(function ($model) {
			$rawChanges = $model->getChanges();
			unset($rawChanges['updated_at']);

			$changedKeys = array_keys($rawChanges);
			$watched = method_exists($model, 'activityWatchedAttributes')
				? (array) $model::activityWatchedAttributes()
				: $changedKeys; // default: all changed except timestamps

			$relevantChanged = array_values(array_intersect($changedKeys, $watched));
			if (empty($relevantChanged)) {
				return; // nothing interesting changed
			}

			// Special case: Installment paid => emit a single rich 'paid' activity and stop
			if (class_basename(get_class($model)) === 'Installment'
				&& in_array('status', $relevantChanged, true)
				&& ($rawChanges['status'] ?? null) === 'paid'
				&& $model->getOriginal('status') !== 'paid') {
				$changesPaid = [
					'before' => ['status' => $model->getOriginal('status')],
					'after' => ['status' => 'paid'],
				];
				self::logActivity($model, 'paid', $changesPaid);
				return;
			}

			$changes = [
				'before' => Arr::only($model->getOriginal(), $relevantChanged),
				'after' => Arr::only($rawChanges, $relevantChanged),
			];
			self::logActivity($model, 'updated', $changes);
		});

		static::deleted(function ($model) {
			self::logActivity($model, 'deleted');
		});
	}

	protected static function logActivity($model, string $action, ?array $changes = null): void
	{
		// Avoid errors before migrations run
		if (!Schema::hasTable('activities')) {
			return;
		}

		$userId = Auth::id();
		$modelType = get_class($model);
		$modelId = $model->getKey();

		$modelName = class_basename($modelType);
		$template = config('messages.models.' . $modelName . '.' . $action)
			?: config('messages.activity.' . $action, ':user ' . $action . ' :model #:id');

		$basePlaceholders = [
			':user' => optional(Auth::user())->name ?? 'System',
			':model' => $modelName,
			':id' => (string) $modelId,
		];

		$modelPlaceholders = self::buildModelPlaceholders($model);
		$placeholders = array_merge($basePlaceholders, $modelPlaceholders);

		$message = strtr($template, $placeholders);

		Activity::create([
			'user_id' => $userId,
			'action' => $action,
			'model_type' => $modelType,
			'model_id' => $modelId,
			'message' => $message,
			'changes' => $changes,
		]);
	}

	/**
	 * Build model-specific placeholders for activity messages.
	 */
	protected static function buildModelPlaceholders($model): array
	{
		$placeholders = [];
		switch (class_basename(get_class($model))) {
			case 'User':
				$placeholders[':target_name'] = $model->name ?? '';
				$placeholders[':target_email'] = $model->email ?? '';
				break;
			case 'Customer':
				$placeholders[':name'] = $model->name ?? '';
				$placeholders[':account_no'] = $model->account_no ?? '';
				$placeholders[':nic'] = $model->nic ?? '';
				break;
			case 'Guarantor':
				$placeholders[':name'] = $model->name ?? '';
				$placeholders[':customer_name'] = optional($model->customer)->name ?? '';
				break;
			case 'Product':
				$placeholders[':product_company'] = $model->company ?? '';
				$placeholders[':product_model'] = $model->model ?? '';
				$placeholders[':serial_no'] = $model->serial_no ?? '';
				break;
			case 'Purchase':
				$placeholders[':customer_name'] = optional($model->customer)->name ?? '';
				$placeholders[':product_model'] = optional($model->product)->model ?? '';
				$placeholders[':total_price'] = (string) ($model->total_price ?? '');
				break;
			case 'Installment':
				$placeholders[':customer_name'] = optional($model->customer)->name ?? '';
				$placeholders[':customer_nic'] = optional($model->customer)->nic ?? '';
				$placeholders[':customer_father'] = optional($model->customer)->father_name ?? '';
				$placeholders[':installment_amount'] = (string) ($model->installment_amount ?? '');
				$placeholders[':due_date'] = optional($model->due_date)->format('Y-m-d');

				// Paid-specific fields
				$placeholders[':receipt_no'] = $model->receipt_no ?? '';
				$placeholders[':paid_date'] = optional($model->date)->format('Y-m-d');

				// Compute installment number within its purchase schedule (ordered by due_date then id)
				try {
					$idsOrdered = $model->purchase
						? $model->purchase->installments()
							->orderBy('due_date')
							->orderBy('id')
							->pluck('id')
							->toArray()
						: [];
					$index = array_search($model->id, $idsOrdered, true);
					$placeholders[':installment_number'] = $index === false ? '' : (string) ($index + 1);
				} catch (\Throwable $e) {
					$placeholders[':installment_number'] = '';
				}
				break;
			case 'RecoveryOfficer':
				$placeholders[':name'] = $model->name ?? '';
				$placeholders[':employee_id'] = $model->employee_id ?? '';
				break;
			case 'Setting':
				$placeholders[':key'] = $model->key ?? '';
				$placeholders[':setting_user'] = optional($model->user)->name ?? '';
				break;
			default:
				break;
		}

		// Ensure all placeholders are strings
		foreach ($placeholders as $k => $v) {
			$placeholders[$k] = is_scalar($v) ? (string) $v : '';
		}

		return $placeholders;
	}
}


