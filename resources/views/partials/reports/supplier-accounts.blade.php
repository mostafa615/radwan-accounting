<!-- Modal -->
<div class="modal fade" id="supplierAccountsModal" tabindex="-1" role="dialog" aria-labelledby="supplierAccountsModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="supplierAccountsModalLabel">كشف حساب مورد </h4>
      </div>
      <form class="validate" action="{{route('reports.supplier-accounts')}}" method="GET">
      <div class="modal-body">
       <div class="row">
      
        <div class="col-md-12">
            <div class="form-group">
                <label for="date_supplier_accounts_from">من</label>
                <input required type="text" class="form-control date" name="date_supplier_accounts_from" id="date_supplier_accounts_from">
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label for="date_supplier_accounts_to">الي</label>
                <input  required type="text" class="form-control date" name="date_supplier_accounts_to" id="date_supplier_accounts_to">
            </div>
        </div>


         <div class="col-md-12">
            <div class="form-group">
                <label for="supplier_id">المورد</label>
                <select name="supplier_id" id="supplier_id">
                    @foreach ($suppliers as $supplier)
                        <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>


       
    </div> 
    {{--  .row  --}}
      </div>
      {{--  .modal-body  --}}
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-sm btn-flat" data-dismiss="modal">الغاء</button>
        <button type="submit" class="btn btn-primary btn-sm btn-flat">موافق </button>
      </div>
    </form>
    </div>
  </div>
</div>
