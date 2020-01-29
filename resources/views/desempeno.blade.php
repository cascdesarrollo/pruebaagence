@extends('layouts.app')

@section('content')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <style type="text/css">
   .box{
    width:800px;
    margin:0 auto;
   }
  </style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Desempeño por Consultor
                </div>
                <div class="card-body">
                    <div class="row form-group">
                        <div class="col-sm-12 col-md-2 col-lg-2">
                            <label>Periodo</label>
                        </div>
                        <div class="col-sm-12 col-md-5 col-lg-5">
                            <div class="row">
                                <div class="col-3">Desde</div>
                                <div class="col-5">
                                    <select name="mesDesde" id="mesDesde" class="form-control">
                                        <option value="01">Enero</option>
                                        <option value="02">Febrero</option>
                                        <option value="03">Marzo</option>
                                        <option value="04">Abril</option>
                                        <option value="05">Mayo</option>
                                        <option value="06">Junio</option>
                                        <option value="07">Julio</option>
                                        <option value="08">Agosto</option>
                                        <option value="09">Septiembre</option>
                                        <option value="10">Octubre</option>
                                        <option value="11">Noviembre</option>
                                        <option value="12">Diciembre</option>
                                    </select>
                                </div>
                                <div class="col-4">
                                    <select name="anoDesde" id="anoDesde" class="form-control">
                                        <option value="2003">2003</option>
                                        <option value="2004">2004</option>
                                        <option value="2005">2005</option>
                                        <option value="2006">2006</option>
                                        <option value="2007">2007</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-5 col-lg-5">
                            <div class="row">
                                <div class="col-3">Hasta</div>
                                <div class="col-5">
                                    <select name="mesHasta" id="mesHasta" class="form-control">
                                        <option value="01">Enero</option>
                                        <option value="02">Febrero</option>
                                        <option value="03">Marzo</option>
                                        <option value="04">Abril</option>
                                        <option value="05">Mayo</option>
                                        <option value="06">Junio</option>
                                        <option value="07">Julio</option>
                                        <option value="08">Agosto</option>
                                        <option value="09">Septiembre</option>
                                        <option value="10">Octubre</option>
                                        <option value="11">Noviembre</option>
                                        <option value="12">Diciembre</option>
                                    </select>
                                </div>
                                <div class="col-4">
                                    <select name="anoHasta" id="anoHasta" class="form-control">
                                        <option value="2003">2003</option>
                                        <option value="2004">2004</option>
                                        <option value="2005">2005</option>
                                        <option value="2006">2006</option>
                                        <option value="2007">2007</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-12 col-md-2 col-lg-2">
                            <label>Consultores</label>
                        </div>
                        <div class="col-sm-11 col-md-5 col-lg-5">
                            <div class="row">
                                <div class="col-10">
                                    <div class="form-group">
                                        <label for="consultores1">Listado General</label>
                                        <select multiple class="form-control" name="consultores1" id="consultores1">
                                            @foreach($consultores as $consul)
                                                <option value="{{$consul->co_usuario}}">{{$consul->no_usuario}}</option>
                                            @endforeach
                                        </select> 
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <label></label>
                                        <div>
                                            <button type="submit" class="btn btn-primary" style="margin-bottom: 10px; margin-top: 10px;" onclick="move(consultores1,consultores2)">>></button>
                                            
                                            <button type="submit" class="btn btn-primary" onclick="move(consultores2,consultores1)"><<</button>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        <div class="col-sm-12 col-md-4 col-lg-4">
                            <div class="form-group">
                                <label for="consultores2">Consultores a filtrar</label>
                                <select multiple class="form-control" name="consultores2" id="consultores2">
                              
                              </select> 
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary" onclick="consultarListadoDesempeno()">
                        Relatorio
                        <i class="fa fa-list"></i>
                        </button>
                        <button type="submit" class="btn btn-success" onclick="consultarGraficaDesempeno()">
                        Grafico
                        <i class="fa fa-chart-bar"></i>
                        </button>
                        <button type="submit" class="btn btn-danger" onclick="consultarPizza()">Pizza
                        <i class="fa fa-chart-pie"></i></button>
                        </div>
                    </div>
                    <div id="relatorio" class="row form-group" style="margin-top: 30px; visibility: hidden">
                
                    </div>
                    <div id="grafico" class="row form-group" style="margin-top: 30px; visibility: hidden; ">
                    </div>
                    <div id="pizza" class="row form-group" style="margin-top: 30px; visibility: hidden;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script language="JavaScript">
