<?php

use App\Http\Controllers\RetailController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/home', function () {
    return view('home');
})->middleware(['auth'])->name('home');

require __DIR__ . '/auth.php';



//-----------------------------------------Subscriber---------------------------------------------------------------------------
// Route::get('/subscriber-create','SubscriberController@create');
// Route::post('/subscriber-create', 'SubscriberController@store');

//------------------------------------------Store-----------------------------------------------------------------------------
// Route::get('/store-create','StoreController@create');
// Route::post('/store-create', 'StoreController@store');
Route::get('/store-list/{subscriberId}', 'StoreAPIController@list');
// Route::get('/store-edit/{id}', 'StoreController@edit');
// Route::put('/store-edit/{id}', 'StoreController@update');
// Route::delete('/store-delete/{id}', 'StoreController@destroy')->name('stores.destroy');
Route::get('/total-stock/{storeId}', 'DashboardAPIController@totalStock');
Route::get('/out-stock/{subscriberId}', 'DashboardAPIController@outStock');
Route::get('/dashboard/{subscriberId}', 'DashboardAPIController@showDashboard');
Route::get('/top-product/{subscriberId}', 'DashboardAPIController@topProduct');

//------------------------------------------ Pos------------------------------------------------------------------------------
// Route::get('/pos-create','PosController@create');
// Route::post('/pos-create', 'PosController@store');
// Route::get('/pos-list', 'PosController@list');
// Route::get('/pos-edit/{id}', 'PosController@edit');
// Route::put('/pos-edit/{id}', 'PosController@update');
// Route::delete('/pos-delete/{id}', 'PosController@destroy')->name('pos.destroy');


//-------------------------------------------Category-----------------------------------------------------------------------------
// Route::get('/category-create','CategoryController@create');
Route::post('/category-create/{subscriberId}', 'CategoryAPIController@store');
Route::get('/category-list/{subscriberId}', 'CategoryAPIController@list');
// Route::get('/category-edit/{id}', 'CategoryController@edit');
// Route::put('/category-edit/{id}', 'CategoryController@update');
// Route::delete('/category-delete/{id}', 'CategoryController@destroy')->name('categories.destroy');
Route::get('/category-subcategory/{subscriberId}', 'CategoryAPIController@categorySubcategoryList');

//-------------------------------------------Subcategory-----------------------------------------------------------------------------
// Route::get('/subcategory-create','SubcategoryController@create');
Route::post('/subcategory-create/{subscriberId}', 'SubcategoryAPIController@store');
Route::get('/subcategory-list/{subscriberId}', 'SubcategoryAPIController@list');
// Route::get('/subcategory-edit/{id}', 'SubcategoryController@edit');
// Route::put('/subcategory-edit/{id}', 'SubcategoryController@update');
// Route::delete('/subcategory-delete/{id}', 'SubcategoryController@destroy')->name('subcategories.destroy');


//-------------------------------------------Supplier----------------------------------------------------------------------------------
// Route::get('/supplier-create','SupplierController@create');
Route::post('/supplier-create/{subscriberId}', 'SupplierAPIController@store');
Route::get('/supplier-list/{subscriberId}', 'SupplierAPIController@list');
// Route::get('/supplier-edit/{id}', 'SupplierController@edit');
// Route::put('/supplier-edit/{id}', 'SupplierController@update');
// Route::delete('/supplier-delete/{id}', 'SupplierController@destroy')->name('suppliers.destroy');


//-----------------------------------------Brand------------------------------------------------------------------------------------------
// Route::get('/brand-create','BrandController@create');
Route::post('/brand-create/{subscriberId}', 'BrandAPIController@store');
Route::get('/brand-list/{subscriberId}', 'BrandAPIController@list');
// Route::get('/brand-edit/{id}', 'BrandController@edit');
// Route::put('/brand-edit/{id}', 'BrandController@update');
// Route::delete('/brand-delete/{id}', 'BrandController@destroy')->name('brands.destroy');


