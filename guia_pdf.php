<?php
// Conexion a la base de datos
include 'db.php';
include 'datos.php';
require('fpdf/fpdf.php');

// Prueba FPDF
$conexion = mysqli_connect("localhost", "root", "", "pasteleria");
?>

<?php

if (isset($_GET['num_guia'])){
    $num_guia = $_GET['num_guia'];
    $rut_vendedor=$_GET['rut_vendedor'];
    $rut_comprador=$_GET['rut_comprador'];
    $nom_cliente=$_GET['nom_cliente'];
}
?>

<?php

class PDF extends FPDF
{
// Cabecera de página
    function Header()
    
    {
        // Logo
        $this->Image('img/logo.png',6,6,33);
        $this->SetFont('Times','',9);
        $rut=RUT_EMPRESA;
        $name=NOMBRE_EMPRESA;
        
        $this->Cell(201,1,'RUT: ',0,1,'R');
        $this->SetFont('Times','',9);
        $this->Cell(200,6,$rut,0,1,'R');
        $this->Ln(1);
       
        $this->Cell(200,2,'Guia de despacho',0,1,'R');
        $this->Cell(200,5,'Electronica',0,1,'R');
        $this->SetFont('Times','B',10);
        $num_guia = $_GET['num_guia'];
        $this->Cell(200,6,'Folio '.$num_guia,0,1,'R');
        $this->Line(186.6,25, 216-5,25); 
        $this->Line(186.5,31, 216-5,31);
        $this->Line(186.5,25, 186.5,31);
        $this->Line(216-5,25, 216-5,31);  


        $this->Line(5,36, 216-5,36); 
        $this->Ln(7);
    }

// Pie de página
    function Footer()
    {
        //$this->Line(5,113, 216-5,113);
        //$this->Line(216-5,107, 216-5,113);
        //$this->Line(5,107, 5,113);
        $this->SetY(-10);
        $this->SetFont('Times','I',8);
        // Número de página
        $this->Cell(0,10,'Pagina '.$this->PageNo().' de {nb}',0,0,'C');
    }
}


$pdf = new PDF('P','mm','LETTER');
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetFont('Times','B',10);
$pdf->cell(0,1,'Datos Empresa: ',0,1,'L');
$pdf->ln(2);
$pdf->SetFont('Times','',10);
$nombre_e=NOMBRE_EMPRESA;
$dir=DIRECCION_EMPRESA;
$tel=TELEFONO_EMPRESA;
$mail=EMAIL_EMPRESA;
$pdf->cell(0,5,'Nombre empresa: '.$nombre_e,0,1,'L');
$pdf->cell(0,5,'Direccion empresa: '.$dir,0,1,'L');
$pdf->cell(0,5,'Telefono empresa: '.$tel,0,1,'L');
$pdf->cell(0,5,'Correo empresa: '.$mail,0,1,'L');
$pdf->Line(5,61, 216-5,61); 
$pdf->Line(5,41, 216-5,41); 
$pdf->Line(5,36, 5,61);
$pdf->Line(216-5,36, 216-5,61); 
$pdf->ln();

$pdf->SetFont('Times','B',10);
$pdf->cell(0,0,'Datos Cliente: ',0,1,'L');
$pdf->ln(2);
$pdf->SetFont('Times','',10);
$pdf->Line(5,63, 216-5,63);
$pdf->Line(5,68, 216-5,68);
$pdf->Line(5,63, 5,93);
$pdf->Line(216-5,63, 216-5,93); 
$pdf->Line(5,93, 216-5,93);

$query_guia = "SELECT * FROM guias_despacho g, clientes c WHERE g.n_guia = $num_guia AND c.rut = g.rut_comprador";
$result_guia = mysqli_query($conexion,$query_guia);

while ($row=$result_guia->fetch_assoc()){
    $pdf->cell(0,5,'Nombre cliente: '.$row['nom_cliente'],0,1,'L');
    $pdf->cell(0,5,'Rut cliente: '.$row['rut'],0,1,'L');
    $pdf->cell(0,5,'Telefono cliente: '.$row['telefono'],0,1,'L');
    $pdf->cell(0,5,'Email cliente: '.$row['email'],0,1,'L');
    $pdf->cell(0,5,'Referencia: '.$row['referencia'],0,1,'R');
    $pdf->cell(0,-5,'Direccion: '.$row['direccion'],0,1,'L');

}

