<?php 
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Venta{


	//implementamos nuestro constructor
public function __construct(){

}

//metodo insertar registro
public function insertar($idcliente,$idusuario,$tipo_comprobante,$tipo_presupuesto, $num_comprobante,$fecha_hora,$impuesto,$total_venta,$monto,$idarticulo,$cantidad, $precio_venta, $descuento, $iva){
	date_default_timezone_set('America/Argentina/Buenos_Aires');
	setlocale(LC_TIME,"es_ES");
	$fecha_hora = date("Y-m-d H:i:s"); 
	$sql="INSERT INTO venta (idcliente,idusuario,tipo_comprobante,tipo_presupuesto, num_comprobante,fecha_hora,impuesto,total_venta,estado) VALUES ('$idcliente','$idusuario','$tipo_comprobante','$tipo_presupuesto','$num_comprobante','$fecha_hora','$impuesto','$total_venta','Aceptado')";
	//return ejecutarConsulta($sql);
	 $idventanew=ejecutarConsulta_retornarID($sql);

	 	 
	 $num_elementos=0;
	 $sw=true;
	 while ($num_elementos < count($idarticulo)) {

	 	$cons = "SELECT * FROM articulo WHERE idarticulo = '$idarticulo[$num_elementos]'";
	 	$rtacosto = ejecutarConsulta($cons) or $sw=false;
	 	$costo=$rtacosto->fetch_object();
	 	$co = $costo->costo;

	 	$sql_detalle="INSERT INTO detalle_venta (idventa,idarticulo,cantidad,precio_venta, descuento, iva,costo) VALUES('$idventanew','$idarticulo[$num_elementos]','$cantidad[$num_elementos]','$precio_venta[$num_elementos]','$descuento[$num_elementos]','$iva[$num_elementos]', '$co')";

	 	ejecutarConsulta($sql_detalle) or $sw=false;

	 	$num_elementos=$num_elementos+1;
	 }

	 	$sql3="INSERT INTO cccliente (idcliente, idusuario, fechahorapago, pago, factura ) VALUES ('$idcliente','$idusuario','$fecha_hora','$monto', ' $idventanew' )";

	 	 	ejecutarConsulta($sql3) or $sw=false;
	 if($sw){return  $idventanew."&&fecha=". $fecha_hora;}
	 
}

public function anular($idventa){
	$venta = new Venta();
	$rpta = $venta->listarDetalle($idventa);
	while ($reg=$rpta->fetch_object()) {
			$sql3 = "SELECT * FROM articulo WHERE idarticulo ='".$reg->idarticulo."'";
			$stock = ejecutarConsulta($sql3);
			$st=$stock->fetch_object();
			$stockact = $reg->cantidad + $st->stock;
			$sql5 = "UPDATE articulo SET stock = '$stockact' WHERE idarticulo = '".$reg->idarticulo."'";
			ejecutarConsulta($sql5);
			$sql2 = "DELETE FROM detalle_venta WHERE idarticulo = '".$reg->idarticulo."' AND idventa = ".$idventa."";
			ejecutarConsulta($sql2);
		}
		$ven = $venta->mostrarVenta($idventa);
		$re=$ven->fetch_object();
	$sql4 = "DELETE FROM cccliente WHERE idcliente = '".$re->idcliente."' AND fechahorapago = '".$re->fecha."'";
	ejecutarConsulta($sql4);
	$sql="DELETE FROM venta WHERE idventa='$idventa'";
	ejecutarConsulta($sql);
	return ejecutarConsulta($sql4);
}


//implementar un metodopara mostrar los datos de unregistro a modificar
public function mostrar($idventa){
	$sql="SELECT v.idventa,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario, v.tipo_comprobante,v.tipo_presupuesto,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario WHERE idventa='$idventa'";
	return ejecutarConsultaSimpleFila($sql);
}
public function mostrarVenta($idventa){
	$sql="SELECT v.idventa,v.fecha_hora as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario, v.tipo_comprobante,v.tipo_presupuesto,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario WHERE idventa='$idventa'";
	return ejecutarConsulta($sql);
}

public function listarDetalle($idventa){
	$sql="SELECT dv.idventa,dv.idarticulo,a.nombre,dv.cantidad,dv.precio_venta,dv.descuento,a.stock, dv.iva, (dv.cantidad*dv.precio_venta-((dv.precio_venta * dv.cantidad) * (dv.descuento * 0.01)) + ((dv.precio_venta * dv.cantidad) * (dv.iva * 0.01))) as subtotal FROM detalle_venta dv INNER JOIN articulo a ON dv.idarticulo=a.idarticulo WHERE dv.idventa='$idventa'";
	return ejecutarConsulta($sql);
}

//listar registros
public function listar(){
	$sql="SELECT v.idventa, v.fecha_hora as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario, v.tipo_comprobante,v.tipo_presupuesto,v.num_comprobante,v.total_venta,v.impuesto,v.estado FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario ORDER BY v.idventa DESC";
	return ejecutarConsulta($sql);
}


public function ventacabecera($idventa){
	$sql= "SELECT v.idventa, v.idcliente, v.fecha_hora, p.nombre AS cliente, p.direccion, p.tipo_documento, p.num_documento, p.email, p.telefono, v.idusuario, u.nombre AS usuario, v.tipo_comprobante, v.num_comprobante, DATE(v.fecha_hora) AS fecha, v.impuesto, v.total_venta FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario WHERE v.idventa='$idventa'";
	return ejecutarConsulta($sql);
}

public function pagos($idcliente, $fecha){
	$sql = "SELECT * FROM cccliente WHERE fechahorapago ='$fecha' AND idcliente = '$idcliente'";
	return ejecutarConsulta($sql);
}

public function ventadetalles($idventa){
	$sql="SELECT a.nombre AS articulo, a.codigo, a.idarticulo, d.cantidad, d.precio_venta, d.descuento, d.iva FROM detalle_venta d  INNER JOIN articulo a ON d.idarticulo=a.idarticulo WHERE d.idventa='$idventa'";
         return ejecutarConsulta($sql);
}


public function tickettotal($cliente, $fecha){
	$sql="SELECT A.*, B.total from (SELECT cc.idcccliente, cc.idcliente, p.nombre as cliente, SUM(cc.pago) as suma FROM cccliente cc INNER JOIN persona p ON cc.idcliente = p.idpersona WHERE cc.fechahorapago <= '$fecha' GROUP BY cc.idcliente ORDER BY p.nombre ASC) as A inner join (SELECT v.idcliente, p.nombre, SUM(v.total_venta) as total FROM venta v INNER JOIN persona p ON v.idcliente = p.idpersona WHERE v.fecha_hora <= '$fecha' GROUP BY v.idcliente ORDER BY p.nombre ASC) as B on A.idcliente = B.idcliente WHERE A.idcliente = '$cliente'";
	return ejecutarConsulta($sql);
}
public function totalesventa($cliente, $fecha){
	$sql="SELECT A.pagos, B.total from (SELECT idcliente, SUM(pago) as pagos FROM cccliente WHERE idcliente = '$cliente' AND fechahorapago <='$fecha') as A left join (SELECT idcliente, SUM(total_venta) as total FROM venta WHERE idcliente ='$cliente' AND fecha_hora <= '$fecha') as B on A.idcliente = B.idcliente";
	return ejecutarConsulta($sql);
}

public function totalesVtas($cliente, $fecha){
	$sql="SELECT SUM(total_venta) as total FROM venta WHERE idcliente ='$cliente' AND fecha_hora <= '$fecha'";
	return ejecutarConsulta($sql);
}

}

 ?>
