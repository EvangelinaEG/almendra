<?php
require('../fpdf181/fpdf.php');


class PDF extends FPDF
  {
    function Header()
    {
      $fecha = DATE("d-m-Y");
     // $this->Image('images/logo.png', 5, 5, 30 );
      $this->SetFont('Arial','B', 'S',12);
      $this->Cell(80,10, 'ALIMENTOS VITALES',0,0,'L');
        $this->Ln(10);
        $this->SetFont('Arial','I',9);
      $this->Cell(60,5, 'Direccion: Wilde 844 - Local 1 y 2',0,0,'L');
      $this->Cell(50,5, 'Telefono: (0341) - 156-159146',0,0,'L');
      $this->Cell(50,5, 'Email: distribuidoraalvit@hotmail.com.ar',0,0,'L');
      $this->Ln(5);
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

/*$arreglo = [];
$i = 0;
while ($post = each($_POST))
{
echo $post[0];
 array_push($arreglo[], $post[0]) ;
}
//echo  . " </br>";
while ($i<count($_POST)) {
  for($j=0;$j<4;$j++){
  $arreglo[$i][$j] = $post[$i];
    echo $arreglo[$i][$j];
  }
  $i++;
  }
for($a=0;$a<count($arreglo);$a++){
 // for($b=0;$b<4;$b++){
    echo $arreglo[$a] . "<br>";
  //}
}
*/

 
/*
foreach ($_POST["nombre"] as  $key=>$value) {

  echo   $value . "<br>";


echo $_POST["idarticulo"][$key] . "<br>";
  
  if(isset($_POST["codigo"])){
  echo $_POST["codigo"][$key] . "<br>";

}
if(isset($_POST["precio1"])){

  echo $_POST["precio1"][$key] . "<br>";

}
if(isset($_POST["precio2"])){

  echo $_POST["precio2"][$key] . "<br>";

}
if(isset($_POST["precio3"])){

  echo $_POST["precio3"][$key] . "<br>";

}
if(isset($_POST["precio4"])){

  echo $_POST["precio4"][$key] . "<br>";

}
}*/




/*echo $_POST['categoria'];
echo $_POST['proveedor'];
echo $_POST['cadena'];


if($_POST){

$id = (isset($_POST['idalumno']))?$_POST['idalumno']:"";
$nombre = (isset($_POST['nombre']))?$_POST['nombre']:"";
list($p11, $p12, $p13, $p14, $idcategoria, $idproveedor) = explode(' ', $cadena);
$precio1 = (isset($_POST['pl1']))?$_POST['pl1']:"";
$precio2 = (isset($_POST['pl2']))?$_POST['pl2']:"";
$precio3 = (isset($_POST['pl3']))?$_POST['pl3']:"";
$precio4 = (isset($_POST['pl4']))?$_POST['pl4']:"";
$categoria = (isset($_POST['idcategoria']))?$_POST['idcategoria']:"TODAS";
$proveedor = (isset($_POST['idproveedor']))?$_POST['idproveedor']:"TODOS";

//$cadena = "";
  }
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

$categoria = $_POST["categoria"];
$proveedor = $_POST["proveedor"];
$cadena = $_POST["cadena"];








  if(!isset($_POST["idarticulo"])){
  echo "No hay resultados para mostrar";
}else{
  $pdf = new PDF();
  $pdf->AliasNbPages();
  $pdf->AddPage();
  
  $pdf->SetFillColor(232,232,232);
  $pdf->SetFont('Arial','B',12);
 
  $pdf->Cell(10,6,'ID',1,0,'C',1);
/*if(isset($_POST["codigo"])){
  $pdf->Cell(30,6,'CODIGO',1,0,'C',1);}*/
  $pdf->Cell(90,6,'NOMBRE',1,0,'C',1);
if(isset($_POST["precio1"])){
  $pdf->Cell(16,6,'P.Vta 1',1,0,'C',1);}
if(isset($_POST["precio2"])){
  $pdf->Cell(16,6,'P.Vta 2',1,0,'C',1);}
if(isset($_POST["precio3"])){
  $pdf->Cell(16,6,'P.Vta 3',1,0,'C',1);}
if(isset($_POST["precio4"])){
  $pdf->Cell(16,6,'P.Vta 4',1,0,'C',1);}
// $pdf->Cell(20,6,'',1,1,'C',1);
    $pdf->Cell(1,6,'',1,1,'C',1); 
  $pdf->SetFont('Arial','',10);


foreach ($_POST["idarticulo"] as  $key=>$value) 
  {
  if($categoria != "TODAS" && $proveedor != "TODOS"){

  $query = "SELECT ".$cadena." nombre, idarticulo FROM articulo WHERE idcategoria = ".$categoria." AND idproveedor = ".$proveedor." AND condicion = '1' AND idarticulo = '$value'";
 $row = ejecutarConsultaSimpleFila($query);
 }
 else if($categoria != "TODAS" && $proveedor == "TODOS"){

  $query = "SELECT ".$cadena." nombre, idarticulo FROM articulo WHERE idcategoria = ".$categoria." AND condicion = '1' AND idarticulo = '$value'";
 $row = ejecutarConsultaSimpleFila($query);

 }else if($categoria == "TODAS" && $proveedor != "TODOS"){
    $query = "SELECT ".$cadena." nombre, idarticulo FROM articulo WHERE idproveedor = ".$proveedor." AND condicion = '1' AND idarticulo = '$value'";
 $row = ejecutarConsultaSimpleFila($query);
 }else if($categoria =="TODAS" && $proveedor == "TODOS"){
    $query = "SELECT ".$cadena." nombre, idarticulo FROM articulo WHERE condicion = '1' AND idarticulo = '$value'";
 $row = ejecutarConsultaSimpleFila($query) ;

 }




    if(isset($row["idarticulo"]))
   { $pdf->Cell(10,6,$row["idarticulo"],1,0,'L');}
  $pdf->Cell(90,6,utf8_decode($row["nombre"]),1,0,'L');
 /*if(isset($_POST["codigo"]))
   { $pdf->Cell(30,6,$_POST["codigo"][$key],1,0,'L');}
 elseif(isset($row["codigo"])){$pdf->Cell(30,6,'',1,0,'L');}
    $pdf->Cell(90,6,utf8_decode($row["codigo"]),1,0,'L');*/
  if(isset($row["precioventa1"])){
    $pdf->Cell(16,6,$row["precioventa1"],1,0,'R');
  }
  if(isset($row["precioventa2"])){
    $pdf->Cell(16,6,$row["precioventa2"],1,0,'R');
  }
  if(isset($row["precioventa3"])){
    $pdf->Cell(16,6,$row["precioventa3"],1,0,'R');
  }
  
  if(isset($row["precioventa4"]))
    {$pdf->Cell(16,6,$row["precioventa4"],1,0,'R');}
  
   $pdf->Cell(1,6,'',1,1,'L');
}
  
  $pdf->Output();
  }

?>