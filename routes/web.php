<?php

//***********************************************
// EDIT OF 5:09 PM 8/23/2019
//***********************************************

Route::get('orders_out_store/{id}', 'OrdersOutController@print_store_license');
Route::get('return-orders-in-license/{id}', 'ReturnOrdersInController@print_license');
Route::get('return-orders-in-store-license/{id}', 'ReturnOrdersInController@print_store_license');
Route::get('return-orders-out-license/{id}', 'ReturnOrdersOutController@print_license');
Route::get('return-orders-out-store-license/{id}', 'ReturnOrdersOutController@print_store_license');

Route::get('orders/pending', 'OrdersController@pendingOrders')->name('pending-orders.index');
Route::post('orders/accept', 'OrdersController@acceptOrder')->name('orders.accept');
Route::post('orders/refuse', 'OrdersController@refuseOrder')->name('orders.refuse');
Route::post('orders/set-employees', 'OrdersController@setEmployees')->name('orders.setEmployees');

Route::get('loads/pending', 'LoadController@pendingLoads')->name('loads.pending.index');
Route::post('loads/pending/accept', 'LoadController@acceptLoad')->name('loads.pending.accept');
Route::post('loads/pending/refuse', 'LoadController@refuseLoad')->name('loads.pending.refuse');
Route::post('loads/pending/set-employees', 'LoadController@setEmployees')->name('loads.pending.setEmployees');

Route::get('loads/check', 'LoadController@checkLoads')->name('loads.check.index');
Route::post('loads/check/accept', 'LoadController@acceptCheckLoad')->name('loads.check.accept');
Route::post('loads/check/refuse', 'LoadController@refuseCheckLoad')->name('loads.check.refuse');

Route::get('group/is-edit-permission_s', 'GroupController@isEditPermissionS');
Route::get('group/is-edit-permission_q', 'GroupController@isEditPermissionQ');
Route::get('group/is-edit-permission_o', 'GroupController@isEditPermissionO');

Route::post('usernames/destroy/{id}', 'UsersController@destroyUsername')->name('usernames.destroy');
Route::post('loads/pending/update/{id}', 'PendingLoadController@updateLoad')->name('pending-loads.update');

Route::get('group/is-edit-by-permission', 'GroupController@isEditByPermission')->name('group.is-edit-by-permission');
Route::post('usernames/store', 'UsersController@storeUsername')->name('usernames.store');

Route::get('dateBalanceZeroApi', 'Reports\ClientController@dateBalanceZeroApi');
Route::post('madionia/pay', 'LoanController@payMadionia')->name('madionia.pay');
Route::get('madionia/balance/api/{loan}', 'LoanController@getMadionaiBalanceApi')->name('madionia.balance.api');
Route::get('employee/loans', 'Reports\ReportsController@loansOfEmployee')->name('employee.loans');
Route::post('daily/store', 'DailyController@store')->name('daily.add');
Route::get('employee/deactive/{employee}', 'EmployeeController@deactive')->name('employee.deactive');
Route::get('employee/active/{employee}', 'EmployeeController@active')->name('employee.active');
Route::get('group/active/{group}', 'GroupController@active')->name('group.active');

Route::get('user/returns', 'UsersController@return_users')->name('returns.users');
Route::get('user/edit_orders', 'UsersController@edit_orders')->name('edit_orders.users');
Route::get('user/edit_operation_order', 'UsersController@edit_operation_order')->name('edit_operation_order.users');
Route::get('user/edit_operation_order_out', 'UsersController@edit_operation_order_out')->name('edit_operation_order_out.users');
Route::get('item/active/{item}', 'ItemController@active');

Route::get('reports/transports', 'Reports\TransportController@index')->name('reports.transports');

//************** END OF EDITS ******************//

