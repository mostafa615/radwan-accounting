@extends('layout.app')

@section('title', 'لوحة الإحصائيات')
@section('sub-title', 'من ' . $from . ' إلى ' . $to)

@push('styles')
<style>
  .kpi { border-radius: 6px; padding: 16px 18px; color: #fff; position: relative; overflow: hidden; min-height: 108px; }
  .kpi .k-label { font-size: 13px; opacity: .9; }
  .kpi .k-value { font-size: 26px; font-weight: 700; line-height: 1.25; margin-top: 4px; }
  .kpi .k-sub   { font-size: 12px; opacity: .85; margin-top: 3px; }
  .kpi .k-icon  { position: absolute; left: 12px; bottom: 8px; font-size: 46px; opacity: .16; }
  .kpi .k-chg   { font-size: 12px; font-weight: 700; margin-top: 5px; }
  .k-aqua{background:#00c0ef}.k-yellow{background:#f39c12}.k-green{background:#00a65a}
  .k-red{background:#dd4b39}.k-blue{background:#3c8dbc}.k-purple{background:#605ca8}
  .chart-box { position: relative; height: 320px; }
  .chart-box-sm { position: relative; height: 260px; }
  .tbl-tight > tbody > tr > td, .tbl-tight > thead > tr > th { padding: 6px 8px; font-size: 13px; }
  .alert-tile { display:block; border-radius:6px; padding:14px; color:#fff; text-align:center; margin-bottom:14px; text-decoration:none; }
  .alert-tile:hover { color:#fff; opacity:.9; text-decoration:none; }
  .alert-tile .a-n { font-size:24px; font-weight:700; }
  .alert-tile .a-l { font-size:12px; }
  .bar-mini { height:6px; background:#eee; border-radius:3px; overflow:hidden; }
  .bar-mini > span { display:block; height:100%; background:#dd4b39; }
  .num { font-variant-numeric: tabular-nums; }
</style>
@endpush

@section('content')

{{-- ================= الفلاتر ================= --}}
<div class="box box-solid">
  <div class="box-body">
    <form method="GET" action="{{ route('dashboard') }}" class="form-inline">
      <div class="form-group">
        <label>من تاريخ</label>
        <input type="date" name="date_from" value="{{ $from }}" class="form-control">
      </div>
      <div class="form-group" style="margin-right:10px">
        <label>إلى تاريخ</label>
        <input type="date" name="date_to" value="{{ $to }}" class="form-control">
      </div>
      <div class="form-group" style="margin-right:10px">
        <label>الفرع</label>
        <select name="branch_id" class="form-control">
          <option value="">كل الفروع</option>
          @foreach($branches as $b)
            <option value="{{ $b->id }}" {{ $branchId == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
          @endforeach
        </select>
      </div>
      <button type="submit" class="btn btn-primary" style="margin-right:10px">عرض</button>
      <a href="{{ route('dashboard') }}" class="btn btn-default">الشهر الحالي</a>
      <span class="text-muted" style="margin-right:14px; font-size:12px">
        المقارنة مع: {{ $prevFrom }} → {{ $prevTo }}
      </span>
    </form>
  </div>
</div>

{{-- ================= الصف ١: الكروت ================= --}}
<div class="row">
  @foreach($kpi as $c)
  <div class="col-lg-2 col-md-4 col-sm-6" style="margin-bottom:15px">
    <div class="kpi k-{{ $c['colour'] }}">
      <i class="fa {{ $c['icon'] }} k-icon"></i>
      <div class="k-label">{{ $c['label'] }}</div>
      <div class="k-value num">
        {{ number_format($c['value'], ($c['unit'] === 'ج.م' ? 0 : 0)) }}
        <small style="font-size:12px; font-weight:400">{{ $c['unit'] }}</small>
      </div>
      <div class="k-sub">{{ $c['sub'] }}</div>
      @if(!is_null($c['change']))
        <div class="k-chg">
          <i class="fa fa-arrow-{{ $c['change'] >= 0 ? 'up' : 'down' }}"></i>
          {{ abs($c['change']) }}%
          <span style="opacity:.8; font-weight:400">مقابل الفترة السابقة</span>
        </div>
      @endif
    </div>
  </div>
  @endforeach
</div>

{{-- ================= الصف ٢: الاتجاه ================= --}}
<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">المبيعات والمشتريات والتحصيلات — آخر ١٢ شهر</h3>
      </div>
      <div class="box-body">
        <div class="chart-box"><canvas id="trendChart"></canvas></div>
      </div>
    </div>
  </div>
</div>

{{-- ================= الصف ٣: الفروع ================= --}}
<div class="row">
  <div class="col-md-5">
    <div class="box box-info">
      <div class="box-header with-border"><h3 class="box-title">مبيعات كل فرع</h3></div>
      <div class="box-body"><div class="chart-box-sm"><canvas id="branchChart"></canvas></div></div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="box box-info">
      <div class="box-header with-border">
        <h3 class="box-title">متوسط الفاتورة لكل فرع</h3>
        <small class="pull-left text-muted">يفرّق الجملة عن التجزئة</small>
      </div>
      <div class="box-body"><div class="chart-box-sm"><canvas id="ticketChart"></canvas></div></div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="box box-info">
      <div class="box-header with-border"><h3 class="box-title">أعمار الديون</h3></div>
      <div class="box-body"><div class="chart-box-sm"><canvas id="agingChart"></canvas></div></div>
    </div>
  </div>
</div>

{{-- ================= الصف ٤: الجداول ================= --}}
<div class="row">
  <div class="col-md-6">
    <div class="box box-danger">
      <div class="box-header with-border">
        <h3 class="box-title">أعلى العملاء مديونية</h3>
      </div>
      <div class="box-body table-responsive no-padding">
        <table class="table table-hover tbl-tight">
          <thead><tr><th>العميل</th><th class="text-left">الرصيد</th><th style="width:110px">% من الإجمالي</th></tr></thead>
          <tbody>
          @forelse($topDebtors as $d)
            <tr>
              <td>{{ $d->name }}</td>
              <td class="text-left num">{{ number_format($d->bal, 2) }}</td>
              <td>
                <div class="bar-mini"><span style="width:{{ min($d->share, 100) }}%"></span></div>
                <small class="text-muted">{{ $d->share }}%</small>
              </td>
            </tr>
          @empty
            <tr><td colspan="3" class="text-center text-muted">لا يوجد</td></tr>
          @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="box box-success">
      <div class="box-header with-border"><h3 class="box-title">أعلى الأصناف مبيعًا في الفترة</h3></div>
      <div class="box-body table-responsive no-padding">
        <table class="table table-hover tbl-tight">
          <thead><tr><th>الصنف</th><th class="text-left">الكمية</th><th class="text-left">القيمة</th></tr></thead>
          <tbody>
          @forelse($topItems as $i)
            <tr>
              <td>{{ $i->name }}</td>
              <td class="text-left num">{{ number_format($i->qty, 2) }}</td>
              <td class="text-left num">{{ number_format($i->value, 2) }}</td>
            </tr>
          @empty
            <tr><td colspan="3" class="text-center text-muted">لا حركة في الفترة</td></tr>
          @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-4">
    <div class="box box-primary">
      <div class="box-header with-border"><h3 class="box-title">أرصدة الخزن</h3></div>
      <div class="box-body table-responsive no-padding">
        <table class="table table-hover tbl-tight">
          <thead><tr><th>الخزنة</th><th class="text-left">الرصيد</th></tr></thead>
          <tbody>
          @foreach($safes as $s)
            <tr>
              <td>{{ $s->name }}</td>
              <td class="text-left num {{ $s->bal < 0 ? 'text-red' : '' }}">{{ number_format($s->bal, 2) }}</td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="box box-warning">
      <div class="box-header with-border">
        <h3 class="box-title">أصناف رصيدها صفر أو سالب</h3>
      </div>
      <div class="box-body table-responsive no-padding">
        <table class="table table-hover tbl-tight">
          <thead><tr><th>الصنف</th><th>المخزن</th><th class="text-left">الرصيد</th></tr></thead>
          <tbody>
          @forelse($lowStock as $l)
            <tr>
              <td>{{ $l->name }}</td>
              <td>{{ $l->store ?: '—' }}</td>
              <td class="text-left num {{ $l->quantity < 0 ? 'text-red' : 'text-muted' }}">
                {{ number_format($l->quantity, 2) }}
              </td>
            </tr>
          @empty
            <tr><td colspan="3" class="text-center text-muted">لا يوجد</td></tr>
          @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="box box-default">
      <div class="box-header with-border"><h3 class="box-title">المصنع في الفترة</h3></div>
      <div class="box-body">
        <table class="table tbl-tight">
          <tr><td>أوامر التشغيل</td><td class="text-left num">{{ number_format($factory['orders']) }}</td></tr>
          <tr>
            <td>خامة داخلة <small class="text-muted">(وزن)</small></td>
            <td class="text-left num">{{ number_format($factory['input'], 2) }}</td>
          </tr>
          <tr>
            <td>خردة + فضل <small class="text-muted">(وزن)</small></td>
            <td class="text-left num">{{ number_format($factory['waste'], 2) }}</td>
          </tr>
          <tr style="background:#f9f9f9">
            <td><strong>نسبة الهالك</strong> <small class="text-muted">(وزن ÷ وزن)</small></td>
            <td class="text-left num">
              <strong>{{ is_null($factory['waste_pct']) ? '—' : $factory['waste_pct'] . '%' }}</strong>
            </td>
          </tr>
          <tr>
            <td>ناتج التشغيل <small class="text-muted">(عدد قطع)</small></td>
            <td class="text-left num">{{ number_format($factory['output']) }}</td>
          </tr>
        </table>
        <p class="text-muted" style="font-size:11px; margin:6px 0 0">
          الناتج مسجّل بالقطعة والخامة بالوزن، فمينفعش يتقارنوا بنسبة مباشرة.
          نسبة الهالك وزن ÷ وزن فهي سليمة.
        </p>
      </div>
    </div>
  </div>
</div>

{{-- ================= الصف ٥: التنبيهات ================= --}}
<div class="row">
  <div class="col-md-12">
    <div class="box box-solid">
      <div class="box-header with-border"><h3 class="box-title">بنود تحتاج انتباه</h3></div>
      <div class="box-body">
        <div class="row">
          @foreach($alerts as $a)
          <div class="col-md-2 col-sm-4 col-xs-6">
            <a href="{{ $a['url'] }}" class="alert-tile k-{{ $a['colour'] }}">
              <i class="fa {{ $a['icon'] }}" style="font-size:20px; opacity:.8"></i>
              <div class="a-n num">{{ number_format($a['n']) }}</div>
              <div class="a-l">{{ $a['label'] }}</div>
            </a>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/chart.umd.min.js') }}"></script>
<script>
(function () {
  if (typeof Chart === 'undefined') { return; }

  Chart.defaults.font.family = "inherit";
  Chart.defaults.plugins.legend.labels.boxWidth = 14;

  var money = function (v) {
    if (Math.abs(v) >= 1e6) return (v / 1e6).toFixed(1) + 'M';
    if (Math.abs(v) >= 1e3) return (v / 1e3).toFixed(0) + 'K';
    return v;
  };
  var fmt = function (v) { return Number(v).toLocaleString('en-US', {maximumFractionDigits: 2}); };
  var tip = { callbacks: { label: function (c) { return c.dataset.label + ': ' + fmt(c.parsed.y != null ? c.parsed.y : c.parsed); } } };

  // ---- الصف ٢: الاتجاه ----
  var trend = @json($trend);
  new Chart(document.getElementById('trendChart'), {
    data: {
      labels: trend.map(function (r) { return r.month; }),
      datasets: [
        { type: 'line', label: 'المبيعات', data: trend.map(function (r) { return r.sales; }),
          borderColor: '#00a65a', backgroundColor: 'rgba(0,166,90,.12)', fill: true, tension: .3, borderWidth: 2, pointRadius: 3 },
        { type: 'line', label: 'التحصيلات', data: trend.map(function (r) { return r.collected; }),
          borderColor: '#3c8dbc', backgroundColor: 'rgba(60,141,188,.10)', fill: true, tension: .3, borderWidth: 2, pointRadius: 3 },
        { type: 'bar', label: 'المشتريات', data: trend.map(function (r) { return r.purchases; }),
          backgroundColor: 'rgba(243,156,18,.65)', borderRadius: 3 }
      ]
    },
    options: {
      responsive: true, maintainAspectRatio: false, interaction: { mode: 'index', intersect: false },
      plugins: { tooltip: tip, legend: { position: 'top' } },
      scales: { y: { beginAtZero: true, ticks: { callback: money } } }
    }
  });

  // ---- الصف ٣ ----
  var br = @json($branchStats);
  var palette = ['#00c0ef', '#00a65a', '#f39c12', '#605ca8', '#dd4b39', '#3c8dbc'];

  new Chart(document.getElementById('branchChart'), {
    type: 'bar',
    data: {
      labels: br.map(function (r) { return r.name; }),
      datasets: [{ label: 'المبيعات', data: br.map(function (r) { return r.v; }),
                   backgroundColor: palette, borderRadius: 4 }]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      plugins: { legend: { display: false }, tooltip: tip },
      scales: { y: { beginAtZero: true, ticks: { callback: money } } }
    }
  });

  new Chart(document.getElementById('ticketChart'), {
    type: 'bar',
    data: {
      labels: br.map(function (r) { return r.name; }),
      datasets: [{ label: 'متوسط الفاتورة', data: br.map(function (r) { return r.avg_ticket; }),
                   backgroundColor: '#605ca8', borderRadius: 4 }]
    },
    options: {
      indexAxis: 'y',
      responsive: true, maintainAspectRatio: false,
      plugins: { legend: { display: false }, tooltip: tip },
      scales: { x: { beginAtZero: true, ticks: { callback: money } } }
    }
  });

  var aging = @json($aging);
  new Chart(document.getElementById('agingChart'), {
    type: 'doughnut',
    data: {
      labels: Object.keys(aging),
      datasets: [{ data: Object.keys(aging).map(function (k) { return aging[k]; }),
                   backgroundColor: ['#00a65a', '#00c0ef', '#f39c12', '#dd4b39', '#999'] }]
    },
    options: {
      responsive: true, maintainAspectRatio: false, cutout: '55%',
      plugins: {
        legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } },
        tooltip: { callbacks: { label: function (c) { return c.label + ': ' + fmt(c.parsed); } } }
      }
    }
  });
})();
</script>
@endpush