var csrfToken = "";

function validarFiltro(){
    if( parseInt( $('#anoDesde').val())> parseInt( $('#anoHasta').val())){
        alert('Año de inicio debe ser igual o menor que año final de búsqueda');
        return false;
    }
    if( parseInt( $('#anoDesde').val()) == parseInt( $('#anoHasta').val())
    && parseInt( $('#mesDesde').val()) > parseInt( $('#mesHasta').val())
    ){
        alert('Mes de inicio debe ser igual o menor que mes final de búsqueda');
        return false;
    }
    return true;
}

function mensajeRespuestaVacia(){
    alert('Consulta no arrojó resultados para filtro asignado');
}

function consultarGraficaDesempeno(){
    if(!validarFiltro()){
        return;
    }
    var consultores = [];
    $('#consultores2 option').each(function() {
        consultores.push($(this).val());
    });
    var dataPost = {
        "_token": "{{ csrf_token() }}",
        "data":consultores,
        "desde": $('#anoDesde').val()+ $('#mesDesde').val(),
        "hasta": $('#anoHasta').val()+ $('#mesHasta').val()
    };
    $.ajax({
        url: 'graficadesempeno',
        type: 'POST',
        data: dataPost,
        headers: {
        'X-CSRF-Token': csrfToken
        },
        success: function (data) {
            $('#relatorio').css("visibility", "hidden");
            $('#relatorio').html('');
            $('#pizza').css("visibility", "hidden");
            $('#pizza').html('');
            $('#pizza').css( "height","0px");
            $('#grafico').css("visibility", "visible");
            $('#grafico').css( "height","500px");
            if (data['ok']) 
            {
                if(data.data.consultores.length==0){
                    mensajeRespuestaVacia();
                    return;
                }
               //preparar data
               var dataPreparada = [];
               var cabecera=[];
               cabecera.push('Mes');
               data.data.consultores.forEach(consultor => {
                cabecera.push(consultor.consultor);
               });
               cabecera.push('Salario Promedio');
               dataPreparada.push(cabecera);
               for (let index = 0; index < data.data.meses.length; index++) {
                    var linea=[];
                    linea.push(data.data.meses[index]);
                    data.data.consultores.forEach(consultor => {
                        linea.push(consultor.ganancias[index]);
                    });
                    linea.push(data.data.promedio);
                    dataPreparada.push(linea);
               }
               console.log(dataPreparada);
               pintarGraficoBarras(dataPreparada, data.data.consultores.length);
               
            }
        },
        error: function (dataError) {
            console.log(dataError)
            alert(dataError.responseText);
        }
    });
}

google.charts.load('current', {'packages':['corechart']});
function pintarGraficoBarras(dataPreparada, indicePromedio) {
    var data = new google.visualization.arrayToDataTable(
        dataPreparada
    );
    var options = {
        width: 900,
        chart: {
        title: 'Grafico de Desempeño'
        },
        seriesType: 'bars',
        //series: {0: {type: 'line'}},
        axes: {
        x: {
            distance: {label: 'parsecs'}, // Bottom x-axis.
            brightness: {side: 'top', label: 'apparent magnitude'} // Top x-axis.
        }
        },
        yAxis: { color:'#0f0',
        format: 'R$#'  // <-- format
        }
    };
    myObj = {}; 
    myObj[indicePromedio]= {type: 'line'};
    options.series = myObj;
    var chart = new google.visualization.ComboChart(document.getElementById('grafico'));
    chart.draw(data, options);
};


