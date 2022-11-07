<?php

namespace Database\Seeders;

use App\Models\Accounting\LedgerAccount;
use Illuminate\Database\Seeder;

class LedgerAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LedgerAccount::create(['code_ledger_account' =>  '1.', 'name_ledger_account' => 'ACTIVO', 'id_type_ledger_account' => 1]);
        LedgerAccount::create(['code_ledger_account' =>  '1.1.', 'name_ledger_account' => 'ACTIVOS CIRCULANTES', 'id_type_ledger_account' => 1]);
        LedgerAccount::create(['code_ledger_account' =>  '1.1.1.', 'name_ledger_account' => 'ACTIVOS DISPONIBLES', 'id_type_ledger_account' => 1]);
        LedgerAccount::create(['code_ledger_account' =>  '1.1.1.1.', 'name_ledger_account' => 'CAJA', 'id_type_ledger_account' => 1]);
        LedgerAccount::create(['code_ledger_account' =>  '1.1.1.1.001', 'name_ledger_account' => 'CAJA GENERAL', 'id_type_ledger_account' => 1]);
        LedgerAccount::create(['code_ledger_account' =>  '1.1.1.1.002', 'name_ledger_account' => 'CAJA CHICA', 'id_type_ledger_account' => 1]);
        LedgerAccount::create(['code_ledger_account' =>  '1.1.1.2.', 'name_ledger_account' => 'BANCOS', 'id_type_ledger_account' => 1]);
        LedgerAccount::create(['code_ledger_account' =>  '1.1.1.2.001', 'name_ledger_account' => 'EMPRESA X  CTA.CTE.#10012705215805', 'id_type_ledger_account' => 1]);
        LedgerAccount::create(['code_ledger_account' =>  '1.1.2.', 'name_ledger_account' => 'ACTIVOS EXIGIBLES', 'id_type_ledger_account' => 1]);
        LedgerAccount::create(['code_ledger_account' =>  '1.1.2.1.', 'name_ledger_account' => 'CUENTAS POR COBRAR', 'id_type_ledger_account' => 1]);
        LedgerAccount::create(['code_ledger_account' =>  '1.1.2.1.001', 'name_ledger_account' => 'CUENTAS POR COBRAR CLIENTES', 'id_type_ledger_account' => 1]);
        LedgerAccount::create(['code_ledger_account' =>  '1.1.2.1.002', 'name_ledger_account' => 'CUENTAS POR COBRAR EMPLEADOS', 'id_type_ledger_account' => 1]);
        LedgerAccount::create(['code_ledger_account' =>  '1.1.2.1.003', 'name_ledger_account' => 'CUENTAS POR COBRAR OTROS', 'id_type_ledger_account' => 1]);
        LedgerAccount::create(['code_ledger_account' =>  '1.1.2.1.004', 'name_ledger_account' => 'ANTICIPO A JUSTIFICAR', 'id_type_ledger_account' => 1]);
        LedgerAccount::create(['code_ledger_account' =>  '1.1.3.', 'name_ledger_account' => 'ACTIVOS REALIZABLES', 'id_type_ledger_account' => 1]);
        LedgerAccount::create(['code_ledger_account' =>  '1.1.3.1.', 'name_ledger_account' => 'INVENTARIO DE MATERIALES', 'id_type_ledger_account' => 1]);
        LedgerAccount::create(['code_ledger_account' =>  '1.1.3.1.001', 'name_ledger_account' => 'INV. DE ACCESORIOS', 'id_type_ledger_account' => 1]);
        LedgerAccount::create(['code_ledger_account' =>  '1.1.3.1.002', 'name_ledger_account' => 'INV. DE PAPERIA Y UTILES  DE OFICINA', 'id_type_ledger_account' => 1]);
        LedgerAccount::create(['code_ledger_account' =>  '1.1.3.1.003', 'name_ledger_account' => 'INV. DE MATERIALES Y LIMPIEZA', 'id_type_ledger_account' => 1]);
        LedgerAccount::create(['code_ledger_account' =>  '1.1.3.2.', 'name_ledger_account' => 'INVENTARIO DE MERCADERIAS', 'id_type_ledger_account' => 1]);
        LedgerAccount::create(['code_ledger_account' =>  '1.1.3.2.001', 'name_ledger_account' => 'GRANOS BASICOS', 'id_type_ledger_account' => 1]);
        LedgerAccount::create(['code_ledger_account' =>  '1.1.4.', 'name_ledger_account' => 'OTROS ACTIVOS CIRCULANTES', 'id_type_ledger_account' => 1]);
        LedgerAccount::create(['code_ledger_account' =>  '1.1.4.1.', 'name_ledger_account' => 'IMPUESTOS ANTICIPADOS Y RETENIDOS', 'id_type_ledger_account' => 1]);
        LedgerAccount::create(['code_ledger_account' =>  '1.1.4.1.001', 'name_ledger_account' => 'IVA CREDITO TRIBUNAL', 'id_type_ledger_account' => 1]);
        LedgerAccount::create(['code_ledger_account' =>  '1.1.4.1.002', 'name_ledger_account' => 'ANTICIPO IMPUESTO A LA RENTA', 'id_type_ledger_account' => 1]);
        LedgerAccount::create(['code_ledger_account' =>  '1.1.4.1.003', 'name_ledger_account' => 'ANTICIPO RETENCION EN LA FUENTE', 'id_type_ledger_account' => 1]);
        LedgerAccount::create(['code_ledger_account' =>  '1.2.', 'name_ledger_account' => 'ACTIVOS FIJOS', 'id_type_ledger_account' => 1]);
        LedgerAccount::create(['code_ledger_account' =>  '1.2.1.', 'name_ledger_account' => 'ACTIVOS TANGIBLES', 'id_type_ledger_account' => 1]);
        LedgerAccount::create(['code_ledger_account' =>  '1.2.1.1.', 'name_ledger_account' => 'ACTIVOS DEPRECIABLES', 'id_type_ledger_account' => 1]);
        LedgerAccount::create(['code_ledger_account' =>  '1.2.1.1.001', 'name_ledger_account' => 'INMUEBLES', 'id_type_ledger_account' => 1]);
        LedgerAccount::create(['code_ledger_account' =>  '1.2.1.1.002', 'name_ledger_account' => 'INSTALACIONES', 'id_type_ledger_account' => 1]);
        LedgerAccount::create(['code_ledger_account' =>  '1.2.1.2.', 'name_ledger_account' => 'ACTIVOS NO DEPRECIABLES', 'id_type_ledger_account' => 1]);
        LedgerAccount::create(['code_ledger_account' =>  '1.2.1.2.001', 'name_ledger_account' => 'TERRENO # 1', 'id_type_ledger_account' => 1]);
        LedgerAccount::create(['code_ledger_account' =>  '1.2.1.3.', 'name_ledger_account' => 'DEPRECIACION ACUMULDA', 'id_type_ledger_account' => 1]);
        LedgerAccount::create(['code_ledger_account' =>  '1.2.1.3.001', 'name_ledger_account' => 'INMUEBLES DEP', 'id_type_ledger_account' => 1]);
        LedgerAccount::create(['code_ledger_account' =>  '1.2.1.3.002', 'name_ledger_account' => 'INSTALACIONES DEP', 'id_type_ledger_account' => 1]);
        LedgerAccount::create(['code_ledger_account' =>  '1.2.2.', 'name_ledger_account' => 'ACTIVOS INTANGIBLES', 'id_type_ledger_account' => 1]);
        LedgerAccount::create(['code_ledger_account' =>  '1.2.2.1.', 'name_ledger_account' => 'MARCAS', 'id_type_ledger_account' => 1]);
        LedgerAccount::create(['code_ledger_account' =>  '1.2.2.1.001', 'name_ledger_account' => 'SERVICIO DE EMPRESA', 'id_type_ledger_account' => 1]);
        LedgerAccount::create(['code_ledger_account' =>  '1.2.2.1.002', 'name_ledger_account' => 'MARCA DE PRODUCTO', 'id_type_ledger_account' => 1]);
        LedgerAccount::create(['code_ledger_account' =>  '1.2.2.2.', 'name_ledger_account' => 'SOFTWARE', 'id_type_ledger_account' => 1]);
        LedgerAccount::create(['code_ledger_account' =>  '1.2.2.2.001', 'name_ledger_account' => 'SOFTWARE CONTABLE', 'id_type_ledger_account' => 1]);
        LedgerAccount::create(['code_ledger_account' =>  '1.3.', 'name_ledger_account' => 'OTROS ACTIVOS', 'id_type_ledger_account' => 1]);
        LedgerAccount::create(['code_ledger_account' =>  '1.3.1.', 'name_ledger_account' => 'ACTIVOS DIFERIDOS', 'id_type_ledger_account' => 1]);
        LedgerAccount::create(['code_ledger_account' =>  '1.3.1.1.', 'name_ledger_account' => 'GASTOS DE CONSTITUCION', 'id_type_ledger_account' => 1]);
        LedgerAccount::create(['code_ledger_account' =>  '1.3.1.1.001', 'name_ledger_account' => 'GASTOS DE CONSTITUCION', 'id_type_ledger_account' => 1]);
        LedgerAccount::create(['code_ledger_account' =>  '1.3.1.2.', 'name_ledger_account' => 'GASTOS DE CONSTRUCCION', 'id_type_ledger_account' => 1]);
        LedgerAccount::create(['code_ledger_account' =>  '1.3.2.', 'name_ledger_account' => 'OTROS ACTIVOS VARIOS', 'id_type_ledger_account' => 1]);
        LedgerAccount::create(['code_ledger_account' =>  '1.3.2.1.', 'name_ledger_account' => 'DEPOSITOD ENTREGADO EN GARANTIA', 'id_type_ledger_account' => 1]);
        LedgerAccount::create(['code_ledger_account' =>  '1.3.2.1.001', 'name_ledger_account' => 'INMUEBLES ENTREGADOS EN GARANTIA', 'id_type_ledger_account' => 1]);
        LedgerAccount::create(['code_ledger_account' =>  '2.', 'name_ledger_account' => 'PASIVO', 'id_type_ledger_account' => 2]);
        LedgerAccount::create(['code_ledger_account' =>  '2.1.', 'name_ledger_account' => 'PASIVOS A CORTO PLAZO', 'id_type_ledger_account' => 2]);
        LedgerAccount::create(['code_ledger_account' =>  '2.1.1.', 'name_ledger_account' => 'CUENTAS POR PAGAR', 'id_type_ledger_account' => 2]);
        LedgerAccount::create(['code_ledger_account' =>  '2.1.1.1.', 'name_ledger_account' => 'PROVEEDORES NACIONALES', 'id_type_ledger_account' => 2]);
        LedgerAccount::create(['code_ledger_account' =>  '2.1.1.1.001', 'name_ledger_account' => 'INDUSTRIALES CXP', 'id_type_ledger_account' => 2]);
        LedgerAccount::create(['code_ledger_account' =>  '2.1.1.2.', 'name_ledger_account' => 'PROVEEDORES LOCALES', 'id_type_ledger_account' => 2]);
        LedgerAccount::create(['code_ledger_account' =>  '2.1.1.2.001', 'name_ledger_account' => 'PROVEEDOR X CXP', 'id_type_ledger_account' => 2]);
        LedgerAccount::create(['code_ledger_account' =>  '2.1.1.3.', 'name_ledger_account' => 'SERVICIOS POR PAGAR', 'id_type_ledger_account' => 2]);
        LedgerAccount::create(['code_ledger_account' =>  '2.1.1.3.001', 'name_ledger_account' => 'SERV. ENERGIA  CXP', 'id_type_ledger_account' => 2]);
        LedgerAccount::create(['code_ledger_account' =>  '2.1.1.3.002', 'name_ledger_account' => 'TELEFONO E INTERNET CXP', 'id_type_ledger_account' => 2]);
        LedgerAccount::create(['code_ledger_account' =>  '2.1.1.4.', 'name_ledger_account' => 'OTRAS CUENTAS POR PAGAR', 'id_type_ledger_account' => 2]);
        LedgerAccount::create(['code_ledger_account' =>  '2.1.1.4.001', 'name_ledger_account' => 'CUENTAS VARIAS POR PAGAR', 'id_type_ledger_account' => 2]);
        LedgerAccount::create(['code_ledger_account' =>  '2.1.2.', 'name_ledger_account' => 'NOMINA POR PAGAR', 'id_type_ledger_account' => 2]);
        LedgerAccount::create(['code_ledger_account' =>  '2.1.2.1.', 'name_ledger_account' => 'REMUNERACIONES POR PAGAR', 'id_type_ledger_account' => 2]);
        LedgerAccount::create(['code_ledger_account' =>  '2.1.2.1.001', 'name_ledger_account' => 'SUELDOS POR PAGAR', 'id_type_ledger_account' => 2]);
        LedgerAccount::create(['code_ledger_account' =>  '2.1.2.1.002', 'name_ledger_account' => 'DESCUENTOS POR ATRASOS', 'id_type_ledger_account' => 2]);
        LedgerAccount::create(['code_ledger_account' =>  '2.1.2.1.003', 'name_ledger_account' => 'LIQUIDACIONES POR PAGAR', 'id_type_ledger_account' => 2]);
        LedgerAccount::create(['code_ledger_account' =>  '2.1.2.2.', 'name_ledger_account' => 'INSS E INATEC POR PAGAR', 'id_type_ledger_account' => 2]);
        LedgerAccount::create(['code_ledger_account' =>  '2.1.2.2.001', 'name_ledger_account' => 'APORTES PERSONAL Y PATRONAL CXP', 'id_type_ledger_account' => 2]);
        LedgerAccount::create(['code_ledger_account' =>  '2.1.2.2.002', 'name_ledger_account' => 'INATEC POR PAGAR', 'id_type_ledger_account' => 2]);
        LedgerAccount::create(['code_ledger_account' =>  '2.1.2.3.', 'name_ledger_account' => 'PROVICIONES POR PAGAR', 'id_type_ledger_account' => 2]);
        LedgerAccount::create(['code_ledger_account' =>  '2.1.2.3.001', 'name_ledger_account' => 'DECIMO TERCER MES POR PAGAR', 'id_type_ledger_account' => 2]);
        LedgerAccount::create(['code_ledger_account' =>  '2.1.2.3.002', 'name_ledger_account' => 'VACACIONES POR PAGAR', 'id_type_ledger_account' => 2]);
        LedgerAccount::create(['code_ledger_account' =>  '2.1.2.3.003', 'name_ledger_account' => 'INDEMNIZACIONES POR PAGAR', 'id_type_ledger_account' => 2]);
        LedgerAccount::create(['code_ledger_account' =>  '2.1.3.', 'name_ledger_account' => 'OBLIGACIONES FISCALES POR PAGAR', 'id_type_ledger_account' => 2]);
        LedgerAccount::create(['code_ledger_account' =>  '2.1.3.1.', 'name_ledger_account' => 'IMPUESTOS POR PAGAR', 'id_type_ledger_account' => 2]);
        LedgerAccount::create(['code_ledger_account' =>  '2.1.3.1.001', 'name_ledger_account' => 'IMPUESTO A LA RENTA POR PAGAR', 'id_type_ledger_account' => 2]);
        LedgerAccount::create(['code_ledger_account' =>  '2.1.3.1.002', 'name_ledger_account' => 'IVA POR PAGAR (15%)', 'id_type_ledger_account' => 2]);
        LedgerAccount::create(['code_ledger_account' =>  '2.1.3.2.', 'name_ledger_account' => 'RETENCIONES FUENTE POR PAGAR', 'id_type_ledger_account' => 2]);
        LedgerAccount::create(['code_ledger_account' =>  '2.1.3.2.001', 'name_ledger_account' => '2% RET.FTE. SERVICIOS', 'id_type_ledger_account' => 2]);
        LedgerAccount::create(['code_ledger_account' =>  '2.1.3.2.002', 'name_ledger_account' => '5% RET.FTE. SERVICIO', 'id_type_ledger_account' => 2]);
        LedgerAccount::create(['code_ledger_account' =>  '2.1.3.2.003', 'name_ledger_account' => '2% RET. FTE DE COMPRAS', 'id_type_ledger_account' => 2]);
        LedgerAccount::create(['code_ledger_account' =>  '2.1.3.2.004', 'name_ledger_account' => '10% RET.FTE.HONORARIOS PROFESIONALES', 'id_type_ledger_account' => 2]);
        LedgerAccount::create(['code_ledger_account' =>  '2.1.3.3.', 'name_ledger_account' => 'RETENCIONES IVA POR PAGAR', 'id_type_ledger_account' => 2]);
        LedgerAccount::create(['code_ledger_account' =>  '2.1.3.3.001', 'name_ledger_account' => '15% RET. IVA VENTAS', 'id_type_ledger_account' => 2]);
        LedgerAccount::create(['code_ledger_account' =>  '2.1.3.4.', 'name_ledger_account' => 'OTROS IMPUESTOS POR PAGAR', 'id_type_ledger_account' => 2]);
        LedgerAccount::create(['code_ledger_account' =>  '2.1.3.5.', 'name_ledger_account' => 'RETENCIONES SOBRE SALARIO', 'id_type_ledger_account' => 2]);
        LedgerAccount::create(['code_ledger_account' =>  '2.1.3.5.001', 'name_ledger_account' => 'RETENCIONES SOBRE SALARIO DGI  XP', 'id_type_ledger_account' => 2]);
        LedgerAccount::create(['code_ledger_account' =>  '2.2.', 'name_ledger_account' => 'PASIVOS A LARGO PLAZO', 'id_type_ledger_account' => 2]);
        LedgerAccount::create(['code_ledger_account' =>  '2.2.1.', 'name_ledger_account' => 'PRESTAMOS A LARGO PLAZO', 'id_type_ledger_account' => 2]);
        LedgerAccount::create(['code_ledger_account' =>  '2.2.1.1.', 'name_ledger_account' => 'PRESTMOS SOCIOS LARGO PLAZO', 'id_type_ledger_account' => 2]);
        LedgerAccount::create(['code_ledger_account' =>  '2.2.1.1.001', 'name_ledger_account' => 'R.G. PMO LARGO PLAZO', 'id_type_ledger_account' => 2]);
        LedgerAccount::create(['code_ledger_account' =>  '3.', 'name_ledger_account' => 'PATRIMONIO', 'id_type_ledger_account' => 3]);
        LedgerAccount::create(['code_ledger_account' =>  '3.1.', 'name_ledger_account' => 'PATRIMONIO NEGOCIO X', 'id_type_ledger_account' => 3]);
        LedgerAccount::create(['code_ledger_account' =>  '3.1.1.', 'name_ledger_account' => 'CAPITAL SOCIAL', 'id_type_ledger_account' => 3]);
        LedgerAccount::create(['code_ledger_account' =>  '3.1.1.1.', 'name_ledger_account' => 'CAPITAL PAGADO', 'id_type_ledger_account' => 3]);
        LedgerAccount::create(['code_ledger_account' =>  '3.1.1.1.001', 'name_ledger_account' => 'CAPITAL NEGOCIO X', 'id_type_ledger_account' => 3]);
        LedgerAccount::create(['code_ledger_account' =>  '3.1.2.', 'name_ledger_account' => 'RESERVAS', 'id_type_ledger_account' => 3]);
        LedgerAccount::create(['code_ledger_account' =>  '3.1.2.1.', 'name_ledger_account' => 'RESERVAS PATRIMONIALES', 'id_type_ledger_account' => 3]);
        LedgerAccount::create(['code_ledger_account' =>  '3.1.2.1.001', 'name_ledger_account' => 'RESERVA FACULTATIVA', 'id_type_ledger_account' => 3]);
        LedgerAccount::create(['code_ledger_account' =>  '3.1.2.1.002', 'name_ledger_account' => 'RESERVA DE CAPITAL', 'id_type_ledger_account' => 3]);
        LedgerAccount::create(['code_ledger_account' =>  '3.1.2.1.003', 'name_ledger_account' => 'RESERVA LEGAL', 'id_type_ledger_account' => 3]);
        LedgerAccount::create(['code_ledger_account' =>  '3.1.3.', 'name_ledger_account' => 'RESULTADOS', 'id_type_ledger_account' => 3]);
        LedgerAccount::create(['code_ledger_account' =>  '3.1.3.1.', 'name_ledger_account' => 'RESULTADOS ANTERIORES', 'id_type_ledger_account' => 3]);
        LedgerAccount::create(['code_ledger_account' =>  '3.1.3.1.001', 'name_ledger_account' => 'UTILIDADES ANTERIORES', 'id_type_ledger_account' => 3]);
        LedgerAccount::create(['code_ledger_account' =>  '3.1.3.1.002', 'name_ledger_account' => 'PERDIDAS ANTERIORES', 'id_type_ledger_account' => 3]);
        LedgerAccount::create(['code_ledger_account' =>  '3.1.3.1.003', 'name_ledger_account' => 'AMORTIZACION PERDIDAS ANTERIORES', 'id_type_ledger_account' => 3]);
        LedgerAccount::create(['code_ledger_account' =>  '3.1.3.2.', 'name_ledger_account' => 'RESULTADOS DEL EJERCICIO', 'id_type_ledger_account' => 3]);
        LedgerAccount::create(['code_ledger_account' =>  '3.1.3.2.001', 'name_ledger_account' => 'UTILIDADES DE EJERCICIO', 'id_type_ledger_account' => 3]);
        LedgerAccount::create(['code_ledger_account' =>  '4.', 'name_ledger_account' => 'INGRESOS', 'id_type_ledger_account' => 4]);
        LedgerAccount::create(['code_ledger_account' =>  '4.1.', 'name_ledger_account' => 'INGRESOS OPERACIONALES', 'id_type_ledger_account' => 4]);
        LedgerAccount::create(['code_ledger_account' =>  '4.1.1.', 'name_ledger_account' => 'VENTAS GENERALES', 'id_type_ledger_account' => 4]);
        LedgerAccount::create(['code_ledger_account' =>  '4.1.1.1.', 'name_ledger_account' => 'VENTAS NETAS', 'id_type_ledger_account' => 4]);
        LedgerAccount::create(['code_ledger_account' =>  '4.1.1.1.001', 'name_ledger_account' => 'VENTAS DE GRANOS BASICOS', 'id_type_ledger_account' => 4]);
        LedgerAccount::create(['code_ledger_account' =>  '5.', 'name_ledger_account' => 'COSTOS', 'id_type_ledger_account' =>5]);
        LedgerAccount::create(['code_ledger_account' =>  '5.1.', 'name_ledger_account' => 'COSTOS OPERACIONALES', 'id_type_ledger_account' =>5]);
        LedgerAccount::create(['code_ledger_account' =>  '5.1.1.', 'name_ledger_account' => 'COSTO DE VENTA', 'id_type_ledger_account' =>5]);
        LedgerAccount::create(['code_ledger_account' =>  '5.1.1.1.', 'name_ledger_account' => 'COSTOS DE VENTAS GRANOS BASICO', 'id_type_ledger_account' =>5]);
        LedgerAccount::create(['code_ledger_account' =>  '5.1.1.1.001', 'name_ledger_account' => 'COSTO GRANOS BASICO', 'id_type_ledger_account' =>5]);
        LedgerAccount::create(['code_ledger_account' =>  '6.', 'name_ledger_account' => 'GASTOS', 'id_type_ledger_account' =>6]);
        LedgerAccount::create(['code_ledger_account' =>  '6.1.', 'name_ledger_account' => 'GASTOS OPERACIONALES', 'id_type_ledger_account' =>6]);
        LedgerAccount::create(['code_ledger_account' =>  '6.1.1.', 'name_ledger_account' => 'GASTOS DE ADMINISTRACION', 'id_type_ledger_account' =>6]);
        LedgerAccount::create(['code_ledger_account' =>  '6.1.1.1.', 'name_ledger_account' => 'SUELDOS Y SIMILARES ADMON', 'id_type_ledger_account' =>6]);
        LedgerAccount::create(['code_ledger_account' =>  '6.1.1.1.001', 'name_ledger_account' => 'SUELDOS Y SALARIOS ADMON', 'id_type_ledger_account' =>6]);
        LedgerAccount::create(['code_ledger_account' =>  '6.1.1.1.002', 'name_ledger_account' => 'COMPONENTE SALARIAL ADM', 'id_type_ledger_account' =>6]);
        LedgerAccount::create(['code_ledger_account' =>  '6.1.1.1.003', 'name_ledger_account' => 'HORA EXTRA ADM', 'id_type_ledger_account' =>6]);
        LedgerAccount::create(['code_ledger_account' =>  '6.1.2.', 'name_ledger_account' => 'GASTOS DE VENTAS', 'id_type_ledger_account' =>6]);
        LedgerAccount::create(['code_ledger_account' =>  '6.1.2.1.', 'name_ledger_account' => 'SUELDOS Y SIMILARES', 'id_type_ledger_account' =>6]);
        LedgerAccount::create(['code_ledger_account' =>  '6.1.2.1.001', 'name_ledger_account' => 'SUELDOS Y SALARIOS DE VENTAS', 'id_type_ledger_account' =>6]);
        LedgerAccount::create(['code_ledger_account' =>  '6.1.2.1.002', 'name_ledger_account' => 'COMPROBANTE SALARIAL DE VTA', 'id_type_ledger_account' =>6]);
        LedgerAccount::create(['code_ledger_account' =>  '6.1.2.1.003', 'name_ledger_account' => 'HORAS EXTRAS VTAS', 'id_type_ledger_account' =>6]);
        LedgerAccount::create(['code_ledger_account' =>  '6.1.3.', 'name_ledger_account' => 'GASTOS FINANCIEROS', 'id_type_ledger_account' =>6]);
        LedgerAccount::create(['code_ledger_account' =>  '6.1.3.1.', 'name_ledger_account' => 'GASTOS BANCARIOS', 'id_type_ledger_account' =>6]);
        LedgerAccount::create(['code_ledger_account' =>  '6.1.3.1.001', 'name_ledger_account' => 'GTOS BANC.X CHEQUERA', 'id_type_ledger_account' =>6]);
        LedgerAccount::create(['code_ledger_account' =>  '6.1.3.1.002', 'name_ledger_account' => 'GAST.X SERV.BANC.', 'id_type_ledger_account' =>6]);
        LedgerAccount::create(['code_ledger_account' =>  '6.1.3.1.003', 'name_ledger_account' => 'GAST. X DEVALUACION DE LA MONEDA FIN.', 'id_type_ledger_account' =>6]);
        LedgerAccount::create(['code_ledger_account' =>  '6.1.4.', 'name_ledger_account' => 'GASTOS GENERALES', 'id_type_ledger_account' =>6]);
        LedgerAccount::create(['code_ledger_account' =>  '6.1.4.1.', 'name_ledger_account' => 'MANTENIMIENTO DE MUEBLES Y ENSERES GEN', 'id_type_ledger_account' =>6]);
        LedgerAccount::create(['code_ledger_account' =>  '6.1.4.1.001', 'name_ledger_account' => 'MANT. DE MUEBLES GEN', 'id_type_ledger_account' =>6]);
        LedgerAccount::create(['code_ledger_account' =>  '6.1.4.1.002', 'name_ledger_account' => 'MANT. DE ENSERES GEN', 'id_type_ledger_account' =>6]);
        LedgerAccount::create(['code_ledger_account' =>  '6.1.4.2.', 'name_ledger_account' => 'CONS. DE ENEGIA ELECTRICA GEN', 'id_type_ledger_account' =>6]);
        LedgerAccount::create(['code_ledger_account' =>  '6.1.4.2.01', 'name_ledger_account' => 'CONS.ENEGIA ENEL GEN', 'id_type_ledger_account' =>6]);
        LedgerAccount::create(['code_ledger_account' =>  '6.1.4.3.', 'name_ledger_account' => 'MOVILIZACIONE LOCALE GEN', 'id_type_ledger_account' =>6]);
        LedgerAccount::create(['code_ledger_account' =>  '6.1.4.3.003', 'name_ledger_account' => 'VIATICOS GEN', 'id_type_ledger_account' =>6]);
        LedgerAccount::create(['code_ledger_account' =>  '6.1.4.3.004', 'name_ledger_account' => 'PASAJES AEREOS GEN.', 'id_type_ledger_account' =>6]);

        
    }









}


