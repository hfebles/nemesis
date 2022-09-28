
@if ($item['submenu'] == [])
    <li class="nav-item">
        @if ($item['href'] == 1)
            @if(Gate::check('adm-list') || Gate::check($item['grupo'].'-list'))
                <a class="nav-link"  href="{{$item['slug']}}"><i class="{{ $item['icono'] }}"></i><span>{{ $item['name'] }}</span> </a>
            
                @endif
                
        @else
            @if(Gate::check('adm-list') || Gate::check($item['grupo'].'-list'))
            <a class="nav-link" href="{{ route($item['slug']) }}"><i class="{{ $item['icono'] }}"></i><span>{{ $item['name'] }}</span> </a>
            @endif
        @endif
    </li>
    
@else
    @if(Gate::check('adm-list') || Gate::check($item['grupo'].'-list'))
    
    <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse_{{$item['id']}}"
                    aria-expanded="true" aria-controls="collapse_{{$item['id']}}"><i class="{{ $item['icono'] }}"></i><span>{{ $item['name'] }}</span> </a>
        <div id="collapse_{{$item['id']}}" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">

            @foreach ($item['submenu'] as $submenu)
            
                @if ($submenu['submenu'] == [])
                
                    @if ($submenu['href'] == 1)
                    
                    @if(Gate::check('adm-list') || Gate::check($submenu['grupo'].'-list'))
                    
                            <a class="collapse-item" href="{{$submenu['slug']}}"><i class="{{$submenu['icono']}} mr-2"></i><span> {{ $submenu['name'] }} </span></a>
                            @endif

                        
                    @else

                    @if(Gate::check('adm-list') || Gate::check($submenu['grupo'].'-list'))
                            <a class="collapse-item" href="{{ route($submenu['slug']) }}"><i class="{{$submenu['icono']}} mr-2"></i><span>{{ $submenu['name'] }}</span> </a>
                            
                            @endif
                    @endif
                @else
                    @if(Gate::check('adm-list'))
                        @include('conf.menu.partials.menu-item', [ 'item' => $submenu ])
                    @endif
                @endif
            @endforeach
            </div>
        </div>
    </li>
    @endif
@endif