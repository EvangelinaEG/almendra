<?php 
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Articulo{


	//implementamos nuestro constructor
public function __construct(){

}

//metodo insertar regiustro
public function insertar($idcategoria,$nombre,$stock,$puntopedido,$descripcion, $proveedor){
	$nom = strtoupper($nombre);
	$sql="INSERT INTO articulo (idcategoria,nombre,stock, puntopedido,descripcion,condicion,idproveedor)
	 VALUES ('$idcategoria','$nom','$stock', '$puntopedido','$descripcion','1','$proveedor')";
	return ejecutarConsulta($sql);
 
}


public function agregarActivo($nombre,$codigo,$idcategoria){
		$nom = strtoupper($nombre);
	$sql="INSERT INTO articulo (idcategoria,codigo,nombre,stock, puntopedido,descripcion,condicion, idproveedor)
	 VALUES ('$idcategoria','$codigo','$nom', '0', '0','','1')";
	return ejecutarConsulta($sql);
}


public function editar($idarticulo,$idcategoria,$nombre,$stock,$puntopedido,$descripcion, $proveedor){
	$nom = strtoupper($nombre);
	$sql="UPDATE articulo SET idcategoria='$idcategoria',nombre='$nom',stock='$stock', puntopedido='$puntopedido',descripcion='$descripcion' , idproveedor='$proveedor' 
	WHERE idarticulo='$idarticulo'";
	return ejecutarConsulta($sql);
}

public function actualizarCosto($idarticulo, $costo){
 $fechahoy = date('Y-m-d H:i:s');


 $sql ="INSERT INTO precios (idarticulo, fechahora, costo) VALUES($idarticulo, $fechahoy, $costo)";
  ejecutarConsulta($sql);

	$consulta = "SELECT * FROM articulo WHERE idarticulo = '$idarticulo'";
	$row = ejecutarConsultaSimpleFila($consulta);
	$id = $row['idarticulo'];
		$cos = floatval($row['costo']);
		$p1 = $row['precioventa1'];
		$p2 = $row['precioventa2'];
		$p3 = $row['precioventa3'];
		$p4 = $row['precioventa4'];

		if($p1>$cos){
		$po1 = (($p1 - $cos) * 100)/$cos;
		$pre1 = ($costo * $po1)/100 + $costo;
	}else{$pre1 = $p1;}
	if($p2>$cos){
		$po2 = (($p2 - $cos) * 100)/$cos;
		$pre2 = ($costo * $po2)/100 + $costo;
		}else{$pre2 = $p2;}
		if($p3>$cos){
		$po3 = (($p3 - $cos) * 100)/$cos;
		$pre3 = ($costo * $po3)/100 + $costo;
		}else{$pre3 = $p3;}
		if($p4>$cos){
		$po4 = (($p4 - $cos) * 100)/$cos;
		$pre4 = ($costo * $po4)/100 + $costo;
		}else{$pre4 = $p4;}
		
		
		
		


		$sql="UPDATE articulo SET costo ='$costo', precioventa1 = '$pre1', precioventa2 = '$pre2', precioventa3 = '$pre3', precioventa4 = '$pre4' WHERE idarticulo ='$id'";
		
			
	return ejecutarConsulta($sql);
}

public function actualizarPrecios($idarticulo,$p1,$p2,$p3,$p4, $codigo){
	$sql="UPDATE articulo SET precioventa1='$p1',precioventa2='$p2', precioventa3='$p3', precioventa4='$p4', codigo='$codigo' 
	WHERE idarticulo='$idarticulo'";
	return ejecutarConsulta($sql);
}

public function desactivar($idarticulo){
	$sql="UPDATE articulo SET condicion='0' WHERE idarticulo='$idarticulo'";
	return ejecutarConsulta($sql);
}
public function activar($idarticulo){
	$sql="UPDATE articulo SET condicion='1' WHERE idarticulo='$idarticulo'";
	return ejecutarConsulta($sql);
}

//metodo para mostrar registros
public function mostrar($idarticulo){
	$sql="SELECT * FROM articulo WHERE idarticulo='$idarticulo'";
	return ejecutarConsultaSimpleFila($sql);
}



//listar registros 
public function listar(){
	$sql="SELECT a.idarticulo,a.idcategoria,c.nombre as categoria,a.codigo, a.nombre, a.stock, a.puntopedido , a.descripcion,a.precioventa1,  a.precioventa2,  a.precioventa3,  a.precioventa4, a.costo,a.condicion FROM articulo a INNER JOIN categoria c ON a.idcategoria=c.idcategoria";
	return ejecutarConsulta($sql);
}
public function listarProv($prov){
	$sql="SELECT a.idarticulo,a.idcategoria,c.nombre as categoria,a.codigo, a.nombre, a.stock, a.puntopedido , a.descripcion,a.precioventa1, a.precioventa2, a.precioventa3, a.precioventa4, a.costo,a.condicion, p.nombre as nombreprov, p.telefono, a.idproveedor FROM articulo a INNER JOIN categoria c ON a.idcategoria=c.idcategoria INNER JOIN persona p ON a.idproveedor = a.idproveedor WHERE a.stock <= a.puntopedido AND p.nombre = '$prov' GROUP BY a.codigo";
	return ejecutarConsulta($sql);
}
/*public function listarProv($prov){
	$sql="SELECT a.idarticulo,a.idcategoria,c.nombre as categoria,a.codigo, a.nombre, a.stock, a.puntopedido , a.descripcion,a.precioventa1, a.precioventa2, a.precioventa3, a.precioventa4, a.costo,a.condicion, prov.nombreprov, prov.telefono, prov.fecha_hora FROM articulo a INNER JOIN categoria c ON a.idcategoria=c.idcategoria LEFT JOIN (SELECT p.nombre as nombreprov, p.telefono, i.idproveedor, p.idpersona, di.idarticulo, i.fecha_hora, i.idingreso, di.iddetalle_ingreso FROM ingreso i INNER JOIN persona p ON i.idproveedor = p.idpersona INNER JOIN detalle_ingreso di ON di.idingreso = i.idingreso) as prov ON a.idarticulo = prov.idarticulo WHERE a.stock <= a.puntopedido AND prov.nombreprov = '$prov' GROUP BY a.codigo";
	return ejecutarConsulta($sql);
}*/
//filtrar busqueda
public function filtrar($valor){
	$sql="SELECT a.idarticulo,a.idcategoria,c.nombre as categoria,a.codigo, a.nombre, a.stock, a.puntopedido , a.descripcion,a.precioventa1,  a.precioventa2,  a.precioventa3,  a.precioventa4, a.costo, a.condicion FROM articulo a INNER JOIN categoria c ON a.idcategoria=c.idcategoria WHERE a.nombre LIKE '%$valor%' OR a.codigo LIKE '%$valor%'";
	return ejecutarConsulta($sql);
}
public function filtrarVenta($valor){
	$sql="SELECT a.idarticulo,a.idcategoria,c.nombre as categoria,a.codigo, a.nombre, a.stock, a.puntopedido , a.descripcion,a.precioventa1,  a.precioventa2,  a.precioventa3,  a.precioventa4, a.costo, a.condicion FROM articulo a INNER JOIN categoria c ON a.idcategoria=c.idcategoria WHERE a.stock > 0 AND a.nombre LIKE '%$valor%'  OR a.codigo LIKE '%$valor%'";
	return ejecutarConsulta($sql);
}

public function listarStock(){
	$sql="SELECT a.idarticulo,a.idcategoria,c.nombre as categoria,a.codigo, a.nombre, a.stock, a.puntopedido , a.descripcion,a.precioventa1, a.precioventa2, a.precioventa3, a.precioventa4, a.costo,a.condicion, p.nombre as nombreprov, p.telefono FROM articulo a INNER JOIN categoria c ON a.idcategoria=c.idcategoria INNER JOIN persona p ON a.idproveedor = p.idpersona  WHERE a.stock <= a.puntopedido GROUP BY a.idarticulo";
	return ejecutarConsulta($sql);
}

//listar registros sin stock
/*public function listarStock(){
	$sql="SELECT A.*, B.* FROM (SELECT a.idarticulo,a.idcategoria,c.nombre as categoria,a.codigo, a.nombre, a.stock, a.puntopedido , a.descripcion,a.precioventa1, a.precioventa2, a.precioventa3, a.precioventa4, a.costo,a.condicion FROM articulo a INNER JOIN categoria c ON a.idcategoria=c.idcategoria WHERE A.stock <= A.puntopedido) as A LEFT JOIN (SELECT p.nombre as nombreprov, p.telefono, i.idproveedor, p.idpersona, di.idarticulo, i.fecha_hora, i.idingreso, di.iddetalle_ingreso FROM ingreso i INNER JOIN persona p ON i.idproveedor = p.idpersona INNER JOIN detalle_ingreso di ON di.idingreso = i.idingreso) as B ON A.idarticulo = B.idarticulo GROUP BY A.idarticulo";
	return ejecutarConsulta($sql);
}
*/
//listar registros activos
public function listarActivos(){
	$sql="SELECT a.idarticulo,a.idcategoria,c.nombre as categoria,a.codigo, a.nombre,a.stock,a.puntopedido,a.descripcion,a.costo,a.condicion FROM articulo a INNER JOIN categoria c ON a.idcategoria=c.idcategoria WHERE a.condicion='1'";
	return ejecutarConsulta($sql);
}
//listar registros activos
public function buscarActivo($codigo){
$sql="SELECT * FROM articulo WHERE codigo = '$codigo'";
	return ejecutarConsulta($sql);


}
public function buscaridActivo($id){
$sql="SELECT A.*, B.* FROM (SELECT * FROM articulo WHERE idarticulo = '$id') AS A LEFT JOIN (SELECT a.idarticulo as id, di.precio_compra as precompra FROM articulo a LEFT JOIN detalle_ingreso di ON a.idarticulo = di.idarticulo WHERE a.condicion= '1' AND di.iddetalle_ingreso = (SELECT MAX(iddetalle_ingreso) FROM detalle_ingreso WHERE idarticulo = '$id')) AS B ON A.idarticulo = B.id";
	return ejecutarConsultaSimpleFila($sql);
}



public function buscarActivoVenta($codigo){
$sql="SELECT * FROM articulo WHERE codigo='$codigo' AND condicion = '1' AND stock >0 AND (precioventa1>0 OR precioventa2>0 OR precioventa3>0 OR precioventa4>0)";
	return ejecutarConsultaSimpleFila($sql);
}
public function buscaridActivoVenta($codigo){
$sql="SELECT * FROM articulo WHERE idarticulo='$codigo' AND condicion = '1' AND stock >0 AND (precioventa1>0 OR precioventa2>0 OR precioventa3>0 OR precioventa4>0)";
	return ejecutarConsultaSimpleFila($sql);
}

//listar registros activos
public function buscarActivoS($codigo){
$sql="SELECT * FROM articulo WHERE codigo='$codigo' AND condicion = '1' AND stock > 0";
	return ejecutarConsultaSimpleFila($sql);
}
//implementar un metodo para listar los activos, su ultimo precio y el stock(vamos a unir con el ultimo registro de la tabla detalle_ingreso)
public function listarActivosVenta(){
	$sql="SELECT a.idarticulo,a.idcategoria,c.nombre as categoria,a.codigo, a.nombre,a.stock,a.puntopedido, a.descripcion,a.costo,a.condicion FROM articulo a INNER JOIN categoria c ON a.idcategoria=c.idcategoria WHERE a.condicion='1' AND a.stock > a.puntopedido";
	return ejecutarConsulta($sql);
}
}
 ?>