//-------------------------------------------Product-----------------------------------------------------------------------------------------
Route::get('/product-create/{subscriberId}', 'ProductCreateAPIController@create');
Route::post('/product-create/{subscriberId}', 'ProductCreateAPIController@store');
// Route::get('/product-create/{id}', 'ProductController@showSubcategory');
// Route::get('/product-create-tax/{id}', 'ProductController@isTaxExcluded');
// Route::post('/product-create', 'ProductController@store');
// Route::post('/product-image-create', 'ProductController@imageStore');
// Route::post('/product-image-update/{id}', 'ProductController@imageUpdate');
Route::get('/product-list/{subscriberId}', 'ProductAPIController@productlist');
Route::get('/product-list/serialize/{subscriberId}', 'ProductAPIController@serializeProductlist');
Route::get('/product-list/{subscriberId}/{storeId}', 'ProductAPIController@list');
Route::get('/product-list/variant/{subscriberId}/{storeId}', 'ProductAPIController@productVariantList');

// Route::get('/product-edit/{id}', 'ProductController@edit');
// Route::get('/product-edit-subcategory/{id}', 'ProductController@showSubcategory');
// Route::put('/product-edit/{id}', 'ProductController@update');
// Route::delete('/product-delete/{id}', 'ProductController@destroy')->name('products.destroy');

Route::get('/purchase-date/{subscriberId}', 'AccountController@purcahse_date');
Route::get('/payment-direction', 'ProductAPIController@payment_direction');
Route::post('/unit-create/{subscriberId}', 'ProductAPIController@unitStore');
Route::get('/unit-list/{subscriberId}', 'ProductAPIController@unitList');

Route::get('/subscriber-mobile', 'SubscriberController@list');
Route::post('/transaction-id', 'ProductAPIController@transaction');
//--------------------------------------------Discount------------------------------------------------------------------------
// Route::get('/discount-create','DiscountController@create');
// Route::post('/discount-create', 'DiscountController@store');
// Route::get('/discount-list', 'DiscountController@list');
Route::get('/discount-list/{subscriberId}/{storeId}', 'DiscountAPIController@list');
// Route::get('/discount-edit/{id}', 'DiscountController@edit');
// Route::put('/discount-edit/{id}', 'DiscountController@update');
// Route::delete('/discount-delete/{id}', 'DiscountController@destroy')->name('discounts.destroy');
// Route::get('/discount-listJson', 'DiscountController@listJson');


//----------------------------------------------------Vat-------------------------------------
// Route::get('/vat-create','VatController@create');
// Route::post('/vat-create', 'VatController@store');
// Route::get('/vat-list', 'VatController@list');
Route::get('/vat-list/{subscriberId}', 'VatAPIController@list');
// Route::get('/vat-edit/{id}', 'VatController@edit');
// Route::put('/vat-edit/{id}', 'VatController@update');
// Route::delete('/vat-delete/{id}', 'VatController@destroy')->name('vats.destroy');



//-----------------------------------------------------Order-------------------------------------------------------------------
Route::post('/get-order', 'OrderController@store');
Route::get('/order-list/{subscriberId}/{date}', 'OrderController@list');
Route::get('/ordered-product-list/{subscriberId}/{orderId}', 'OrderController@productList');
Route::post('/set-order', 'OrderController@submit');

//Due Payment Sale
Route::post('/due-payment-sale', 'DuePaymentSaleController@store');

Route::get('/packages', 'PackageController@getPackages');

//Image
Route::get('/download/{imageName}', 'ProductImageController@image');


//-----------------------------------Role-----------------------------------------------------------------------------------------
// Route::get('/role-create','RoleController@create');
// Route::post('/role-create', 'RoleController@store');
// Route::get('/role-list', 'RoleController@list');
// Route::get('/role-edit/{id}', 'RoleController@edit');
// Route::put('/role-edit/{id}', 'RoleController@update');
// Route::delete('/role-delete/{id}', 'RoleController@destroy')->name('roles.destroy');


//----------------------------------Employee------------------------------------------------------------------------------
// Route::get('/employee-create','EmployeeController@create');
// Route::post('/employee-create', 'EmployeeController@store');
// Route::get('/employee-list', 'EmployeeController@list');
// Route::get('/employee-edit/{id}', 'EmployeeController@edit');
// Route::put('/employee-edit/{id}', 'EmployeeController@update');
// Route::delete('/employee-delete/{id}', 'EmployeeController@destroy')->name('employees.destroy');

