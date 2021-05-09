<?php
    include_once('../libreriaFPDF/fpdf.php');
    if (!isset($_SESSION)) {
        session_start();
    }
    $secretaria = 'SE_AD';
    $coordinador = 'C_C';
    $estratega = 'U_E';
    if (($_SESSION['abrevTipoUsuario'] != $secretaria) &&
        ($_SESSION['abrevTipoUsuario'] != $coordinador) && 
        ($_SESSION['abrevTipoUsuario'] != $estratega)) {
        header('Location: 401.php');
    }
    if (!isset($_SESSION['correoInstitucional'])) {
        header('Location: 401.php');
    }
    
    if (isset($_POST['exportar'])){
        $doc = new FPDF();
        $doc->AddPage();
        
        $doc->Ln();
        //R-170,2,25
        $doc->Image('../img/control-envio-permisos/logo-unah.png',10,12,43);
        $doc->Image('../img/control-envio-permisos/logo-fondo.png',110,50,100);
        $doc->Image('../img/control-envio-permisos/logo-sedp.png',158,18,43);
        $doc->Ln(14);
        $doc->SetFont('Helvetica','I',12);
        $doc->Cell(0,0,utf8_decode('Universidad Nacional Autónoma de Honduras'),0,1,"C");
        $doc->Ln(5);
        $doc->Cell(0,0,utf8_decode('Departamento de Efectividad del Recurso Humano'),0,1,"C");
        $doc->Ln(5);
        $doc->SetFont('Arial','B',12);
        $doc->Cell(0,0,utf8_decode('Control de Permisos Personales'),0,1,"C");
        $doc->Ln(14);
        
        $doc->SetFont('Arial','',11,);

        $doc->cell(50,9,'Nombre Completo: '.utf8_decode($_SESSION['nombrePersona'].' '.$_SESSION['apellidoPersona']). '                    '. 'No. de Empleado: '.utf8_decode($_SESSION['codigoEmpleado']) ,0,2,'L');
        
        //$doc->cell(50,9,'                               '.utf8_decode('______________________________').'               '.utf8_decode('______________________________'),0,2,'L');
        $doc->cell(50,9,'Departamento donde labora: '.utf8_decode($_SESSION['nombreDepartamento']),0,2,'L');
        $doc->SetFont('Arial','B',11);
        $doc->cell(50,9,'Solicito Permiso por motivo de: ',0,2,'L');
        $doc->SetFont('Arial','',11);
        $doc->MultiCell(190, 6,utf8_decode($_POST['R-motivoPermiso']));
        $doc->Ln(2);
        $doc->SetFont('Arial','',11);
        $doc->cell(50,9,'Edificio donde tiene registrada su asistencia: '.utf8_decode($_POST['R-edificioAsistencia']),0,2,'L');
        $doc->SetFont('Arial','',11);
        $doc->cell(50,9,utf8_decode('Tiempo de duración del permiso:'),0,2,'L');
       // $fechaInicio =$_POST['R-fechaInicio'];
        $doc->cell(50,9,'Fecha Inicio: '.date('d-m-Y', strtotime($_POST['R-fechaInicio'])).'              '.'Fecha Fin: '.date('d-m-Y', strtotime($_POST['R-fechaFin'])).'              '.'Hora Inicio: '.utf8_decode($_POST['R-horaInicio']).'              '.'Hora Fin: '.utf8_decode($_POST['R-horaFin']),0,2,'L');
        //$doc->cell(50,9,'Fecha Fin: '.utf8_decode($_POST['R-fechaFin']),0,2,'L');
        //$doc->cell(50,9,'Hora Inicio: '.utf8_decode($_POST['R-horaInicio']),0,2,'L');
        //$doc->cell(50,9,'Hora Fin: '.utf8_decode($_POST['R-horaFin']),0,2,'L');
        $doc->Ln(18);
        $doc->cell(50,9,'                    '.utf8_decode('______________________________').'               '.utf8_decode('______________________________'),0,2,'L');
        $doc->cell(50,9,'                             '.utf8_decode('     Firma del Solicitante    ').'                                   '.utf8_decode('    V.B del Jefe Inmediato    '),0,2,'L');
        $doc->Ln(20);
        $doc->Cell(0,0,utf8_decode('______________________________'),0,1,"C");
        $doc->Ln(8);
        $doc->Cell(0,0,utf8_decode('Departamento de Efectividad'),0,1,"C");
        $doc->Ln(5);
        $doc->Cell(0,0,utf8_decode('del Recurso Humano'),0,1,"C");



        ob_end_clean();
        $doc->Output("solicitudPermiso.pdf", "I");

    }
    else{
        echo "nada";
    }
?>
