<?php 
require_once "../modelos/Categoria.php";

$categoria=new Categoria();

$idcategoria=isset($_POST["idcategoria"])? limpiarCadena($_POST["idcategoria"]):"";
$idcategoriau=isset($_POST["idcategoriau"])? limpiarCadena($_POST["idcategoriau"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
$costou=isset($_POST["costou"])? limpiarCadena($_POST["costou"]):"";
$precioc1=isset($_POST["precioc1"])? limpiarCadena($_POST["precioc1"]):"";
$precioc2=isset($_POST["precioc2"])? limpiarCadena($_POST["precioc2"]):"";
$precioc3=isset($_POST["precioc3"])? limpiarCadena($_POST["precioc3"]):"";
$precioc4=isset($_POST["precioc4"])? limpiarCadena($_POST["precioc4"]):"";
//$proveedor=isset($_POST["prov"])? limpiarCadena($_POST["prov"]):"";


switch ($_GET["op"]) {
	case 'guardaryeditar':
	if (empty($idcategoria)) {
		$rspta=$categoria->insertar($nombre,$descripcion);
		echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar los datos";
	}else{
         $rspta=$categoria->editar($idcategoria,$nombre,$descripcion);
		echo $rspta ? "Datos actualizados correctamente" : "No se pudo actualizar los datos";
	}
		break;
	case 'actCostoCat':
		$proveedor = $_POST['prov'];
		$rspta = $categoria->actualizarCosto($idcategoriau, $costou, $proveedor);
		echo $rspta ? "Se actualizaron " .$rspta ." registros con el nuevo costo" : "No se pudo actualizar los costos";
		break;
	case 'actPreciosCat':
		$proveedor = $_POST['prov'];
		$rspta = $categoria->actualizarPrecios($idcategoriau, $precioc1, $precioc2, $precioc3, $precioc4, $proveedor);
		echo $rspta ? "Se actualizaron " .$rspta ." registros con los nuevos precios" : "No se pudo actualizar los precios";
		break;

	case 'desactivar':
		$rspta=$categoria->desactivar($idcategoria);
		echo $rspta ? "Datos desactivados correctamente" : "No se pudo desactivar los datos";
		break;
	case 'activar':
		$rspta=$categoria->activar($idcategoria);
		echo $rspta ? "Datos activados correctamente" : "No se pudo activar los datos";
		break;
	
	case 'mostrar':
		$rspta=$categoria->mostrar($idcategoria);
		echo json_encode($rspta);
		break;

	case 'selectProveedor':
			require_once "../modelos/Persona.php";
			$persona = new Persona();
			$categoria = $_GET['c'];
			$rspta = $persona->listarcp($categoria);

			while ($reg = $rspta->fetch_object()) {
				echo '<option value='.$reg->idproveedor.'>'.$reg->proveedor.'</option>';
			}
			break;
    case 'listar':
		$rspta=$categoria->listar();
		$data=Array();

		while ($reg=$rspta->fetch_object()) {
			$data[]=array(
            "0"=>($reg->condicion)?'<button title="Editar" class="btn btn-warning btn-xs" onclick="mostrar('.$reg->idcategoria.')"><i class="fa fa-pencil"></i></button>'.' '.'<button title="Actualizar Precios" class="btn btn-warning btn-xs" onclick="mostrarActP('.$reg->idcategoria.')"><i class="fa fa-edit"></i></button>'.' '.'<button class="btn btn-danger btn-xs" onclick="desactivar('.$reg->idcategoria.')"><i class="fa fa-close"></i></button>':'<button title="Desactivar" class="btn btn-warning btn-xs" onclick="mostrar('.$reg->idcategoria.')"><i class="fa fa-pencil"></i></button>'.' '.'<button class="btn btn-primary btn-xs" onclick="activar('.$reg->idcategoria.')"><i class="fa fa-check"></i></button>',
            "1"=>$reg->nombre,
            "2"=>$reg->descripcion,
            "3"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':'<span class="label bg-red">Desactivado</span>'
              );
		}
		$results=array(
             "sEcho"=>1,//info para datatables
             "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
             "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
             "aaData"=>$data); 
		echo json_encode($results);
		break;
}
 ?>