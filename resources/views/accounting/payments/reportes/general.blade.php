<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>{!! date('dmY') !!}</title>
    <style>
        * {
            font-size: 11pt;
            font-family: sans-serif, arial;
        }

        .divs {
            width: 100%;
            height: auto;
        }

        .divs.header {
            margin-bottom: 20px;
            margin-top: 20px;
        }

        .divs.body table tr td {
            padding: 5px 5px;
        }

        .divs.footer {
            margin-top: 20px;
            position: fixed;
            bottom: 0%;
        }

        .divs.footer table {
            width: 100%;

        }

        .table {
            width: 100%;
            border-collapse: collapse;

        }

        .table,
        td,
        th {
            border: 1px solid black;
        }

        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: right;
        }
    </style>
</head>

<body>
    <table class="table">
        <tr class="bg-dark text-white">
            <th width="8%" class="text-center align-middle">Fecha</th>
            <th class="text-center align-middle">Cliente</th>
            <th width="8%" class="text-center align-middle">Factura</th>
            <th width="8%" class="text-center align-middle">Pedido</th>
            <th width="25%" class="text-center align-middle">Banco</th>
            <th width="8%" class="text-center align-middle">Referencia</th>
            <th width="15%" class="text-center align-middle">Monto</th>
        </tr>
        @foreach ($data as $tabla)
            <tr>
                <td class="text-center align-middle">{{ date('d/m/Y', strtotime($tabla->date_payment)) }}</td>
                <td class="text-center align-middle">{{ $tabla->name_client }}</td>
                <td class="text-center align-middle">{{ $tabla->ref_name_invoicing ?? 'N/A' }}</td>
                <td class="text-center align-middle">{{ $tabla->ref_name_delivery_note ?? 'N/A' }}</td>
                <td class="text-center align-middle">{{ $tabla->name_bank }}</td>
                <td class="text-center align-middle">{{ $tabla->ref_payment }}</td>
                <td class="text-center align-middle">Bs. {{ number_format($tabla->amount_payment, 2, ',', '.') }}</td>

            </tr>
        @endforeach

    </table>
</body>

</html>
