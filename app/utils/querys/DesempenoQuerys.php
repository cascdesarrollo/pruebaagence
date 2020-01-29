<?php

namespace App\utils\querys;

use Illuminate\Support\Facades\DB;



class DesempenoQuerys
{
    public static function prepararDataGanancias($consultores, $desde, $hasta)
    {
        $lista = array();
        $totalGanancia = 0;
        $totalCostoFijo = 0;
        $totalComision = 0;
        $totalBeneficio = 0;
        foreach ($consultores as $con) {
            $dataMeses = DesempenoQuerys::consultarDataConsultor($con, $desde, $hasta);
            if (count($dataMeses) > 0) {
                $consultor = ConsultoresQuerys::consultarConsultor($con);
                $salario = ConsultoresQuerys::consultarSalarioBrutoConsultor($con);
                foreach ($dataMeses as $mes) {
                    $totalCostoFijo += round($salario, 2);
                    $totalGanancia += round($mes->ganancia, 2);
                    $totalComision += round($mes->comision, 2);
                    $mes->costoFijo = $salario;
                    $mes->ganancia = round($mes->ganancia, 2);
                    $mes->comision = round($mes->comision, 2);
                    $mes->beneficio = round($mes->ganancia - ($salario + $mes->comision), 2);
                    $totalBeneficio = round($mes->beneficio, 2);
                }
                $lista[] = array(
                    "consultor" => $consultor,
                    "data" => $dataMeses,
                    "ganancia" => $totalGanancia,
                    "costoFijo" => $totalCostoFijo,
                    "comision" => $totalComision,
                    "beneficio" => $totalBeneficio
                );
            }
        }
        return $lista;
    }

    public static function consultarDataConsultor($coUsuario, $desde, $hasta)
    {

        $query = DesempenoQuerys::queryBaseGanancias();
        $query = DesempenoQuerys::camposGanancias($query);
        $query = DesempenoQuerys::condicionGanancias($query, $coUsuario, $desde, $hasta);
        $query->groupBy('fecha', 'fecha_orden')
            ->orderBy('fecha_orden');
        return $query->get();
    }

    public static function consultarGananciaMes($coUsuario, $mes)
    {

        $query = DesempenoQuerys::queryBaseGanancias();
        $query = DesempenoQuerys::camposGananciaMes($query);
        $query = DesempenoQuerys::condicionGananciaMes($query, $coUsuario, $mes);
        return $query->first();
    }

    private static function queryBaseGanancias()
    {
        return DB::table('cao_fatura AS fac')
            ->join('cao_os AS ord', 'fac.co_os', '=', 'ord.co_os');
    }

    private static function camposGanancias($query)
    {
        return $query->select(DB::raw(
            'DATE_FORMAT(data_emissao,\'%b %Y\') fecha, '
                . 'DATE_FORMAT(data_emissao,\'%Y%m\') fecha_orden, '
                . ' SUM( fac.valor - (fac.valor * (fac.total_imp_inc/100)) ) ganancia,'
                . ' SUM( (fac.valor - (fac.valor * (fac.total_imp_inc/100)) ) * (fac.comissao_cn/100) ) comision'
        ));
    }

    private static function condicionGanancias($query, $coUsuario, $desde, $hasta)
    {
        return $query->where('ord.co_usuario', '=', $coUsuario)
            ->where(DB::raw('DATE_FORMAT(data_emissao,\'%Y%m\')'), '>=', $desde)
            ->where(DB::raw('DATE_FORMAT(data_emissao,\'%Y%m\')'), '<=', $hasta);
    }


    private static function camposGananciaMes($query)
    {
        return $query->select(DB::raw('SUM( fac.valor - (fac.valor * (fac.total_imp_inc/100)) ) ganancia'));
    }

    private static function condicionGananciaMes($query, $coUsuario, $mes)
    {
        return $query->where('ord.co_usuario', '=', $coUsuario)
            ->where(DB::raw('DATE_FORMAT(data_emissao,\'%Y%m\')'), '=', $mes);
    }



