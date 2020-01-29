<?php

namespace App\utils\querys;

use Illuminate\Support\Facades\DB;
use App\models\CaoUsuario;
use App\models\CaoSalario;



class ConsultoresQuerys
{
    public static function listarConsultores(){
        $query = ConsultoresQuerys::queryBaseListarConsultores();
        $query = ConsultoresQuerys::camposListarConsultores($query);
        $query = ConsultoresQuerys::condicionListaConsultores($query);
        return $query->get();
    }

    public static function consultarConsultor($coUsuario){
        return CaoUsuario::select()->where('co_usuario','=',$coUsuario)->firstOrFail();
    }

    public static function consultarSalarioBrutoConsultor($coUsuario){
        $salario =CaoSalario::select()->where('co_usuario','=',$coUsuario)->first();
        if($salario){
            return $salario->brut_salario;
        }else{
            return 0;
        }
    }

    private static function queryBaseListarConsultores(){
        return DB::table('cao_usuario AS usu')
        ->join('permissao_sistema AS per', 'usu.co_usuario', '=', 'per.co_usuario');
    }

    private static function camposListarConsultores($query){
        return $query->select('usu.co_usuario','usu.no_usuario');
    }

    private static function condicionListaConsultores($query){
        return $query->where('per.co_sistema','=',1)
        ->where('per.in_ativo','=','S')
        ->WhereIn('per.co_tipo_usuario', [0,1,2]);
    }

}