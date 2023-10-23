<?php

namespace App\Http\Controllers\HistoriasMedicas;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\CollectionDataTable;
use App\Core\Entities\Compromisos\Ubicacion;
use DB;

use App\Exports\CompromisosExport;
use Maatwebsite\Excel\Facades\Excel;

/*EDITAR EXCEL */
use PhpOffice\PhpSpreadsheet\{Spreadsheet, IOFactory, Exception};
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\{Border,Color,Alignment,Fill,NumberFormat};
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
//use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Chart\{Chart,Layout};
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
/*FIN EDITAR EXCEL*/

class ReportesController extends Controller
{
    public function reportes(){
        //CONSULTA DE GABINETE
        $cqlGabinete = Institucion::select('id','descripcion')->where('nivel','1')->pluck('descripcion','id');
        //CONSULTA DE INSTITUCION
        $cqlInstitucion = Institucion::select('id','descripcion')->where('nivel','2')->pluck('descripcion','id');
        //CONSULTA ESTADO GESTIÓN
        $gestion_filtro = EstadoPorcentaje::select('id','descripcion')
        ->pluck('descripcion','id');
        //CONSULTA COMPROMISOS
        $compromiso_filtro = Compromiso::select('id','nombre_compromiso')
        ->where('estado','ACT')->pluck('nombre_compromiso','id');

        //CONSULTA UBICACION
        $ubicacion_filtro = parametro_ciudad::with(['fatherpara'=>function($q){
            $q->with('fatherpara');
        }])
        ->where('nivel','3')
        ->orderby('descripcion','asc')->pluck('descripcion','id');

        return view('modules.compromisos.reporte.index',compact('cqlGabinete','cqlInstitucion','gestion_filtro','ubicacion_filtro','compromiso_filtro'));
    }
    //REPORTE EJECUTIVO
    //TRAE LOS COMPROMISOS
    public function consulta_compromiso(request $request){
        $cqlFiltroCompromisos = Compromiso::select(["id", DB::RAW("CONCAT(codigo,'/ ',nombre_compromiso) as nombre_compromiso")])
        ->where('estado','ACT')
        ->get();
        $array_response['status'] = 200;
        $array_response['datos'] = $cqlFiltroCompromisos;
        return response()->json($array_response, 200);
    }
    //TRAE PERIODOS ACTUALES
    public function consulta_periodo_actual(request $request){
        $cqlPeriodoCompromiso = Compromiso::
        select(DB::RAW("CONCAT(compromisos.fecha_inicio,' | ',compromisos.fecha_fin,'    REF: ',compromisos.nombre_compromiso) as periodo_compromiso"),"compromisos.id as id")
        //->leftjoin('sc_compromisos.objetivos as objetivos','objetivos.id','periodos.objetivo_id')
        ->where('compromisos.estado','ACT');
        if ($request->hoy != null || $request->hoy != ''){
            $cqlPeriodoCompromiso=$cqlPeriodoCompromiso->whereDate('compromisos.fecha_inicio','<=',$request->hoy)
            ->whereDate('compromisos.fecha_fin','>=',$request->hoy);
        }
        $cqlPeriodoCompromiso=$cqlPeriodoCompromiso->get();
        //pluck('periodo_compromiso','id')->toArray();
        //dd($cqlPeriodoCompromiso);
        $array_response['status'] = 200;
        $array_response['datos'] = $cqlPeriodoCompromiso;
        return response()->json($array_response, 200);
    }
    public function consultaReporteEjecutivo(request $request){
        $array_response['status'] = 200;
        if ($request->tipo_compromiso==true || $request->tipo_periodo==true){
            $cqlCompromiso = Compromiso::select(
                'compromisos.id as id',
                'compromisos.nombre_compromiso as nombre_compromiso',
                'insitucion_responsable.descripcion as institucion_responsable',
                //'insitucion_corresponsable.descripcion as institucion_corresponsable',
                'gabinete_sectorial.descripcion as gabinete_sectorial',
                'compromisos.fecha_inicio as fecha_inicio',
                'compromisos.fecha_fin as fecha_fin',
                'tb_parametro.descripcion as canton',
                'tb_parametro_provincia.descripcion as ciudadades'
            )
            //RESPONSABLE
            ->leftjoin('sc_compromisos.responsables as responsables','responsables.compromiso_id','compromisos.id')
            ->leftjoin('sc_compromisos.instituciones as insitucion_responsable','insitucion_responsable.id','responsables.institucion_id')
            /*CORRESPONSABLE
            ->leftjoin('sc_compromisos.corresponsables as corresponsables','corresponsables.compromiso_id','compromisos.id')
            ->leftjoin('sc_compromisos.instituciones as insitucion_corresponsable','insitucion_corresponsable.id','corresponsables.institucion_corresponsable_id')*/
            //GABINETE SECTORIAL
            ->leftjoin('sc_compromisos.instituciones as gabinete_sectorial','gabinete_sectorial.id','insitucion_responsable.institucion_id')
            //UBICACION
            ->leftjoin('sc_compromisos.ubicaciones as ubicaciones','ubicaciones.compromiso_id','compromisos.id')
            ->leftjoin('core.tb_parametro as tb_parametro','tb_parametro.id','ubicaciones.parametro_id')
            ->leftjoin('core.tb_parametro as tb_parametro_provincia','tb_parametro_provincia.id','tb_parametro.parametro_id')

            ->where('compromisos.estado','ACT')
            ->where('compromisos.id',$request->id)
            ->first();
            //OBTENER CORRESPONSABLE
            $cqlCorresponsable = Corresponsable::select('insitucion_corresponsable.descripcion')
            ->leftjoin('sc_compromisos.instituciones as insitucion_corresponsable','insitucion_corresponsable.id','corresponsables.institucion_corresponsable_id')
            ->where('corresponsables.estado','ACT')
            ->where('corresponsables.compromiso_id',$cqlCompromiso->id)
            ->orderBy('corresponsables.id','asc')
            ->pluck('insitucion_corresponsable.descripcion')->toArray();
            //OBTENER OBJETIVOS
            $cqlObjetvios = Objetivo::select('objetivos.id','objetivos.objetivo')->where('estado','ACT')->where('compromiso_id',$cqlCompromiso->id)->orderBy('id','asc')->pluck('objetivos.objetivo','objetivos.id')->toArray();
            $cqlTipoObjetvios = Objetivo::select('objetivos.id','objetivos.objetivo')->where('estado','ACT')->where('compromiso_id',$cqlCompromiso->id)->orderBy('id','asc')->pluck('objetivos.objetivo','objetivos.id')->toArray();

            $array_response['datos'] = $cqlCompromiso;
            $array_response['objetivos'] = $cqlObjetvios;
            $array_response['corresponsables'] = $cqlCorresponsable;
        }
        $array_response['message'] = 'Generado con exito';
        return response()->json($array_response, 200);
    }
    public function generaReporteEjecutivo(request $request){
        $ID=$request->id;
        $COMPROMISO=strtoupper($request->nombre_compromiso);
        $INSTITUCION_RESPONSABLE=strtoupper($request->institucion_responsable);
        $GABINETE_SECTORIAL=strtoupper($request->gabinete_sectorial);
        $FECHA_INICIO=$request->fecha_inicio;
        $FECHA_FIN=$request->fecha_fin;
        $UBICACION_PROVINCIA=$request->ciudadades=="CIUDADES"?"NACIONAL":'NO DISPONIBLE';
        $UBICACION_CANTON=strtoupper($request->canton!=null?$request->canton:'NO DISPONIBLE');
        $array_response['status'] = "200";

        //$url='storage/reporte_plantilla.xlsx';
        $url='storage/FORMATOS/EJECUTIVO_COMPROMISOS.xlsx';
        $reader = IOFactory::createReader("Xlsx");
        //$reader->setIncludeCharts(true);
        $spread = $reader->load($url);
            try {
                //$spread = new Spreadsheet();
                $sheet = $spread->getActiveSheet();
                //$sheet->getDefaultRowDimension()->setRowHeight(15);
                $writer = IOFactory::createWriter($spread, 'Xlsx');
                //BLOQUE PRINCIPAL - COMPROMISOS

                $sheet->setCellValue("B1", $COMPROMISO);
                $sheet->setCellValue("B2", $INSTITUCION_RESPONSABLE);
                //INSTITUCION CORRESPONSABLE
                foreach ($request->institucion_corresponsable as $key => $institucion_corresponsable_){
                    if ($key==0) $sheet->setCellValue("B3", $institucion_corresponsable_);
                    if ($key==1) $sheet->setCellValue("C3", $institucion_corresponsable_);
                    if ($key==2) $sheet->setCellValue("D3", $institucion_corresponsable_);
                }
                $sheet->setCellValue("B4", $GABINETE_SECTORIAL);
                $sheet->setCellValue("B5", date("d/m/Y", strtotime($FECHA_INICIO)));
                $sheet->setCellValue("D5", date("d/m/Y", strtotime($FECHA_FIN)));
                $sheet->setCellValue("B6", $UBICACION_PROVINCIA);
                $sheet->setCellValue("D6", $UBICACION_CANTON);
                if ($request->objetivo!=null){
                    $celda_fila = 2;
                    $celda_columna = "E";
                    foreach ($request->objetivo as $objetivo){
                        $sheet->setCellValue($celda_columna.$celda_fila, strtoupper($objetivo));
                        if ($celda_fila==6 && $celda_columna=="E"){
                            $celda_fila = 1;
                            $celda_columna = "H";
                        }
                        $celda_fila++;
                        if ($celda_fila == 7) break;
                    }
                }
                //FIN BLOQUE PRINCIPAL - COMPROMISOS

                //CONSTRUYENDO BLOQUE DE OBJETIVOS  Y PERIODOS
                //ESTILO PARA CELDAS DE BLOQUES OBJETIVOS
                $styleTagObjetivos = [
                    'font' => [
                        'bold' => true, 'color' => array('rgb' => '000000'), 'size'  => 16, 'name'  => 'Calibri'
                    ],
                    'alignment' => [
                        'horizontal' => 'center', 'vertical' => 'center',
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'D9E1F2',
                        ],
                    ],
                ];
                $styleEtiquetaSinFondo = [
                    'font' => [
                        'bold' => true, 'color' => array('rgb' => '000000'), 'size'  => 16, 'name'  => 'Calibri'
                    ],
                    'alignment' => [
                        'horizontal' => 'center', 'vertical' => 'center',
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ];
                $styleEtiquetaNotas = [
                    'font' => [
                        'bold' => false, 'color' => array('rgb' => '000000'), 'size'  => 16, 'name'  => 'Calibri'
                    ],
                    'alignment' => [
                        'horizontal' => 'center', 'vertical' => 'center',
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ];
                $styleMarcoObjetivo = [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => Border::BORDER_MEDIUM,
                        ],
                    ]
                ];
                $styleTagPeriodos_Porcentaje_grafico = [
                    'font' => [
                        'bold' => false, 'color' => array('rgb' => '305496'), 'size'  => 32, 'name'  => 'Bernard MT Condensed'
                    ],
                    'alignment' => [
                        'horizontal' => 'center', 'vertical' => 'center',
                    ],
                    'numberFormat' => [
                        'formatCode' => NumberFormat::FORMAT_PERCENTAGE_00,
                    ],
                ];
                //ESTILO PARA CELDAS DE BLOQUES PERIODO
                $styleTagPeriodos_blue = [
                    'font' => [
                        'bold' => true, 'color' => array('rgb' => 'F0F0F0'), 'size'  => 16, 'name'  => 'Calibri'
                    ],
                    'alignment' => [
                        'horizontal' => 'center', 'vertical' => 'center',
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => '44546A',
                        ],
                    ],
                ];
                $styleTagPeriodos_green = [
                    'font' => [
                        'bold' => true, 'color' => array('rgb' => '000000'), 'size'  => 16, 'name'  => 'Calibri'
                    ],
                    'alignment' => [
                        'horizontal' => 'center', 'vertical' => 'center',
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'A9D08E',
                        ],
                    ],
                ];
                $styleTagPeriodos_yellow = [
                    'font' => [
                        'bold' => true, 'color' => array('rgb' => '000000'), 'size'  => 16, 'name'  => 'Calibri'
                    ],
                    'alignment' => [
                        'horizontal' => 'center', 'vertical' => 'center',
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'FFC000',
                        ],
                    ],
                ];
                $styleTagPeriodos_noFondo = [
                    'font' => [
                        'bold' => false, 'color' => array('rgb' => '000000'), 'size'  => 16, 'name'  => 'Calibri'
                    ],
                    'alignment' => [
                        'horizontal' => 'center', 'vertical' => 'center',
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ];
                $styleTagPeriodos_Porcentaje = [
                    'font' => [
                        'bold' => false, 'color' => array('rgb' => '000000'), 'size'  => 16, 'name'  => 'Calibri'
                    ],
                    'alignment' => [
                        'horizontal' => 'center', 'vertical' => 'center',
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'numberFormat' => [
                        'formatCode' => NumberFormat::FORMAT_PERCENTAGE_00,
                    ],
                ];

                $celdaCrearObjetivo = 9;//PARA BLOQUE OBJETIVOS
                $celdaCrearPeriodo = 14;//PARA BLOQUE PERIODOS
                $contadorObjetivo = 1;
                $columnaInicial = 2; //desde donde se empieza a contar los periodos COLUMNAS
                foreach ($request->objetivo as $key => $objetivo){
                    //CONSULTA A BASE DE OBJETIVOS
                    $cqlObjetivos = Objetivo::select(
                        'objetivos.meta as meta',
                        'objetivos.fecha_inicio as fecha_inicio',
                        'objetivos.fecha_fin as fecha_fin',
                        'objetivos.motivo_negado as motivo_negado',
                        'temporalidad.descripcion as temporalidad',
                        'tipo_objetivo.descripcion as tipo_objetivo',
                        'periodo.numero as periodo_numero',
                    )
                    ->join('sc_compromisos.temporalidades as temporalidad','temporalidad.id','objetivos.temporalidad_id')
                    ->join('sc_compromisos.tipos_objetivos as tipo_objetivo','tipo_objetivo.id','objetivos.tipo_objetivo_id')
                    ->leftjoin('sc_compromisos.periodos as periodo','periodo.objetivo_id','objetivos.id')
                    ->where('objetivos.id',$key)->first();
                    //CONSULTA A BASE DE PERIODOS
                    $cqlPeriodo_ = Periodo::select(
                        'periodos.numero as numero',
                        'periodos.fecha_inicio_periodo as fecha_inicio_periodo',
                        'periodos.descripcion_meta as descripcion_meta',
                        'periodos.caracterizacion as caracterizacion',
                        'periodos.meta_periodo as meta_periodo',
                        'periodos.cumplimiento_periodo as cumplimiento_periodo',
                        DB::RAW("case when periodos.meta_periodo!=0 then (periodos.cumplimiento_periodo/periodos.meta_periodo::numeric) else 0 end as cumplimiento_periodo_porcentaje"),
                        'periodos.pendiente_periodo as pendiente_periodo',
                        'periodos.meta_acumulada as meta_acumulada',
                        'periodos.cumplimiento_acumulado as cumplimiento_acumulado',
                        'periodos.pendiente_acumulado as pendiente_acumulado',
                        'periodos.observaciones as observaciones',
                    )
                    ->where('periodos.objetivo_id',$key)
                    //->where('periodos.estado','ACT')
                    ->where('periodos.eliminado',false)
                    ->orderBy('numero','asc')
                    ->get()->toArray();

                    //BLOQUES OBJETIVOS
                    //EVALUA TIPO DE OBJETIVO Y REALIZA ACCIONES
                        if ($cqlObjetivos->tipo_objetivo=="CUANTITATIVO"){
                            //TAG PARA BORRAR
                            $sheet->removeRow($celdaCrearObjetivo+5,4);
                            //TAG PORCENTAJE GRAFICO BARRAS
                            //$sheet->getStyle("H".($celdaCrearObjetivo+1))->applyFromArray($styleTagPeriodos_Porcentaje_grafico);
                            $sheet->getStyle("J".($celdaCrearObjetivo+2))->applyFromArray($styleTagPeriodos_Porcentaje_grafico);
                            //$sheet->setCellValue("H".($celdaCrearObjetivo+1),"=J".$celdaCrearObjetivo);
                            $sheet->setCellValue("J".($celdaCrearObjetivo+2),"=J".$celdaCrearObjetivo);

                            $label_a = 'hoja1!$E$'.($celdaCrearObjetivo+1).':$E$'.($celdaCrearObjetivo+1);//cumplimiento acumulado
                            $label_b = 'hoja1!$E$'.($celdaCrearObjetivo+2).':$E$'.($celdaCrearObjetivo+2);//meta acumulada
                            $label_c = 'hoja1!$E$'.($celdaCrearObjetivo+3).':$E$'.($celdaCrearObjetivo+3);//meta final
                            $dataseriesLabels1 = array(
                                new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $label_a, NULL, 1, [], NULL, '7FD7A7'), //cumplimiento acumulado
                                new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $label_b, NULL, 1, [], NULL, 'FFDE7A'), //meta acumulada
                                new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $label_c, NULL, 1, [], NULL, 'DAE3F3'), //meta final
                            );

                            //$xAxisTickValues = array(new DataSeriesValues('String', 'hoja1!$E$'.($celdaCrearObjetivo+1).':$E$'.($celdaCrearObjetivo+3), NULL, 3));
                            $xAxisTickValues = array(new DataSeriesValues('String', 'hoja1!$E$'.($celdaCrearObjetivo-1).':$E$'.($celdaCrearObjetivo-1), NULL,1));

                            $dataSeriesValues1 = array(
                                new DataSeriesValues('Number', 'hoja1!$F$'.($celdaCrearObjetivo+1), NULL, 1),
                                new DataSeriesValues('Number', 'hoja1!$F$'.($celdaCrearObjetivo+2), NULL, 1),
                                new DataSeriesValues('Number', 'hoja1!$F$'.($celdaCrearObjetivo+3), NULL, 1),
                            );


                            //  Construye la serie de datos
                            $series1 = new DataSeries(
                                    DataSeries::TYPE_BARCHART, // plotType
                                    DataSeries::GROUPING_CLUSTERED, // plotGrouping STANDARD CLUSTERED
                                    range(0, count($dataSeriesValues1) - 1), // plotOrder
                                    $dataseriesLabels1, // plotLabel
                                    $xAxisTickValues, // plotCategory
                                    $dataSeriesValues1 // plotValues
                            );
                            $layout = new Layout();
                            $layout->setShowVal(true);
                            $series1->setPlotDirection(DataSeries::DIRECTION_HORIZONTAL);
                            $plotarea = new PlotArea($layout, array($series1));
                            $legend = new Legend(Legend::POSITION_BOTTOM, NULL, false);

                            //  Crea el gráfico
                            $chart = new Chart(
                                'chart', // name
                                NULL, //title
                                $legend, // legend
                                $plotarea,
                                true,
                                DataSeries::EMPTY_AS_GAP, // displayBlanksAs
                            );
                            //  Establezca la posición donde debe aparecer el gráfico en la hoja de trabajo
                            $chart->setTopLeftPosition('G'.($celdaCrearObjetivo+1));
                            $chart->setBottomRightPosition('J'.($celdaCrearObjetivo+4));
                            //  Agregue el gráfico a la hoja de trabajo
                            $sheet->addChart($chart);
                            $chart->render($spread);
                        }else{
                            //TAG PARA BORRAR
                            $sheet->removeRow($celdaCrearObjetivo+1,4);
                            $sheet->setCellValue("F".($celdaCrearObjetivo+1), "NO APLICA");
                            $sheet->setCellValue("F".($celdaCrearObjetivo+2), "NO APLICA");
                            $sheet->setCellValue("F".($celdaCrearObjetivo+3), "NO APLICA");
                            //TAG PORCENTAJE GRAFICO ANILLO PORCENTUAL
                            //$sheet->getStyle("H".($celdaCrearObjetivo+2))->applyFromArray($styleTagPeriodos_Porcentaje_grafico);
                            $sheet->getStyle("J".($celdaCrearObjetivo+2))->applyFromArray($styleTagPeriodos_Porcentaje_grafico);
                            //$sheet->setCellValue("H".($celdaCrearObjetivo+2),"=J".$celdaCrearObjetivo);
                            $sheet->setCellValue("J".($celdaCrearObjetivo+2),"=J".$celdaCrearObjetivo);
                            //GRAFICO PASTEL
                            $colors = [
                                '2F5597', 'E4E9F2'
                            ];
                            $label_a = 'hoja1!$J$'.($celdaCrearObjetivo-1).':$J$'.($celdaCrearObjetivo-1);//cumplimiento acumulado
                            $dataseriesLabels1 = [];
                            $xAxisTickValues = array(new DataSeriesValues('String', 'hoja1!$I$'.($celdaCrearObjetivo).':$I$'.($celdaCrearObjetivo), null,1));
                            //$xAxisTickValues = array(new DataSeriesValues('String', 'hoja1!$I$'.($celdaCrearObjetivo-1).':$I$'.($celdaCrearObjetivo-1), null,1));
                            $dataSeriesValues1 = array(
                                new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'hoja1!$J$'.($celdaCrearObjetivo).':$K$'.($celdaCrearObjetivo), null, 2,[], null, $colors),
                            );
                            //  Construye la serie de datos
                            $series1 = new DataSeries(
                                    DataSeries::TYPE_DOUGHNUTCHART, // plotType
                                    null,
                                    //DataSeries::GROUPING_CLUSTERED, // plotGrouping STANDARD CLUSTERED
                                    range(0, count($dataSeriesValues1) - 1), // plotOrder
                                    $dataseriesLabels1, // plotLabel
                                    $xAxisTickValues, // plotCategory
                                    $dataSeriesValues1 // plotValues
                            );
                            $layout = new Layout();
                            //$layout->setShowVal(true);
                            $layout->setShowPercent(true);

                            //$series1->setPlotDirection(DataSeries::DIRECTION_HORIZONTAL);
                            $plotarea = new PlotArea($layout, array($series1));
                            $legend = new Legend(Legend::POSITION_BOTTOM, NULL, false);

                            //  Crea el gráfico
                            $chart = new Chart(
                                'chart', // name
                                null, //title
                                null,//$legend, // legend
                                $plotarea,
                                true,
                                DataSeries::EMPTY_AS_GAP, // displayBlanksAs
                                null,
                                null
                            );
                            //  Establezca la posición donde debe aparecer el gráfico en la hoja de trabajo
                            $chart->setTopLeftPosition('G'.($celdaCrearObjetivo+1));
                            $chart->setBottomRightPosition('J'.($celdaCrearObjetivo+4));
                            //  Agregue el gráfico a la hoja de trabajo
                            $sheet->addChart($chart);
                            $chart->render($spread);
                        }
                        //TAG OBJETIVO
                        $sheet->getStyle("A".$celdaCrearObjetivo)->applyFromArray($styleTagObjetivos);
                        $sheet->setCellValue("A".$celdaCrearObjetivo,"OBJETIVO"." ".$contadorObjetivo);
                        //TAG NOMBRE DEL OBJETIVO
                        $sheet->mergeCells("B".$celdaCrearObjetivo.":"."D".$celdaCrearObjetivo);
                        $sheet->getStyle("B".$celdaCrearObjetivo.":"."D".$celdaCrearObjetivo)->applyFromArray($styleEtiquetaNotas);
                        $sheet->setCellValue("B".$celdaCrearObjetivo, $objetivo);
                        //TAG DESCRIPCIÓN DE LA META
                        $sheet->getStyle("A".($celdaCrearObjetivo+1))->applyFromArray($styleTagObjetivos);
                        $sheet->setCellValue("A".($celdaCrearObjetivo+1), "DESCRIPCIÓN DE LA META");
                        //TAG META
                        $sheet->mergeCells("B".($celdaCrearObjetivo+1).":D".($celdaCrearObjetivo+1));
                        $sheet->getStyle("B".($celdaCrearObjetivo+1).":"."D".($celdaCrearObjetivo+1))->applyFromArray($styleEtiquetaNotas);
                        $sheet->setCellValue("B".($celdaCrearObjetivo+1), $cqlObjetivos->meta);
                        //TAG FECHA INICIO
                        $sheet->getStyle("A".($celdaCrearObjetivo+2))->applyFromArray($styleTagObjetivos);
                        $sheet->setCellValue("A".($celdaCrearObjetivo+2), "FECHA INICIO");
                        //TAG FECHA INICIO - VAR
                        $sheet->getStyle("B".($celdaCrearObjetivo+2))->applyFromArray($styleEtiquetaNotas);
                        $sheet->setCellValue("B".($celdaCrearObjetivo+2), date("d/m/Y", strtotime($cqlObjetivos->fecha_inicio)));
                        //TAG FECHA FIN
                        $sheet->getStyle("C".($celdaCrearObjetivo+2))->applyFromArray($styleTagObjetivos);
                        $sheet->setCellValue("C".($celdaCrearObjetivo+2), "FECHA FIN");
                        //TAG FECHA FIN - VAR
                        $sheet->getStyle("D".($celdaCrearObjetivo+2))->applyFromArray($styleEtiquetaNotas);
                        $sheet->setCellValue("D".($celdaCrearObjetivo+2),date("d/m/Y", strtotime($cqlObjetivos->fecha_fin)));
                        //TAG TEMPORALIDAD
                        $sheet->getStyle("A".($celdaCrearObjetivo+3))->applyFromArray($styleTagObjetivos);
                        $sheet->setCellValue("A".($celdaCrearObjetivo+3), "TEMPORALIDAD");
                        //TAG TEMPORALIDAD - VAR
                        $sheet->mergeCells("B".($celdaCrearObjetivo+3).":D".($celdaCrearObjetivo+3));
                        $sheet->getStyle("B".($celdaCrearObjetivo+3).":D".($celdaCrearObjetivo+3))->applyFromArray($styleEtiquetaNotas);
                        $sheet->setCellValue("B".($celdaCrearObjetivo+3), $cqlObjetivos->temporalidad);
                        //TAG OBSERVACION
                        $sheet->getStyle("A".($celdaCrearObjetivo+4))->applyFromArray($styleEtiquetaSinFondo);
                        $sheet->setCellValue("A".($celdaCrearObjetivo+4), "OBSERVACIONES");
                        //TAG NOTA DE OBSERVACIÓN
                        $sheet->mergeCells("B".($celdaCrearObjetivo+4).":F".($celdaCrearObjetivo+4));
                        $sheet->getStyle("B".($celdaCrearObjetivo+4).":F".($celdaCrearObjetivo+4))->applyFromArray($styleEtiquetaNotas);
                        $sheet->setCellValue("B".($celdaCrearObjetivo+4), $cqlObjetivos->motivo_negado);
                        //TAG PERIODO NRO
                        $sheet->getStyle("E".$celdaCrearObjetivo)->applyFromArray($styleTagObjetivos);
                        $sheet->setCellValue("E".$celdaCrearObjetivo,"PERIODO NRO");
                        //TAG PERIODO NRO - VAR
                        $sheet->getStyle("F".$celdaCrearObjetivo)->applyFromArray($styleEtiquetaNotas);
                        $sheet->setCellValue("F".$celdaCrearObjetivo,$cqlObjetivos->periodo_numero);
                        //TAG CUMPLIMIENTO
                        $sheet->getStyle("E".($celdaCrearObjetivo+1))->applyFromArray($styleTagObjetivos);
                        $sheet->setCellValue("E".($celdaCrearObjetivo+1),"CUMPLIMIENTO ACUMULADO");
                        //TAG CUMPLIMIENTO - VAR
                        $sheet->getStyle("F".($celdaCrearObjetivo+1))->applyFromArray($styleEtiquetaNotas);
                        //TAG CUMPLIMIENTO
                        $sheet->getStyle("E".($celdaCrearObjetivo+2))->applyFromArray($styleTagObjetivos);
                        $sheet->setCellValue("E".($celdaCrearObjetivo+2),"META ACUMULADA");
                        //TAG CUMPLIMIENTO - VAR
                        $sheet->getStyle("F".($celdaCrearObjetivo+2))->applyFromArray($styleEtiquetaNotas);
                        //TAG META FINAL
                        $sheet->getStyle("E".($celdaCrearObjetivo+3))->applyFromArray($styleTagObjetivos);
                        $sheet->setCellValue("E".($celdaCrearObjetivo+3),"META FINAL");
                        //TAG META FINAL - VAR
                        $sheet->getStyle("F".($celdaCrearObjetivo+3))->applyFromArray($styleEtiquetaNotas);
                        //TAG PERIODO
                        $sheet->getStyle("G".$celdaCrearObjetivo)->applyFromArray($styleTagObjetivos);
                        $sheet->setCellValue("G".$celdaCrearObjetivo,"PERIODO");
                        //TAG PERIODO - VAR
                        $sheet->getStyle("H".$celdaCrearObjetivo)->applyFromArray($styleEtiquetaNotas);
                        //$sheet->setCellValue("H".$celdaCrearObjetivo, "??");
                        //TAG % CUMPLIMIENTO
                        $sheet->getStyle("I".$celdaCrearObjetivo)->applyFromArray($styleTagObjetivos);
                        $sheet->setCellValue("I".$celdaCrearObjetivo,"% CUMPLIMIENTO");
                        //TAG % CUMPLIMIENTO - VAR
                        $sheet->getStyle("J".$celdaCrearObjetivo)->applyFromArray($styleEtiquetaNotas);
                        //TAG OBSERVACION CORTA DEL OBJETIVO
                        $sheet->getStyle("J".($celdaCrearObjetivo+1))->getFont()->setName('Calibri')->setBold(true)->setSize(16)->getColor()->setRGB('000000');
                        $sheet->getStyle("J".($celdaCrearObjetivo+1))->getAlignment()->setVertical('center')->setHorizontal('center');
                        //DIFERENCIA DEL PORCENTAJE DE CUMPLIMIENTO
                        $sheet->getStyle("K".($celdaCrearObjetivo))->getFont()->setName('Calibri')->setBold(false)->setSize(16)->getColor()->setRGB('000000');
                        $sheet->getStyle("K".($celdaCrearObjetivo))->getAlignment()->setVertical('center')->setHorizontal('center');
                        //BORDE DE TODO EL OBJETIVO
                        $sheet->getStyle("A".$celdaCrearObjetivo.":J".($celdaCrearObjetivo+4))->applyFromArray($styleMarcoObjetivo);


                    //FIN BLOQUES OBJETIVOS
                    //BLOQUE PERIODO
                        //LLENADO DE PERIODO
                        $meta_final_total = 0;
                        foreach ($cqlPeriodo_ as $key => $periodo){
                            //TAG NUMERO DE PERIODO
                            $sheet->getStyleByColumnAndRow($columnaInicial,$celdaCrearPeriodo)->applyFromArray($styleTagPeriodos_blue);
                            $sheet->setCellValueByColumnAndRow($columnaInicial,$celdaCrearPeriodo,$periodo['numero']);
                            //TAG FECHA DE INICIO DE PERIODO
                            $sheet->getStyleByColumnAndRow($columnaInicial,($celdaCrearPeriodo+1))->applyFromArray($styleTagPeriodos_blue);
                            $sheet->setCellValueByColumnAndRow($columnaInicial,($celdaCrearPeriodo+1),$periodo['fecha_inicio_periodo']);
                            //TAG DESCRIPCIÓN DE LA META DEL PERIODO
                            $sheet->getStyleByColumnAndRow($columnaInicial,($celdaCrearPeriodo+2))->applyFromArray($styleTagPeriodos_noFondo);
                            $sheet->setCellValueByColumnAndRow($columnaInicial,($celdaCrearPeriodo+2),$periodo['descripcion_meta']);
                            //TAG CARACTERIZACIÓN
                            $sheet->getStyleByColumnAndRow($columnaInicial,($celdaCrearPeriodo+3))->applyFromArray($styleTagPeriodos_noFondo);
                            $sheet->setCellValueByColumnAndRow($columnaInicial,($celdaCrearPeriodo+3),$periodo['caracterizacion']);
                            //TAG META DEL PERIODO
                            $sheet->getStyleByColumnAndRow($columnaInicial,($celdaCrearPeriodo+4))->applyFromArray($styleTagPeriodos_noFondo);
                            $sheet->setCellValueByColumnAndRow($columnaInicial,($celdaCrearPeriodo+4),$periodo['meta_periodo']);
                            //TAG CUMPLIMIENTO DEL PERIODO
                            $sheet->getStyleByColumnAndRow($columnaInicial,($celdaCrearPeriodo+5))->applyFromArray($styleTagPeriodos_noFondo);
                            $sheet->setCellValueByColumnAndRow($columnaInicial,($celdaCrearPeriodo+5),$periodo['cumplimiento_periodo']);
                            //TAG PORCENTAJE DEL CUMPLIMIENTO DEL PERIODO
                            $sheet->getStyleByColumnAndRow($columnaInicial,($celdaCrearPeriodo+6))->applyFromArray($styleTagPeriodos_Porcentaje);
                            $sheet->setCellValueByColumnAndRow($columnaInicial,($celdaCrearPeriodo+6),$periodo['cumplimiento_periodo_porcentaje']);
                            //TAG PENDIENTE DE PERIODO FILA 21
                            $sheet->getStyleByColumnAndRow($columnaInicial,($celdaCrearPeriodo+7))->applyFromArray($styleTagPeriodos_noFondo);
                            $sheet->setCellValueByColumnAndRow($columnaInicial,($celdaCrearPeriodo+7),$periodo['pendiente_periodo']);
                            //TAG META ACUMULADA
                            $sheet->getStyleByColumnAndRow($columnaInicial,($celdaCrearPeriodo+8))->applyFromArray($styleTagPeriodos_noFondo);
                            //TAG CUMPLIMIENTO ACUMULADO
                            $sheet->getStyleByColumnAndRow($columnaInicial,($celdaCrearPeriodo+9))->applyFromArray($styleTagPeriodos_noFondo);
                            if ($cqlObjetivos->tipo_objetivo=="CUANTITATIVO"){
                                //TAG META ACUMULADA
                                $sheet->setCellValueByColumnAndRow($columnaInicial,($celdaCrearPeriodo+8),$periodo['meta_acumulada']);
                                //TAG CUMPLIMIENTO ACUMULADO
                                $sheet->setCellValueByColumnAndRow($columnaInicial,($celdaCrearPeriodo+9),$periodo['cumplimiento_acumulado']);
                                //TAG CUMPLIMIENTO ACUMULADO PORCENTAJE
                                if ($periodo['cumplimiento_acumulado']==0 || $periodo['meta_acumulada'] == 0){
                                    $porcentajeCumplimientoAcumulado = 0;
                                }else{
                                    $porcentajeCumplimientoAcumulado = round(+($periodo['cumplimiento_acumulado']/$periodo['meta_acumulada']),2);
                                }
                                $sheet->getStyleByColumnAndRow($columnaInicial,($celdaCrearPeriodo+10))->applyFromArray($styleTagPeriodos_Porcentaje);
                                $sheet->setCellValueByColumnAndRow($columnaInicial,($celdaCrearPeriodo+10),$porcentajeCumplimientoAcumulado);
                            }else{
                                //TAG META ACUMULADA
                                $sheet->setCellValueByColumnAndRow($columnaInicial,($celdaCrearPeriodo+8),"NO APLICA");
                                //TAG CUMPLIMIENTO ACUMULADO
                                $sheet->setCellValueByColumnAndRow($columnaInicial,($celdaCrearPeriodo+9),"NO APLICA");
                                $sheet->getStyleByColumnAndRow($columnaInicial,($celdaCrearPeriodo+10))->applyFromArray($styleTagPeriodos_Porcentaje);
                            }
                            //TAG PENDIENTE ACUMULADO
                            $sheet->getStyleByColumnAndRow($columnaInicial,($celdaCrearPeriodo+11))->applyFromArray($styleTagPeriodos_noFondo);
                            $sheet->setCellValueByColumnAndRow($columnaInicial,($celdaCrearPeriodo+11), $periodo['pendiente_acumulado']);
                            //TAG OBSERVACIONES
                            $sheet->getStyleByColumnAndRow($columnaInicial,($celdaCrearPeriodo+12))->applyFromArray($styleTagPeriodos_noFondo);
                            $sheet->setCellValueByColumnAndRow($columnaInicial,($celdaCrearPeriodo+12), $periodo['observaciones'] );
                            $columnaInicial++;
                            //META FINAL SUMA
                            $meta_final_total = $meta_final_total + $periodo['meta_periodo'];
                        }
                        $columnaInicial = 2;
                        //META FINAL
                        if ($cqlObjetivos->tipo_objetivo=="CUANTITATIVO")
                            $sheet->setCellValue("F".($celdaCrearObjetivo+3), $meta_final_total);
                        //TAG PERIODO
                        $sheet->getStyle("A".$celdaCrearPeriodo)->applyFromArray($styleTagPeriodos_blue);
                        $sheet->setCellValue("A".$celdaCrearPeriodo,"PERIODO");
                        //TAG VACIO
                        $sheet->getStyle("A".($celdaCrearPeriodo+1))->applyFromArray($styleTagPeriodos_blue);
                        //TAG DESCRIPCIÓN DE LA META DEL PERIODO
                        $sheet->getStyle("A".($celdaCrearPeriodo+2))->applyFromArray($styleTagPeriodos_green);
                        $sheet->setCellValue("A".($celdaCrearPeriodo+2),"DESCRIPCIÓN DE LA META DEL PERIODO");
                        //TAG CARACTERIZACIÓN
                        $sheet->getStyle("A".($celdaCrearPeriodo+3))->applyFromArray($styleTagPeriodos_green);
                        $sheet->setCellValue("A".($celdaCrearPeriodo+3),"CARACTERIZACIÓN");
                        //TAG META DEL PERIODO
                        $sheet->getStyle("A".($celdaCrearPeriodo+4))->applyFromArray($styleTagPeriodos_green);
                        $sheet->setCellValue("A".($celdaCrearPeriodo+4),"META DEL PERIODO");
                        //TAG CUMPLIMIENTO DEL PERIODO
                        $sheet->mergeCells("A".($celdaCrearPeriodo+5).":A".($celdaCrearPeriodo+6));
                        $sheet->getStyle("A".($celdaCrearPeriodo+5).":A".($celdaCrearPeriodo+6))->applyFromArray($styleTagPeriodos_green);
                        $sheet->setCellValue("A".($celdaCrearPeriodo+5),"CUMPLIMIENTO DEL PERIODO");
                        //TAG PENDIENTE DEL PERIODO
                        $sheet->getStyle("A".($celdaCrearPeriodo+7))->applyFromArray($styleTagPeriodos_green);
                        $sheet->setCellValue("A".($celdaCrearPeriodo+7),"PENDIENTE DEL PERIODO");
                        //TAG META ACUMULADA
                        $sheet->getStyle("A".($celdaCrearPeriodo+8))->applyFromArray($styleTagPeriodos_yellow);
                        $sheet->setCellValue("A".($celdaCrearPeriodo+8),"META ACUMULADA");
                        //TAG CUMPLIMIENTO ACUMULADO
                        $sheet->mergeCells("A".($celdaCrearPeriodo+9).":A".($celdaCrearPeriodo+10));
                        $sheet->getStyle("A".($celdaCrearPeriodo+9).":A".($celdaCrearPeriodo+10))->applyFromArray($styleTagPeriodos_yellow);
                        $sheet->setCellValue("A".($celdaCrearPeriodo+9),"CUMPLIMIENTO ACUMULADO");
                        //TAG PENDIENTE ACUMULADO
                        $sheet->getStyle("A".($celdaCrearPeriodo+11))->applyFromArray($styleTagPeriodos_yellow);
                        $sheet->setCellValue("A".($celdaCrearPeriodo+11),"PENDIENTE ACUMULADO");
                        //TAG OBSERVACIONES
                        $sheet->getStyle("A".($celdaCrearPeriodo+12))->applyFromArray($styleTagPeriodos_yellow);
                        $sheet->setCellValue("A".($celdaCrearPeriodo+12),"OBSERVACIONES");
                    //FIN BLOQUE PERIODO
                    $contadorObjetivo++;
                    //OCULTAR LAS CELDAS QUE FORMAN PERIODO
                    if ($request->tipo_reporte ==true)
                    for ($i = $celdaCrearPeriodo; $i <= $celdaCrearPeriodo+12; $i++){
                        $sheet->getRowDimension($i)->setRowHeight(0);
                    }
                    $celdaCrearObjetivo = $celdaCrearObjetivo+19; //para bloque objetivos
                    $celdaCrearPeriodo = $celdaCrearPeriodo+19; //para bloque periodos
                }

                //FIN CONSTRUYENDO BLOQUE DE OBJETIVOS
            } catch (\Exception $e) {
                $array_response['status']=300;
            }
            $writer = IOFactory::createWriter($spread, 'Xlsx');
            $writer->setIncludeCharts(true);
            $documento_generado = "EJECUTIVO_COMPROMISOS_".date('Y-m-d').".xlsx";
            //$writer->save("reporte_compromiso.xlsx");
            $writer->save("storage/COMPROMISOS_GENERADOS/".$documento_generado);
            $array_response['status']=200;
            $array_response['documento_nombre']=$documento_generado;
            
        return response()->json($array_response, 200);
    }
    public function exportarExcelMinisterio(request $request)
    {
        $array_response['status'] = "200";
        //$url='storage/compromisos_ministerio.xlsx';
        $url='storage/FORMATOS/MINISTERIO_COMPROMISOS.xlsx';
        $reader = IOFactory::createReader("Xlsx");
        $spread = $reader->load($url);
        $spread->setActiveSheetIndex(0);
        $sheet_1 = $spread->getActiveSheet();
        $writer = IOFactory::createWriter($spread, 'Xlsx');

        $cql=Compromiso::select(
            'compromisos.id as id',
            'compromisos.codigo as codigo_compromiso',
            'compromisos.nombre_compromiso as nombre_compromiso',
            'estado_gestion.descripcion as estado_gestion',
            'estado_compromiso.descripcion as estado_compromiso',
            'compromisos.avance as porcentaje_avance',
            'compromisos.avance_compromiso as avance_compromiso'
        )
        ->leftjoin('sc_compromisos.responsables as responsable_','responsable_.compromiso_id','compromisos.id')
        ->join('sc_compromisos.instituciones as institucion_','institucion_.id','responsable_.institucion_id')
        ->leftjoin('sc_compromisos.estados as estado_compromiso','estado_compromiso.id','compromisos.estado_id')
        ->leftjoin('sc_compromisos.estados_porcentaje as estado_gestion','estado_gestion.id','compromisos.estado_porcentaje_id')
        ->where('compromisos.estado','ACT')
        ->where('responsable_.estado','ACT')
        ->orderBy('id','asc');
        if($request->institucion != '--')
            $cql = $cql->where('institucion_.id',$request->institucion);
        $cql_principal = $cql->get()->toArray();

        $sheet_1->setCellValue("A3", $request->nombre_institucion);

        // build spreadsheet from array
        /*$sheet_1->fromArray($cql_principal,
            NULL, // array values with this value will not be set
            'A5');*/
            $styleCompromisos = [
                'font' => [
                    'color' => array('rgb' => '000000'), 'size'  => 12, 'name'  => 'Calibri'
                ],
                'alignment' => [
                    'horizontal' => 'justify', 'vertical' => 'center','wrapText' => true
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ];
            foreach ($cql_principal as $key => $compromiso){
                $fila_=5+$key;
                $sheet_1->setCellValue('A'.$fila_,$compromiso['id']);
                $sheet_1->setCellValue('B'.$fila_,$compromiso['codigo_compromiso']);
                $sheet_1->setCellValue('C'.$fila_,$compromiso['nombre_compromiso']);
                $sheet_1->setCellValue('D'.$fila_,$compromiso['estado_gestion']);
                $sheet_1->setCellValue('E'.$fila_,$compromiso['estado_compromiso']);
                $sheet_1->setCellValue('F'.$fila_,$compromiso['porcentaje_avance']);
                $sheet_1->setCellValue('G'.$fila_,$compromiso['avance_compromiso']);
                $sheet_1->getStyle('C'.$fila_.':G'.$fila_)->applyFromArray($styleCompromisos);
            }
        $contador = $cql->get()->count();
        //SEGUNDA HOJA: TABLA RESUMEN Y GRAFICO
        $spread->setActiveSheetIndex(1);
        $sheet_2 = $spread->getActiveSheet();

        $cqlResumen=DB::connection('pgsql_presidencia')
                    ->select('select *
                    from sc_compromisos.fn_compromisos_ministerio(?)',
                    [
                        $request->institucion
                    ]);

        $data = collect($cqlResumen)->map(function($x){ return (array) $x; })->toArray();

                            /*if(count($cqlResumen)>0){
                        foreach ($cqlResumen as $actividad) {
                            $obj_=$actividad["estado_gestion"];
                            //$obj_=json_decode($obj_);
                            dd($obj_);
                    }
                }*/

        $sheet_2->fromArray($data,
            NULL,
            'A3');
        //GENERACION DEL GRAFICO
        $colors = [
                '4F6228', '0070C0', 'FFD966', 'F4B183', 'FF0000'
        ];
        $dataseriesLabels1 = array(
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Grafico!$B$2', NULL, 1, [], NULL, "4F6228"), //optimo
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Grafico!$C$2', NULL, 1, [], NULL, "0070C0"), //bueno
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Grafico!$D$2', NULL, 1, [], NULL, 'FFD966'), //atraso leve
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Grafico!$E$2', NULL, 1, [], NULL, "F4B183"), //atraso moderado
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Grafico!$F$2', NULL, 1, [], NULL, "FF0000"), //atraso grave
        );

        $dataSeriesValues1 = array(
            new DataSeriesValues('Number', 'Grafico!$B$3:$B$7', NULL, 5),
            new DataSeriesValues('Number', 'Grafico!$C$3:$C$7', NULL, 5),
            new DataSeriesValues('Number', 'Grafico!$D$3:$D$7', NULL, 5),
            new DataSeriesValues('Number', 'Grafico!$E$3:$E$7', NULL, 5),
            new DataSeriesValues('Number', 'Grafico!$F$3:$F$7', NULL, 5),
        );

        $xAxisTickValues = array(new DataSeriesValues('String', 'Grafico!$A$3:$A$7', NULL, 5), //  Cumpli, Cerrado, etc
        );
        //  Construye la serie de datos
        $series1 = new DataSeries(
                DataSeries::TYPE_BARCHART, // plotType
                DataSeries::GROUPING_STANDARD, // plotGrouping STANDARD CLUSTERED
                range(0, count($dataSeriesValues1) - 1), // plotOrder
                $dataseriesLabels1, // plotLabel
                $xAxisTickValues, // plotCategory
                $dataSeriesValues1                              // plotValues
        );

        $series1->setPlotDirection(DataSeries::DIRECTION_COL);
        $plotarea = new PlotArea(NULL, array($series1));
        $legend = new Legend(Legend::POSITION_RIGHT, NULL, false);

        //  Crea el gráfico
        $chart = new Chart(
            'Grafico', // name
            NULL, //title
            $legend, // legend
            $plotarea
        );
        //  Establezca la posición donde debe aparecer el gráfico en la hoja de trabajo
        $chart->setTopLeftPosition('A10');
        $chart->setBottomRightPosition('I26');
        //  Agregue el gráfico a la hoja de trabajo
        $sheet_2->addChart($chart);
        $chart->render($spread);
        $writer->setIncludeCharts(TRUE);

        $documento_generado = "MINISTERIO_COMPROMISOS_".date('Y-m-d').".xlsx";
        $writer->save("storage/COMPROMISOS_GENERADOS/".$documento_generado);
        //$writer->save("reporte_compromisos_ministerio.xlsx");

        $array_response['status']=200;
        $array_response['documento_nombre']=$documento_generado;

        return response()->json($array_response, 200);
    }
    //REPORTES POR GABINETE
    public function exportarExcelGabinete(request $request)
    {
        //ESTILO PARA CELDAS DE Institucion
        $styleInstitucion = [
            'font' => [
                'bold' => true, 'color' => array('rgb' => '000000'), 'size'  => 12, 'name'  => 'Calibri'
            ],
            'alignment' => [
                'horizontal' => 'left', 'vertical' => 'center',
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];
        $styleCompromisos = [
            'font' => [
                'bold' => false, 'color' => array('rgb' => '000000'), 'size'  => 12, 'name'  => 'Calibri'
            ],
            'alignment' => [
                'horizontal' => 'left', 'vertical' => 'center','wrapText' => true
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];
        $styleTotales = [
            'font' => [
                'bold' => true, 'color' => array('rgb' => '000000'), 'size'  => 12, 'name'  => 'Calibri'
            ],
            'alignment' => [
                'horizontal' => 'center', 'vertical' => 'center'
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'DCE6F1',
                ],
            ],
        ];
        $styleResumen = [
            'font' => [
                'color' => array('rgb' => '000000'), 'size'  => 12, 'name'  => 'Calibri'
            ],
            'alignment' => [
                'horizontal' => 'center', 'vertical' => 'center'
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];

        $array_response['status'] = "200";
        //$url='storage/compromisos_gabinete.xlsx';
        $url='storage/FORMATOS/GABINETE_COMPROMISOS.xlsx';
        $reader = IOFactory::createReader("Xlsx");
        $spread = $reader->load($url);
        $spread->setActiveSheetIndex(0);
        $sheet_1 = $spread->getActiveSheet();
        $sheet_1->getDefaultRowDimension()->setRowHeight(30);
        $writer = IOFactory::createWriter($spread, 'Xlsx');

        $cqlInstituciones=Institucion::select(
            'descripcion as institucion',
            'id as id'
        )
        ->where('institucion_id',$request->gabinete)
        ->orderBy('descripcion','asc')
        ->get();

        $sheet_1->setCellValue("A3", $request->nombre_gabinete);

        $fila = 5;
        if(count($cqlInstituciones)>0){
            foreach ($cqlInstituciones as $key => $institucion){
                $cqlCompromisos=Compromiso::select(
                    'compromisos.id as id',
                    'compromisos.codigo as codigo_compromiso',
                    'compromisos.nombre_compromiso as nombre_compromiso',
                    'estado_gestion.descripcion as estado_gestion',
                    'estado_compromiso.descripcion as estado_compromiso',
                    'compromisos.avance as porcentaje_avance',
                    'compromisos.avance_compromiso as avance_compromiso'
                )
                ->leftjoin('sc_compromisos.responsables as responsable_','responsable_.compromiso_id','compromisos.id')
                ->join('sc_compromisos.instituciones as institucion_','institucion_.id','responsable_.institucion_id')
                ->leftjoin('sc_compromisos.estados as estado_compromiso','estado_compromiso.id','compromisos.estado_id')
                ->leftjoin('sc_compromisos.estados_porcentaje as estado_gestion','estado_gestion.id','compromisos.estado_porcentaje_id')
                ->where('compromisos.estado','ACT')
                ->where('responsable_.estado','ACT')
                ->where('institucion_.id',$institucion['id'])
                ->orderBy('institucion_.descripcion','asc');
                $contarCompromisos = $cqlCompromisos->get()->count();
                $cqlCompromisos=$cqlCompromisos->get()->toArray();

                if(count($cqlCompromisos)>0){
                    $sheet_1->getStyle("A".$fila.":G".$fila)->applyFromArray($styleInstitucion);
                    $sheet_1->mergecells("A".$fila.":G".$fila);
                    $sheet_1->setCellValue("A".$fila,$institucion['institucion']);

                    $fila++;
                    // construir la seccion de los compromisos por institucion
                    /*$sheet_1->fromArray($cqlCompromisos,
                    NULL, // array values with this value will not be set
                    'A'.$fila
                    );*/
                    foreach ($cqlCompromisos as $key => $compromiso){
                        $fila_=$fila+$key;
                        $sheet_1->setCellValue('A'.$fila_,$compromiso['id']);
                        $sheet_1->setCellValue('B'.$fila_,$compromiso['codigo_compromiso']);
                        $sheet_1->setCellValue('C'.$fila_,$compromiso['nombre_compromiso']);
                        $sheet_1->setCellValue('D'.$fila_,$compromiso['estado_gestion']);
                        $sheet_1->setCellValue('E'.$fila_,$compromiso['estado_compromiso']);
                        $sheet_1->setCellValue('F'.$fila_,$compromiso['porcentaje_avance']);
                        $sheet_1->setCellValue('G'.$fila_,$compromiso['avance_compromiso']);
                        $sheet_1->getStyle('A'.$fila_.':G'.$fila_)->applyFromArray($styleCompromisos);
                    }
                    $fila=$fila+$contarCompromisos;
                }
            }
        }

        //$contador = $cql->get()->count();
        //SEGUNDA HOJA: TABLA RESUMEN Y GRAFICO
        $spread->setActiveSheetIndex(1);
        $sheet_2 = $spread->getActiveSheet();
        $cqlResumen=DB::connection('pgsql_presidencia')
        ->select('select *
        from sc_compromisos.fn_compromisos_gabinete(?)',
        [
            $request->gabinete
        ]);

        $data = collect($cqlResumen)->map(function($x){ return (array) $x; })->toArray();
        $fila_r = 3;
        foreach ($data as $key => $resumen){
            $fila_=$fila_r+$key;
            $sheet_2->setCellValue('A'.$fila_,$resumen['siglas']);
            $sheet_2->setCellValue('B'.$fila_,$resumen['CUMPLIDO']);
            $sheet_2->setCellValue('C'.$fila_,$resumen['EN EJECUCIÓN']);
            $sheet_2->setCellValue('D'.$fila_,$resumen['EN PLANIFICACIÓN']);
            $sheet_2->setCellValue('E'.$fila_,$resumen['CERRADO']);
            $sheet_2->setCellValue('F'.$fila_,$resumen['STAND BY']);
        }
        //SUMA DE TOTALES horizontal
        $totales=$fila_+1;
        $sheet_2->getStyle('A'.$totales.':G'.$totales)->applyFromArray($styleTotales);
        $sheet_2->setCellValue("A".$totales,'TOTAL');
        $sheet_2->setCellValue("B".$totales,'=SUM(B3:B'.$fila_.')');
        $sheet_2->setCellValue("C".$totales,'=SUM(C3:C'.$fila_.')');
        $sheet_2->setCellValue("D".$totales,'=SUM(D3:D'.$fila_.')');
        $sheet_2->setCellValue("E".$totales,'=SUM(E3:E'.$fila_.')');
        $sheet_2->setCellValue("F".$totales,'=SUM(F3:F'.$fila_.')');
        $sheet_2->setCellValue("G".$totales,'=SUM(G3:G'.$fila_.')');
        //SUMA DE TOTALES vertical
        $i=1;
        while($i<$fila_){
            $sheet_2->getStyle('A'.$fila_r.':G'.$fila_r)->applyFromArray($styleResumen);
            $sheet_2->setCellValue("G".$fila_r,'=SUM(B'.$fila_r.':F'.$fila_r.')');
            $fila_r++;
            $i++;
        }

        /*$sheet_2->fromArray($data,
        NULL,
        'A3');*/
        //GENERACION DEL GRAFICO
        $dataseriesLabels1 = array(
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Grafico!$B$2', NULL, 1, [], NULL, "4472C4"), //cumplido
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Grafico!$C$2', NULL, 1, [], NULL, "548235"), //en ejecucion
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Grafico!$D$2', NULL, 1, [], NULL, 'A6A6A6'), //en planificacion
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Grafico!$E$2', NULL, 1, [], NULL, "FFD966"), //cerrado
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Grafico!$F$2', NULL, 1, [], NULL, "EB84EC"), //stand by
        );

        $dataSeriesValues1 = array(
            new DataSeriesValues('Number', 'Grafico!$B$3:$B$'.$fila_, NULL, 5),
            new DataSeriesValues('Number', 'Grafico!$C$3:$C$'.$fila_, NULL, 5),
            new DataSeriesValues('Number', 'Grafico!$D$3:$D$'.$fila_, NULL, 5),
            new DataSeriesValues('Number', 'Grafico!$E$3:$E$'.$fila_, NULL, 5),
            new DataSeriesValues('Number', 'Grafico!$F$3:$F$'.$fila_, NULL, 5),
        );

        $xAxisTickValues = array(new DataSeriesValues('String', 'Grafico!$A$3:$A'.$fila_, NULL, 5), //  Cumpli, Cerrado, etc
        );
        //  Construye la serie de datos
        $series1 = new DataSeries(
            DataSeries::TYPE_BARCHART, // plotType
            DataSeries::GROUPING_STANDARD, // plotGrouping STANDARD CLUSTERED
            range(0, count($dataSeriesValues1) - 1), // plotOrder
            $dataseriesLabels1, // plotLabel
            $xAxisTickValues, // plotCategory
            $dataSeriesValues1                              // plotValues
        );

        $series1->setPlotDirection(DataSeries::DIRECTION_COL);
        $plotarea = new PlotArea(NULL, array($series1));
        $legend = new Legend(Legend::POSITION_RIGHT, NULL, false);

        //  Crea el gráfico
        $chart = new Chart(
            'Grafico', // name
            NULL, //title
            $legend, // legend
            $plotarea
        );
        //  Establezca la posición donde debe aparecer el gráfico en la hoja de trabajo
        $inicia_grafico = $fila_+3;
        $termina_grafico = $inicia_grafico+15;
        $chart->setTopLeftPosition('A'.$inicia_grafico);
        $chart->setBottomRightPosition('J'.$termina_grafico);
        //  Agregue el gráfico a la hoja de trabajo
        $sheet_2->addChart($chart);
        $chart->render($spread);
        $writer->setIncludeCharts(TRUE);

        $documento_generado = "GABINETE_COMPROMISOS_".date('Y-m-d').".xlsx";
        //$writer->save("reporte_compromisos_gabinete.xlsx");
        $writer->save("storage/COMPROMISOS_GENERADOS/".$documento_generado);
        //$writer->save("storage/COMPROMISOS_GENERADOS/GABINETE_COMPROMISOS.xlsx");  
        
        $array_response['status']=200;
        $array_response['documento_nombre']=$documento_generado;

        return response()->json($array_response, 200);
    }

}