$pdf->ln(10);
$pdf->Line(5,95, 216-5,95);
$pdf->SetFont('Times','B',10);
$pdf->cell(0,0,'Rut vendedor ',0,1,'L');
$pdf->cell(0,0,'Tipo de traslado',0,1,'C');
$pdf->cell(187,0,'Fecha ',0,1,'R');
$pdf->Line(5,100, 216-5,100);

$pdf->Line(216-5,95, 216-5,105);
$pdf->Line(5,95, 5,105);

$pdf->Line(5,105, 216-5,105);
$pdf->SetFont('Times','',10);
$query_guia_datos = "SELECT * FROM guias_despacho g, clientes c WHERE g.n_guia = $num_guia AND c.rut = g.rut_comprador";
$result_guia_datos = mysqli_query($conexion,$query_guia_datos);
while ($row=$result_guia_datos->fetch_assoc()){
    $pdf->cell(0,10,$row['rut_vendedor'],0,1,'L');
    $pdf->cell(0,-10,$row['tipo_traslado'],0,1,'C');
    $pdf->cell(0,10,$row['fecha'],0,1,'R');

}

$pdf->ln(2);
$pdf->Line(5,107, 216-5,107);
$pdf->SetFont('Times','B',10);
$pdf->cell(0,0,'CANTIDAD ',0,1,'L');
$pdf->cell(95,0,'DESCRIPCION',0,1,'C');
$pdf->cell(220,0,'TIPO UNIDAD ',0,1,'C');
$pdf->cell(290,0,'PRECIO UNIDAD',0,1,'C');
$pdf->cell(0,0,'TOTAL',0,1,'R');
$pdf->Line(5,113, 216-5,113);
$pdf->Line(216-5,107, 216-5,113);
$pdf->Line(5,107, 5,113);

//$pdf->Line(5,208, 216-5,208);

$pdf->ln(5);
$pdf->SetFont('Times','',8);


$query_detalle = "SELECT * FROM guias_detalle gd, productos p WHERE gd.n_guia  = $num_guia and gd.cod_producto = p.cod_producto ";
$result_detalle = mysqli_query($conn, $query_detalle);
$imp_adic_guia=0;
$neto_guia=0;
$total_guia=0;
$total_iva=0;
$total_con_iva=0;
$precio_subtotal=0;

while ($row=$result_detalle->fetch_assoc()){
    $pdf->ln(0.7);
    $pdf->cell(0,5,number_format($row['cantidad'],0,'','.'),0,1,'L');
    $pdf->ln(0.7);
    $pdf->cell(100,-5,$row['nom_producto'],0,1,'C');
    $pdf->ln(0.7);
    $pdf->cell(220,4,$row['tipo_unidad'],0,1,'C');
    $pdf->ln(0.7);
    $pdf->cell(290,-6,'$ '.number_format(($row['precio_venta']*0.81),0,'','.'),0,1,'C');   
    $pdf->ln(0.7);
    $pdf->cell(0,5,'$ '.number_format(($row['precio_venta']*0.81*$row['cantidad']),0,'','.'),0,1,'R');
    $pdf->ln(0.7);
    $imp_adic_guia=$imp_adic_guia+($row['imp_adic']);
    $imp_adic_guia_format=number_format($imp_adic_guia,0,'','.');

    $neto_guia=$neto_guia+$row['valor_neto'];
    $neto_guia_format=number_format($neto_guia,0,'','.');

    $total_guia=$total_guia+($row['subtotal']);
    $total_guia_format=number_format($total_guia,0,'','.');

    $total_iva=$total_iva+$row['iva_19']*0.81;
    $total_iva_format=number_format($total_iva,0,'','.');
    $total_final_guia=$total_iva+$imp_adic_guia+$neto_guia;
    $total_format=number_format($total_final_guia,0,'','.');
}

$pdf->ln(62);
$pdf->SetFont('Times','',10);
//$pdf->Line(5,210, 216-5,210);
//$pdf->Line(5,233, 216-5,233);
//$pdf->Line(5,210,5,233);
//$pdf->Line(216-5,210,216-5,233);
$pdf->cell(200,5,'SUBTOTAL NETO:   $ '.$neto_guia_format,1,1,'R');
$pdf->cell(200,5,'IVA (19%):   $ '.$total_iva_format,1,1,'R');
$pdf->SetFont('Times','',10);
$pdf->cell(200,5,'IMP ADICIONAL:   $ '.$imp_adic_guia_format,1,1,'R');
$pdf->SetFont('Times','B',10);
$pdf->cell(200,5,'TOTAL:   $ '.$total_format,1,1,'R');


// salida guia pdf
$pdf->Output('i','Guia_'.$num_guia.'.pdf',false);
?>
