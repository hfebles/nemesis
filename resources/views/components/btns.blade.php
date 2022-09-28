@if(isset($back))
    @if (isset($back['show']))
        <a href="{{ $back['route'] }}" class="btn btn-sm btn-dark btn-icon-split ml-auto">
            <span class="icon text-white-50">
                <i class="fas fa-chevron-circle-left"></i>
            </span>
            <span class="text">Atras</span>
        </a>

        
        @elseif(isset($back['url']))
        <a href="{{ $back['url'] }}" class="btn btn-sm btn-dark btn-icon-split ml-auto">
        <span class="icon text-white-50">
            <i class="fas fa-chevron-circle-left"></i>
        </span>
        <span class="text">Atras</span>
        </a>
        @else
   
    
        <a href="{{ route($back) }}" class="btn btn-sm btn-dark btn-icon-split ml-auto">
        <span class="icon text-white-50">
            <i class="fas fa-chevron-circle-left"></i>
        </span>
        <span class="text">Atras</span>
    </a>
    
    @endif

@endif

@if(isset($backShow))
<a href="{{ route($backShow['route'], $backShow['id']) }}" class="btn btn-sm btn-dark btn-icon-split ml-auto">
    <span class="icon text-white-50">
        <i class="fas fa-chevron-circle-left"></i>
    </span>
    <span class="text">Atras</span>
</a>
@endif


@if(isset($create))
    @if(Gate::check('adm-create') || Gate::check($group.'-create'))
        @if (isset($create['btn_type']))
        <button class="btn btn-sm btn-success btn-icon-split @if(isset($back) || isset($edit)) ml-3 @else ml-auto @endif" data-bs-toggle="offcanvas"
            data-bs-target="#offcanvasWithBothOptions"
            aria-controls="offcanvasWithBothOptions">
            <span class="icon text-white-50">
                <i class="fas fa-plus-circle"></i>
            </span>
            <span class="text">{{$create['name']}}</span>
        </button> 
        @else
        <a href="{!! route($create['route']) !!}" class="btn btn-sm btn-success btn-icon-split @if(isset($back) || isset($edit)) ml-3 @else ml-auto @endif">
            <span class="icon text-white-50">
                <i class="fas fa-plus-circle"></i>
            </span>
            <span class="text">{{$create['name']}}</span>
        @endif
        
        </a>
    @endcan
@endif

@if(isset($edit))
    @if(Gate::check('adm-create') || Gate::check($group.'-edit'))
    <a href="{{ route($edit['route'], $edit['id']) }}" class="btn btn-sm btn-warning btn-icon-split @if(isset($back) || isset($create)) ml-3 @else ml-auto @endif">
            <span class="icon text-white-50">
                <i class="fas fa-user-edit"></i>
            </span>
            <span class="text">Editar</span>
        </a>
    @endcan
@endif


@if(isset($delete) && $delete == true )
    @if(Gate::check('adm-create') || Gate::check($group.'-delete'))
        <a class="btn btn-danger btn-icon-split ml-3 btn-sm" data-toggle="modal" data-target="#deleteModal">
            <span class="icon text-white-50">
                <i class="fas fa-trash"></i>
            </span>
            <span class="text">{{$delete['name']}}</span>
        </a>
    @endcan
@endif