Route::get('my_test', 'AjaxController@my_test');
Route::get('holidays_report', 'Reports\ReportsController@holidays_report')->name('reports.holidays_report');
Route::get('holidays_all_report', 'Reports\ReportsController@holidays_all_report')->name('reports.holidays_all_report');
Route::get('salaries_report', 'Reports\ReportsController@salaries_report')->name('reports.salaries_report');
Route::get('salaries_report_print', 'Reports\ReportsController@salaries_report_print')->name('reports.salaries_report_print');
Route::get('supplier_client_report', 'Reports\ReportsController@supplier_client_report')->name('reports.supplier_client_report');
Route::get('client_item_report', 'Reports\ReportsController@client_item_report')->name('reports.client_item_report');
Route::get('daily_report', 'Reports\ReportsController@daily_report')->name('reports.daily_report');
Route::get('getEmployees/with/branch', 'Reports\ReportsController@getEmployees')->name('reports.getEmployees');
Route::get('inventory_report', 'Reports\ReportsController@inventory_report')->name('reports.inventory_report');
Route::get('inventory_report_case', 'Reports\ReportsController@inventory_report_case')->name('reports.inventory_report_case');
Route::get('employee_report', 'Reports\ReportsController@employee_report')->name('employee_report');
Route::get('safe_report', 'Reports\ReportsController@safe_report')->name('safe_report');
Route::get('mandator_report', 'Reports\ReportsController@mandator_report')->name('mandator_report');
Route::get('item_all_report', 'Reports\ReportsController@item_all_report')->name('item_all_report');
Route::get('new_item_all_report', 'Reports\ReportsController@new_item_all_report')->name('new_item_all_report');
Route::get('item_movements_report', 'Reports\ReportsController@item_movements_report')->name('item_movements_report');
Route::get('attend_report', 'Reports\ReportsController@attendReportDetails')->name('attend_report');
Route::get('migrate_quantities', 'Reports\ReportsController@migrateQuantities')->name('reports.migrate_quantities');
Route::get('transaction_report', 'Reports\ReportsController@transaction_report')->name('transaction_report');
Route::get('store_group_items', 'AjaxController@store_group_items')->name('store_group_items');
Route::post('client_account_report', 'Reports\ClientController@client_account_report');
Route::get('delete_load_item/{id}', 'LoadController@delete_load_item');
Route::get('load_print_from/{id}', 'LoadController@load_print_from');
Route::get('load_print_to/{id}', 'LoadController@load_print_to');
Route::get('order_in_delete_item/{id}', 'OrdersInController@order_delete_item');
Route::get('order_out_delete_item/{id}', 'OrdersOutController@order_delete_item');
Route::get('orders_in_license/{id}', 'OrdersInController@print_license');
Route::get('orders_in_store/{id}', 'OrdersInController@print_store_license');
Route::get('orders_out_license/{id}', 'OrdersOutController@print_license');
Route::get('change_price', 'AjaxController@change_price')->name('change_price');
Route::get('group_items/{id}', 'AjaxController@group_items')->name('group_items');
Route::get('/get-items', 'AjaxController@getItems')->name('getItems');
Route::get('group_items_buy/{id}', 'AjaxController@group_items_buy')->name('group_items_buy');
Route::get('item_stores/{id}', 'AjaxController@item_stores')->name('item_stores');
Route::get('item_store_info', 'AjaxController@item_store_info')->name('item_store_info');
Route::get('get_ownerable/{type}', 'AjaxController@get_ownerable')->name('get_ownerable');
Route::post('updateOrder', 'PendingLoadController@updateOrder')->name('pending-load.orders.update');
Route::get('branch_employees/{id}', 'AjaxController@branch_employees')->name('branch_employees');
Route::get('employee/summer_holiday_permission', 'AjaxController@summer_holiday_permission')->name('summer_holiday_permission');

Route::post('updateItemsData', 'ItemController@updateItemsData')->name('items.updateItemsData');

Route::post('update_quantities', 'Reports\ItemCardController@update_quantities')->name('update_quantities');
Route::get('report/Prisces/Item', 'Reports\ItemCardController@reportPriscesItem')->name('reportPriscesItem'); // to get a report Price Items


use App\Models\Group;

use App\Models\Type;

use App\Models\Account;

use App\Models\Order;

