<?php

namespace App\DataTables;

use App\Models\LoadDetail;
use Yajra\DataTables\Services\DataTable;

class LoadDetailsDataTable extends DataTable
{
     /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables($query);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\client $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(LoadDetail $model)
    {
        return $model
        ->select('load_details.id','items.name','quantity','load_details.notes','status')
        ->where('load_id',$this->request()->load->id)
        ->leftJoin('items','items.id','=','load_details.item_id');
        
       
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
                'name'=>'items.name',
                'data'=>'name',
                'title'=>'اسم الصنف',                
            ],
             [
                'name'=>'quantity',
                'data'=>'quantity',
                'title'=>'الكمية',                
            ], 
            [
                'name'=>'status',
                'data'=>null,
                'title'=>'الحالة',
                'render' => 'function() {
                    if(data.status == "accepted") {
                        return `<span class="text-success">مقبول <i class="fa fa-check" aria-hidden="true"></i></span>`;
                    }
                    else if(data.status == "refused") {
                        return `<span class="text-danger">مرفوض <i class="fa fa-ban" aria-hidden="true"></i></span>`;
                    }
                    else {
                        return `<span class="text-warning">فى الانتظار <i class="fa fa-clock-o" aria-hidden="true"></i></span>`;
                    }
                }'
            ],
            [
                'name'=>'notes',
                'data'=>'notes',
                'title'=>'ملاحظات',                
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
            // 
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'LoadDetails_' . date('YmdHis');
    }
}
