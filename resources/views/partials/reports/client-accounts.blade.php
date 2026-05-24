<!-- Modal -->
<div class="modal fade" id="clientAccountsModal" tabindex="-1" role="dialog" aria-labelledby="clientAccountsModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="clientAccountsModalLabel">كشف حساب عميل</h4>
      </div>
      <form class="validate" action="{{route('reports.client-accounts')}}" method="GET">
      <div class="modal-body">
       <div class="row">
      
        <div class="col-md-12">
            <div class="form-group">
                <label for="date_client_accounts_from">من</label>
                <input required type="text" class="form-control date" name="date_client_accounts_from" id="date_client_accounts_from">
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label for="date_client_accounts_to">الي</label>
                <input  required type="text" class="form-control date" name="date_client_accounts_to" id="date_client_accounts_to">
            </div>
        </div>


         <div class="col-md-12">
            <div class="form-group">
                <label for="client_id">العميل</label>
                <select name="client_id" id="client_id">
                    @foreach ($clients as $client)
                        <option value="{{$client->id}}">{{$client->name}}</option>
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