    public static function formarFecha($fecha)
    {
        $ano = substr($fecha, 0, 4);
        $mes = substr($fecha, 4, 2);
        return $ano . '-' . $mes . '-01';
    }

    public static function arregloMeses($desde, $hasta, $formato)
    {
        return DesempenoQuerys::generarRango(
            DesempenoQuerys::formarFecha($desde),
            DesempenoQuerys::formarFecha($hasta),
            $formato
        );
    }

    public static function generarRango($inicio, $fin, $formato)
    {
        $range = array();
        $inicio = strtotime($inicio);
        $fin = strtotime($fin);
        do {
            $range[] = date($formato, $inicio);
            $inicio = strtotime("+ 1 month", $inicio);
        } while ($inicio <= $fin);
        return $range;
    }

    public static function prepararDataGrafico($consultores, $desde, $hasta)
    {
        $data = array();
        $totalSalarios = 0;
        $arregloMeses = DesempenoQuerys::arregloMeses($desde, $hasta, 'Ym');
        $dataConsultores = array();
        foreach ($consultores as $con) {
            $consultor = ConsultoresQuerys::consultarConsultor($con);
            $totalSalarios += ConsultoresQuerys::consultarSalarioBrutoConsultor($con);
            $gananciasConsultor = array();
            foreach ($arregloMeses as $mes) {
                $gananciaMes = DesempenoQuerys::consultarGananciaMes($con, $mes);
                if ($gananciaMes) {
                    $gananciasConsultor[] = round($gananciaMes->ganancia, 2);
                } else {
                    $gananciasConsultor[] = 0;
                }
            }
            $dataConsultores[] = array(
                "consultor" => $consultor->no_usuario,
                "ganancias" => $gananciasConsultor
            );
        }
        if (count($dataConsultores) > 0) {
            $promedio = round($totalSalarios / count($dataConsultores), 2);
        } else {
            $promedio = 0;
        }
        return array(
            "meses" => DesempenoQuerys::arregloMeses($desde, $hasta, 'Y-m'),
            "consultores" => $dataConsultores,
            "promedio" => $promedio
        );
    }


    public static function prepararDataPizza($consultores, $desde, $hasta)
    {
        $data = array();
        $ganancias = DesempenoQuerys::consultarGananciasConsultores($consultores, $desde, $hasta);
        $totalGanancias = 0;
        foreach ($ganancias as $linea) {
            $totalGanancias += $linea->ganancia;
        }
        foreach ($ganancias as $linea) {
            $data[] = array(
                "consultor" => $linea->no_usuario,
                "ganancia" => round(($linea->ganancia * 100) / $totalGanancias, 2)
            );
        }

        return $data;
    }

    public static function consultarGananciasConsultores($consultores, $desde, $hasta)
    {

        $query = DesempenoQuerys::queryBaseGananciasConsultores();
        $query = DesempenoQuerys::camposGananciasConsultores($query);
        $query = DesempenoQuerys::condicionGananciasConsultores($query, $consultores, $desde, $hasta);
        return $query->groupBy('usu.no_usuario')->get();
    }

    private static function queryBaseGananciasConsultores()
    {
        return DB::table('cao_fatura AS fac')
            ->join('cao_os AS ord', 'fac.co_os', '=', 'ord.co_os')
            ->join('cao_usuario AS usu', 'ord.co_usuario', '=', 'usu.co_usuario');
    }

    private static function camposGananciasConsultores($query)
    {
        return $query->select(DB::raw(
            'usu.no_usuario,'
                . ' SUM( fac.valor - (fac.valor * (fac.total_imp_inc/100)) ) ganancia'
        ));
    }

    private static function condicionGananciasConsultores($query, $consultores, $desde, $hasta)
    {
        return $query->WhereIn('ord.co_usuario', $consultores)
            ->where(DB::raw('DATE_FORMAT(data_emissao,\'%Y%m\')'), '>=', $desde)
            ->where(DB::raw('DATE_FORMAT(data_emissao,\'%Y%m\')'), '<=', $hasta);
    }

}