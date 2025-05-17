<?php 
//activamos almacenamiento en el buffer
ob_start();
if (strlen(session_id())<1) 
  session_start();

if (!isset($_SESSION['nombre'])) {
  echo "debe ingresar al sistema correctamente para visualizar el reporte";
}else{

if ($_SESSION['ventas']==1) {

//incluimos el archivo factura



require('Factura.php');
require('Letras.php');



//establecemos los datos de la empresa
$logo="logo.jpg";
$ext_logo="png";
$empresa="Alimentos Vitales";
$documento="";
$direccion="Wilde 844 - Local 1 y 2";
$telefono="(0341) - 152278148";
$email="distribuidoraalvit@hotmail.com.ar";

//obtenemos los datos de la cabecera de la venta actual
require_once "../modelos/Venta.php";
$venta= new Venta();
$rsptav=$venta->ventacabecera($_GET["id"], $_GET["fecha"]);

//recorremos todos los valores que obtengamos
$regv=$rsptav->fetch_object();

$rspv=$venta->pagos($regv->idcliente, $_GET["fecha"]);
if($rv=$rspv->fetch_object()){

$pag = round($rv->pago,2);


}
else{
$pag = 0;
}

$totv=$venta->totalesventa($regv->idcliente, $regv->fecha_hora);

//recorremos todos los valores que obtengamos
if($tv=$totv->fetch_object()){

  
$saldo = round($tv->pagos - $tv->total,2);

}
else{
  $totv=$venta->totalesVtas($regv->idcliente,  $_GET["fecha"]);
$tv=$totv->fetch_object();
  $saldo = round($tv->total,2);
}

//configuracion de la factura
$pdf = new PDF_Invoice('p','mm','A4');

$rsptad=$venta->ventadetalles($_GET["id"]);
$pags = ($rsptad->num_rows > 24)? ceil($rsptad->num_rows /25) : 1;

for($i=0; $i<$pags; $i++){
$pdf->AddPage();

//enviamos datos de la empresa al metodo addSociete de la clase factura
$pdf->addSociete(utf8_decode($empresa),
                 $documento."\n".
                 utf8_decode("Direccion: "). utf8_decode($direccion)."\n".
                 utf8_decode("Telefono: ").$telefono."\n".
                 "Email: ".$email,$logo,$ext_logo);

$pdf->fact_dev("$regv->tipo_comprobante ","$regv->num_comprobante");
$pdf->temporaire( "" );
$pdf->addDate(date("d/m/Y", strtotime($regv->fecha)));

//enviamos los datos del cliente al metodo addClientAddresse de la clase factura
$pdf->addClientAdresse(utf8_decode($regv->cliente),
                       "Domicilio: ".utf8_decode($regv->direccion), 
                       $regv->tipo_documento.": ".$regv->num_documento, 
                       "Email: ".$regv->email, 
                       "Telefono: ".$regv->telefono);

//establecemos las columnas que va tener lña seccion donde mostramos los detalles de la venta
$cols=array( "CODIGO"=>23,
	         "DESCRIPCION"=>78,
	         "CANTIDAD"=>22,
	         "P.U."=>25,
	         "DSCTO"=>20,
           
	         "SUBTOTAL"=>22);
$pdf->addCols( $cols);
$cols=array( "CODIGO"=>"C",
             "DESCRIPCION"=>"L",
             "CANTIDAD"=>"R",
             "P.U."=>"R",
             "DSCTO"=>"R",
            
             "SUBTOTAL"=>"R" );
$pdf->addLineFormat( $cols);
$pdf->addLineFormat($cols); 

//actualizamos el valor de la coordenada "y" quie sera la ubicacion desde donde empecemos a mostrar los datos 
$y=85;

//obtenemos todos los detalles del a venta actual

$total =0;
$j = 1;
$total = 0;
$subtotal = 0;
while($regd=$rsptad->fetch_object() ){
   
  $pu = $regd->precio_venta /(( $regd->iva * 0.01)+1);
  $pv = ($regd->cantidad * $pu) *( ($regd->iva * 0.01)+1);
  $desc = ($regd->cantidad * $pu) * ($regd->descuento * 0.01);
  $subtotal = round(($pv - $desc),2);
  $total = $total + $subtotal;

  //$pv = round($regd->precio_venta, 2);
  //$subtotal = round($regd->subtotal, 2);
  $line = array( "CODIGO"=>"$regd->idarticulo",
                 "DESCRIPCION"=>utf8_decode("$regd->articulo"),
                 "CANTIDAD"=>str_replace('.', ',', "$regd->cantidad"),
                 "P.U."=>"$regd->precio_venta",
                 "DSCTO"=>"$regd->descuento",
                 "SUBTOTAL"=>number_format("$subtotal", 2, ',', ''));

  $size = $pdf->addLine( $y, $line );
  $y += $size +2;



//$total=$regv->total_venta; 
//$iva = $regv->total_venta * $regd->iva;






$j++;
  if($j > 24){
	
$V = new EnLetras();
//$V=new EnLetras(); 
$V->substituir_un_mil_por_mil = true;

//$valor = ($regv->total_venta * 1.00);
 ///$con_letra=strtoupper($V->ValorEnLetras(20000, "PESOS")); 
// if($saldo){
$pdf->addCadreTVAs(" " , "---SU SALDO ACTUAL ES: $".$saldo);
/*}else{
  $pdf->addCadreTVAs("---".$con_letra);
}*/
//$pdf->addCadreTVAs("---SU SALDO ES:".$saldo);


//mostramos el impuesto
$impuesto = round($total * ($regv->impuesto * 0.01),2);
$pdf->addTVAs( $impuesto, $total, "$ ", $pag);
$pdf->addCadreEurosFrancs(""." ");


	$subtotal = 0;
    break;
  }
}  
}

/*aqui falta codigo de letras*/


$V = new EnLetras();
//$V=new EnLetras(); 
$V->substituir_un_mil_por_mil = true;

//$valor = ($regv->total_venta * 1.00);
 ///$con_letra=strtoupper($V->ValorEnLetras(20000, "PESOS")); 
// if($saldo){
$pdf->addCadreTVAs(" " , "---SU SALDO ACTUAL ES: $".$saldo);
/*}else{
  $pdf->addCadreTVAs("---".$con_letra);
}*/
//$pdf->addCadreTVAs("---SU SALDO ES:".$saldo);


//mostramos el impuesto
$impuesto = round($total * ($regv->impuesto * 0.01),2);
$pdf->addTVAs( $impuesto, $total, "$ ", $pag);
$pdf->addCadreEurosFrancs(""." ");



$pdf->Output('Reporte de Venta' ,'I');



	}else{
echo "No tiene permiso para visualizar el reporte";
}

}

ob_end_flush();
  ?>
