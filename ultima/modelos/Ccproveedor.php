<?php 
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Ccproveedor{


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
public function registrarP($monto, $prov, $usu){
	date_default_timezone_set('America/Argentina/Buenos_Aires');
	setlocale(LC_TIME,"es_ES");
	$fecha_hora = date("Y-m-d H:i:s"); 
	
	$sql = "INSERT INTO ccproveedor (idproveedor,fechahorapago, monto, idusuario) VALUES ('$prov','$fecha_hora', '$monto', '$usu')";
	return ejecutarConsulta($sql);
}
public function listarPagosProv($proveedor){
	$sql="SELECT ccp.idcuentacorriente, ccp.idingreso, DATE(fechahorapago) as fecha, ccp.monto, u.nombre FROM ccproveedor ccp INNER JOIN usuario u ON ccp.idusuario = u.idusuario WHERE ccp.idingreso='$proveedor'";
	return ejecutarConsulta($sql);
}

public function listarPagos($idproveedor){
	$sql="SELECT ccp.idcuentacorriente, ccp.fechahorapago, ccp.monto, u.nombre FROM ccproveedor ccp INNER JOIN usuario u ON ccp.idusuario = u.idusuario WHERE ccp.idproveedor ='$idproveedor'";
	return ejecutarConsulta($sql);
}

//metodo para mostrar registros
public function mostrar($idproveedor){
	$sql="SELECT p.nombre as proveedor, u.idusuario, u.nombre as usuario, ccp.idcuentacorriente, ccp.monto, ccp.fechahorapago as fecha FROM ccproveedor ccp INNER JOIN persona p ON ccp.idproveedor = p.idpersona INNER JOIN usuario u ON ccp.idusuario = u.idusuario WHERE ccp.idproveedor = '$idproveedor'";
	return ejecutarConsultaSimpleFila($sql);
}
//listar registros
public function listar(){
	$sql="SELECT A.*, B.total from (SELECT ccp.idcuentacorriente, ccp.idproveedor, p.nombre as proveedor, SUM(ccp.monto) as suma FROM ccproveedor ccp INNER JOIN persona p ON ccp.idproveedor = p.idpersona GROUP BY ccp.idproveedor ORDER BY p.nombre ASC) as A inner join (SELECT i.idproveedor, p.nombre, SUM(i.total_compra) as total FROM ingreso i INNER JOIN persona p ON i.idproveedor = p.idpersona GROUP BY i.idproveedor ORDER BY p.nombre ASC) as B on A.idproveedor = B.idproveedor";
	return ejecutarConsulta($sql);
}



public function tickettotal($proveedor, $fecha){
	$sql="SELECT A.*, B.total from (SELECT ccp.idcuentacorriente, ccp.idproveedor, p.nombre as proveedor, SUM(ccp.monto) as suma FROM ccproveedor ccp INNER JOIN persona p ON ccp.idproveedor = p.idpersona WHERE ccp.fechahorapago <= '$fecha' GROUP BY ccp.idproveedor ORDER BY p.nombre ASC) as A inner join (SELECT i.idproveedor, p.nombre, SUM(i.total_compra) as total FROM ingreso i INNER JOIN persona p ON i.idproveedor = p.idpersona WHERE i.fecha_hora <= '$fecha' GROUP BY i.idproveedor ORDER BY p.nombre ASC) as B on A.idproveedor = B.idproveedor WHERE A.idproveedor = '$proveedor'";
	return ejecutarConsulta($sql);
}
public function ticketcabecera($idproveedor, $fecha){
	$sql= "SELECT ccp.idcuentacorriente, ccp.idproveedor, ccp.fechahorapago, ccp.monto, u.nombre, p.nombre as proveedor, p.direccion FROM persona p INNER JOIN ccproveedor ccp ON p.idpersona = ccp.idproveedor INNER JOIN usuario u ON ccp.idusuario = u.idusuario WHERE ccp.idproveedor='$idproveedor' AND ccp.fechahorapago = '$fecha'";
	return ejecutarConsulta($sql);
}

public function ticketdetalles($idproveedor){
	$sql="SELECT a.nombre AS articulo, a.codigo, d.cantidad, d.precio_venta, d.descuento, (d.cantidad*d.precio_venta-d.descuento) AS subtotal, SUM(cc.pago) as pagos FROM detalle_venta d INNER JOIN cccliente cc ON d.idventa = cc.idventa INNER JOIN articulo a ON d.idarticulo=a.idarticulo WHERE d.idventa='$idventa'";
         return ejecutarConsulta($sql);
}
}

 ?>
