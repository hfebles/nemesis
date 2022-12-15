@extends('layouts.app')

@section('title-section', $conf['title-section'])

@section('btn')
    <x-btns :back="$conf['back']" :group="$conf['group']" />
@endsection

@section('content')
    {!! Form::model($data, [
        'novalidate',
        'class' => 'needs-validation',
        'method' => 'PATCH',
        'route' => ['purchase-config.update', $data->id_purchase_config],
    ]) !!}
    <div class="row">
        <x-cards>
            <table class="table table-bordered table-sm">
                <tr>
                    <td>Correlativo:</td>
                    <td>{!! Form::text('correlative_id_purchase_config', null, [
                        'id' => 'correlative_id_purchase_config',
                        'autocomplete' => 'off',
                        'required',
                        'class' => 'form-control form-control-sm',
                    ]) !!}</td>
                </tr>
                <tr>
                    <td>Nombre de impresión:</td>
                    <td>{!! Form::text('print_name_id_purchase_config', null, [
                        'id' => 'print_name_id_purchase_config',
                        'autocomplete' => 'off',
                        'required',
                        'class' => 'form-control form-control-sm',
                    ]) !!}</td>
                </tr>
                <tr>
                    <td>Numero de control:</td>
                    <td>{!! Form::text('control_number_id_purchase_config', null, [
                        'id' => 'control_number_id_purchase_config',
                        'autocomplete' => 'off',
                        'required',
                        'class' => 'form-control form-control-sm',
                    ]) !!}</td>
                </tr>

                <tr>
                    <td>Tipo de cuenta contable asociada:</td>
                    <td>
                        {!! Form::select('type_ledger', $typeLedger, $typeLedger, [
                            'onchange' => 'selectSubAccount(this.value, "c")',
                            'required',
                            'class' => 'form-select form-control-sm',
                            'placeholder' => 'Seleccione',
                        ]) !!}
                    </td>
                </tr>

                <tr id="subcuenta" style="display: none">
                    <td>Cuenta contable asociada:</td>
                    <td>
                        <select class="form-select form-control-sm" name="id_ledger_account" required id="subcuentas"></select>
                    </td>
                </tr>

            </table>

        </x-cards>
    </div>
    <x-btns-save />
    {!! Form::close() !!}
@endsection


@section('js')
    <script>
        function selectSubAccount(type, location) {
            const csrfToken = "{{ csrf_token() }}";
            if (location == 'c') {
                var div = document.getElementById('subcuenta');
                div.style.display = "";
                var select = document.getElementById('subcuentas');
                var opts = "";
            } else {
                var select = document.getElementById('subcuentas_m');
            }

            fetch('/accounting/ledger-account/search-ledgers', {
                method: 'POST',
                body: JSON.stringify({
                    type: type,
                }),
                headers: {
                    'content-type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            }).then(response => {
                return response.json();
            }).then(data => {
                select.innerHTML = ""
                for (i in data) {
                    //console.log(data[i] )
                    opts +=
                        `<option value="${data[i].id_ledger_account}">${data[i].code_ledger_account} - ${data[i].name_ledger_account}</option>`
                }
                select.innerHTML = opts;
            });
        }

</script>
    @endsection
