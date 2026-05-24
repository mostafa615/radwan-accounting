@extends('layout.app')
@section('title', 'تقرير النقلات')
@section('sub-title', 'التقارير')
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-body">
        <div class="table-responsive">
          <table class="table table-bordered text-center">
            <thead>
            <tr>
              <th>اسم السائق</th>
              <th>من تاريخ</th>
              <th>الى تاريخ</th>
            </tr>
            </thead>
            <tbody>
              <tr>
                <td>{{$resource->name ?? '.. .. ..'}}</td>
                <td>{{$dateFrom}}</td>
                <td>{{$dateTo}}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-body">
        <div class="table-responsive">
          <table class="table table-bordered" width="100%" id="example_15">
            <thead>
              <tr class="bg-primary">
                {{-- <td>الكود</td> --}}
                <td>التاريخ</td>
                <td>العميل</td>
                <td>الفرع</td>
                <td>النوع</td>
                <td>المبلغ</td>
              </tr>
            </thead>
            <tbody>
              <?php
                $totalCost = 0;
              ?>
              @foreach($transports as $transport)
              <?php
                if($transport->type == 'out') {
                  $totalCost -= $transport->cost;
                } else {
                  $totalCost += $transport->cost;
                }
              ?>
              <tr>
                {{-- <td>{{$transport->id}}</td> --}}
                <td>{{$transport->date}}</td>
                <td>{{$transport->client}}</td>
                <td>{{$transport->branch}}</td>
                <td>
                  @if($transport->type == 'in')
                  <span class="text-success">
                    <i class="fa fa-check"></i> بيع
                  </span>
                  @else
                  <span class="text-danger">
                    <i class="fa fa-ban"></i> مرتجع
                  </span>
                  @endif
                </td>
                <td>{{$transport->cost}}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <hr/>
        <div class="table-responsive">
          <table class="table table-bordered text-center">
            <thead>
            <tr>
              <th>الاجمالى</th>
              <th>نسبة السائق</th>
              <th>المبلغ المستحق</th>
            </tr>
            </thead>
            <tbody>
              <?php
                $transportPercent = \App\Models\Setting::first()->transport_percent;  
              ?>
              <tr>
                <td>{{$totalCost}}</td>
                <td>{{$transportPercent}}</td>
                <td>{{$totalCost * ($transportPercent / 100)}}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection