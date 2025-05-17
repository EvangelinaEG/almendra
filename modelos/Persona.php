<?php 
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Persona{


	//implementamos nuestro constructor
public function __construct(){

}

//metodo insertar regiustro
public function insertar($tipo_persona,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email){
	if($tipo_persona == "Proveedor" || $tipo_persona == "Cliente"){
		$nom = strtoupper($nombre);
	}else{$nom = $nombre;}
	$sql="INSERT INTO persona (tipo_persona,nombre,tipo_documento,num_documento,direccion,telefono,email) VALUES ('$tipo_persona','$nom','$tipo_documento','$num_documento','$direccion','$telefono','$email')";
	return ejecutarConsulta($sql);
}


public function editar($idpersona,$tipo_persona,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email){
	if($tipo_persona == "Proveedor" || $tipo_persona == "Cliente"){
		$nom = strtoupper($nombre);
	}else{$nom = $nombre;}
	$sql="UPDATE persona SET tipo_persona='$tipo_persona', nombre='$nom',tipo_documento='$tipo_documento',num_documento='$num_documento',direccion='$direccion',telefono='$telefono',email='$email' 
	WHERE idpersona='$idpersona'";
	return ejecutarConsulta($sql);
}
//funcion para eliminar datos
public function eliminar($idpersona){
	$sql="DELETE FROM persona WHERE idpersona='$idpersona'";
	return ejecutarConsulta($sql);
}

//metodo para mostrar registros
public function mostrar($idpersona){
	$sql="SELECT * FROM persona WHERE idpersona='$idpersona'";
	return ejecutarConsultaSimpleFila($sql);
}

//listar registros
public function listarp(){
	$sql="SELECT * FROM persona WHERE tipo_persona='Proveedor'";
	return ejecutarConsulta($sql);
}
public function listarcp($cat){
	$sql="SELECT p.nombre as proveedor, p.idpersona, a.idcategoria, a.idarticulo, a.descripcion, a.idproveedor FROM persona p INNER JOIN articulo a ON p.idpersona = a.idproveedor WHERE a.idcategoria ='$cat' GROUP BY p.idpersona ";
	return ejecutarConsulta($sql);
}


public function listarcpp($prov){
	$sql="SELECT p.nombre as proveedor, p.idpersona, cat.nombre as categoria, a.idcategoria, a.idarticulo, a.descripcion, i.idproveedor FROM persona p INNER JOIN ingreso i ON p.idpersona = i.idproveedor INNER JOIN detalle_ingreso di ON i.idingreso = di.idingreso INNER JOIN articulo a ON di.idarticulo = a.idarticulo INNER JOIN categoria cat ON a.idcategoria = cat.idcategoria WHERE p.idpersona ='$prov' GROUP BY a.idcategoria";
	return ejecutarConsulta($sql);
}



public function listarc(){
	$sql="SELECT * FROM persona WHERE tipo_persona='Cliente'";
	return ejecutarConsulta($sql);
}


public function actualizarCosto($idproveedor, $costo, $categoria){
	$cont =0;
	if($categoria == "TODOS"){
	$consulta = "SELECT p.nombre as proveedor, p.idpersona, a.idcategoria, a.idarticulo, a.descripcion, a.costo, a.precioventa1, a.precioventa2, a.precioventa3, a.precioventa4, a.idproveedor FROM persona p  INNER JOIN articulo a ON p.idpersona = a.idproveedor WHERE a.idproveedor = '$idproveedor'";
}else{
	$consulta = "SELECT p.nombre as proveedor, p.idpersona, a.idcategoria, a.idarticulo, a.descripcion, a.costo, a.precioventa1, a.precioventa2, a.precioventa3, a.precioventa4, a.idproveedor FROM persona p INNER JOIN articulo a ON p.idpersona = a.idproveedor WHERE a.idcategoria ='$categoria' AND a.idproveedor = '$idproveedor'";
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

		$cont = $cont +1;
		}
	}
		return $cont;
		
}

public function actualizarPrecios($idproveedor, $por1, $por2, $por3, $por4, $categoria){
		$cont =0;
		if($por1 == ""){ $por1 =0;}
		if($por2 == ""){ $por2 =0;}
		if($por3 == ""){ $por3 =0;}
		if($por4 == ""){ $por4 =0;}
	if($categoria == "TODOS"){
	$consulta = "SELECT p.nombre as proveedor, p.idpersona, a.idcategoria, a.idarticulo, a.descripcion, a.costo, a.precioventa1, a.precioventa2, a.precioventa3, a.precioventa4, a.idproveedor FROM persona p  INNER JOIN articulo a ON p.idpersona = a.idproveedor WHERE a.idproveedor = '$idproveedor'";
}else{
	$consulta = "SELECT p.nombre as proveedor, p.idpersona, a.idcategoria, a.idarticulo, a.descripcion, a.costo, a.precioventa1, a.precioventa2, a.precioventa3, a.precioventa4, a.idproveedor FROM persona p INNER JOIN articulo a ON p.idpersona = a.idproveedor WHERE a.idcategoria ='$categoria' AND a.idproveedor = '$idproveedor'";
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



}

 ?>
