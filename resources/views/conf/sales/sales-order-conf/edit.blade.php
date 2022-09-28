@extends('layouts.app')

@section('title-section', $conf['title-section'])

@section('btn')
<x-btns :back="$conf['back']" :group="$conf['group']" />
@endsection

@section('content')
{!! Form::model($data[0], ['novalidate', 'class' => 'needs-validation', 'method' => 'PATCH', 'route' => ['order-config.update', $data[0]->id_sale_order_configuration]]) !!}
<div class="row">
    <x-cards>
        <table class="table table-bordered table-sm">
            <tr>
                <td>Correlativo:</td>
                <td>{!! Form::text('correlative_sale_order_configuration', null, array('id' => 'correlative_sale_order_configuration', 'autocomplete' => 'off','required', 'class' => 'form-control form-control-sm')) !!}</td>
            </tr>
            <tr>
                <td>Nombre de impresión:</td>
                <td>{!! Form::text('print_name_sale_order_configuration', null, array('id' => 'print_name_sale_order_configuration', 'autocomplete' => 'off','required', 'class' => 'form-control form-control-sm')) !!}</td>
            </tr>
            <tr>
                <td>Numero de control:</td>
                <td>{!! Form::text('control_number_sale_order_configuration', null, array('id' => 'control_number_sale_order_configuration', 'autocomplete' => 'off','required', 'class' => 'form-control form-control-sm')) !!}</td>
            </tr>
            {{-- <tr>
                <td>Cuenta contable:</td>
                <td>
                
                <select name="id_sub_ledger_account" onchange="selection(this.value);" class="form-select">
                @for ($k = 0; $k < count($dataG); $k++)
                <optgroup label="{{$dataG[$k]->name_group}}">
                    @for ($i = 0; $i < count($sg[$k]); $i++)
                    
                            @for ($o = 0; $o < count($dataLA[$k][$i]); $o++)
                                    <option value="{{$dataLA[$k][$i][$o]->id_ledger_account}}|{{$sg[$k][$i]->id_sub_group}}" class="p-1" >
                                    
                                        {{$dataG[$k]->code_group}}.{{$sg[$k][$i]->code_sub_group}}.{{$dataLA[$k][$i][$o]->code_ledger_account}} 
                                        - {{$dataLA[$k][$i][$o]->name_ledger_account}}
                                    </option>
                                @for ($d = 0; $d < count($dataSLA[$k][$i][$o]); $d++)
                                        <option value="{{$dataSLA[$k][$i][$o][$d]->id_sub_ledger_account}}|{{$dataLA[$k][$i][$o]->id_ledger_account}}" class="p-1" >
                                        
                                            {{trim($dataG[$k]->code_group)}}.{{$sg[$k][$i]->code_sub_group}}.{{$dataLA[$k][$i][$o]->code_ledger_account}}.{{$dataSLA[$k][$i][$o][$d]->code_sub_ledger_account}} 
                                            - {{$dataSLA[$k][$i][$o][$d]->name_sub_ledger_account}}
                                        </option>
                                    @for ($c = 0; $c < count($s2[$k][$i][$o][$d]); $c++)
                                        
                                            <option value="{{$s2[$k][$i][$o][$d][$c]->id_sub_ledger_account2}}|{{$dataSLA[$k][$i][$o][$d]->id_sub_ledger_account}}"  class="p-1">
                                            
                                                {{$dataG[$k]->code_group}}.{{$sg[$k][$i]->code_sub_group}}.{{$dataLA[$k][$i][$o]->code_ledger_account}}.{{$dataSLA[$k][$i][$o][$d]->code_sub_ledger_account}}.{{$s2[$k][$i][$o][$d][$c]->code_sub_ledger_account2}} 
                                            - {{$s2[$k][$i][$o][$d][$c]->name_sub_ledger_account2}}</option>
                                        @for ($g = 0; $g < count($s3[$k][$i][$o][$d][$c]); $g++)
                                        
                                                <option value="{{$s3[$k][$i][$o][$d][$c][$g]->id_sub_ledger_account3}}|{{$s2[$k][$i][$o][$d][$c]->id_sub_ledger_account2}}" class="p-1">
                                                    {{$dataG[$k]->code_group}}.{{$sg[$k][$i]->code_sub_group}}.{{$dataLA[$k][$i][$o]->code_ledger_account}}.{{$dataSLA[$k][$i][$o][$d]->code_sub_ledger_account}}.{{$s2[$k][$i][$o][$d][$c]->code_sub_ledger_account2}}.{{$s3[$k][$i][$o][$d][$c][$g]->code_sub_ledger_account3}} 
                                                 - {{$s3[$k][$i][$o][$d][$c][$g]->name_sub_ledger_account3}}</option>
                                            @for ($j = 0; $j < count($s4[$k][$i][$o][$d][$c][$g]); $j++)
                                            
                                                    <option value="{{$s4[$k][$i][$o][$d][$c][$g][$j]->id_sub_ledger_account4}}|{{$s3[$k][$i][$o][$d][$c][$g]->id_sub_ledger_account3}}" class="p-1">
                                                        {{$dataG[$k]->code_group}}.{{$sg[$k][$i]->code_sub_group}}.{{$dataLA[$k][$i][$o]->code_ledger_account}}.{{$dataSLA[$k][$i][$o][$d]->code_sub_ledger_account}}.{{$s2[$k][$i][$o][$d][$c]->code_sub_ledger_account2}}.{{$s3[$k][$i][$o][$d][$c][$g]->code_sub_ledger_account3}}.{{$s4[$k][$i][$o][$d][$c][$g][$j]->code_sub_ledger_account4}} 
                                                    - {{$s4[$k][$i][$o][$d][$c][$g][$j]->name_sub_ledger_account4}}</option>
                                            @endfor
                                        @endfor
                                    @endfor
                                @endfor
                            @endfor
                        @endfor
                        </optgroup>
                        @endfor

                </select>
                </td>
            </tr> --}}

        </table>

    </x-cards>
</div>
<x-btns-save />
{!! Form::close() !!}
@endsection


@section('js')
<script>
    function selection(value){


        
    }
</script>
@endsection