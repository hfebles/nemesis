        @if (isset($conf['caption']))
            <p class="text-muted fs-4 p-2 mb-2">{{$conf['caption']}}</p>
        @endif
        
        <table class="{{$conf['c_table']}}   table-sm mb-0">        
            <thead class="{{$conf['c_thead']}}">
                <tr>
                    @foreach ($conf['ths'] as $k => $th)
                    <th class="text-uppercase font-weight-bolder {{$conf['c_ths'][$k]}}"  width="{{$conf['w_ts'][$k]}}%">{{$th}}</th>
                    @endforeach
                    @if ($conf['edit_modal'] == true)
                        <th width="3%" class="{{$conf['c_ths'][$k]}}" ></th>
                    @endif
                </tr>
            </thead>

            <tbody id="body-table">
            @for ($o = 0; $o < count($conf['data']); $o++)
            @if ($conf['show'] == true)
                <tr style="cursor:pointer;" onclick="window.location='{{$conf['url']}}/{{$conf['data'][$o][$conf['id']]}}';">
            
            @elseif ($conf['edit'] == true)
                @if (Gate::check($conf['group'].'-edit') || Gate::check('adm-edit'))
                
                    <tr style="cursor:pointer;" onclick="window.location='{{$conf['url']}}/{{$conf['data'][$o][$conf['id']]}}/edit';">
                @endif
            @else
                <tr>
            @endif   
                    <td class="{{$conf['c_ths'][0]}}" >{{++$conf['i']}}</td>
                    @for ($oa = 0; $oa < count($conf['tds']); $oa++)
                        <td class="{{$conf['c_ths'][$oa+1]}}" >
                        @if(is_string($conf['data'][$o][$conf['tds'][$oa]]) == false && is_null($conf['data'][$o][$conf['tds'][$oa]]) == false)
                            {{ number_format($conf['data'][$o][$conf['tds'][$oa]], '2', ',', '.')  }}
                        @elseif(is_null($conf['data'][$o][$conf['tds'][$oa]]) == true)
                            {{$conf['data'][$o][$conf['tds'][$oa]] ?? 'N/A'}}
                        @elseif(DateTime::createFromFormat('Y-m-d', $conf['data'][$o][$conf['tds'][$oa]]) == true)
                            {{date('d-m-Y', strtotime($conf['data'][$o][$conf['tds'][$oa]]))}}
                        @else
                            {{$conf['data'][$o][$conf['tds'][$oa]] ?? 'N/A'}}
                        @endif
                        </td>
                    @endfor
                    @if ($conf['edit_modal'] == true )
                        @if (Gate::check($conf['group'].'-edit') || Gate::check('adm-edit'))
                            <td width="3%" class="{{$conf['c_ths'][0]}}" ><a class="btn btn-warning mb-0" id="editCompany" onclick="editModal('{{$conf['data'][$o][$conf['id']]}}');"><i class="fas fa-pen"></i></a></td>
                        @endif
                        
                    @endif
                </tr>
            @endfor
            </tbody>
        </table>
<div id="paginacion" class="d-flex justify-content-center mt-3 nb-0">
    {!! $conf['data']->render() !!}      
</div>
