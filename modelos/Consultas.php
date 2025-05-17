<?php 
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Consultas{


	//implementamos nuestro constructor
public function __construct(){

}

//listar registros
public function comprasfecha($fecha_inicio,$fecha_fin){
	$sql="SELECT DATE(i.fecha_hora) as fecha, u.nombre as usuario, p.nombre as proveedor, i.tipo_comprobante, i.serie_comprobante, i.num_comprobante, i.total_compra,i.impuesto,i.estado FROM ingreso i INNER JOIN persona p ON i.idproveedor=p.idpersona INNER JOIN usuario u ON i.idusuario=u.idusuario WHERE DATE(i.fecha_hora)>='$fecha_inicio' AND DATE(i.fecha_hora)<='$fecha_fin'";
	return ejecutarConsulta($sql);
}


public function productosfechacat($fecha_inicio,$fecha_fin,$idcategoria){
	$sql="SELECT dv.idventa, dv.idarticulo, a.nombre, a.descripcion, SUM(dv.cantidad) AS cantidad, SUM(dv.cantidad * dv.precio_venta) as total FROM `detalle_venta` dv INNER JOIN venta v ON dv.idventa = v.idventa INNER JOIN articulo a ON dv.idarticulo = a.idarticulo WHERE a.idcategoria = '$idcategoria' AND DATE(v.fecha_hora)>='$fecha_inicio' AND DATE(v.fecha_hora)<='$fecha_fin' GROUP BY dv.idarticulo ORDER BY cantidad DESC";
	return ejecutarConsulta($sql);
}



public function productosfecha($fecha_inicio,$fecha_fin){
	$sql="SELECT dv.idventa, dv.idarticulo, a.nombre, a.descripcion, SUM(dv.cantidad) AS cantidad, SUM(dv.cantidad * dv.precio_venta) as total FROM `detalle_venta` dv INNER JOIN venta v ON dv.idventa = v.idventa INNER JOIN articulo a ON dv.idarticulo = a.idarticulo WHERE DATE(v.fecha_hora)>='$fecha_inicio' AND DATE(v.fecha_hora)<='$fecha_fin' GROUP BY dv.idarticulo ORDER BY dv.cantidad DESC";
	return ejecutarConsulta($sql);
}


public function productosUfecha($fecha_inicio,$fecha_fin){
	$sql="SELECT a.idarticulo, a.nombre, a.descripcion, v.fecha_hora, SUM(dv.cantidad) as cant, SUM((dv.cantidad * dv.precio_venta - (dv.cantidad * dv.precio_venta)*(dv.descuento*0.01)- (dv.cantidad* dv.costo))) as utilidad FROM detalle_venta dv INNER JOIN articulo a ON dv.idarticulo = a.idarticulo INNER JOIN venta v ON dv.idventa = v.idventa WHERE DATE(v.fecha_hora) BETWEEN '$fecha_inicio' AND '$fecha_fin' GROUP BY dv.idarticulo";
	return ejecutarConsulta($sql);
}


public function productosUfechacategoria($fecha_inicio,$fecha_fin){
	$sql="SELECT a.idcategoria, c.nombre, v.fecha_hora, SUM(dv.cantidad) as cant, SUM((dv.cantidad * dv.precio_venta - (dv.cantidad * dv.precio_venta)*(dv.descuento*0.01)- (dv.cantidad* dv.costo))) as utilidad FROM detalle_venta dv INNER JOIN articulo a ON dv.idarticulo = a.idarticulo INNER JOIN categoria c ON a.idcategoria = c.idcategoria INNER JOIN venta v ON dv.idventa = v.idventa WHERE DATE(v.fecha_hora) BETWEEN '$fecha_inicio' AND '$fecha_fin' GROUP BY c.idcategoria ORDER BY cant DESC";
	return ejecutarConsulta($sql);
}

public function productosUfechacat($fecha_inicio,$fecha_fin, $categoria){
	$sql="SELECT a.idarticulo, a.nombre, a.descripcion, v.fecha_hora, SUM(dv.cantidad) as cant, SUM((dv.cantidad * dv.precio_venta - (dv.cantidad * dv.precio_venta)*(dv.descuento*0.01)- (dv.cantidad* dv.costo))) as utilidad FROM detalle_venta dv INNER JOIN articulo a ON dv.idarticulo = a.idarticulo INNER JOIN venta v ON dv.idventa = v.idventa WHERE a.idcategoria = '$categoria' AND DATE(v.fecha_hora) BETWEEN '$fecha_inicio' AND '$fecha_fin' GROUP BY dv.idarticulo" ;
	return ejecutarConsulta($sql);
}

public function totalcomprahoy(){
	date_default_timezone_set('America/Argentina/Buenos_Aires');
	setlocale(LC_TIME,"es_ES");
	$fecha = date("Y-m-d"); 
	$sql="SELECT IFNULL(SUM(total_compra),0) as total_compra FROM ingreso WHERE DATE(fecha_hora)='$fecha'";
	return ejecutarConsulta($sql);
}

public function productosStock(){
	$sql = "SELECT * FROM articulo WHERE stock >0";
	return ejecutarConsulta($sql);
}

public function productosStockC($idcategoria){
	$sql = "SELECT * FROM articulo WHERE stock >0 AND idcategoria = '$idcategoria'";
	return ejecutarConsulta($sql);
}

public function totalventahoy(){
	date_default_timezone_set('America/Argentina/Buenos_Aires');
	setlocale(LC_TIME,"es_ES");
	$fecha = date("Y-m-d"); 
	$sql="SELECT IFNULL(SUM(total_venta),0) as total_venta FROM venta WHERE DATE(fecha_hora)='$fecha'";
	return ejecutarConsulta($sql);
}

public function comprasultimos_10dias(){
	$sql=" SELECT CONCAT(DAY(fecha_hora),'-',MONTH(fecha_hora)) AS fecha, SUM(total_compra) AS total FROM ingreso GROUP BY fecha_hora ORDER BY fecha_hora DESC LIMIT 0,10";
	return ejecutarConsulta($sql);
}

public function ventasultimos_12meses(){
	$sql=" SELECT DATE_FORMAT(fecha_hora,'%M') AS fecha, SUM(total_venta) AS total FROM venta GROUP BY MONTH(fecha_hora) ORDER BY fecha_hora DESC LIMIT 0,12";
	return ejecutarConsulta($sql);
}


}

 ?>
