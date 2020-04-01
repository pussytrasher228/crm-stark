<?php

Route::group(
    [
        'prefix' => 'company',
        'as' => 'company.',
        'namespace' => 'Company',
    ],
    function () {

    }
);

Route::resource('companies', 'Company\CompanyController')->only(['create', 'store']);

//Route::get('/companies/{token}', 'Company\CompanyController@show')->name('companies.show')->middleware('auth');
Route::get('/home', 'Company\CompanyController@show')->name('companies.show')->middleware('auth');

Route::get('/home/total', 'Company\CompanyController@showTotal')->name('companiesTotal.show')->middleware('auth');
Route::get('/home/exprenses', 'Company\CompanyController@showExprenses')->name('companiesExprenses.show')->middleware('auth');
Route::get('/home/analytics', 'Company\CompanyController@showAnalytics')->name('companiesAnalytics.show')->middleware('auth');

Route::get('/companies/categories/create', 'Company\ExpenseCategoryController@create')->name('category.create')->middleware('auth');
Route::get('/companies/categories/edit/{id}', 'Company\ExpenseCategoryController@edit')->name('category.edit')->middleware('auth');
Route::post('/companies/categories/store', 'Company\ExpenseCategoryController@store')->name('category.store')->middleware('auth');
Route::post('/companies/categories/edit/{id}', 'Company\ExpenseCategoryController@update')->name('category.update')->middleware('auth');

Route::get('/companies/edit/{id}', 'Admin\AdminController@editCompany')->name('company.edit')->middleware('can:admin');
Route::post('/companies/edit/{companies}', 'Admin\AdminController@updateCompany')->name('company.update')->middleware('can:admin');

Route::get('/companies/expenses/create', 'Company\ExpenseController@create')->name('expense.create')->middleware('auth');
Route::post('/companies/expenses/store', 'Company\ExpenseController@store')->name('expense.store')->middleware('auth');
Route::get('/companies/expenses/edit/{id}', 'Company\ExpenseController@edit')->name('expense.edit')->middleware('auth');
Route::post('/companies/expenses/edit/{id}', 'Company\ExpenseController@update')->name('expense.update')->middleware('auth');
Route::post('/companies/expenses/remove/{id}', 'Company\ExpenseController@delete')->name('expense.remove')->middleware('auth');

Route::get('/companies/incomes/create', 'Company\IncomeController@create')->name('income.create')->middleware('auth');
Route::post('/companies/incomes/store', 'Company\IncomeController@store')->name('income.store')->middleware('auth');
Route::get('/companies/incomes/edit/{id}', 'Company\IncomeController@edit')->name('income.edit')->middleware('auth');
Route::post('/companies/incomes/edit/{id}', 'Company\IncomeController@update')->name('income.update')->middleware('auth');
Route::post('/companies/incomes/remove/{id}', 'Company\IncomeController@delete')->name('income.remove')->middleware('auth');
Route::get('/companies/incomes/set-payed/{income}', 'Company\IncomeController@setPayed')->name('income.set-payed')->middleware('auth');
Route::get('/companies/incomes/set-unpayed/{income}', 'Company\IncomeController@setUnpayed')->name('income.set-unpayed')->middleware('auth');
Route::get('/companies/incomes/print/{income}', 'Company\IncomeController@printPdf')->name('income.print')->middleware('auth');
Route::post('/companies/incomes/print-exit/{income}', 'Company\IncomeController@printExit')->name('income.print.exit')->middleware('auth');
Route::post('/companies/incomes/print-exi/', 'Company\IncomeController@printExi')->name('income.print.exi')->middleware('auth');


Route::get('/companies/regular/show', 'Company\RegularController@show')->name('regularClients.show')->middleware('auth');
Route::get('/companies/regular/create', 'Company\RegularController@create')->name('regularClients.create')->middleware('auth');
Route::post('/companies/regular/store', 'Company\RegularController@store')->name('regularClients.store')->middleware('auth');
Route::post('/companies/regular/remove/{id}', 'Company\RegularController@delete')->name('regularClients.remove')->middleware('auth');
Route::get('/companies/regular/edit/{id}', 'Company\RegularController@edit')->name('regularClients.edit')->middleware('auth');
Route::post('/companies/regular/edit/{id}', 'Company\RegularController@update')->name('regularClients.update')->middleware('auth');



