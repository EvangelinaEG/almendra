<?php 
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Cccliente{


	//implementamos nuestro constructor
public function __construct(){

}

//metodo insertar registro
/*public function insertar($idproveedor,$idusuario,$tipo_comprobante,$serie_comprobante,$num_comprobante,$fecha_hora,$impuesto,$total_compra,$monto, $idarticulo,$cantidad,$precio_compra){
	$sql="INSERT INTO ingreso (idproveedor,idusuario,tipo_comprobante,serie_comprobante,num_comprobante,fecha_hora,impuesto,total_compra,estado) VALUES ('$idproveedor','$idusuario','$tipo_comprobante','$serie_comprobante','$num_comprobante','$fecha_hora','$impuesto','$total_compra','Aceptado')";
	//return ejecutarConsulta($sql);
	 $idingresonew=ejecutarConsulta_retornarID($sql);

	 	$sql3="INSERT INTO ccproveedor (idproveedor,idingreso,fechahorapago,monto) VALUES ('$idproveedor','$idingresonew','$fecha_hora','$monto')";
	//return ejecutarConsulta($sql);
	 ejecutarConsulta($sql3);


	 $num_elementos=0;
	 $sw=true;
	 while ($num_elementos < count($idarticulo)) {

	 	$sql_detalle="INSERT INTO detalle_ingreso (idingreso,idarticulo,cantidad,precio_compra) VALUES('$idingresonew','$idarticulo[$num_elementos]','$cantidad[$num_elementos]','$precio_compra[$num_elementos]')";

	 	ejecutarConsulta($sql_detalle) or $sw=false;

	 	

	 	$costo = $precio_compra[$num_elementos];
	 	$id = $idarticulo[$num_elementos];

	 	 $sql2 = "UPDATE articulo SET costo = '$costo' WHERE idarticulo = '$id'";
	 ejecutarConsulta($sql2);


	 $num_elementos=$num_elementos+1;
	 }
	 return $sw;
}

public function anular($idingreso){
	$sql="UPDATE ingreso SET estado='Anulado' WHERE idingreso='$idingreso'";
	return ejecutarConsulta($sql);
}


*/

public function eliminarpago($id){
	$sql = "DELETE FROM cccliente WHERE idcccliente = '$id'";
	return ejecutarConsulta($sql);
}
public function registrarP($id, $monto,  $usu){
	date_default_timezone_set('America/Argentina/Buenos_Aires');
	setlocale(LC_TIME,"es_ES");
	$fecha = date("Y-m-d H:i:s"); 
	$sql = "INSERT INTO cccliente (idcccliente, idcliente, idusuario, fechahorapago, pago ) VALUES (null, '$id', '$usu', '$fecha', '$monto')";
	return ejecutarConsulta($sql);
}
public function listarPagosCli($cliente){
	$sql="SELECT cc.idcccliente, cc.idventa, DATE(fechahorapago) as fecha, cc.pago, u.nombre FROM cccliente cc INNER JOIN usuario u ON cc.idusuario = u.idusuario WHERE cc.idcliente='$cliente'";
	return ejecutarConsulta($sql);
}

public function listarPagos($idcliente){
	$sql="SELECT cc.idcccliente, cc.fechahorapago, cc.pago, u.nombre, cc.factura, v.total_venta FROM cccliente cc  LEFT JOIN venta v ON cc.factura = v.idventa LEFT JOIN usuario u ON cc.idusuario = u.idusuario WHERE cc.idcliente ='$idcliente'";
	return ejecutarConsulta($sql);
}

//metodo para mostrar registros
public function mostrar($idcliente){
	$sql="SELECT p.nombre as cliente, u.idusuario, u.nombre as usuario, cc.idcccliente, cc.pago, cc.fechahorapago as fecha FROM cccliente cc INNER JOIN persona p ON cc.idcliente = p.idpersona INNER JOIN usuario u ON cc.idusuario = u.idusuario WHERE cc.idcliente = '$idcliente'";
	return ejecutarConsultaSimpleFila($sql);
}
//listar registros
public function listar(){
	$sql="SELECT A.*, B.total from (SELECT cc.idcccliente, cc.idcliente, p.nombre as cliente, SUM(cc.pago) as suma FROM cccliente cc INNER JOIN persona p ON cc.idcliente = p.idpersona GROUP BY cc.idcliente ORDER BY p.nombre ASC) as A inner join (SELECT v.idcliente, p.nombre, SUM(v.total_venta) as total FROM venta v INNER JOIN persona p ON v.idcliente = p.idpersona GROUP BY v.idcliente ORDER BY p.nombre ASC) as B on A.idcliente = B.idcliente";
	return ejecutarConsulta($sql);
}
public function tickettotal($cliente, $fecha){
	$sql="SELECT A.*, B.total from (SELECT cc.idcccliente, cc.idcliente, p.nombre as cliente, SUM(cc.pago) as suma FROM cccliente cc INNER JOIN persona p ON cc.idcliente = p.idpersona WHERE cc.fechahorapago <= '$fecha' GROUP BY cc.idcliente ORDER BY p.nombre ASC) as A inner join (SELECT v.idcliente, p.nombre, SUM(v.total_venta) as total FROM venta v INNER JOIN persona p ON v.idcliente = p.idpersona WHERE v.fecha_hora <= '$fecha' GROUP BY v.idcliente ORDER BY p.nombre ASC) as B on A.idcliente = B.idcliente WHERE A.idcliente = '$cliente'";
	return ejecutarConsulta($sql);
}
public function ticketcabecera($idcliente, $fecha){
	$sql= "SELECT cc.idcccliente, cc.idcliente, cc.fechahorapago, cc.pago, u.nombre, p.nombre as cliente, p.direccion FROM persona p INNER JOIN cccliente cc ON p.idpersona = cc.idcliente INNER JOIN usuario u ON cc.idusuario = u.idusuario WHERE cc.idcliente='$idcliente' AND cc.fechahorapago = '$fecha'";
	return ejecutarConsulta($sql);
}

public function ticketdetalles($idcliente){
	$sql="SELECT a.nombre AS articulo, a.codigo, d.cantidad, d.precio_venta, d.descuento, (d.cantidad*d.precio_venta-d.descuento) AS subtotal, SUM(cc.pago) as pagos FROM detalle_venta d INNER JOIN cccliente cc ON d.idventa = cc.idventa INNER JOIN articulo a ON d.idarticulo=a.idarticulo WHERE d.idventa='$idventa'";
         return ejecutarConsulta($sql);
}

}

 ?>
