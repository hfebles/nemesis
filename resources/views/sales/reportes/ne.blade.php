<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>{!! date('dmY', strtotime($data->date_delivery_note))."_".$data->ref_name_delivery_note !!}</title>
    <style>
        *{
            font-size: 11pt;
            font-family: sans-serif, arial;
        }
        .divs {
            width: 100%;
            height: auto;
        }
        .divs.header{
            margin-bottom:20px;
            margin-top: 20px;
        }

        .divs.body table tr td{
            padding: 5px 5px;
        }

        .divs.footer {
            margin-top: 20px;
            position: fixed;
            bottom: 0%;
        }

        .divs.footer table{
            width: 100%;
            
        }

        .text-center{
            text-align: center;
        }

        .text-end{
            text-align: right;
        }


    </style>
</head>

<body>
    <div class="divs header">
        <table width="100%" >
            <tr >
                <td class="text-center" colspan="2"><h3>{{$conf['title']}}</h3></td>
            </tr>
            <tr>
                <td class="text-end"><strong>Factura Nro:</strong></td>
                <td width="20%" class="text-end">{{ $data->ref_name_delivery_note }}</td>
            </tr>
            <tr>
                <td class="text-end"><strong>Fecha:</strong></td>
                <td class="text-end">{{ date('d/m/Y', strtotime($data->date_delivery_note)) }}</td>
            </tr>
            <tr>
                <td class="text-end"><strong>Condición de pago:</strong></td>
                @switch($data->type_payment)
                @case(1)
                <td class="text-end">Contado</td>
                @break
                <td class="text-end">Credito</td>
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
    </div>
    <div class="divs body">
        <table class="table" width="100%">
            <tr>
                <th class="align-middle">DESCRIPCIÓN</th>
                <th class="text-center align-middle" width="10%">CANTIDAD</th>
                <th class="text-center align-middle" width="15%">P/U</th>
                <th class="text-center align-middle " width="15%">SUB-TOTAL</th>
            </tr>

            @for ($i = 0; $i < count($dataProducts); $i++) @foreach ($dataProducts[$i] as $k=> $products)
                <tr>

                    <td class="align-middle">{{$products->name_product}} {{$products->name_presentation_product}} {{$products->short_unit_product}} @if ($products->tax_exempt_product == 1) (E) @endif
                    </td>
                    <td class="text-end align-middle" width="10%">{{number_format($obj['cantidad'][$i], 2, ',', '.')}}
                    </td>
                    <td class="text-end align-middle" width="10%">Bs. {{number_format($obj['precio_producto'][$i], 2, ',', '.')}}</td>
                    <td class="text-end align-middle " width="10%">Bs. {{number_format($obj['precio_producto'][$i]*$obj['cantidad'][$i], 2, ',', '.')}}</td>
                </tr>
                @endforeach
                @endfor
        </table>
        <table width="100%">
            <tr>
                <td width="35%"></td>
                <td class="text-end" width="10%">Total:</td>
                <td class="text-end" width="10%">{{number_format($dataGeneral['cantidad'], 2, ',', '.')}}</td>
                <td width="24%"></td>

            </tr>
        </table>
    </div>
    <div class="divs footer">
        <table class="tabla-footer" id="footer" style="border: collapse">
            <tr>
                <td>
                    TIPO DE TASA DE CAMBIO BCV:
                    <span class="text-danger">{{date('d-m-Y', strtotime($data->date_exchange))}}</span>
                </td>
                <td class="text-end">$ {{number_format($data->amount_exchange, 2, ',', '.')}}</td>
            </tr>
            <tr>
                <td width="45%" class="text-end">BASE IMPONIBLE:</td>
                <td width="10%" class="text-end">$ {{number_format($data->no_exempt_amout_delivery_note/$data->amount_exchange, 2, ',', '.')}}
                </td>

                <td width="25%" class="text-end">BASE IMPONIBLE:</td>
                <td class="text-end">
                    Bs. {{number_format($data->no_exempt_amout_delivery_note, 2, ',', '.')}}
                </td>
            </tr>
            <tr>
                <td class="text-end">EXENTO:</td>
                <td width="15%" class="text-end">
                    $ {{number_format($data->exempt_amout_delivery_note/$data->amount_exchange, 2, ',', '.')}}
                </td>

                <td class="text-end">EXENTO:</td>
                <td class="text-end">
                    Bs. {{number_format($data->exempt_amout_delivery_note, 2, ',', '.')}}
                </td>
            </tr>
            <tr>
                <td class="text-end">TOTAL A PAGAR:</td>
                <td class="text-end">
                    $ {{number_format(($data->total_amount_delivery_note)/$data->amount_exchange, 2, ',', '.')}}
                </td>

                <td class="text-end">TOTAL A PAGAR:</td>
                <td class="text-end">
                    Bs. {{number_format(($data->total_amount_delivery_note), 2, ',', '.')}}
                </td>
            </tr>
        </table>

        <div id="nota" style="margin-top:20px;">
            <p style="text-transform: uppercase">
                nota: luego de 3 días de mora debe pagarse al tipo de cambio vigente
                al momento del pago
            </p>
            <p style="text-align: right">_________________________________</p>
            <p style=" 
                text-transform: uppercase;
                text-align: right;
                padding-right: 55px;">
                Recibí conforme
            </p>
        </div>
    </div>
</body>

</html>