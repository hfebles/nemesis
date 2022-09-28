<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<style>
    *{
        font-family: Arial;
        font-size: 12pt;
    }
    .text-center{
        text-align:center;
    }
    .text-end{
        text-align:right;
    }
    .text-start{
        text-align:left;
    }

    .table tr td, .table tr th, .table {

        border-collapse: collapse;
        padding:5px 2px;
        margin-top:20px;
        margin-bottom:10px;
    }

    #footer{
        border-collapse: collapse;
        padding:0px 10px;
        position: fixed;
        bottom:15%;
        width:100%;

    }
    


</style>
</head>
<body>
    <table align="right">
        <tr>
            <td><strong>Factura Nro:</strong></td>
            <td>{{ $data->ref_name_invoicing }}</td>
        </tr>
        <tr>
            <td><strong>Fecha:</strong></td>
            <td>{{ date('d/m/Y', strtotime($data->date_invoicing)) }}</td>
        </tr>
        <tr>
            <td><strong>Condición de pago:</strong></td>
            @switch($data->type_payment)
                @case(1)
                    <td>Contado</td>
                    @break
                    <td>Credito</td>
                @default
                    
            @endswitch
            
        </tr>
        
    </table>

    <table>
        <tr>
            <td><strong>Nombre o razón social:</strong></td>
            <td>{{$data->name_client}}</td>
        </tr>
        <tr>
            <td><strong>Dirección</strong></td>
            <td>{{$data->address_client}}</td>
        </tr>
        <tr>
            <td><strong>R.I.F.:</strong></td>
            <td>{{$data->idcard_client}}</td>
        </tr>
        <tr>
            <td><strong>Telefono</strong></td>
            <td>{{$data->phone_client}}</td>
        </tr>
        <tr>
            <td><strong>Vendedor</strong></td>
            <td></td>
        </tr>
    </table>
                                             

    <table class="table"  width="100%" >
        <tr>
            <th scope="col" class="align-middle">DESCRIPCIÓN</th>
            <th scope="col" class="text-center align-middle" width="10%">CANTIDAD</th>
            <th scope="col" class="text-center align-middle" width="15%">P/U</th>
            <th scope="col" class="text-center align-middle " width="15%">SUB-TOTAL</th>
        </tr>
        
        @for ($i = 0; $i < count($dataProducts); $i++)
            
        
            @foreach ($dataProducts[$i] as $k => $products)
            <tr>
                
                <td  class="align-middle">{{$products->name_product}} {{$products->name_presentation_product}} {{$products->short_unit_product}} @if ($products->tax_exempt_product == 1) (E) @endif</td>
                <td class="text-end align-middle" width="10%">{{number_format($obj['cantidad'][$i], 2, ',', '.')}}</td>
                <td class="text-end align-middle" width="10%">Bs. {{number_format($obj['precio_producto'][$i], 2, ',', '.')}}</td>
                <td class="text-end align-middle " width="10%">Bs. {{number_format($obj['precio_producto'][$i]*$obj['cantidad'][$i], 2, ',', '.')}}</td>
            </tr>
            @endforeach
            @endfor
        
        
    </table>         
    <table width="100%" >
        <tr>
            <td width="35%"></td>
<td class="text-end" width="10%">Total:</td>
<td class="text-end" width="10%">{{$dataGeneral['cantidad']}}</td>
<td width="24%"></td>

        </tr>
    </table>          


                    <table class="table" id="footer">

                        <tr>
                            <td rowspan="4"  class="text-center" style="vertical-align:bottom; ">__________________________ <br><br>RECIBÍ CONFORME</td>
                            <td class="text-end">BASE IMPONIBLE: </td>
                            <td width="20%" class="text-end"><label id="subFacs">Bs. {{number_format($data->no_exempt_amout_invoicing, 2, ',', '.')}}</label></td>
                        </tr>
                        <tr>
                            <td class="text-end">EXENTO:</th>
                            <td  width="10%" class="text-end"><label id="bimponibleivas">Bs. {{number_format($data->exempt_amout_invoicing, 2, ',', '.')}}</label></td>
                        </tr>
                        <tr>
                            <td class="text-end">IVA:</td>
                            <td  width="10%" class="text-end"><label>Bs. {{number_format($data->total_amount_tax_invoicing, 2, ',', '.')}}</label></td>
                        </tr>
                        <tr>
                            <td class="text-end">TOTAL: </td>
                            <td  width="10%" class="text-end"><label id="totalTotals">Bs. {{number_format($data->total_amount_invoicing, 2, ',', '.')}}</label></td>
                        </tr>
                    </table>
 

</body>
</html>