<?php 
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Categoria{


	//implementamos nuestro constructor
public function __construct(){

}

//metodo insertar regiustro
public function insertar($nombre,$descripcion){
	$nom = strtoupper($nombre);
	$sql="INSERT INTO categoria (nombre,descripcion,condicion) VALUES ('$nom','$descripcion','1')";
	return ejecutarConsulta($sql);
}

public function actualizarCosto($idcategoria, $costo, $proveedor){


	$cont =0;
	if($proveedor=="TODOS"){
	$consulta = "SELECT * FROM articulo WHERE idcategoria = '$idcategoria'";
}else{
$consulta = "SELECT p.nombre as proveedor, p.idpersona, a.idcategoria, a.idarticulo, a.descripcion, a.costo, a.precioventa1, a.precioventa2, a.precioventa3, a.precioventa4, i.idproveedor FROM persona p INNER JOIN ingreso i ON p.idpersona = i.idproveedor INNER JOIN detalle_ingreso di ON i.idingreso = di.idingreso INNER JOIN articulo a ON di.idarticulo = a.idarticulo WHERE a.idcategoria ='$idcategoria' AND i.idproveedor = '$proveedor'";
}
	$rta = ejecutarConsulta($consulta);
	while ($row = $rta->fetch_assoc()) {
		# code...
		$id = $row['idarticulo'];
		$cos = floatval($row['costo']);
		$p1 = $row['precioventa1'];
		$p2 = $row['precioventa2'];
		$p3 = $row['precioventa3'];
		$p4 = $row['precioventa4'];

		$costoact = (($cos * floatval($costo))/100) + $cos;
		if($costoact >0){
		$po1 = (($p1 - $cos) * 100)/$cos;
		$po2 = (($p2 - $cos) * 100)/$cos;
		$po3 = (($p3 - $cos) * 100)/$cos;
		$po4 = (($p4 - $cos) * 100)/$cos;

		$pre1 = ($costoact * $po1)/100 + $costoact;
		$pre2 = ($costoact * $po2)/100 + $costoact;
		$pre3 = ($costoact * $po3)/100 + $costoact;
		$pre4 = ($costoact * $po4)/100 + $costoact;


		$sql="UPDATE articulo SET costo ='$costoact', precioventa1 = '$pre1', precioventa2 = '$pre2', precioventa3 = '$pre3', precioventa4 = '$pre4' WHERE idarticulo ='$id'";
		ejecutarConsultaSimple($sql);

		$fechahoy = date('Y-m-d H:i:s');


 $sql2 ="INSERT INTO precios (idarticulo, fechahora, costo) VALUES($id, $fechahoy, $cos)";
  ejecutarConsulta($sql2);

		$cont++;
		}
	}
		return $cont;
		
}

public function actualizarPrecios($idcategoria, $por1, $por2, $por3, $por4, $proveedor){
		$cont =0;
		if($por1 == ""){ $por1 = 0;}
		if($por2 == ""){ $por2 = 0;}
		if($por3 == ""){ $por3 = 0;}
		if($por4 == ""){ $por4 = 0;}
	if($proveedor=="TODOS"){
	$consulta = "SELECT * FROM articulo WHERE idcategoria = '$idcategoria'";
}else{
$consulta = "SELECT p.nombre as proveedor, p.idpersona, a.idcategoria, a.idarticulo, a.descripcion, a.costo, a.precioventa1, a.precioventa2, a.precioventa3, a.precioventa4, i.idproveedor FROM persona p INNER JOIN ingreso i ON p.idpersona = i.idproveedor INNER JOIN detalle_ingreso di ON i.idingreso = di.idingreso INNER JOIN articulo a ON di.idarticulo = a.idarticulo WHERE a.idcategoria ='$idcategoria' AND i.idproveedor = '$proveedor'";
}
	$rta = ejecutarConsulta($consulta);
	while ($row = $rta->fetch_assoc()) {
		$id = $row['idarticulo'];
		$costo = $row['costo'];
		
		$p1 = $row['precioventa1'];
		$p2 = $row['precioventa2'];
		$p3 = $row['precioventa3'];
		$p4 = $row['precioventa4'];

		if($p1>$costo && $costo>0){
		$po1 = ((($p1 - $costo) * 100)/$costo)+$por1;
		
	}else{$po1 = (($costo * $por1)/100)+ $costo;}


		if($p2>$costo && $costo>0){
		$po2 = ((($p2 - $costo) * 100)/$costo)+$por2;
		
	}else{$po2 = (($costo * $por2)/100)+ $costo;}


	if($p3>$costo && $costo>0){
		$po3 = ((($p3 - $costo) * 100)/$costo)+$por3;
		
		}else{$po3 = (($costo * $por3)/100)+ $costo;}


		if($p4>$costo && $costo>0){
		$po4 = ((($p4 - $costo) * 100)/$costo)+$por4;
		
} else{$po4 = (($costo * $por4)/100)+ $costo;}
	

		$np1 = (($costo * $po1)/100) + $costo;
		$np2 = (($costo * $po2)/100) + $costo;
		$np3 = (($costo * $po3)/100) + $costo;
		$np4 = (($costo * $po4)/100) + $costo;

		
		$sql="UPDATE articulo SET precioventa1 = '$np1', precioventa2 = '$np2', precioventa3 = '$np3', precioventa4 = '$np4' WHERE idarticulo ='$id'";
		ejecutarConsultaSimple($sql);
		$cont++;
		
	}

	return $cont;
}


public function editar($idcategoria,$nombre,$descripcion){
	$nom = strtoupper($nombre);
	$sql="UPDATE categoria SET nombre='$nom',descripcion='$descripcion' 
	WHERE idcategoria='$idcategoria'";
	return ejecutarConsulta($sql);
}
public function desactivar($idcategoria){
	$sql="UPDATE categoria SET condicion='0' WHERE idcategoria='$idcategoria'";
	return ejecutarConsulta($sql);
}
public function activar($idcategoria){
	$sql="UPDATE categoria SET condicion='1' WHERE idcategoria='$idcategoria'";
	return ejecutarConsulta($sql);
}

//metodo para mostrar registros
public function mostrar($idcategoria){
	$sql="SELECT * FROM categoria WHERE idcategoria='$idcategoria'";
	return ejecutarConsultaSimpleFila($sql);
}



//listar registros
public function listar(){
	$sql="SELECT * FROM categoria";
	return ejecutarConsulta($sql);
}
//listar y mostrar en selct
public function select(){
	$sql="SELECT * FROM categoria WHERE condicion=1";
	return ejecutarConsulta($sql);
}
}

 ?>
