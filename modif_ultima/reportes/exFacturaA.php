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

//establecemos los datos de la empresa
$logo="logo.jpg";
$ext_logo="png";
$empresa="Alimentos Vitales";
$documento="";
$direccion="Wilde 844 - Local 1 y 2";
$telefono="(0341) - 156-159146";
$email="distribuidoraalvit@hotmail.com.ar";

//obtenemos los datos de la cabecera de la venta actual
require_once "../modelos/Venta.php";
$venta= new Venta();
$rsptav=$venta->ventacabecera($_GET["id"]);
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
$pdf->AddPage();

//enviamos datos de la empresa al metodo addSociete de la clase factura
$pdf->addSociete(utf8_decode($empresa),
                 $documento."\n".
                 utf8_decode("Direccion: "). utf8_decode($direccion)."\n".
                 utf8_decode("Telefono: ").$telefono."\n".
                 "Email: ".$email,$logo,$ext_logo);


$pdf->fact_dev("PRESUPUESTO","$regv->num_comprobante");
$pdf->temporaire( "" );
$pdf->addDate(date("d/m/Y", strtotime($regv->fecha)));

//enviamos los datos del cliente al metodo addClientAddresse de la clase factura
$pdf->addClientAdresse(utf8_decode($regv->cliente),
                       "Domicilio: ".utf8_decode($regv->direccion), 
                       $regv->tipo_documento.": ".$regv->num_documento, 
                       "Email: ".$regv->email, 
                       "Telefono: ".$regv->telefono);

//establecemos las columnas que va tener lña seccion donde mostramos los detalles de la venta
$cols=array( "CODIGO"=>20,
	         "DESCRIPCION"=>62,
	         "CANTIDAD"=>22,
	         "P.U."=>25,
	         "DSCTO"=>20,
           "IVA"=>20,
	         "SUBTOTAL"=>22);
$pdf->addCols( $cols);
$cols=array( "CODIGO"=>"C",
             "DESCRIPCION"=>"L",
             "CANTIDAD"=>"C",
             "P.U."=>"R",
             "DSCTO"=>"R",
             "IVA"=>"R",
             "SUBTOTAL"=>"R" );
$pdf->addLineFormat( $cols);
$pdf->addLineFormat($cols); 

//actualizamos el valor de la coordenada "y" quie sera la ubicacion desde donde empecemos a mostrar los datos 
$y=85;

//obtenemos todos los detalles del a venta actual
$rsptad=$venta->ventadetalles($_GET["id"]);
$total = 0;
$ivas = 0;
while($regd=$rsptad->fetch_object()){
  //$pv = round($regd->precio_venta - ($regd->precio_venta * 0.21), 2);
  //$ivas = $ivas + $regd->iva;
 $pu = $regd->precio_venta / (($regd->iva*0.01)+1);
  $pv = ($regd->cantidad * $pu)*($regd->iva*0.01);
  $desc = ($regd->cantidad * $pu) * ($regd->descuento * 0.01);
  //$subtotal = round(($regd->cantidad * $regd->precio_venta) - $desc, 2);
  //$total = round($total + $subtotal, 2);




  //$pv = round(($regd->cantidad * $pu)*$regd->iva, 2);
  //$desc = round(($regd->cantidad * $pu) * ($regd->descuento * 0.01), 2);
  $subtotal = round((($regd->cantidad * $pu) - $desc),2);
  $ivas = round($ivas + $pv,2); 
  $total = $total + $subtotal;
  $line = array( "CODIGO"=>"$regd->idarticulo",
                 "DESCRIPCION"=>utf8_decode("$regd->articulo"),
                 "CANTIDAD"=>str_replace('.', ',', "$regd->cantidad"),
                 "P.U."=>round("$pu", 2),
                 "DSCTO"=>"$regd->descuento",
                 "IVA"=>"$regd->iva",
                 "SUBTOTAL"=>number_format("$subtotal", 2, ',', ''));

  $size = $pdf->addLine( $y, $line );
  $y += $size +2;

}  

/*aqui falta codigo de letras*/
require_once "Letras.php";
$V = new EnLetras();


//$total=$regv->total_venta; 

$V=new EnLetras(); 
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
$impuesto = round($regv->total_venta * ($regv->impuesto * 0.01), 2);
$pdf->addTVAsA( $impuesto, $ivas, $total,  $regv->total_venta, "$ ", $pag);
$pdf->addCadreEurosFrancsA(""." ");
$pdf->Output('Reporte de Venta' ,'I');

	}else{
echo "No tiene permiso para visualizar el reporte";
}

}

ob_end_flush();
  ?>
