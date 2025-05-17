<?php 
//incluir la conexion de base de datos
require "../config/Conexion.php";
require_once "../modelos/Articulo.php";
class Ingreso{


	//implementamos nuestro constructor
public function __construct(){

}

//metodo insertar registro
public function insertar($idproveedor,$idusuario,$tipo_comprobante,$serie_comprobante,$num_comprobante,$fecha_hora,$total,$total_compra,$monto, $idarticulo,$cantidad,$precio_compra){
	//$impuestos = floatval($impuesto + $otros + $varios);
	date_default_timezone_set('America/Argentina/Buenos_Aires');
	setlocale(LC_TIME,"es_ES");
	$fecha_hora = date("Y-m-d H:i:s"); 
	$sql="INSERT INTO ingreso (idproveedor,idusuario,tipo_comprobante,serie_comprobante,num_comprobante,fecha_hora,impuesto,total_compra,estado) VALUES ('$idproveedor','$idusuario','$tipo_comprobante','$serie_comprobante','$num_comprobante','$fecha_hora','$total','$total_compra','Aceptado')";
	//return ejecutarConsulta($sql);
	 $idingresonew=ejecutarConsulta_retornarID($sql);

	 	$sql3="INSERT INTO ccproveedor (idproveedor,fechahorapago,monto, idusuario) VALUES ('$idproveedor','$fecha_hora','$monto', '$idusuario')";
	//return ejecutarConsulta($sql);
	 ejecutarConsulta($sql3);

	

	 $num_elementos=0;
	 $sw=true;
	 while ($num_elementos < count($idarticulo)) {

	 	$sql_detalle="INSERT INTO detalle_ingreso (idingreso,idarticulo,cantidad,precio_compra) VALUES('$idingresonew','$idarticulo[$num_elementos]','$cantidad[$num_elementos]','$precio_compra[$num_elementos]')";

	 	ejecutarConsulta($sql_detalle) or $sw=false;

		$fechahoy = date('Y-m-d H:i:s');

$costo = $precio_compra[$num_elementos];
$idart = $idarticulo[$num_elementos];
 $sql_act ="INSERT INTO precios (idarticulo, fechahora, costo) VALUES('$idart', '$fechahoy', '$costo')";
  ejecutarConsulta($sql_act)  or $sw=false;
  	


	 $num_elementos=$num_elementos+1;
	 }
	 return $sw;
}

public function anular($idingreso){
	$sql="UPDATE ingreso SET estado='Anulado' WHERE idingreso='$idingreso'";
	return ejecutarConsulta($sql);
}


//metodo para mostrar registros
public function mostrar($idingreso){
	$sql="SELECT i.idingreso,DATE(i.fecha_hora) as fecha,i.idproveedor,p.nombre as proveedor,u.idusuario,u.nombre as usuario, i.tipo_comprobante,i.serie_comprobante,i.num_comprobante,i.total_compra,i.impuesto,i.estado FROM ingreso i INNER JOIN persona p ON i.idproveedor=p.idpersona INNER JOIN usuario u ON i.idusuario=u.idusuario WHERE idingreso='$idingreso'";
	return ejecutarConsultaSimpleFila($sql);
}

public function listarDetalle($idingreso){
	$sql="SELECT di.idingreso,di.idarticulo,a.nombre,di.cantidad,di.precio_compra,di.precio_venta, i.impuesto, i.total_compra FROM detalle_ingreso di INNER JOIN ingreso i ON di.idingreso = i.idingreso INNER JOIN articulo a ON di.idarticulo=a.idarticulo WHERE di.idingreso='$idingreso'";
	return ejecutarConsulta($sql);
}

//listar registros
public function listar(){
	$sql="SELECT i.idingreso,DATE(i.fecha_hora) as fecha,i.idproveedor, i.impuesto, p.nombre as proveedor,u.idusuario,u.nombre as usuario, i.tipo_comprobante,i.serie_comprobante,i.num_comprobante,i.total_compra,i.impuesto,i.estado FROM ingreso i INNER JOIN persona p ON i.idproveedor=p.idpersona INNER JOIN usuario u ON i.idusuario=u.idusuario ORDER BY i.idingreso DESC";
	return ejecutarConsulta($sql);
}

}

 ?>
