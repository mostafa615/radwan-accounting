<?php

namespace App\DataTables;

use App\Models\Group;
use Yajra\DataTables\Services\DataTable;

class GroupsDataTable extends DataTable
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
             ->addColumn('action', 'groups.actions');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\client $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Group $model)
    {
        return $model->select('name','id','notes', 'price', 'edit_permission_s', 'edit_permission_q', 'edit_permission_o')
        ->latest();
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
    protected function getColumns()
    {
        return [
            [
                'name'=>'name',
                'data'=>'name',
                'title'=>'الاسم',                
            ],
            [
                'name'=>'price',
                'data'=>'price',
                'title'=>'السعر',                
            ],
            [
                'name' => 'option',
                'data' => null,
                'title' => 'تفعيل التعديل بصلاحية',
                'render' => 'function() {
                    return `
                        <div class="form-check" style="display: inline-block;">
                            <input class="form-check-input" type="checkbox" name="option1" value="${data.id}" onchange="sendStatus1(${data.id})" id="option1_${data.id}" ${data.edit_permission_s == 1 ? "checked" : "" }>
                            <label class="form-check-label"> سبتية </label>
                        </div>

                        <div class="form-check" style="display: inline-block;">
                            <input class="form-check-input" type="checkbox" name="option2" value="${data.id}" onchange="sendStatus2(${data.id})" id="option2_${data.id}" ${data.edit_permission_q == 1 ? "checked" : "" }>
                            <label class="form-check-label"> قليوب </label>
                        </div>

                        <div class="form-check" style="display: inline-block;">
                            <input class="form-check-input" type="checkbox" name="option3" value="${data.id}" onchange="sendStatus3(${data.id})" id="option3_${data.id}" ${data.edit_permission_o == 1 ? "checked" : "" }>
                            <label class="form-check-label"> اكتوبر </label>
                        </div>
                    `;
                }',
                'exportable' => false,
                'printable' => false,
                'searchable' => false,
                'orderable' => false,
            ],
             [
                'name'=>'notes',
                'data'=>'notes',
                'title'=>'ملاحظات',                
            ], 
            [
                'name'=>'action',
                'data'=>'action',
                'title'=>'عمليات',   
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
        return 'Groups_' . date('YmdHis');
    }
}
