
<?php

return [
	// 'activity' => [
	// 	'created' => ':user created :model #:id',
	// 	'updated' => ':user updated :model #:id',
	// 	'deleted' => ':user deleted :model #:id',
	// ],

	// Model-specific message templates. These override the defaults above when present.
	// Available placeholders depend on model and context merger in LogsActivity trait.
	'models' => [
		'User' => [
			'created' => ':user created user :target_name (:target_email) #ID:id',
			'updated' => ':user updated user :target_name (:target_email) #ID:id',
			'deleted' => ':user deleted user :target_name (:target_email) #ID:id',
		],
		'Customer' => [
			'created' => ':user created customer :name (:account_no, :nic) #ID:id',
			'updated' => ':user updated customer :name (:account_no, :nic) #ID:id',
			'deleted' => ':user deleted customer :name (:account_no, :nic) #ID:id',
		],
		'Guarantor' => [
			'created' => ':user added guarantor :name (for customer :customer_name) #ID:id',
			'updated' => ':user updated guarantor :name (for customer :customer_name) #ID:id',
			'deleted' => ':user removed guarantor :name (for customer :customer_name) #ID:id',
		],
		'Product' => [
			'created' => ':user created product :product_company :product_model (SN :serial_no) #ID:id',
			'updated' => ':user updated product :product_company :product_model (SN :serial_no) #ID:id',
			'deleted' => ':user deleted product :product_company :product_model (SN :serial_no) #ID:id',
		],
		'Purchase' => [
			'created' => ':user created purchase for :customer_name (product :product_model) total :total_price #ID:id',
			'updated' => ':user updated purchase for :customer_name (product :product_model) total :total_price #ID:id',
			'deleted' => ':user deleted purchase for :customer_name (product :product_model) total :total_price #ID:id',
		],
		'Installment' => [
			'created' => ':user recorded installment :installment_amount for :customer_name due :due_date #ID:id',
			'updated' => ':user updated installment :installment_amount for :customer_name due :due_date #ID:id',
			'paid' => ':user recorded payment for :customer_name (NIC :customer_nic, Father :customer_father) Installment #:installment_number ID:id amount :installment_amount receipt :receipt_no paid :paid_date due :due_date',
			'deleted' => ':user deleted installment :installment_amount for :customer_name due :due_date #ID:id',
		],
		'RecoveryOfficer' => [
			'created' => ':user created recovery officer :name (:employee_id) #ID:id',
			'updated' => ':user updated recovery officer :name (:employee_id) #ID:id',
			'deleted' => ':user deleted recovery officer :name (:employee_id) #ID:id',
		],
		'Setting' => [
			'created' => ':user created setting :key for :setting_user',
			'updated' => ':user updated setting :key for :setting_user',
			'deleted' => ':user deleted setting :key for :setting_user',
		],
	],
];

