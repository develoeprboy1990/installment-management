<?php

return [
	'models' => [
		'User' => [
			'created' => ':user ne naya user banaya — Naam: :target_name (Email: :target_email) #ID:id',
			'updated' => ':user ne user ki maloomat update ki — Naam: :target_name (Email: :target_email) #ID:id',
			'deleted' => ':user ne user ko delete kar diya — Naam: :target_name (Email: :target_email) #ID:id',
		],
		'Customer' => [
			'created' => ':user ne naya customer add kiya — Naam: :name (Account No: :account_no, NIC: :nic) #ID:id',
			'updated' => ':user ne customer ki detail update ki — Naam: :name (Account No: :account_no, NIC: :nic) #ID:id',
			'deleted' => ':user ne customer ko delete kar diya — Naam: :name (Account No: :account_no, NIC: :nic) #ID:id',
		],
		'Guarantor' => [
			'created' => ':user ne naya guarantor add kiya — Naam: :name (Customer: :customer_name) #ID:id',
			'updated' => ':user ne guarantor ki maloomat update ki — Naam: :name (Customer: :customer_name) #ID:id',
			'deleted' => ':user ne guarantor ko delete kar diya — Naam: :name (Customer: :customer_name) #ID:id',
		],
		'Product' => [
			'created' => ':user ne naya product add kiya — :product_company :product_model (Serial No: :serial_no) #ID:id',
			'updated' => ':user ne product update kiya — :product_company :product_model (Serial No: :serial_no) #ID:id',
			'deleted' => ':user ne product delete kar diya — :product_company :product_model (Serial No: :serial_no) #ID:id',
		],
		'Purchase' => [
			'created' => ':user ne nayi purchase banai — Customer: :customer_name (Product: :product_model) Total: :total_price #ID:id',
			'updated' => ':user ne purchase update ki — Customer: :customer_name (Product: :product_model) Total: :total_price #ID:id',
			'deleted' => ':user ne purchase delete kar di — Customer: :customer_name (Product: :product_model) Total: :total_price #ID:id',
		],
		'Installment' => [
			'created' => ':user ne nayi installment record ki — Amount: :installment_amount Customer: :customer_name Due: :due_date #ID:id',
			'updated' => ':user ne installment update ki — Amount: :installment_amount Customer: :customer_name Due: :due_date #ID:id',
			'paid' => ':user ne payment record ki — Customer: :customer_name (NIC: :customer_nic, Father: :customer_father), Installment #:installment_number, Amount: :installment_amount, Receipt: :receipt_no, Paid: :paid_date, Due: :due_date #ID:id',
			'deleted' => ':user ne installment delete kar di — Amount: :installment_amount Customer: :customer_name Due: :due_date #ID:id',
		],
		'RecoveryOfficer' => [
			'created' => ':user ne naya recovery officer add kiya — Naam: :name (ID: :employee_id) #ID:id',
			'updated' => ':user ne recovery officer ki detail update ki — Naam: :name (ID: :employee_id) #ID:id',
			'deleted' => ':user ne recovery officer delete kar diya — Naam: :name (ID: :employee_id) #ID:id',
		],
		'Setting' => [
			'created' => ':user ne nayi setting banai — Key: :key (User: :setting_user)',
			'updated' => ':user ne setting update ki — Key: :key (User: :setting_user)',
			'deleted' => ':user ne setting delete kar di — Key: :key (User: :setting_user)',
		],
	],
];
