<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\utils\querys\ConsultoresQuerys;
use App\utils\querys\DesempenoQuerys;

class DesempenoController extends Controller
{
    public function desempenoConsultor()
    {
        $consultores = ConsultoresQuerys::listarConsultores();
        return view('desempeno', compact('consultores'));
    }

    public function consultarListadoDesempeno(Request $request)
    {
        $consultores = $request->data;
        $desde = $request->desde;
        $hasta = $request->hasta;
 
        if (!$consultores) {
            $consultores = array();
            $listaConsultores = ConsultoresQuerys::listarConsultores();
            foreach ($listaConsultores as $con) {
                $consultores[] = $con->co_usuario;
            }
        }
        $lista =DesempenoQuerys::prepararDataGanancias($consultores,$desde, $hasta);
        return array('ok' => true, 'data' => $lista);
    }

    public function consultarDatosGraficaDesempeno(Request $request)
    {
        $consultores = $request->data;
        $desde = $request->desde;
        $hasta = $request->hasta;
        if (!$consultores) {
            $consultores = array();
            $listaConsultores = ConsultoresQuerys::listarConsultores();
            foreach ($listaConsultores as $con) {
                $consultores[] = $con->co_usuario;
            }
        }
        $data =DesempenoQuerys::prepararDataGrafico($consultores,$desde, $hasta);
        return array('ok' => true, 'data' => $data);
    }

    public function consultarPizza(Request $request)
    {
        $consultores = $request->data;
        $desde = $request->desde;
        $hasta = $request->hasta;
        if (!$consultores) {
            $consultores = array();
            $listaConsultores = ConsultoresQuerys::listarConsultores();
            foreach ($listaConsultores as $con) {
                $consultores[] = $con->co_usuario;
            }
        }
        $data =DesempenoQuerys::prepararDataPizza($consultores,$desde, $hasta);
        return array('ok' => true, 'data' => $data);
    }
}
