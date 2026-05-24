<?php

use Illuminate\Http\Request;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


// apply validation
Route::post('validate', 'ValidationController@validation')->name('validate');
Route::post('items-in-the-store', 'ItemsInStoreController@index')->name('api.get-items-in-the-store');
Route::post('get-orders', 'GetOrdersController@index')->name('api.get-orders');
Route::post('employee-attendance', 'EmployeeAttendanceController@index')->name('api.employee-attendance');

Route::post('employee-loan', 'EmployeeLoanController@index')->name('api.employee-loan');
Route::post('reposites', 'RepositesController@index')->name('api.reposites');

Route::get('supply/data', 'AttendanceController@supply_data')->name('api.attendance-supply_data');
Route::post('attendance', 'AttendanceController@store')->name('api.attendance-store');
Route::delete('attendance', 'AttendanceController@destroy')->name('api.attendance-destroy');

Route::post('get-users-in-branch', 'BranchController@users')->name('api.get-users-in-branch');
// 

Route::group(['prefix'=>'return-orders-in' ,'as'=>'api.return-orders-in.'],function(){
    Route::post('items-in-group','ReturnOrdersInController@itemsInGroup')
         ->name('items-in-group');
    Route::post('items-quantities-in-store','ReturnOrdersInController@itemsQuantitiesInStore')
    ->name('items-quantities-in-store');

    Route::post('get-buyers','ReturnOrdersInController@getBuyers')
    ->name('get-buyers');

    Route::post('items-with-buyer','ReturnOrdersInController@itemsDataTable')
    ->name('items-with-buyer');
});


Route::group(['prefix'=>'return-orders-out' ,'as'=>'api.return-orders-out.'],function(){
    Route::post('items-in-group','ReturnOrdersOutController@itemsInGroup')
         ->name('items-in-group');
    Route::post('items-quantities-in-store','ReturnOrdersOutController@itemsQuantitiesInStore')
    ->name('items-quantities-in-store');

    Route::post('items-with-buyer','ReturnOrdersOutController@itemsDataTable')
    ->name('items-with-buyer');
});


Route::group(['prefix'=>'orders-in' ,'as'=>'api.orders-in.'],function(){
    Route::post('items-in-group','OrdersInController@itemsInGroup')
         ->name('items-in-group');

    Route::post('items-quantities-in-store','OrdersInController@itemsQuantitiesInStore')
    ->name('items-quantities-in-store');

    Route::post('get-buyers','OrdersInController@getBuyers')
    ->name('get-buyers');

    Route::post('item-in-stores','OrdersInController@itemsDataTable')
    ->name('item-in-stores');
});



Route::group(['prefix'=>'orders-out' ,'as'=>'api.orders-out.'],function(){
    Route::post('items-in-group','OrdersOutController@itemsInGroup')
         ->name('items-in-group');
});


Route::put('pending-price/{detail}', 'PendingPriceController@update')->name('api.pending-price.update');
Route::delete('pending-price/{detail}', 'PendingPriceController@destroy')->name('api.pending-price.destroy');



Route::put('pending-load/order/{detail}', 'PendingLoadController@updateOrder')->name('api.pending-load.orders.update');
Route::delete('pending-load/order/{detail}', 'PendingLoadController@destroyOrder')->name('api.pending-load.orders.destroy');



Route::put('pending-load/load/{detail}/{quantity}', 'PendingLoadController@updateLoad')->name('api.pending-load.loads.update');
Route::delete('pending-load/load/{detail}', 'PendingLoadController@destroyLoad')->name('api.pending-load.loads.destroy');



//Route::put('pending-pays/accounts/{account}', 'PendingPaysController@updateAccount')->name('api.pending-pays.account.update');
Route::post('pending-pays/accounts/{account}', 'PendingPaysController@updateAccount')->name('api.pending-pays.account.update');
Route::put('pending-pays/transactions/{transaction}', 'PendingPaysController@updateTransaction')->name('api.pending-pays.transaction.update');

Route::put('pending-pays/loans/{loan}', 'PendingPaysController@updateLoan')->name('api.pending-pays.loan.update');
Route::put('pending-pays/dailies/{daily}', 'PendingPaysController@updateDaily')->name('api.pending-pays.daily.update');

Route::post('attendance-settings/update', 'AttendanceSettingsController@update')->name('api.attendance-settings.update');


Route::post('salary', 'SalaryController@performSave')->name('api.salary.perform-save');
Route::post('load/get-items-in-group', 'LoadController@getItemsInGroup')->name('api.load.get-items-in-group');


Route::post('store/set-quantitity-of-item', 'StoreController@setQuantityOfItem')->name('api.store.set-quantitity-of-item');

Route::post('item-card/groups-has-quantities-in-store', 'ItemCardController@groupsHasQuantitiesInStore')->name('api.item-card.groups-has-quantities-in-store');
Route::post('item-card/item-quantities-in-store', 'ItemCardController@itemQuantitiesInStore')->name('api.item-card.item-quantities-in-store');



Route::get('save-bonus', 'SalaryController@saveBonus');
Route::get('reset-salary', 'SalaryController@resetSalary');