Route::get('/companies/act/show', 'Company\ActController@show')->name('act.show')->middleware('auth');
Route::get('/companies/act/create', 'Company\ActController@create')->name('act.create')->middleware('auth');
Route::post('/companies/act/store', 'Company\ActController@store')->name('act.store')->middleware('auth');


Route::get('/companies/registerAct/show', 'Company\RegisterActController@show')->name('registerAct.show')->middleware('auth');
Route::get('/companies/registerAct/create', 'Company\RegisterActController@create')->name('registerAct.create')->middleware('auth');
Route::post('/companies/registerAct/store', 'Company\RegisterActController@store')->name('registerAct.store')->middleware('auth');
Route::get('/companies/registerAct/edit/{registerAct}', 'Company\RegisterActController@edit')->name('registerAct.edit')->middleware('auth');
Route::post('/companies/registerAct/edit/{registerAct}', 'Company\RegisterActController@update')->name('registerAct.update')->middleware('auth');
Route::post('/companies/registerAct/remove/{registerAct}', 'Company\RegisterActController@delete')->name('registerAct.remove')->middleware('auth');

Route::get('/companies/api/get/data', 'GetApiDataController@getApiData')->name('get.api.data')->middleware('auth');

Auth::routes();

Route::get('/', 'Company\CompanyController@show')->name('companies.show')->middleware('auth');

Route::get('/admin', 'Admin\AdminController@show')->name('admin')->middleware('can:admin');
Route::get('/clients/create', 'Admin\AdminController@createClient')->name('client.create')->middleware('auth');
Route::get('/admin/services/create', 'Admin\AdminController@createService')->name('service.create')->middleware('can:admin');

Route::get('/admin/pay-services/create', 'Admin\AdminController@createPayService')->name('pay_service.create')->middleware('can:admin');
Route::get('/admin/pay-services/remove/{payService}', 'Admin\AdminController@removePayService')->name('pay_service.remove')->middleware('can:admin');
Route::post('/admin/pay-services/create', 'Admin\AdminController@storePayService')->name('pay_service.store')->middleware('can:admin');
Route::get('/admin/pay-services/edit/{payService}', 'Admin\AdminController@editPayService')->name('pay_service.edit')->middleware('can:admin');
Route::post('/admin/pay-services/edit/{payService}', 'Admin\AdminController@updatePayService')->name('pay_service.update')->middleware('can:admin');



Route::get('/admin/account_number/create', 'Admin\AdminController@createAccountNumber')->name('account_number.create')->middleware('can:admin');
Route::post('/admin/account_number/create', 'Admin\AdminController@storeAccountNumber')->name('account_number.store')->middleware('can:admin');
Route::get('/admin/account_number/edit/{accountNumber}', 'Admin\AdminController@editAccountNumber')->name('account_number.edit')->middleware('can:admin');
Route::post('/admin/account_number/edit/{accountNumber}', 'Admin\AdminController@updateAccountNumber')->name('account_number.update')->middleware('can:admin');
Route::get('/admin/account_number/remove/{accountNumber}', 'Admin\AdminController@removeAccountNumber')->name('account_number.remove')->middleware('can:admin');


Route::get('/admin/logo/create', 'Admin\AdminController@createLogo')->name('logo.create')->middleware('can:admin');
Route::post('/admin/logo/create', 'Admin\AdminController@storeLogo')->name('logo.store')->middleware('can:admin');
Route::get('/admin/logo/remove/{logo}', 'Admin\AdminController@removeLogo')->name('logo.remove')->middleware('can:admin');


Route::post('/clients/create', 'Admin\AdminController@storeClient')->name('client.store')->middleware('auth');
Route::post('/admin/services/create', 'Admin\AdminController@storeService')->name('service.store')->middleware('can:admin');
Route::get('/admin/services/edit/{service}', 'Admin\AdminController@editService')->name('service.edit')->middleware('can:admin');
Route::post('/admin/edit/{service}', 'Admin\AdminController@updateService')->name('service.update')->middleware('auth');
Route::get('/admin/service/remove/{service}', 'Admin\AdminController@removeService')->name('service.remove')->middleware('can:admin');

