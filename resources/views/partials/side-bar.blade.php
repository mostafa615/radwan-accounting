<ul  class="sidebar-menu"  data-widget="tree">
    @if(auth()->user()->hasRole('admin'))
        <li class="{{request()->is('dashboard')?'active':''}}">
            <a href="{{route('dashboard')}}">
                <i class="fa fa-dashboard"></i> <span>لوحة الإحصائيات</span>
            </a>
        </li>
    @endif
    <li class="header"> العناصر الاساسية</li>
    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('branch_factor_respons') || auth()->user()->hasRole('safe_factor_response') || auth()->user()->hasRole('factor_response') || auth()->user()->can('system_factor'))
        <li>
            <a target="_blank" href="http://new.radwansteel.org/">
                <i class="fa fa-list-ol"></i> <span>سستم المصنع</span>
            </a>
        </li>
    @endif
    @if($perms->contains('users'))
        <li class="{{request()->is('*users*')?'active':''}}">
            <a href="{{route('users.index')}}">
                <i class="fa fa-user"></i> <span>المستخدمين</span>
            </a>
        </li>
        <li class="{{request()->is('*roles*')?'active':''}}">
            <a href="{{route('roles.index')}}">
                <i class="fa fa-key"></i> <span>مستوي الصلاحيات</span>
            </a>
        </li>
    @endif

    @if($perms->contains('branch'))
        <li class="{{request()->is('*branch*') ?'active':''}}">
            <a href="{{route('branch.index')}}">
                <i class="fa fa-list-ol"></i> <span>الفروع</span>
            </a>
        </li>
    @endif


    @if($perms->contains('clients'))
        <li class="{{request()->is('client*') ||  request()->is('accounts/client*') ?'active':''}}">
            <a href="{{route('clients.index')}}">
                <i class="fa fa-user"></i> <span>العملاء</span>
            </a>
        </li>

    @endif

    @if($perms->contains('actors'))
        <li class="{{request()->is('*actors*') ?'active':''}}">
            <a href="{{route('actors.index')}}">
                <i class="fa fa-users"></i> <span>ممثلين الموردين</span>
            </a>
        </li>
    @endif

    @if($perms->contains('supplier'))
        <li class="{{request()->is('supplier*') || request()->is('accounts/supplier*')?'active':''}}">
            <a href="{{route('suppliers.index')}}">
                <i class="fa fa-users"></i> <span>الموردين</span>
            </a>
        </li>
    @endif

    @if($perms->contains('mandators'))
        <li class="{{request()->is('mandators*')?'active':''}}">
            <a href="{{route('mandators.index')}}">
                <i class="fa fa-users"></i> <span>المندوبين</span>
            </a>
        </li>
    @endif
    @if($perms->contains('jobs'))
        <li class="{{request()->is('*jobs*') ?'active':''}}">
            <a href="{{route('jobs.index')}}">
                <i class="fa fa-users"></i> <span>الوظائف</span>
            </a>
        </li>
    @endif

    @if($perms->contains('employees'))
        <li class="{{request()->is('*employees*') ?'active':''}}">
            <a href="{{route('employees.index')}}">
                <i class="fa fa-users"></i> <span>الموظفين</span>
            </a>
        </li>
    @endif
    @if($perms->contains('stores'))
        <li class="{{request()->is('*stores*') ?'active':''}}">
            <a href="{{route('stores.index')}}">
                <i class="fa fa-user"></i> <span>المخازن</span>
            </a>
        </li>
    @endif

    @if($perms->contains('reposites'))
        <li class="{{request()->is('*reposites*') ?'active':''}}">
            <a href="{{route('reposites.index')}}">
                <i class="fa fa-money"></i> <span>الخزن</span>
            </a>
        </li>
    @endif

    @if($perms->contains('group'))
        <li class="{{request()->is('*group*') ?'active':''}}">
            <a href="{{route('group.index')}}">
                <i class="fa fa-list-ol"></i> <span>مجموعات</span>
            </a>
        </li>
    @endif
    @if($perms->contains('items'))
        <li class="{{request()->is('*items*') ?'active':''}}">
            <a href="{{route('items.index')}}">
                <i class="fa fa-list-ol"></i> <span>الاصناف</span>
            </a>
        </li>
    @endif


    <li class="header"> العمليات</li>

    @if($perms->contains('settings'))
        <li class="{{request()->is('*meta*') ?'active':''}}">
            <a href="{{route('meta.index')}}">
                <i class="fa fa-cogs"></i> <span> الاعدادات (الاصناف) </span>
            </a>
        </li>
    @endif



     @if($perms->contains('settings'))                         
    <li class="{{request()->is('transports*') ?'active':''}}">
        <a href="{{route('transports.index')}}">
            <i class="fa fa-truck"></i> <span> النقلات </span>
        </a>
    </li>
     @endif 

    @if($perms->contains('buy'))
        <li class="{{request()->is('orders-out*') ?'active':''}}">
            <a href="{{route('orders-out.index')}}">
                <i class="fa fa-chevron-left"></i> <span>شراء من مورد</span>
            </a>
        </li>

        
    @endif

    @if($perms->contains('return-buy'))
        <li class="{{request()->is('return-orders-out*') ?'active':''}}">
            <a href="{{route('return-orders-out.index')}}">
                <i class="fa fa-chevron-right"></i> <span>مرتجع الي مورد</span>
            </a>
        </li>        
    @endif


    @if($perms->contains('sell'))
        <li class="{{request()->is('orders-in*') ?'active':''}}">
            <a href="{{route('orders-in.index')}}">
                <i class="fa fa-chevron-right"></i> <span>بيع الي عميل</span>
            </a>
        </li>
        
    @endif

    @if($perms->contains('return-sell'))
    <li class="{{request()->is('return-orders-in*') ?'active':''}}">
        <a style="height: 50px" href="{{route('return-orders-in.index')}}">
            <i class="fa fa-chevron-left"></i> <span>مرتجع من عميل</span>
        </a>
    </li>        
    @endif

    @if($perms->contains('load') || auth()->user()->id == 1)
        <li class="{{request()->is('load*') ?'active':''}}">
            <a  href="{{route('load.index')}}">
                <i class="fa fa-truck"></i> <span>تحويل خامات  </span>
            </a>
        </li>
    @endif
    @if(auth()->user()->reposite || auth()->user()->id == 1)
        <li class="{{request()->is('transactions*') ?'active':''}}">
            <a href="{{route('transactions.index')}}">
                <i class="fa fa-money"></i> <span>تحويل نقدي</span>
            </a>
        </li>
    @endif
    @if($perms->contains('loan'))
        <li class="{{request()->is('*loan*') ?'active':''}}">
            <a href="{{route('loans.index')}}">
                <i class="fa fa-money"></i> <span>سلف</span>
            </a>
        </li>
    @endif
    @if($perms->contains('salary'))
        <li class="{{request()->is('*salary*') ?'active':''}}">
            <a href="{{route('salary.index')}}">
                <i class="fa fa-money"></i> <span>المرتبات</span>
            </a>
        </li>
    @endif

    @if($perms->contains('attendance'))
        <li class="{{request()->is('attendance*') ?'active':''}}">
            <a href="{{route('attendance.index')}}">
                <i class="fa  fa-calendar"></i> <span>الحضور والانصراف</span>
            </a>
        </li>

    @endif

    @if($perms->contains('daily'))
        <li class="{{request()->is('*daily*') ?'active':''}}">
            <a href="{{route('daily.index')}}">
                <i class="fa fa-list-ol"></i> <span>التعاملات اليومية</span>
            </a>
        </li>
    @endif
    
    
    <li class="header"> التقارير</li>

        <li class="{{str_contains(request()->route()->getName(),'reports')?'active':''}}">
            <a href="{{route('reports.index')}}">
                <i class="fa   fa-bar-chart"></i> <span>  التقارير</span>
            </a>
        </li>
    


</ul>