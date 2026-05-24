<?php

use Illuminate\Database\Seeder;

use App\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $permissions = 
        [

            [
                'name'=>'can_accept_price_requests',
                'display_name'=>'الموافقة علي تخفيض الاسعار'
            ],
            [
                'name'=>'reposite_responsible',
                'display_name'=>'مسؤول خزنة'
            ],
            [
                'name'=>'store_responsible',
                'display_name'=>'مسؤول مخزن'
            ],
            [
                'name'=>'can_edit_price',
                'display_name'=>'تخفيض الاسعار'
            ],
            [
                'name'=>'dashboard',
                'display_name'=>'لوحة العدادات'
            ],
            [
                'name'=>'branch',
                'display_name'=>'الفروع'
            ],
            [
                'name'=>'group',
                'display_name'=>'المجموعات'
            ],
            [
                'name'=>'employees',
                'display_name'=>'الموطفين'
            ],
            [
                'name'=>'users',
                'display_name'=>'المستخدمين'
            ],
            [
                'name'=>'clients',
                'display_name'=>'العملاء'
            ],
            [
                'name'=>'actors',
                'display_name'=>'ممثلين الموردين'
            ],
            [
                'name'=>'supplier',
                'display_name'=>'الموردين'
            ],
            [
                'name'=>'mandators',
                'display_name'=>'المندوبين'
            ],
            [
                'name'=>'jobs',
                'display_name'=>'الوظيفة'
            ],

            [
                'name'=>'stores',
                'display_name'=>'المخازن'
            ],

            [
                'name'=>'reposites',
                'display_name'=>'الخزن'
            ],

            [
                'name'=>'items',
                'display_name'=>'الاصناف'
            ],

            [
                'name'=>'settings',
                'display_name'=>'الاعدادت'
            ],

            [
                'name'=>'buy',
                'display_name'=>'شراء'
            ],


            [
                'name'=>'sell',
                'display_name'=>'بيع'
            ],
            [
                'name'=>'load',
                'display_name'=>'التحميل'
            ],

            [
                'name'=>'loan',
                'display_name'=>'السلف'
            ],


            [
                'name'=>'salary',
                'display_name'=>'المرتبات'
            ],

            [
                'name'=>'attendance',
                'display_name'=>'الحضور والانصراف'
            ],

            [
                'name'=>'daily',
                'display_name'=>'التعاملات اليومية'
            ],
            [
                'name'=>'reports',
                'display_name'=>'التقارير'
            ],
        ];

        foreach($permissions as $permission)
        {
            Permission::create($permission);
        }
    }
}