function consultarPizza(){
    if(!validarFiltro()){
        return;
    }
    var consultores = [];
    $('#consultores2 option').each(function() {
        consultores.push($(this).val());
    });
    var dataPost = {
        "_token": "{{ csrf_token() }}",
        "data":consultores,
        "desde": $('#anoDesde').val()+ $('#mesDesde').val(),
        "hasta": $('#anoHasta').val()+ $('#mesHasta').val()
    };
    $.ajax({
        url: 'pizza',
        type: 'POST',
        data: dataPost,
        headers: {
        'X-CSRF-Token': csrfToken
        },
        success: function (data) {
            console.log(data);
            $('#relatorio').css("visibility", "hidden");
            $('#relatorio').html('');
            $('#grafico').css("visibility", "hidden");
            $('#grafico').html('');
            $('#grafico').css( "height","0px");
            $('#pizza').css("visibility", "visible");
            $('#pizza').html('');
            $('#pizza').css( "height","500px");
            if (data['ok']) 
            {
                if(data.data.length==0){
                    mensajeRespuestaVacia();
                    return;
                }
                //preparar data
                var dataPreparada = [];
                var cabecera=[];
                cabecera.push('Consultor');
                cabecera.push('% Ganancia');
                dataPreparada.push(cabecera);
                data.data.forEach(det => {
                    var linea=[];
                    linea.push(det.consultor);
                    linea.push(det.ganancia);
                    dataPreparada.push(linea);
                });
               
               console.log(dataPreparada);
               pintarPizza(dataPreparada);
               
            }
        },
        error: function (dataError) {
            console.log(dataError)
            alert(dataError.responseText);
        }
    });
}

function pintarPizza(dataPreparada){

    var data = google.visualization.arrayToDataTable(
        dataPreparada
    );
    var options = {
    title: 'Particiáción por Consultor'
    };
    var chart = new google.visualization.PieChart(document.getElementById('pizza'));
    chart.draw(data, options);
}

function consultarListadoDesempeno(){;
    if(!validarFiltro()){
        return;
    }
    var consultores = [];
    $('#consultores2 option').each(function() {
        consultores.push($(this).val());
    });
    var dataPost = {
        "_token": "{{ csrf_token() }}",
        "data":consultores,
        "desde": $('#anoDesde').val()+ $('#mesDesde').val(),
        "hasta": $('#anoHasta').val()+ $('#mesHasta').val()
    };
    $.ajax({
        url: 'listadodesempeno',
        type: 'POST',
        data: dataPost,
        headers: {
        'X-CSRF-Token': csrfToken
        },
        success: function (data) {
            $('#grafico').css("visibility", "hidden");
            $('#grafico').html('');
            $('#grafico').css( "height","0px");
            $('#pizza').css("visibility", "hidden");
            $('#pizza').html('');
            $('#pizza').css( "height","0px");
            $('#relatorio').css("visibility", "visible");
            $('#relatorio').html('');
            if (data['ok']) 
            {
                if(data.data.length==0){
                    mensajeRespuestaVacia();
                    return;
                }
               crearTablaRelatorio(data.data);
            } 
        },
        error: function (dataError) {
            console.log(dataError)
            alert(dataError.responseText);
        }
    });
}

function cabeceraTabla(consultor){
    var cabecera = '<thead class="bg-info"><tr><th colspan="5">' + consultor.consultor.no_usuario + '</td></tr></thead>';        
    cabecera += '<tr class="font-weight-bold"><td>Perido</td><td class="text-center">Receita Líquida</td><td class="text-center">Custo Fixo</td><td class="text-center">Comissão</td><td class="text-center">Lucro</td></tr>';
    return cabecera;
}