//-------------------------------------------------------------Purchase Product--------------------------------------------------------
// Route::get('/purchase-create','PurchaseController@create');
// Route::post('/purchase-create','PurchaseController@store');
// Route::get('/purchase-list','PurchaseController@list');
// Route::get('/purchase-product-list/{id}','PurchaseController@productList');
// Route::get('/purchase-product-edit/{id}', 'PurchaseController@productEdit');
// Route::put('/purchase-product-edit/{id}', 'PurchaseController@update');

//Client Image
Route::post('/client-image', 'ClientImageController@store');
Route::get('/client-image/{imageName}', 'ClientImageController@image');
Route::get('/supplier-image/{imageName}', 'SupplierImageController@image');


//Client
Route::post('/client-create', 'ClientAPIController@store');
Route::get('/client-list/{subscriberId}', 'ClientAPIController@list');

//User Information
Route::post('/login-data', 'Auth\LoginController@check');

//Due Payment
Route::post('/due-payment', 'DuePaymentController@store');
Route::get('/due-list/{subscriberId}/{storeId}', 'DuePaymentController@list');
Route::get('/due-list/{subscriberId}/{storeId}/{clientid}', 'DuePaymentController@dueList');

//Reports
// Route::get('/reports', 'ReportController@index');
// Route::post('/reports', 'ReportController@showReport');


//Sales Summary
// Route::get('/sales-summary/{id}', 'SalesSummaryAPIController@salesSummary');

//Payment Method
Route::get('/payment-methods/{subscriberId}', 'PaymentMethodController@list');

//Customer
//Customer
// Route::get('/customer-create', 'ClientController@create');
// Route::post('/customer-create', 'ClientController@store');
// Route::get('/customer-list', 'ClientController@list');
// Route::get('/customer-edit/{id}', 'ClientController@edit');
// Route::put('/customer-edit/{id}', 'ClientController@update');
// Route::get('/customer-details/{id}', 'ClientController@details');
// Route::delete('/customer-delete/{id}', 'ClientController@destroy')->name('suppliers.destroy');


//Deposit
Route::post('/deposit-create', 'DepositAPIController@store');
//Deposit Image
Route::post('/deposit-image', 'DepositImageController@store');
Route::get('/deposit-image/{imageName}', 'DepositImageController@downloadImage');
Route::get('/due-image/{imageName}', 'DueAPIImageController@downloadImage');

//Expense
Route::post('/expense-create', 'ExpenseAPIController@store');
Route::get('/get-expense/{subscriberId}/{storeId}', 'ExpenseAPIController@expenseList');
Route::get('/get-expense/{subscriberId}/{storeId}/{month}', 'ExpenseAPIController@getExpenseMonth');

//Expense Image
Route::post('/expense-image', 'ExpenseImageAPIController@store');
Route::get('/expense-image/{imageName}', 'ExpenseImageAPIController@downloadImage');


//Employee/User list
Route::get('/employee-list/{subscriberId}', 'UserAPIController@employeeList');


//Salary


//Sales, expense, due summary
Route::get('/summary/{subscriberId}/{storeId}', 'SummaryAPIController@data');
Route::get('/weekly-summary/{subscriberId}/{storeId}', 'SummaryAPIController@WeeklyData');


// Get Order Data
Route::get('/sales-list/{subscriberId}/{storeId}', 'GetOrderDataAPIController@salesList');
Route::get('/sales-list/{startdate}/{enddate}/{subscriberId}/{storeId}', 'GetOrderDataAPIController@salesListDetails');

//Order details for app
Route::get('/order-details/{id}', 'OrderWEBController@orderDetailsForAppView')->name('order.details.view');
Route::get('/order-details-data/{id}', 'OrderWEBController@productListForApp')->name('order.details');


//BKASH API INTEGRATION
Route::post('/push-message', 'BkashAPIController@index');

//Sign Up Request
Route::post('/signup-request', 'SignUpRequestAPIController@store');

//stockin
Route::post('/stock-in/{subscriberId}/{storeId}', 'StockInAPIController@productIn');

//Profit Calculation
Route::get('/profit-calculation-data/{subscriberId}/{storeId}', 'ProfitCalculationAPIController@data')->name('profit.calculation.reports.view');

Route::get('/low-stock/{subscriberId}/{storeId}', 'ProductAPIController@lowStock');

Route::post('/retail-sell', [RetailController::class, 'apiSellDevice']);
