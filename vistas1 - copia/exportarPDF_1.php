<?php
require('../fpdf181/fpdf.php');


class PDF extends FPDF
  {
    function Header()
    {
      $fecha = DATE("d-m-Y");
     // $this->Image('images/logo.png', 5, 5, 30 );
      $this->SetFont('Arial','B',12);
       $this->Cell(80,10, 'Listado de Precios',0,0,'L');
      $this->Cell(80,10, $fecha,0,0,'R');
      $this->Cell(40);
      
      $this->Ln(20);
    }
    
    function Footer()
    {
      $this->SetY(-15);
      $this->SetFont('Arial','I', 8);
      $this->Cell(0,10, 'Los precios se modifican sin previo aviso',0,0,'L' );
      $this->Cell(0,10, 'Pagina '.$this->PageNo().'/{nb}',0,1,'R' );
      
    }   
  }

require "../config/Conexion.php";



$id = (isset($_POST['id']))?$_POST['id']:"";
$codigo = (isset($_POST['cod']))?$_POST['cod']:"";
$precio1 = (isset($_POST['pl1']))?$_POST['pl1']:"";
$precio2 = (isset($_POST['pl2']))?$_POST['pl2']:"";
$precio3 = (isset($_POST['pl3']))?$_POST['pl3']:"";
$precio4 = (isset($_POST['pl4']))?$_POST['pl4']:"";
$categoria = (isset($_POST['idcategoria']))?$_POST['idcategoria']:"TODAS";
$proveedor = (isset($_POST['idproveedor']))?$_POST['idproveedor']:"TODOS";

$cadena = "";
  
if($id != ""){
  $cadena.= "idarticulo,";
}
if($codigo != ""){
  $cadena.=" codigo,";
}
if($precio1 != ""){
  $cadena.=" precioventa1,";
}
if($precio2 != ""){
  $cadena.=" precioventa2,";
}

if($precio3 != ""){
  $cadena.=" precioventa3,";
}

if($precio4 != ""){
  $cadena.=" precioventa4,";
}


if($categoria != "TODAS" && $proveedor != "TODOS"){

  $query = "SELECT ".$cadena." nombre FROM articulo WHERE idcategoria = ".$categoria." AND idproveedor = ".$proveedor." AND condicion = '1'";
 $resultado = ejecutarConsulta($query);
 }
 else if($categoria != "TODAS" && $proveedor == "TODOS"){

  $query = "SELECT ".$cadena." nombre FROM articulo WHERE idcategoria = ".$categoria." AND condicion = '1'";
 $resultado = ejecutarConsulta($query);

 }else if($categoria == "TODAS" && $proveedor != "TODOS"){
    $query = "SELECT ".$cadena." nombre FROM articulo WHERE idproveedor = ".$proveedor." AND condicion = '1'";
 $resultado = ejecutarConsulta($query);
 }else if($categoria =="TODAS" && $proveedor == "TODOS"){
    $query = "SELECT ".$cadena." nombre FROM articulo WHERE condicion = '1'";
 $resultado = ejecutarConsulta($query) ;
 }
/*else{
  $query = "SELECT * FROM articulo";
 $resultado = ejecutarConsulta($query) ;
}*/

  //$resultado = $mysqli->query($query);
  
  $pdf = new PDF();
  $pdf->AliasNbPages();
  $pdf->AddPage();
  
  $pdf->SetFillColor(232,232,232);
  $pdf->SetFont('Arial','B',12);
  if($id != ""){
  $pdf->Cell(10,6,'ID',1,0,'C',1);}
if($codigo != ""){
  $pdf->Cell(30,6,'CODIGO',1,0,'C',1);}
  $pdf->Cell(90,6,'NOMBRE',1,0,'C',1);
  if($precio1 != "")
  $pdf->Cell(16,6,'P.Vta 1',1,0,'C',1);
if($precio2 != "")
  $pdf->Cell(16,6,'P.Vta 2',1,0,'C',1);
if($precio3 != "")
  $pdf->Cell(16,6,'P.Vta 3',1,0,'C',1);
if($precio4 != "")
  $pdf->Cell(16,6,'P.Vta 4',1,0,'C',1);
// $pdf->Cell(20,6,'',1,1,'C',1);
    $pdf->Cell(1,6,'',1,1,'C',1); 
  $pdf->SetFont('Arial','',10);
 
  while($row = $resultado->fetch_assoc())
  {
    if(isset($row['idarticulo']))
   { $pdf->Cell(10,6,$row['idarticulo'],1,0,'L');}
 if(isset($row['codigo']))
   { $pdf->Cell(30,6,$row['codigo'],1,0,'L');}
 elseif($codigo != ""){$pdf->Cell(30,6,'',1,0,'L');}
    $pdf->Cell(90,6,utf8_decode($row['nombre']),1,0,'L');
  if(isset($row['precioventa1']))
    $pdf->Cell(16,6,$row['precioventa1'],1,0,'R');
  if(isset($row['precioventa2']))
    $pdf->Cell(16,6,$row['precioventa2'],1,0,'R');
  if(isset($row['precioventa3']))
    $pdf->Cell(16,6,$row['precioventa3'],1,0,'R');
  
  if(isset($row['precioventa4']))
    {$pdf->Cell(16,6,$row['precioventa4'],1,0,'R');}
  
   $pdf->Cell(1,6,'',1,1,'L');
}
  
  $pdf->Output();
?>