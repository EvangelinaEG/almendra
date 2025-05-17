<?php 
//activamos almacenamiento en el buffer
ob_start();
if (strlen(session_id())<1) 
  session_start();

if (!isset($_SESSION['nombre'])) {
  echo "debe ingresar al sistema correctamente para vosualizar el reporte";
}else{

if ($_SESSION['compras']==1) {

?>

<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
	<link rel="stylesheet" href="../public/css/ticket.css">
</head>
<body onload="window.print();">
	<?php 
// incluimos la clase venta
require_once "../modelos/ccproveedor.php";

$ccproveedor = new Ccproveedor();

//en el objeto $rspta obtenemos los valores devueltos del metodo ventacabecera del modelo
$rspta = $ccproveedor->ticketcabecera($_GET["id"], $_GET["fecha"]);

$reg=$rspta->fetch_object();

$rt = $ccproveedor->tickettotal($_GET["id"], $_GET["fecha"]);

$re=$rt->fetch_object();
$saldo = $re->suma - $re->total;
if($saldo<0){
	$saldo = "(-) $". abs($saldo);
}else{
	$saldo = "(+) $". $saldo;
}
//establecemos los datos de la empresa
$empresa = "Alimentos Vitales";
$documento = "102589524";
$direccion = "";
$telefono = "";
$email = "";
	 ?>
<div class="zona_impresion">
	<!--codigo imprimir-->
	<br>
	<table border="0" align="center" width="300px">
		<tr>
			<td align="center">

				<!--mostramos los datos de la empresa en el doc HTML-->
				.::<strong> <?php echo $empresa; ?></strong>::.<br>
				<?php echo $documento; ?><br>
				<?php echo $direccion . '-'.$telefono; ?><br>
			</td>
		</tr>
		<tr>
			<td align="center"><?php echo date("d/m/Y", strtotime($reg->fechahorapago)); ?></td>
		</tr>
		<tr> 
			<td align="center"></td>
		</tr>
		<tr>
			<!--mostramos los datos del cliente -->
			<td>Proveedor: <p style="font-weight: bold; "><?php echo $reg->proveedor; ?></p>
			</td>
		</tr>
		<tr>
			<td>
				<?php "Direccion: ".$reg->direccion; ?>
			</td>
		</tr>
		<tr>
			<td>
				N° de Comprobante: <?php echo $reg->idcuentacorriente; ?>
			</td>
		</tr>
	</table>
	<br>

	<!--mostramos lod detalles de la venta -->


	<table border="0" align="center" width="300px">
		<!-- <tr>
			<td>CANT.</td>
			<td>DESCRIPCION</td>
			<td align="right">IMPORTE</td>
		</tr>
		<tr>
			<td colspan="3">=============================================</td>
		</tr> -->
		<tr>
			<td>ULTIMO PAGO:</td>
			<td><p style="font-weight: bold;">$<?php echo $reg->monto; ?></p></td>
		</tr>
		<tr>
			<td>SALDO ACTUAL:</td>
			<td><p style="font-weight: bold; text-transform: uppercase;"><?php echo $saldo; ?></p></td>
		</tr>
		<?php
		/*$rsptad = $venta->ventadetalles($_GET["id"]);
		$cantidad=0;
		while ($regd = $rsptad->fetch_object()) {
		 	echo "<tr>";
		 	echo "<td>".$regd->cantidad."</td>";
		 	echo "<td>".$regd->articulo."</td>";
		 	echo "<td align='right'>$ ".$regd->subtotal."</td>";
		 	echo "</tr>";
		 	$cantidad+=$regd->cantidad;
		 } 
*/
		 ?>
		 <!--mostramos los totales de la venta-->
		<tr>
			
			<td align="right"><b>TOTAL EN COMPRAS:</b></td>
			<td align="right"><b>$<?php echo $re->total; ?></b></td>
		</tr>
				
		<!-- <tr>
			<td>&nbsp;</td>
			<td align="right"><b>SALDO:</b></td>
			<td align="right"><b>$. <?php echo ($reg->total - $reg->pagos); ?></b></td>
		</tr>
		<tr>
			<td colspan="3">N° de articulos: <?php echo $cantidad; ?> </td>
		</tr>
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr> -->
		<!-- <tr>
			<td colspan="3" align="center">¡Gracias por su compra!</td>
		</tr> -->
		<tr>
			<td colspan="3" align="center">Alimentos Vitales</td>
		</tr>
		<tr>
			<td colspan="3" align="center">Rosario - Santa Fe - Argentina</td>
		</tr>
	</table>
	<br>
</div>
<p>&nbsp;</p>
</body>
</html>



<?php

	}else{
echo "No tiene permiso para visualizar el reporte";
}

}


ob_end_flush();
  ?>