Route::get('/clients/{client}', 'Company\ClientController@show')->name('client.show')->middleware('auth');
Route::get('/clients/edit/{client}', 'Company\ClientController@edit')->name('client.edit')->middleware('auth');
Route::post('/clients/edit/{client}', 'Company\ClientController@update')->name('client.update')->middleware('auth');
Route::get('/admin/client/remove/{client}', 'Admin\AdminController@removeClient')->name('client.remove')->middleware('can:admin');

Route::get('/clients/{client}/account/create', 'Company\ClientController@createClientAccount')->name('client_account.create')->middleware('auth');
Route::post('/clients/{client}/account/create', 'Company\ClientController@storeClientAccount')->name('client_account.store')->middleware('auth');
Route::get('/clients/account/edit/{clientAccount}', 'Company\ClientController@editClientAccount')->name('client_account.edit')->middleware('auth');
Route::post('/clients/account/edit/{clientAccount}', 'Company\ClientController@updateClientAccount')->name('client_account.update')->middleware('auth');

Route::get('/clients/{client}/employees/create', 'Company\ClientController@createEmployees')->name('employees.create')->middleware('auth');
Route::post('/clients/{client}/employees/create', 'Company\ClientController@storeEmployees')->name('employees.store')->middleware('auth');
Route::get('/clients/employees/edit/{employees}', 'Company\ClientController@editEmployees')->name('employees.edit')->middleware('auth');
Route::post('/clients/employees/edit/{employees}', 'Company\ClientController@updateEmployees')->name('employees.update')->middleware('auth');

Route::post('/incomes/update/date', 'Company\IncomeController@getDate')->name('income.get_income_date')->middleware('auth');
Route::post('/act/update/date', 'Company\ActController@getDate')->name('act.get_income_date')->middleware('auth');
Route::get('/companies/act/set-unpayed/{act}', 'Company\ActController@setUnpayed')->name('act.set-unpayed')->middleware('auth');
Route::post('/companies/act/remove/{id}', 'Company\ActController@delete')->name('act.remove')->middleware('auth');
Route::get('/companies/act/edit/{id}', 'Company\ActController@edit')->name('act.edit')->middleware('auth');
Route::post('/companies/act/print-exit/{act}', 'Company\ActController@printExit')->name('act.print.exit')->middleware('auth');
Route::post('/act/income', 'Company\ActController@getIncome')->name('act.getIncome')->middleware('auth');
Route::post('/companies/act/edit/{id}', 'Company\ActController@update')->name('act.update')->middleware('auth');

Route::post('/income/getClientCheckingAccount', 'Company\IncomeController@getClientCheckingAccount')->name('income.getClientCheckingAccount')->middleware('auth');

Route::get('admin/incomePlans/create', 'Admin\AdminController@createIncomePlans')->name('incomePlans.create')->middleware('can:admin');
Route::post('/companies/incomePlans/store', 'Admin\AdminController@storeIncomePlans')->name('incomePlans.store')->middleware('can:admin');
Route::get('/companies/incomePlans/edit/{yars}', 'Admin\AdminController@editIncomePlans')->name('IncomePlans.edit')->middleware('auth');
Route::post('/companies/incomePlans/edit', 'Admin\AdminController@updateIncomePlans')->name('IncomePlans.update')->middleware('auth');

Route::get('/admin/test', 'Admin\AdminController@showTest')->name('showTest')->middleware('can:admin');

Route::get('/project', 'Company\ProjectController@show')->name('project')->middleware('auth');
Route::get('/project/create', 'Company\ProjectController@create')->name('project.create')->middleware('auth');
Route::post('/project/store', 'Company\ProjectController@store')->name('project.store')->middleware('auth');
Route::get('/project/edit/{project}', 'Company\ProjectController@edit')->name('project.edit')->middleware('auth');
Route::post('/project/edit/{project}', 'Company\ProjectController@update')->name('project.update')->middleware('auth');
Route::post('/project/remove/{project}', 'Company\ProjectController@remove')->name('project.remove')->middleware('auth');
Route::get('/project/detals/{project}', 'Company\ProjectController@detals')->name('project.detals')->middleware('auth');