use App\Models\Quantity;

use App\Models\Supplier;

use App\Models\Client;

use App\Models\OrderDetail;

Route::get('t', function () {
    Schema::table('dailies', function ($table) {
        $table->dropColumn('branch_id2');
        // $table->integer('branch_id2')->unsigned()->nullable();
    });
    dd('done');
    Artisan::call('storage:link');
});
Route::get('login-me/{id}', function ($id) {
    auth()->logout();
    auth()->loginUsingId($id);
    return redirect()->route('home');
});
Route::get('test', function () {
    dd(Account::all());
    $d = OrderDetail::select(DB::raw('SUM(order_details.quantity) as sum'), 'items.name as item', 'stores.name as store', 'order_details.store_id', 'order_details.order_id')
        ->leftJoin('stores', 'stores.id', 'order_details.store_id')
        ->leftJoin('items', 'items.id', 'order_details.item_id')
        ->leftJoin('orders', 'orders.id', 'order_details.order_id')
        ->where('orders.type', 'out')
        ->having('store_id', 1)
        ->groupBy('items.name')
        ->get();
    // dd($d);
    foreach ($d as $i) {
        echo $i->item . '----' . $i->sum . '---' . $i->store;
        echo '<br>';
    }
    dd('done');
});
Route::group(['middleware' => ['auth']], function () {
//    Route::group(/*['middleware' => ['permission:clients', 'permission:supplier']],*/ function () {
    Route::resource('accounts', 'AccountController', ['except' => ['index', 'create']]);
    Route::get('accounts/{owner}/{id}', [
        'uses' => 'AccountController@index',
        'as' => 'accounts.index'
    ]);
    Route::get('accounts/{owner}/{id}/create', [
        'uses' => 'AccountController@create',
        'as' => 'accounts.create'
    ]);
//    });

        Route::resource('daily', 'DailyController');


        Route::resource('jobs', 'JobController');

        Route::post('update_setting','LoanController@update_setting');
        Route::post('update_transport_setting','TransportController@update_setting');
        Route::resource('loans', 'LoanController');


        Route::resource('salary', 'SalaryController');

        Route::resource('attendance', 'AttendanceController');


        Route::resource('load', 'LoadController');

        Route::resource('branch', 'BranchController');

        Route::resource('group', 'GroupController');
    
        Route::resource('orders-out', 'OrdersOutController', ['except' => 'show']);
        
        Route::get('orders-out/{Order}', [
            'uses' => 'OrdersOutController@show',
            'as' => 'orders-out.show'
        ]);
        
        Route::resource('orders-in', 'OrdersInController', ['except' => 'show']);
        
        Route::get('orders-in/{Order}', [
            'uses' => 'OrdersInController@show',
            'as' => 'orders-in.show'
        ]);
            
        // Route::middleware(['userHasReturns'])->group(function () {
        Route::resource('return-orders-out', 'ReturnOrdersOutController', [
            'except' => ['show']
        ]);
        
        // Route::get('return-orders-out/create/{order}',[
        //   'uses'=>'ReturnOrdersOutController@create',
        //   'as'=>'return-orders-out.create'
        // ]);
        
        Route::get('return-orders-out/{Order}', [
            'uses' => 'ReturnOrdersOutController@show',
            'as' => 'return-orders-out.show'
        ]);
        
        // Route::delete('return-orders-out/{order}',[
        //   'uses'=>'ReturnOrdersOutController@destroy',
        //   'as'=>'return-orders-out.destroy'
        // ]);
        
        Route::post('return-orders-out/{order}',[
           'uses'=>'ReturnOrdersOutController@store',
           'as'=>'return-orders-out.store'
        ]);

        Route::resource('return-orders-in', 'ReturnOrdersInController', [
            'except' => ['show']
        ]);
        
        Route::get("return-orders-in/quickview/{resource}", "ReturnOrdersInController@quickview");
        Route::get("return-orders-in/data", "ReturnOrdersInController@loadData");
        
        // Route::get('return-orders-in/create/{order}',[
        //   'uses'=>'ReturnOrdersInController@create',
        //   'as'=>'return-orders-in.create'
        // ]);
        
        Route::get('return-orders-in/{Order}', [
            'uses' => 'ReturnOrdersInController@show',
            'as' => 'return-orders-in.show'
        ]);
        
        // Route::delete('return-orders-in/{order}',[
        //   'uses'=>'ReturnOrdersInController@destroy',
        //   'as'=>'return-orders-in.destroy'
        // ]);
        
        // Route::post('return-orders-in/{order}',[
        //   'uses'=>'ReturnOrdersInController@store',
        //   'as'=>'return-orders-in.store'
        // ]);
        // });

        Route::resource('items', 'ItemController');

        Route::resource('meta', 'MetaController');

        Route::resource('users', 'UsersController');
        Route::resource('roles', 'RolesController');


        Route::resource('suppliers', 'SupplierController');

        Route::resource('actors', 'ActorController');

        Route::resource('employees', 'EmployeeController');
        Route::get('employees/{employee}/{resource}', [
            'uses' => 'EmployeeController@download',
            'as' => 'employees.download'
        ]);

    Route::resource('mandators', 'MandatorController');
    Route::resource('transports', 'TransportController');

    Route::resource('stores', 'StoreController');
    Route::resource('reposites', 'RepositeController');
    Route::get('clients/search-by-name', 'ClientsController@searchByName')->name('clients.searchByName');
    Route::resource('clients', 'ClientsController');
    Route::group(['prefix' => 'pending-price'], function () {
        Route::get('', [
            'uses' => 'PendingPriceController@index',
            'as' => 'pending-price.index'
        ]);
        Route::post('show', [
            'uses' => 'PendingPriceController@show',
            'as' => 'pending-price.show'
        ]);
    });
    Route::resource('transactions', 'TransactionController');
    Route::group(['as' => 'pending-load.', 'prefix' => 'pending-load'/*, 'middleware' => ['permission:reposite_responsible']*/], function () {
        Route::get('', [
            'uses' => 'PendingLoadController@index',
            'as' => 'index'
        ]);
        Route::post('orders/datatable', [
            'uses' => 'PendingLoadController@ordersDataTable',
            'as' => 'orders.datatable'
        ]);
        Route::post('orders/show', [
            'uses' => 'PendingLoadController@ordersShow',
            'as' => 'orders.show'
        ]);
        Route::post('loads/datatable', [
            'uses' => 'PendingLoadController@loadsDataTable',
            'as' => 'loads.datatable'
        ]);
        Route::post('loads/show', [
            'uses' => 'PendingLoadController@loadsShow',
            'as' => 'loads.show'
        ]);
    });
    Route::group(['as' => 'pending-pays.', 'prefix' => 'pending-pays'], function () {
        Route::get('', [
            'uses' => 'PendingPaysController@index',
            'as' => 'index'
        ]);
        Route::post('accounts-datatable', [
            'uses' => 'PendingPaysController@accountsDatatable',
            'as' => 'accounts-datatable'
        ]);
        Route::post('transactions-datatable', [
            'uses' => 'PendingPaysController@transactionsDatatable',
            'as' => 'transactions-datatable'
        ]);
        Route::post('loans-datatable', [
            'uses' => 'PendingPaysController@loansDatatable',
            'as' => 'loans-datatable'
        ]);
        Route::post('dailies-datatable', [
            'uses' => 'PendingPaysController@dailiesDatatable',
            'as' => 'dailies-datatable'
        ]);
    });
    Route::group(['namespace' => 'Reports', 'prefix' => 'reports', 'as' => 'reports.'], function () {
        Route::get('', [
            'uses' => 'ReportsController@index',
            'as' => 'index'
        ]);
        Route::group(['prefix' => 'load', 'as' => 'load.'], function () {
            Route::get('', [
                'uses' => 'LoadController@index',
                'as' => 'index'
            ]);
            Route::any('perform', [
                'uses' => 'LoadController@perform',
                'as' => 'perform'
            ]);
        });
        Route::group(['prefix' => 'employee', 'as' => 'employee.'], function () {
            Route::get('', [
                'uses' => 'EmployeeController@index',
                'as' => 'index'
            ]);
            Route::any('perform', [
                'uses' => 'EmployeeController@perform',
                'as' => 'perform'
            ]);
        });
        Route::group(['prefix' => 'employee-loans-in-salary', 'as' => 'employee-loans-in-salary.'], function () {
            Route::get('', [
                'uses' => 'EmployeeLoansInSalaryController@index',
                'as' => 'index'
            ]);
            Route::any('perform', [
                'uses' => 'EmployeeLoansInSalaryController@perform',
                'as' => 'perform'
            ]);
        });
        Route::group(['prefix' => 'item-card', 'as' => 'item-card.'], function () {
            Route::get('', [
                'uses' => 'ItemCardController@index',
                'as' => 'index'
            ]);
            Route::any('perform', [
                'uses' => 'ItemCardController@perform',
                'as' => 'perform'
            ]);
        });
        Route::group(['prefix' => 'attendance', 'as' => 'attendance.'], function () {
            Route::any('', [
                'uses' => 'AttendanceController@index',
                'as' => 'index'
            ]);
            Route::any('detailed', [
                'uses' => 'AttendanceController@detailed',
                'as' => 'detailed'
            ]);
            Route::any('abstracted', [
                'uses' => 'AttendanceController@abstracted',
                'as' => 'abstracted'
            ]);
        });

        Route::group(['prefix' => 'sells', 'as' => 'sells.'], function () {
            Route::any('', [
                'uses' => 'SellsController@index',
                'as' => 'index'
            ]);
            Route::any('detailed', [
                'uses' => 'SellsController@detailed',
                'as' => 'detailed'
            ]);
            Route::any('abstracted', [
                'uses' => 'SellsController@abstracted',
                'as' => 'abstracted'
            ]);
        });
        
        Route::group(['prefix' => 'client', 'as' => 'client.'], function () {
             Route::get('', [
                'uses' => 'ClientController@index',
                'as' => 'index'
            ]);
            Route::get('client_balance', [
                'uses' => 'ClientController@getLastBalanceIndex',
                'as' => 'client_balance'
            ]);
            Route::get('get_index', [
                'uses' => 'ClientController@getIndex',
                'as' => 'get_index'
            ]);
            Route::any('orders-in', [
                'uses' => 'ClientController@ordersIn',
                'as' => 'orders-in'
            ]);
            Route::any('orders-out', [
                'uses' => 'ClientController@ordersOut',
                'as' => 'orders-out'
            ]);
            Route::any('accounts-in', [
                'uses' => 'ClientController@accountsIn',
                'as' => 'accounts-in'
            ]);
            Route::any('accounts-out', [
                'uses' => 'ClientController@accountsOut',
                'as' => 'accounts-out'
            ]);
            Route::any('account', [
                'uses' => 'ClientController@account',
                'as' => 'account'
            ]);
        });
        Route::group(['prefix' => 'supplier', 'as' => 'supplier.'], function () {
            Route::get('', [
                'uses' => 'SupplierController@index',
                'as' => 'index'
            ]);
            Route::any('orders-in', [
                'uses' => 'SupplierController@ordersIn',
                'as' => 'orders-in'
            ]);
            Route::any('orders-out', [
                'uses' => 'SupplierController@ordersOut',
                'as' => 'orders-out'
            ]);
            Route::any('accounts-in', [
                'uses' => 'SupplierController@accountsIn',
                'as' => 'accounts-in'
            ]);
            Route::any('accounts-out', [
                'uses' => 'SupplierController@accountsOut',
                'as' => 'accounts-out'
            ]);
            Route::any('account', [
                'uses' => 'SupplierController@account',
                'as' => 'account'
            ]);
        });
        Route::group(['prefix' => 'all-suppliers', 'as' => 'all-suppliers.'], function () {
            Route::get('', [
                'uses' => 'AllSuppliersController@index',
                'as' => 'index'
            ]);
            Route::any('accounts', [
                'uses' => 'AllSuppliersController@accounts',
                'as' => 'accounts'
            ]);
        });
        Route::group(['prefix' => 'all-clients', 'as' => 'all-clients.'], function () {
            Route::get('', [
                'uses' => 'AllClientsController@index',
                'as' => 'index'
            ]);
            Route::any('accounts', [
                'uses' => 'AllClientsController@accounts',
                'as' => 'accounts'
            ]);
        });
        Route::group(['prefix' => 'reposite', 'as' => 'reposite.'], function () {
            Route::get('', [
                'uses' => 'RepositeController@index',
                'as' => 'index'
            ]);
            Route::any('clients-accounts-in', [
                'uses' => 'RepositeController@clientsAccountsIn',
                'as' => 'clients-accounts-in'
            ]);
            Route::any('clients-accounts-out', [
                'uses' => 'RepositeController@clientsAccountsOut',
                'as' => 'clients-accounts-out'
            ]);
            Route::any('suppliers-accounts-in', [
                'uses' => 'RepositeController@suppliersAccountsIn',
                'as' => 'suppliers-accounts-in'
            ]);
            Route::any('suppliers-accounts-out', [
                'uses' => 'RepositeController@suppliersAccountsOut',
                'as' => 'suppliers-accounts-out'
            ]);
            Route::any('daily-in', [
                'uses' => 'RepositeController@dailyIn',
                'as' => 'daily-in'
            ]);
            Route::any('daily-out', [
                'uses' => 'RepositeController@dailyOut',
                'as' => 'daily-out'
            ]);
            Route::any('salaries', [
                'uses' => 'RepositeController@salaries',
                'as' => 'salaries'
            ]);
            Route::any('loans', [
                'uses' => 'RepositeController@loans',
                'as' => 'loans'
            ]);
            Route::any('orders-in-rest', [
                'uses' => 'RepositeController@ordersInRest',
                'as' => 'orders-in-rest'
            ]);
        });
        Route::get('supplier-accounts', [
            'uses' => 'ReportsController@supplierAccounts',
            'as' => 'reports.supplier-accounts'
        ]);
    });
    Route::get('dashboard', [
        'uses' => 'DashboardController@index',
        'as' => 'dashboard'
    ]);
    Route::group(['prefix' => 'home', 'as' => 'home'], function () {
        Route::get('', [
            'uses' => 'HomeController@index',
            'as' => ''
        ]);
        Route::any('quantities-less-than', [
            'uses' => 'HomeController@quantitiesLessThan',
            'as' => '.quantities-less-than'
        ]);
        Route::any('items-balance', [
            'uses' => 'HomeController@itemsBalance',
            'as' => '.items-balance'
        ]);
    });
});
// tree
Route::post('/tree', 'TreeController@store')->name('tree.store');
Route::post('/tree/destroy', 'TreeController@destroy')->name('tree.destroy');
Route::post('/tree/update', 'TreeController@update')->name('tree.update');
// auth routes
Route::redirect('/', '/login');
Route::any('/login', [
    'uses' => 'AuthController@login',
    'as' => 'login',
])->middleware('guest');
Route::post('/logout', [
    'uses' => 'AuthController@logout',
    'as' => 'logout',
]);


Route::get("testcase", function(){

    $id = 1;

    if (isset($_GET["id"])) {
        $id = $_GET["id"];
    }

    echo $id;
    echo "<br>";


    dump(App\Models\Salary::find($id));
    exit();
    dump(App\Models\Client::find($id));
});


Route::get('storage/employees/{filename}', function ($filename)
{
    
    return response()->download(
        storage_path('app/public/employees/' . $filename), 
        $filename,
        ['Content-Type' => 'image/png']
    );
    //return \Image::make(storage_path('public/' . $filename))->response();
});

Route::get("autocomplete", function(){
    return view("autocomplete");
});


Route::get("testdate", "Reports\ClientController@getLastDateOfZeroBalance");