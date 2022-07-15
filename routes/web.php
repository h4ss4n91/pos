<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::group(['middleware' => 'auth'], function() {
	Route::get('/dashboard', 'HomeController@dashboard');
});

Route::group(['middleware' => ['auth', 'active']], function() {

	Route::get('/', 'HomeController@index');
	Route::get('/dashboard-filter/{start_date}/{end_date}', 'HomeController@dashboardFilter');


	Route::get('language_switch/{locale}', 'LanguageController@switchLanguage');

	Route::get('role/permission/{id}', 'RoleController@permission')->name('role.permission');
	Route::post('role/set_permission', 'RoleController@setPermission')->name('role.setPermission');
	Route::resource('role', 'RoleController');

	Route::post('importunit', 'UnitController@importUnit')->name('unit.import');
	Route::post('unit/deletebyselection', 'UnitController@deleteBySelection');
	Route::get('unit/lims_unit_search', 'UnitController@limsUnitSearch')->name('unit.search');
	Route::resource('unit', 'UnitController');

	Route::post('category/import', 'CategoryController@import')->name('category.import');
	Route::post('category/deletebyselection', 'CategoryController@deleteBySelection');
	Route::post('category/category-data', 'CategoryController@categoryData');
	Route::resource('category', 'CategoryController');

	Route::post('importbrand', 'BrandController@importBrand')->name('brand.import');
	Route::post('brand/deletebyselection', 'BrandController@deleteBySelection');
	Route::get('brand/lims_brand_search', 'BrandController@limsBrandSearch')->name('brand.search');
	Route::resource('brand', 'BrandController');

	Route::post('importsupplier', 'SupplierController@importSupplier')->name('supplier.import');
	Route::post('supplier/deletebyselection', 'SupplierController@deleteBySelection');
	Route::get('supplier/lims_supplier_search', 'SupplierController@limsSupplierSearch')->name('supplier.search');
	Route::resource('supplier', 'SupplierController');

	Route::post('importwarehouse', 'WarehouseController@importWarehouse')->name('warehouse.import');
	Route::post('warehouse/deletebyselection', 'WarehouseController@deleteBySelection');
	Route::get('warehouse/lims_warehouse_search', 'WarehouseController@limsWarehouseSearch')->name('warehouse.search');
	Route::resource('warehouse', 'WarehouseController');

	Route::post('importtax', 'TaxController@importTax')->name('tax.import');
	Route::post('tax/deletebyselection', 'TaxController@deleteBySelection');
	Route::get('tax/lims_tax_search', 'TaxController@limsTaxSearch')->name('tax.search');
	Route::resource('tax', 'TaxController');

	//Route::get('products/getbarcode', 'ProductController@getBarcode');
	Route::post('products/product-data', 'ProductController@productData');
	Route::get('products/gencode', 'ProductController@generateCode');
	Route::get('products/search', 'ProductController@search');
	Route::get('products/saleunit/{id}', 'ProductController@saleUnit');
	Route::get('products/getdata/{id}', 'ProductController@getData');
	Route::get('products/product_warehouse/{id}', 'ProductController@productWarehouseData');
	Route::post('importproduct', 'ProductController@importProduct')->name('product.import');
	Route::post('exportproduct', 'ProductController@exportProduct')->name('product.export');
	Route::get('products/print_barcode','ProductController@printBarcode')->name('product.printBarcode');
	
	Route::get('products/lims_product_search', 'ProductController@limsProductSearch')->name('product.search');
	Route::post('products/deletebyselection', 'ProductController@deleteBySelection');
	Route::post('products/update', 'ProductController@updateProduct');
	Route::resource('products', 'ProductController');

	Route::post('importcustomer_group', 'CustomerGroupController@importCustomerGroup')->name('customer_group.import');
	Route::post('customer_group/deletebyselection', 'CustomerGroupController@deleteBySelection');
	Route::get('customer_group/lims_customer_group_search', 'CustomerGroupController@limsCustomerGroupSearch')->name('customer_group.search');
	Route::resource('customer_group', 'CustomerGroupController');

	Route::post('importcustomer', 'CustomerController@importCustomer')->name('customer.import');
	Route::get('customer/getDeposit/{id}', 'CustomerController@getDeposit');
	Route::post('customer/add_deposit', 'CustomerController@addDeposit')->name('customer.addDeposit');
	Route::post('customer/update_deposit', 'CustomerController@updateDeposit')->name('customer.updateDeposit');
	Route::post('customer/deleteDeposit', 'CustomerController@deleteDeposit')->name('customer.deleteDeposit');
	Route::post('customer/deletebyselection', 'CustomerController@deleteBySelection');
	Route::get('customer/lims_customer_search', 'CustomerController@limsCustomerSearch')->name('customer.search');
	Route::resource('customer', 'CustomerController');

	Route::post('importbiller', 'BillerController@importBiller')->name('biller.import');
	Route::post('biller/deletebyselection', 'BillerController@deleteBySelection');
	Route::get('biller/lims_biller_search', 'BillerController@limsBillerSearch')->name('biller.search');
	Route::resource('biller', 'BillerController');

	Route::post('sales/sale-data', 'SaleController@saleData');
	Route::post('sales/sendmail', 'SaleController@sendMail')->name('sale.sendmail');
	Route::get('sales/sale_by_csv', 'SaleController@saleByCsv');
	Route::get('sales/product_sale/{id}','SaleController@productSaleData');
	Route::post('importsale', 'SaleController@importSale')->name('sale.import');
	Route::get('pos', 'SaleController@posSale')->name('sale.pos');
	Route::get('sales/lims_sale_search', 'SaleController@limsSaleSearch')->name('sale.search');
	Route::get('sales/lims_product_search', 'SaleController@limsProductSearch')->name('product_sale.search');
	Route::get('sales/getcustomergroup/{id}', 'SaleController@getCustomerGroup')->name('sale.getcustomergroup');
	Route::get('sales/getproduct/{id}', 'SaleController@getProduct')->name('sale.getproduct');
	Route::get('sales/getproduct/{category_id}/{brand_id}', 'SaleController@getProductByFilter');
	Route::get('sales/getfeatured', 'SaleController@getFeatured');
	Route::get('sales/get_gift_card', 'SaleController@getGiftCard');
	Route::get('sales/paypalSuccess', 'SaleController@paypalSuccess');
	Route::get('sales/paypalPaymentSuccess/{id}', 'SaleController@paypalPaymentSuccess');
	Route::get('sales/gen_invoice/{id}', 'SaleController@genInvoice')->name('sale.invoice');
	Route::post('sales/add_payment', 'SaleController@addPayment')->name('sale.add-payment');
	Route::get('sales/getpayment/{id}', 'SaleController@getPayment')->name('sale.get-payment');
	Route::post('sales/updatepayment', 'SaleController@updatePayment')->name('sale.update-payment');
	Route::post('sales/deletepayment', 'SaleController@deletePayment')->name('sale.delete-payment');
	Route::get('sales/{id}/create', 'SaleController@createSale');
	Route::post('sales/deletebyselection', 'SaleController@deleteBySelection');
	Route::resource('sales', 'SaleController');

	Route::get('delivery', 'DeliveryController@index')->name('delivery.index');
	Route::get('delivery/create/{id}', 'DeliveryController@create');
	Route::post('delivery/store', 'DeliveryController@store')->name('delivery.store');
	Route::get('delivery/{id}/edit', 'DeliveryController@edit');
	Route::post('delivery/update', 'DeliveryController@update')->name('delivery.update');
	Route::post('delivery/deletebyselection', 'DeliveryController@deleteBySelection');
	Route::post('delivery/delete/{id}', 'DeliveryController@delete')->name('delivery.delete');

	Route::get('quotations/product_quotation/{id}','QuotationController@productQuotationData');
	Route::get('quotations/lims_product_search', 'QuotationController@limsProductSearch')->name('product_quotation.search');
	Route::get('quotations/getcustomergroup/{id}', 'QuotationController@getCustomerGroup')->name('quotation.getcustomergroup');
	Route::get('quotations/getproduct/{id}', 'QuotationController@getProduct')->name('quotation.getproduct');
	Route::get('quotations/{id}/create_sale', 'QuotationController@createSale')->name('quotation.create_sale');
	Route::get('quotations/{id}/create_purchase', 'QuotationController@createPurchase')->name('quotation.create_purchase');
	Route::post('quotations/sendmail', 'QuotationController@sendMail')->name('quotation.sendmail');
	Route::post('quotations/deletebyselection', 'QuotationController@deleteBySelection');
	Route::resource('quotations', 'QuotationController');

	Route::post('purchases/purchase-data', 'PurchaseController@purchaseData');
	Route::get('purchases/product_purchase/{id}','PurchaseController@productPurchaseData');
	Route::get('purchases/lims_product_search', 'PurchaseController@limsProductSearch')->name('product_purchase.search');
	Route::post('purchases/add_payment', 'PurchaseController@addPayment')->name('purchase.add-payment');
	Route::get('purchases/getpayment/{id}', 'PurchaseController@getPayment')->name('purchase.get-payment');
	Route::post('purchases/updatepayment', 'PurchaseController@updatePayment')->name('purchase.update-payment');
	Route::post('purchases/deletepayment', 'PurchaseController@deletePayment')->name('purchase.delete-payment');
	Route::get('purchases/purchase_by_csv', 'PurchaseController@purchaseByCsv');
	Route::post('importpurchase', 'PurchaseController@importPurchase')->name('purchase.import');
	Route::post('purchases/deletebyselection', 'PurchaseController@deleteBySelection');
	Route::resource('purchases', 'PurchaseController');

	Route::get('transfers/product_transfer/{id}','TransferController@productTransferData');
	Route::get('transfers/transfer_by_csv', 'TransferController@transferByCsv');
	Route::post('importtransfer', 'TransferController@importTransfer')->name('transfer.import');
	Route::get('transfers/getproduct/{id}', 'TransferController@getProduct')->name('transfer.getproduct');
	Route::get('transfers/lims_product_search', 'TransferController@limsProductSearch')->name('product_transfer.search');
	Route::post('transfers/deletebyselection', 'TransferController@deleteBySelection');
	Route::resource('transfers', 'TransferController');

	Route::get('qty_adjustment/getproduct/{id}', 'AdjustmentController@getProduct')->name('adjustment.getproduct');
	Route::get('qty_adjustment/lims_product_search', 'AdjustmentController@limsProductSearch')->name('product_adjustment.search');
	Route::post('qty_adjustment/deletebyselection', 'AdjustmentController@deleteBySelection');
	Route::resource('qty_adjustment', 'AdjustmentController');

	Route::get('return-sale/getcustomergroup/{id}', 'ReturnController@getCustomerGroup')->name('return-sale.getcustomergroup');
	Route::post('return-sale/sendmail', 'ReturnController@sendMail')->name('return-sale.sendmail');
	Route::get('return-sale/getproduct/{id}', 'ReturnController@getProduct')->name('return-sale.getproduct');
	Route::get('return-sale/lims_product_search', 'ReturnController@limsProductSearch')->name('product_return-sale.search');
	Route::get('return-sale/product_return/{id}','ReturnController@productReturnData');
	Route::post('return-sale/deletebyselection', 'ReturnController@deleteBySelection');
	Route::resource('return-sale', 'ReturnController');

	Route::get('return-purchase/getcustomergroup/{id}', 'ReturnPurchaseController@getCustomerGroup')->name('return-purchase.getcustomergroup');
	Route::post('return-purchase/sendmail', 'ReturnPurchaseController@sendMail')->name('return-purchase.sendmail');
	Route::get('return-purchase/getproduct/{id}', 'ReturnPurchaseController@getProduct')->name('return-purchase.getproduct');
	Route::get('return-purchase/lims_product_search', 'ReturnPurchaseController@limsProductSearch')->name('product_return-purchase.search');
	Route::get('return-purchase/product_return/{id}','ReturnPurchaseController@productReturnData');
	Route::post('return-purchase/deletebyselection', 'ReturnPurchaseController@deleteBySelection');
	Route::resource('return-purchase', 'ReturnPurchaseController');

	Route::get('report/product_quantity_alert', 'ReportController@productQuantityAlert')->name('report.qtyAlert');
	Route::get('report/warehouse_stock', 'ReportController@warehouseStock')->name('report.warehouseStock');
	Route::post('report/warehouse_stock', 'ReportController@warehouseStockById')->name('report.warehouseStock');
	Route::get('report/daily_sale/{year}/{month}', 'ReportController@dailySale');
	Route::post('report/daily_sale/{year}/{month}', 'ReportController@dailySaleByWarehouse')->name('report.dailySaleByWarehouse');
	Route::get('report/monthly_sale/{year}', 'ReportController@monthlySale');
	Route::post('report/monthly_sale/{year}', 'ReportController@monthlySaleByWarehouse')->name('report.monthlySaleByWarehouse');
	Route::get('report/daily_purchase/{year}/{month}', 'ReportController@dailyPurchase');
	Route::post('report/daily_purchase/{year}/{month}', 'ReportController@dailyPurchaseByWarehouse')->name('report.dailyPurchaseByWarehouse');
	Route::get('report/monthly_purchase/{year}', 'ReportController@monthlyPurchase');
	Route::post('report/monthly_purchase/{year}', 'ReportController@monthlyPurchaseByWarehouse')->name('report.monthlyPurchaseByWarehouse');
	Route::get('report/best_seller', 'ReportController@bestSeller');
	Route::post('report/best_seller', 'ReportController@bestSellerByWarehouse')->name('report.bestSellerByWarehouse');
	Route::post('report/profit_loss', 'ReportController@profitLoss')->name('report.profitLoss');
	Route::post('report/product_report', 'ReportController@productReport')->name('report.product');
	Route::post('report/purchase', 'ReportController@purchaseReport')->name('report.purchase');
	Route::post('report/sale_report', 'ReportController@saleReport')->name('report.sale');
	Route::post('report/payment_report_by_date', 'ReportController@paymentReportByDate')->name('report.paymentByDate');
	Route::post('report/warehouse_report', 'ReportController@warehouseReport')->name('report.warehouse');
	Route::post('report/user_report', 'ReportController@userReport')->name('report.user');
	Route::post('report/customer_report', 'ReportController@customerReport')->name('report.customer');
	Route::post('report/supplier', 'ReportController@supplierReport')->name('report.supplier');
	Route::post('report/due_report_by_date', 'ReportController@dueReportByDate')->name('report.dueByDate');

	Route::get('user/profile/{id}', 'UserController@profile')->name('user.profile');
	Route::put('user/update_profile/{id}', 'UserController@profileUpdate')->name('user.profileUpdate');
	Route::put('user/changepass/{id}', 'UserController@changePassword')->name('user.password');
	Route::get('user/genpass', 'UserController@generatePassword');
	Route::post('user/deletebyselection', 'UserController@deleteBySelection');
	Route::resource('user','UserController');

	Route::get('setting/general_setting', 'SettingController@generalSetting')->name('setting.general');
	Route::post('setting/general_setting_store', 'SettingController@generalSettingStore')->name('setting.generalStore');
	Route::get('setting/general_setting/change-theme/{theme}', 'SettingController@changeTheme');
	Route::get('setting/mail_setting', 'SettingController@mailSetting')->name('setting.mail');
	Route::get('setting/sms_setting', 'SettingController@smsSetting')->name('setting.sms');
	Route::get('setting/createsms', 'SettingController@createSms')->name('setting.createSms');
	Route::post('setting/sendsms', 'SettingController@sendSms')->name('setting.sendSms');
	Route::get('setting/hrm_setting', 'SettingController@hrmSetting')->name('setting.hrm');
	Route::post('setting/hrm_setting_store', 'SettingController@hrmSettingStore')->name('setting.hrmStore');
	Route::post('setting/mail_setting_store', 'SettingController@mailSettingStore')->name('setting.mailStore');
	Route::post('setting/sms_setting_store', 'SettingController@smsSettingStore')->name('setting.smsStore');
	Route::get('setting/pos_setting', 'SettingController@posSetting')->name('setting.pos');
	Route::post('setting/pos_setting_store', 'SettingController@posSettingStore')->name('setting.posStore');
	Route::get('setting/empty-database', 'SettingController@emptyDatabase')->name('setting.emptyDatabase');

	Route::get('expense_categories/gencode', 'ExpenseCategoryController@generateCode');
	Route::post('expense_categories/import', 'ExpenseCategoryController@import')->name('expense_category.import');
	Route::post('expense_categories/deletebyselection', 'ExpenseCategoryController@deleteBySelection');
	Route::resource('expense_categories', 'ExpenseCategoryController');

	Route::post('expenses/deletebyselection', 'ExpenseController@deleteBySelection');
	Route::resource('expenses', 'ExpenseController');

	Route::get('gift_cards/gencode', 'GiftCardController@generateCode');
	Route::post('gift_cards/recharge/{id}', 'GiftCardController@recharge')->name('gift_cards.recharge');
	Route::post('gift_cards/deletebyselection', 'GiftCardController@deleteBySelection');
	Route::resource('gift_cards', 'GiftCardController');

	Route::get('coupons/gencode', 'CouponController@generateCode');
	Route::post('coupons/deletebyselection', 'CouponController@deleteBySelection');
	Route::resource('coupons', 'CouponController');
	//accounting routes
	Route::get('accounts/make-default/{id}', 'AccountsController@makeDefault');
	Route::get('accounts/balancesheet', 'AccountsController@balanceSheet')->name('accounts.balancesheet');
	Route::post('accounts/account-statement', 'AccountsController@accountStatement')->name('accounts.statement');
	Route::resource('accounts', 'AccountsController');
	Route::resource('money-transfers', 'MoneyTransferController');
	// New Routes
	Route::get('accounts/customer_statement', 'AccountsController@customer_statement')->name('accounts.customer_statement');
	Route::get('accounts/supplier_statement', 'AccountsController@supplier_statement')->name('accounts.supplier_statement');
	Route::get('accounts/expense_statement', 'AccountsController@expense_statement')->name('accounts.expense_statement');
	Route::get('accounts/bank_statement', 'AccountsController@bank_statement')->name('accounts.bank_statement');
	
	Route::get('purchase-voucher', 'VoucherController@purchase');
	
	Route::get('general-journal', 'VoucherController@general_journal');
	
	Route::get('add_recieve_voucher', 'VoucherController@add_recieve_voucher');
	Route::get('add_payment_voucher', 'VoucherController@add_payment_voucher');
	
	Route::get('expense-voucher', 'VoucherController@expense_voucher');
	Route::get('bank-voucher', 'VoucherController@bank_voucher');
	
	
	Route::get('customer-payment-voucher', 'VoucherController@customer_payment_voucher');
	Route::get('customer-receiving-voucher', 'VoucherController@customer_receiving_voucher');
	
	
	Route::get('customer-receiving-voucher-list', 'VoucherController@customer_receiving_voucher_list');
	
	Route::get('customer-receiving-voucher-edit/{id}', 'VoucherController@customer_receiving_voucher_edit');
	Route::post('customer-receiving-voucher-edit-post', 'VoucherController@customer_receiving_voucher_edit_post');
	
	Route::get('supplier-voucher-edit/{id}', 'VoucherController@supplier_receiving_voucher_edit');
	Route::post('supplier-receiving-voucher-edit-post', 'VoucherController@supplier_receiving_voucher_edit_post');
	
	
	Route::get('supplier-receiving-voucher', 'VoucherController@supplier_receiving_voucher');
	Route::get('supplier-payment-voucher', 'VoucherController@supplier_payment_voucher');
	Route::get('supplier-receiving-voucher-list', 'VoucherController@supplier_receiving_voucher_list');
	
	Route::get('expense-receiving-voucher', 'VoucherController@expense_receiving_voucher');
	Route::get('expense-payment-voucher', 'VoucherController@expense_payment_voucher');
	
	Route::get('bank-receiving-voucher', 'VoucherController@bank_receiving_voucher');
	Route::get('bank-payment-voucher', 'VoucherController@bank_payment_voucher');
	
	
	
	Route::get('salesman-receive-voucher', 'VoucherController@salesman');
	Route::post('purchase_voucher_store', 'VoucherController@purchase_store');
	Route::post('general_journal23', 'VoucherController@general_journal23');
	
	Route::get('general_journal23_two/{date}', 'VoucherController@general_journal23_two');
	
	Route::resource('receive-voucher', 'VoucherController');


	
Route::post('receive-voucher/{id}/general_journal', 'VoucherController@general_journal');

Route::get('receive-voucher/{id}/cash_in_hand', 'VoucherController@cash_in_hand');

Route::post('receive-voucher/{id}/add_cash_in_hand', 'VoucherController@add_cash_in_hand');

Route::post('receive-voucher/{id}/general_journal_edit', 'VoucherController@general_journal_edit');

Route::get('receive-voucher/general_journal_edit_two/{date}', 'VoucherController@general_journal_edit_two');

Route::post('receive-voucher/{id}/general_journal_edit_payment_voucher', 'VoucherController@general_journal_edit_payment_voucher');
Route::post('receive-voucher/{id}/general_journal_edit_expense_voucher', 'VoucherController@general_journal_edit_expense_voucher');
Route::post('receive-voucher/{id}/general_journal_edit_bank_voucher', 'VoucherController@general_journal_edit_bank_voucher');

Route::get('receive-voucher/{id}/receive_voucher_delete', 'VoucherController@receive_voucher_delete');
Route::get('receive-voucher/{id}/payment_voucher_delete', 'VoucherController@payment_voucher_delete');
Route::get('receive-voucher/{id}/expense_voucher_delete', 'VoucherController@expense_voucher_delete');
Route::get('receive-voucher/{id}/bank_voucher_delete', 'VoucherController@bank_voucher_delete');

Route::get('receive-voucher/{id}/customer_ledger_delete', 'VoucherController@customer_ledger_delete');
Route::get('receive-voucher/{id}/supplier_ledger_delete', 'VoucherController@supplier_ledger_delete');

Route::post('accounts/{id}/customer_ledger_edit', 'AccountsController@customer_ledger_edit');
Route::post('accounts/{id}/supplier_ledger_edit', 'AccountsController@supplier_ledger_edit');

Route::post('accounts/{id}/customer_ledger_update', 'AccountsController@customer_ledger_update');
Route::post('accounts/{id}/supplier_ledger_update', 'AccountsController@supplier_ledger_update');

	//HRM routes
	Route::post('departments/deletebyselection', 'DepartmentController@deleteBySelection');
	Route::resource('departments', 'DepartmentController');

	Route::post('employees/deletebyselection', 'EmployeeController@deleteBySelection');
	Route::resource('employees', 'EmployeeController');

	Route::post('payroll/deletebyselection', 'PayrollController@deleteBySelection');
	Route::resource('payroll', 'PayrollController');

	Route::post('attendance/deletebyselection', 'AttendanceController@deleteBySelection');
	Route::resource('attendance', 'AttendanceController');

	Route::resource('stock-count', 'StockCountController');
	Route::post('stock-count/finalize', 'StockCountController@finalize')->name('stock-count.finalize');
	Route::get('stock-count/stockdif/{id}', 'StockCountController@stockDif');
	Route::get('stock-count/{id}/qty_adjustment', 'StockCountController@qtyAdjustment')->name('stock-count.adjustment');

	Route::post('holidays/deletebyselection', 'HolidayController@deleteBySelection');
	Route::get('approve-holiday/{id}', 'HolidayController@approveHoliday')->name('approveHoliday');
	Route::get('holidays/my-holiday/{year}/{month}', 'HolidayController@myHoliday')->name('myHoliday');
	Route::resource('holidays', 'HolidayController');

	Route::get('/home', 'HomeController@index')->name('home');
	Route::get('my-transactions/{year}/{month}', 'HomeController@myTransaction');

	Route::post('accounts/supplierAccountStatement', 'AccountsController@supplierAccountStatement')->name('accounts.supplier-statement');
	Route::get('sales/{id}/customer_balance', 'SaleController@customer_balance');

	Route::get('accounts/getRecords/{id}','AccountsController@getRecords');
	Route::get('accounts/getRecords_tick/{id}/{current_balance}','AccountsController@getRecords_tick');
	Route::get('accounts/getBillRecords/{id}','AccountsController@getBillRecords');
	Route::get('accounts/checkRecords/{id}','AccountsController@checkRecords');
	Route::get('accounts/getBankRecords_two/{id}','AccountsController@getBankRecords_two');
	Route::get('accounts/getEXPRecords/{id}','AccountsController@getEXPRecords');


	Route::get('/city', 'HomeController@city')->name('city');
	Route::get('/years', 'HomeController@years')->name('years');
	Route::post('/submit_year', 'HomeController@submit_year')->name('submit_year');
	Route::get('/select_year/{year}', 'HomeController@select_year')->name('select_year');
	Route::post('/submit_city', 'HomeController@submit_city')->name('submit_city');
	Route::post('/update_city', 'HomeController@update_city')->name('update_city');
	Route::get('accounts/getSupplierRecords/{id}','AccountsController@getSupplierRecords');
	Route::get('sales/{id}/supplier_balance', 'SaleController@supplier_balance');

	Route::get('customer/{id}/{customer}/{balance}', 'SaleController@customer');
	Route::post('sales/{id}/receive_voucher', 'SaleController@receive_voucher');

	Route::post('sales/{id}/customerPaymentVoucher', 'SaleController@customerPaymentVoucher');
	Route::post('sales/{id}/expense_voucher', 'SaleController@expense_voucher');
	Route::post('sales/{id}/expense_voucher_debit', 'SaleController@expense_voucher_debit');
	Route::post('sales/{id}/bank_voucher', 'SaleController@bank_voucher');
	Route::post('sales/{id}/bank_voucher_debit', 'SaleController@bank_voucher_debit');
	Route::post('sales/{id}/payment_voucher', 'SaleController@payment_voucher');
	Route::post('sales/{id}/supplier_receive_voucher', 'SaleController@supplier_receive_voucher');
	Route::post('sales/receive_voucher/receive/voucher', 'SaleController@receive_voucher');
	Route::get('sales/{id}/supplier_city', 'SaleController@supplier_city');

	Route::get('sales/{id}/expense_balance', 'SaleController@expense_balance');
	Route::get('sales/{id}/bank_balance', 'SaleController@bank_balance');

	
	
	Route::get('accounts/getEXPRecords/{id}','AccountsController@getEXPRecords');
	

});

