<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    require_once('../request-headers.php');
    require_once('../../middlewares/VerificarToken.php');
    require_once('../../controllers/PaccController.php');
    require('../../vendor/autoload.php');
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use PhpOffice\PhpSpreadsheet\IOFactory;

    require_once('../../models/Pacc.php');
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            $verificarTokenAcceso = new verificarTokenAcceso();
            $tokenEsValido = $verificarTokenAcceso->verificarTokenAcceso();
            if ($tokenEsValido) {
                $_POST = json_decode(file_get_contents('php://input'), true);
                if (
                    isset($_POST['fechaPresupuestoActividad']) && 
                    !empty($_POST['fechaPresupuestoActividad']) &&
                    isset($_POST['idDepartamento']) &&
                    !empty($_POST['idDepartamento']) && 
                    isset($_POST['tipoOrdenamiento']) && 
                    !empty($_POST['tipoOrdenamiento'])
                    ) {
                        if ($_POST['tipoOrdenamiento'] === 1) {
                            $orderBy = "codigoObjetoGasto";
                        } else if ($_POST['tipoOrdenamiento'] === 2) {
                            $orderBy = "CorrelativoActividad";
                        } else {
                            $orderBy = "nombreDepartamento";
                        }
                        $pacc = new Pacc();
                        
                        $pacc->setFechaPresupuestoAnual($_POST['fechaPresupuestoActividad']);
                        $pacc->setIdDepartamento($_POST['idDepartamento']);
                        if (is_int($pacc->getFechaPresupuestoAnual()) && is_int($pacc->getIdDepartamento())) {
                            $anioPacc = $pacc->generaAnioPaccSeleccionado();
                            $departamento = $pacc->getDataDepartamento();    
                            $paccDepartamento = $pacc->generaPaccPorDepartamento($orderBy);
                            $costoObjetosGasto = $pacc->generaCostoObjetosGastoPaccDepartamento();
                            $costosPorDepartamentoCorrelativo = $pacc->generaCostoPorCorrelativoDepartamento();
                            $descripcionesObjetoGasto = $pacc->generaDescripcionObjetosGastoPaccDepartamento();
                            $generaCostoTotal = $pacc->generaCostoTotalDescripcionesDepartamento();
                            
                             // Generando excel
                            // Hoja Uno

                            $styleArray = array(
                                'borders' => array(
                                    'outline' => array(
                                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                        'color' => array('rgb' => '0000000'),
                                    ),
                                ),
                            );
                            $nombreDelDocumento = "Reporte-General-Pacc.xlsx";
                            $spreadsheet = new Spreadsheet();
                            $sheet = $spreadsheet->getActiveSheet();
                            $sheet->setTitle('PACC ' . $departamento->nombreDepartamento);
                            $sheet->setCellValue('A2', $departamento->nombreDepartamento);
                            $sheet->setCellValue('A3', 'PLAN ANUAL DE COMPRAS Y CONTRATACIONES (PACC)');
                            $sheet->setCellValue('A4', $anioPacc->anioPacc);
                            $sheet->setCellValue('A10', 'N°');
                            $sheet->setCellValue('B10', 'Objeto del Gasto');
                            $sheet->setCellValue('C10', 'Descripción');
                            $sheet->setCellValue('D10', 'Correlativo POA');
                            $sheet->setCellValue('E10', 'Unidad de Medida');
                            $sheet->setCellValue('F10', 'Cantidad');
                            $sheet->setCellValue('G10', 'Valor Unitario Aproximado');
                            $sheet->setCellValue('H10', 'Valor Total');
                            $sheet->setCellValue('I10', 'Nombre Departamento');
                            $sheet->getStyle('A10')->applyFromArray($styleArray);
                            $sheet->getStyle('B10')->applyFromArray($styleArray);
                            $sheet->getStyle('C10')->applyFromArray($styleArray);
                            $sheet->getStyle('D10')->applyFromArray($styleArray);
                            $sheet->getStyle('E10')->applyFromArray($styleArray);
                            $sheet->getStyle('F10')->applyFromArray($styleArray);
                            $sheet->getStyle('G10')->applyFromArray($styleArray);
                            $sheet->getStyle('H10')->applyFromArray($styleArray);
                            $sheet->getStyle('I10')->applyFromArray($styleArray);

                            $rowCount = 11;
                            $i = 1;
                            foreach ($paccDepartamento as $item) {
                                $sheet->setCellValue('A' . $rowCount, $i);
                                $sheet->setCellValue('B' . $rowCount, $item['codigoObjetoGasto']);
                                $sheet->setCellValue('C' . $rowCount, $item['nombreActividad']);
                                $sheet->setCellValue('D' . $rowCount, $item['CorrelativoActividad']);
                                $sheet->setCellValue('E' . $rowCount, $item['unidadDeMedida']);
                                $sheet->setCellValue('F' . $rowCount, $item['cantidad']);
                                $sheet->setCellValue('G' . $rowCount, $item['costo']);
                                $sheet->setCellValue('H' . $rowCount, $item['costoTotal']);
                                $sheet->setCellValue('I' . $rowCount, $item['nombreDepartamento']);
                                $sheet->getStyle('A'. $rowCount)->applyFromArray($styleArray);
                                $sheet->getStyle('B'. $rowCount)->applyFromArray($styleArray);
                                $sheet->getStyle('C'. $rowCount)->applyFromArray($styleArray);
                                $sheet->getStyle('D'. $rowCount)->applyFromArray($styleArray);
                                $sheet->getStyle('E'. $rowCount)->applyFromArray($styleArray);
                                $sheet->getStyle('F'. $rowCount)->applyFromArray($styleArray);
                                $sheet->getStyle('G'. $rowCount)->applyFromArray($styleArray);
                                $sheet->getStyle('H'. $rowCount)->applyFromArray($styleArray);
                                $sheet->getStyle('I'. $rowCount)->applyFromArray($styleArray);
                                $rowCount++;
                                $i++;
                            }
                            $sheet->setCellValue('H' . $rowCount, $generaCostoTotal->total);
                            $sheet->getStyle('H'. $rowCount)->applyFromArray($styleArray);
                            $rowCount++;
                            $sheet->setCellValue('A' . $rowCount, 'Codigo Objeto Gasto');
                            $sheet->setCellValue('B' . $rowCount, 'Descripcion');
                            $sheet->setCellValue('C' . $rowCount, 'Costo');
                            $sheet->getStyle('A'. $rowCount)->applyFromArray($styleArray);
                            $sheet->getStyle('B'. $rowCount)->applyFromArray($styleArray);
                            $sheet->getStyle('C'. $rowCount)->applyFromArray($styleArray);
                            $rowCount++;

                            foreach ($descripcionesObjetoGasto as $itemDescripcion) {
                                $sheet->setCellValue('A' . $rowCount, $itemDescripcion['codigoObjetoGasto']);
                                $sheet->setCellValue('B' . $rowCount, $itemDescripcion['descripcionCuenta']);
                                $sheet->setCellValue('C' . $rowCount, $itemDescripcion['sumCostoActPorCodObjGasto']);
                                $sheet->getStyle('A'. $rowCount)->applyFromArray($styleArray);
                                $sheet->getStyle('B'. $rowCount)->applyFromArray($styleArray);
                                $sheet->getStyle('C'. $rowCount)->applyFromArray($styleArray);
                                $rowCount++;
                            }
                            $sheet->setCellValue('B' . $rowCount,'GRAN TOTAL');
                            $sheet->getStyle('B'. $rowCount)->applyFromArray($styleArray);
                            $sheet->setCellValue('C' . $rowCount, $generaCostoTotal->total);
                            $sheet->getStyle('C'. $rowCount)->applyFromArray($styleArray);

                            $sheet2 = $spreadsheet->createSheet();
                            $sheet2->setTitle('TOTAL POR OBJETO GASTO');
                            $sheet2->setCellValue('A1', 'Codigo Objeto de Gasto');
                            $sheet2->setCellValue('B1', 'Total');
                            $sheet2->getStyle('A1')->applyFromArray($styleArray);
                            $sheet2->getStyle('B1')->applyFromArray($styleArray);
                            $rowCountSheet2 = 2;
                            foreach ($costoObjetosGasto as $itemObjeto) {
                                $sheet2->setCellValue('A' . $rowCountSheet2, $itemObjeto['codigoObjetoGasto']);
                                $sheet2->setCellValue('B' . $rowCountSheet2, $itemObjeto['sumCostoActPorCodObjGasto']);
                                $sheet2->getStyle('A'. $rowCountSheet2)->applyFromArray($styleArray);
                                $sheet2->getStyle('B'. $rowCountSheet2)->applyFromArray($styleArray);
                                $rowCountSheet2++;
                            }

                            $sheet3 = $spreadsheet->createSheet();
                            $sheet3->setTitle('TOTAL POR CORRELATIVO ACTIVIDAD');
                            $sheet3->setCellValue('A1', 'Correlativo Actividad');
                            $sheet3->setCellValue('B1', 'Total');
                            $sheet3->getStyle('A1')->applyFromArray($styleArray);
                            $sheet3->getStyle('B1')->applyFromArray($styleArray);
                            $rowCountSheet3 = 2;
                            foreach ($costosPorDepartamentoCorrelativo as $itemPorCorrelativo) {
                                $sheet3->setCellValue('A' . $rowCountSheet3, $itemPorCorrelativo['correlativoActividad']);
                                $sheet3->setCellValue('B' . $rowCountSheet3, $itemPorCorrelativo['costoTotal']);
                                $sheet3->getStyle('A'. $rowCountSheet3)->applyFromArray($styleArray);
                                $sheet3->getStyle('B'. $rowCountSheet3)->applyFromArray($styleArray);
                                $rowCountSheet3++;
                            }
                            try {
                                $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
                                ob_start();
                                $writer->save('php://output');
                                
                                $xlsData = ob_get_contents();
                                ob_end_clean();

                                echo json_encode(array(
                                    'status'=> http_response_code(200),
                                    'data' => array('message' => 'Archivo Pacc del Departamento ' . $departamento->nombreDepartamento . ' Generado Con Exito'),
                                    'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData),
                                    'departamento' => $departamento->nombreDepartamento
                                ));

                                exit();
                            } catch(Exception $e){
                                echo json_encode(
                                    array(
                                        'status'=> http_response_code(400),
                                        'data' => array('message' => 'El Archivo Pacc del departamento no se pudo generar, intente nuevamente y si el problema persite comuniquese con el administrador')
                                    )
                                );
                            }
                        } else {
                            echo json_encode(array(
                                'status'=> http_response_code(400),
                                'data' => array('message' => 'Ha ocurrido un error, la fecha no es correcta, no se puede generar el reporte pacc')
                            ));
                        }
                } else {
                    $pacc = new PaccController();
                    $pacc->peticionNoValida();
                }
            } else {
                $pacc = new PaccController();
                $pacc->peticionNoAutorizada();
                require_once('../destruir-sesiones.php');
            }
        break;
        default: 
            $pacc = new PaccController();
            $pacc->peticionNoValida();
        break;
    }
?>