function pieTabla(consultor){
    var pie = '<tfoot class="bg-info">';
    pie += '<tr class="font-weight-bold"><td>SALDO</td>';
    pie += '<td class="text-right"'+formatNumber.new(consultor.ganancia, "R$ ")+'</td>';
    pie += '<td class="text-right">'+formatNumber.new(consultor.costoFijo, "R$ ")+'</td>';
    pie += '<td class="text-right">'+formatNumber.new(consultor.comision, "R$ ")+'</td>';
    pie += '<td class="text-right">'+formatNumber.new(consultor.beneficio, "R$ ")+'</td>';
    pie += '</tr>';
    pie += '</tfoot>';
    return pie;
}

function crearFila(mes){
    var fila='<tr>';
    fila+= '<td>'+mes.fecha+'</td>';
    fila+= '<td class="text-right">'+formatNumber.new(mes.ganancia, "R$ ")+'</td>';
    fila+= '<td class="text-right">'+formatNumber.new(mes.costoFijo, "R$ ")+'</td>';
    fila+= '<td class="text-right">'+formatNumber.new(mes.comision, "R$ ")+'</td>';
    fila+= '<td class="text-right">'+formatNumber.new(mes.beneficio, "R$ ")+'</td>';
    fila+='</tr>';
    return fila;
}

function crearTablaRelatorio(data){
    console.log(data);
    for (let index = 0; index < data.length; index++) {
        var consultor=data[index];
        var content = '<table width="100%" class="table table-hover" style="margin-bottom: 20px;">'
        content += cabeceraTabla(consultor);
        consultor.data.forEach(mes => {
            content += crearFila(mes);
        });
        content += pieTabla(consultor);
        content += '</table>';
        $('#relatorio').append(content);
        $('#relatorio').append('<br><br>');

    
    }
}


function move(fbox, tbox) {
    var arrFbox = new Array();
    var arrTbox = new Array();
    var arrLookup = new Array();
    var i;
    for (i = 0; i < tbox.options.length; i++) {
        arrLookup[tbox.options[i].text] = tbox.options[i].value;
        arrTbox[i] = tbox.options[i].text;
    }
    var fLength = 0;
    var tLength = arrTbox.length;
    for(i = 0; i < fbox.options.length; i++) {
        arrLookup[fbox.options[i].text] = fbox.options[i].value;
        if (fbox.options[i].selected && fbox.options[i].value != "") {
            arrTbox[tLength] = fbox.options[i].text;
            tLength++;
        } else {
        arrFbox[fLength] = fbox.options[i].text;
        fLength++;
      }
    }
    arrFbox.sort();
    arrTbox.sort();
    fbox.length = 0;
    tbox.length = 0;
    var c;
    for(c = 0; c < arrFbox.length; c++) {
        var no = new Option();
        no.value = arrLookup[arrFbox[c]];
        no.text = arrFbox[c];
        fbox[c] = no;
    }
    for(c = 0; c < arrTbox.length; c++) {
        var no = new Option();
        no.value = arrLookup[arrTbox[c]];
        no.text = arrTbox[c];
        tbox[c] = no;
    }
}

var formatNumber = {
 separador: ".", // separador para los miles
 sepDecimal: ',', // separador para los decimales
 formatear:function (num){
 num +='';
 var splitStr = num.split('.');
 var splitLeft = splitStr[0];
 var splitRight = splitStr.length > 1 ? this.sepDecimal + splitStr[1] : '';
 var regx = /(\d+)(\d{3})/;
 while (regx.test(splitLeft)) {
 splitLeft = splitLeft.replace(regx, '$1' + this.separador + '$2');
 }
 return this.simbol + splitLeft +splitRight;
 },
 new:function(num, simbol){
 this.simbol = simbol ||'';
 return this.formatear(num);
 }
}

$('#anoDesde').val('2007')
$('#anoHasta').val('2007')
</script>

@endsection
