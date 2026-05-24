<?php

namespace App\DataTables;

use App\Models\User;
use Yajra\DataTables\Services\DataTable;

class UsersDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables($query)
        ->addColumn('action', 'users.actions');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        return $model->select(
            'users.*',
            'roles.display_name as role_name',
            'branches.name as branch',
            'types.display_name as type'
        )
        ->leftJoin('role_user','users.id','=','role_user.user_id')
        ->leftJoin('roles','roles.id','=','role_user.role_id')
        ->leftJoin('branches','branches.id','=','users.branch_id')
        ->leftJoin('types','types.id','=','users.type_id')
        // ->leftJoin('employees','employees.id','=','users.emp_id')
        ->groupBy('users.id');    
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->parameters($this->getBuilderParameters());
    }

    
    /**
     * Get columns.
     *
     * @return array
     */
    // protected function getColumns()
    // {
    //     return [
    //         [
    //             'name'=>'id',
    //             'data'=>'id',
    //             'title'=>'الكود',                
    //         ],
    //         [
    //             'name'=>'name',
    //             'data'=>'name',
    //             'title'=>'الاسم',                
    //         ],
    //         [
    //             'name'=>'user_name',
    //             'data'=>'user_name',
    //             'title'=>'اسم المستخدم',                
    //         ],
    //         [
    //             'name'=>'branch',
    //             'data'=>'branch',
    //             'title'=>'الفرع',                
    //         ],
    //         [
    //             'name'=>'types.name',
    //             'data'=>'type',
    //             'title'=>'الوظيفة', 
    //         ], 

    //         [
    //             'data'=>'role_name',
    //             'name'=>'roles.display_name',
    //             'title'=>'مستوي الصلاحيات', 
    //         ],
            
    //         [
    //             'name'=>'action',
    //             'data'=>'action',
    //             'title'=>'عمليات',   
    //             'exportable' => false,
    //             'printable' => false,
    //             'searchable' => false,
    //             'orderable' => false,
                            
    //         ], 
                   
    //     ];
    // }

    // protected function getColumns()
    // {
    //     return [
    //         [
    //             'name' => 'id',
    //             'data' => 'id',
    //             'title' => 'الكود',
    //         ],
    //         [
    //             'name' => 'name',
    //             'data' => 'name',
    //             'title' => 'الاسم',
    //         ],
    //         [
    //             'name' => 'user_name',
    //             'data' => 'user_name',
    //             'title' => 'اسم المستخدم',
    //         ],
    //         [
    //             'name' => 'branch',
    //             'data' => 'branch',
    //             'title' => 'الفرع',
    //         ],
    //         [
    //             'name' => 'types.name',
    //             'data' => 'type',
    //             'title' => 'الوظيفة',
    //         ],
    //         [
    //             'data' => 'role_name',
    //             'name' => 'roles.display_name',
    //             'title' => 'مستوى الصلاحيات',
    //         ],
    //         [
    //             'name' => 'option',
    //             'data' => null,
    //             'title' => 'اضافة صلاحية المرتجعات',
    //             'render' => 'function() {
    //                 return `<input type="checkbox" name="option" value="${data.id}" onchange="sendStatus(${data.id})" id="option_${data.id}" ${data.has_returns == 1 ? "checked" : "" }>`;
    //             }',
    //             'exportable' => false,
    //             'printable' => false,
    //             'searchable' => false,
    //             'orderable' => false,
    //         ],
    //         [
    //             'name' => 'action',
    //             'data' => 'action',
    //             'title' => 'عمليات',
    //             'exportable' => false,
    //             'printable' => false,
    //             'searchable' => false,
    //             'orderable' => false,
    //         ],
            
    //     ];
    // }
    
    protected function getColumns()
    {
        return [
            [
                'name' => 'id',
                'data' => 'id',
                'title' => 'الكود',
            ],
            [
                'name' => 'name',
                'data' => 'name',
                'title' => 'الاسم',
            ],
            [
                'name' => 'user_name',
                'data' => 'user_name',
                'title' => 'اسم المستخدم',
            ],
            [
                'name' => 'branch',
                'data' => 'branch',
                'title' => 'الفرع',
            ],
            [
                'name' => 'types.name',
                'data' => 'type',
                'title' => 'الوظيفة',
            ],
            [
                'data' => 'role_name',
                'name' => 'roles.display_name',
                'title' => 'مستوى الصلاحيات',
            ],
            [
                'name' => 'option',
                'data' => null,
                'title' => 'صلاحية المرتجعات',
                'render' => 'function() {
                    return `<input type="checkbox" name="option" value="${data.id}" onchange="sendStatus(${data.id})" id="option_${data.id}" ${data.has_returns == 1 ? "checked" : "" }>`;
                }',
                'exportable' => false,
                'printable' => false,
                'searchable' => false,
                'orderable' => false,
            ],
            [
                'name' => 'option2',
                'data' => null,
                'title' => 'صلاحية تعديل الفواتير',
                'render' => 'function() {
                    return `<input type="checkbox" name="option2" value="${data.id}" onchange="sendStatus2(${data.id})" id="option2_${data.id}" ${data.has_edit_orders == 1 ? "checked" : "" }>`;
                }',
                'exportable' => false,
                'printable' => false,
                'searchable' => false,
                'orderable' => false,
            ],
            [
                'name' => 'option3',
                'data' => null,
                'title' => 'صلاحية تعديل اوامر التشغيل',
                'render' => 'function() {
                    return `<input type="checkbox" name="option3" value="${data.id}" onchange="sendStatus3(${data.id})" id="option3_${data.id}" ${data.has_edit_operation_order == 1 ? "checked" : "" }>`;
                }',
                'exportable' => false,
                'printable' => false,
                'searchable' => false,
                'orderable' => false,
            ],
            
            [
                'name' => 'option4',
                'data' => null,
                'title' => 'صلاحية تعديل اوامر التشغيل الخارجي',
                'render' => 'function() {
                    return `<input type="checkbox" name="option4" value="${data.id}" onchange="sendStatus4(${data.id})" id="option4_${data.id}" ${data.has_edit_operation_order_out == 1 ? "checked" : "" }>`;
                }',
                'exportable' => false,
                'printable' => false,
                'searchable' => false,
                'orderable' => false,
            ],
            [
                'name' => 'action',
                'data' => 'action',
                'title' => 'عمليات',
                'exportable' => false,
                'printable' => false,
                'searchable' => false,
                'orderable' => false,
            ],
            
        ];
    }
    /**
    *Get the builder parameters
    *@return array
    */
    public function getBuilderParameters()
    {
        return [
            'dom' => 'Bfrtip',
            'buttons' => ['excel', 'print', 'reset', 'reload'],
            'language' => [
                      'url' => url('/vendor/datatables/arabic.json')
            ],
            
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'user\Users_' . date('YmdHis');
    }
}
