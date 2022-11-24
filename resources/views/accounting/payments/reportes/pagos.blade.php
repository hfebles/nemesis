<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>{!! date('dmY') !!}</title>
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

        .table{
            width:100%;
            border-collapse: collapse;
            
        }

        .table, td, th{
            border: 1px solid black;
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

<table class="table table-sm table-bordered mb-0">
        <tr>
            <td >Fecha del pago: {{ date('d/m/Y', strtotime($data->date_payment)) }}</td>
            <td>Referencia del Pago: #{{$data->ref_payment}}</td>
        </tr>
        <tr>
            <td>Cliente: {{$data->name_client}}</td>
            <td>Factura: {{$data->ref_name_invoicing ?? $data->ref_name_delivery_note}}</td>
        </tr>
        <tr>
            <td>Banco: {{$data->name_bank}}</td>
            <td>Monto: {{ number_format($data->amount_payment, 2, ',', '.') }}</td>
        </tr>
    </table>
</body>

</html>