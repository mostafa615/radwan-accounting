<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OldItem extends Model
{
    protected $connection='old_db';
    protected $table='items';
    protected $fillable=[
        'Sub_Category_ID', 'Item_ID', 'Item_Code', 'Item_Name_A', 'Item_Name_E', 'Item_Description',
        'Photo_Path', 'Item_Type_ID', 'Supplier_ID', 'Item_Sales_Tax', 'Unit_ID1', 'Exchange1',
        'Price1', 'Unit_ID2', 'Exchange2', 'Price2', 'Request_Point', 'Request_Qty', 'Starting_Cost',
        'Lasting_Cost', 'Average_Cost', 'Minimum_Balance', 'Maxmum_Balance', 'Main_Item_Location',
        'Sub_Item_Location', 'Opening_Balance', 'Opening_Cost', 'Balance', 'Active', 'Entry_ID',
        'Entry_Name', 'Flag', 'Flag_N', 'Notice'
    ];
}
