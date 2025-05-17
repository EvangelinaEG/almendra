<?php 
require_once "../modelos/Persona.php";

$persona=new Persona();

$idpersona=isset($_POST["idpersona"])? limpiarCadena($_POST["idpersona"]):"";
$tipo_persona=isset($_POST["tipo_persona"])? limpiarCadena($_POST["tipo_persona"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$tipo_documento=isset($_POST["tipo_documento"])? limpiarCadena($_POST["tipo_documento"]):"";
$num_documento=isset($_POST["num_documento"])? limpiarCadena($_POST["num_documento"]):"";
$direccion=isset($_POST["direccion"])? limpiarCadena($_POST["direccion"]):"";
$telefono=isset($_POST["telefono"])? limpiarCadena($_POST["telefono"]):"";
$email=isset($_POST["email"])? limpiarCadena($_POST["email"]):"";
$idproveedoru=isset($_POST["idproveedoru"])? limpiarCadena($_POST["idproveedoru"]):"";
$costou=isset($_POST["costou"])? limpiarCadena($_POST["costou"]):"";
$precioc1=isset($_POST["precioc1"])? limpiarCadena($_POST["precioc1"]):"";

$precioc2=isset($_POST["precioc2"])? limpiarCadena($_POST["precioc2"]):"";

$precioc3=isset($_POST["precioc3"])? limpiarCadena($_POST["precioc3"]):"";

$precioc4=isset($_POST["precioc4"])? limpiarCadena($_POST["precioc4"]):"";



switch ($_GET["op"]) {
	case 'guardaryeditar':
	if (empty($idpersona)) {
		$rspta=$persona->insertar($tipo_persona,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email);
		echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar los datos";
	}else{
         $rspta=$persona->editar($idpersona,$tipo_persona,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email);
		echo $rspta ? "Datos actualizados correctamente" : "No se pudo actualizar los datos";
	}
		break;
	

	case 'eliminar':
		$rspta=$persona->eliminar($idpersona);
		echo $rspta ? "Datos eliminados correctamente" : "No se pudo eliminar los datos";
		break;
	
	case 'mostrar':
		$rspta=$persona->mostrar($idpersona);
		echo json_encode($rspta);
		break;
	case 'actCostoProv':
		$cate = $_POST['idcategoria'];
		$rspta = $persona->actualizarCosto($idproveedoru, $costou, $cate);
		echo $rspta ? "Se actualizaron " .$rspta ." registros con el nuevo costo" : "No se pudo actualizar los costos";
		break;
	case 'actPreciosProv':
	$cate = $_POST['idcategoria'];
		$rspta = $persona->actualizarPrecios($idproveedoru, $precioc1, $precioc2, $precioc3, $precioc4, $cate);
		echo $rspta ? "Se actualizaron " .$rspta ." registros con los nuevos precios" : "No se pudo actualizar los precios";
		break;
    case 'listarp':
		$rspta=$persona->listarp();
		$data=Array();

		while ($reg=$rspta->fetch_object()) {
			$data[]=array(
            "0"=>'<button class="btn btn-warning btn-xs" onclick="mostrar('.$reg->idpersona.')"><i class="fa fa-pencil"></i></button>'.' '.'<button title="Actualizar Precios" class="btn btn-warning btn-xs" onclick="mostrarActP('.$reg->idpersona.')"><i class="fa fa-edit"></i></button>'.' '.'<button class="btn btn-danger btn-xs" onclick="eliminar('.$reg->idpersona.')"><i class="fa fa-trash"></i></button>',
            "1"=>$reg->nombre,
            "2"=>$reg->tipo_documento,
            "3"=>$reg->num_documento,
            "4"=>$reg->telefono,
            "5"=>$reg->email
              );
		}
		$results=array(
             "sEcho"=>1,//info para datatables
             "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
             "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
             "aaData"=>$data); 
		echo json_encode($results);
		break;

		case 'selectCategoria':
						
			$proveedor = $_GET['p'];
			$rspta = $persona->listarcpp($proveedor);

			while ($reg = $rspta->fetch_object()) {
				echo "<option value='".$reg->idcategoria."''>".$reg->categoria."</option>";
			}
			break;

		  case 'listarc':
		$rspta=$persona->listarc();
		$data=Array();

		while ($reg=$rspta->fetch_object()) {
			$data[]=array(
            "0"=>'<button class="btn btn-warning btn-xs" onclick="mostrar('.$reg->idpersona.')"><i class="fa fa-pencil"></i></button>'.' '.'<button class="btn btn-danger btn-xs" onclick="eliminar('.$reg->idpersona.')"><i class="fa fa-trash"></i></button>',
            "1"=>$reg->nombre,
            "2"=>$reg->tipo_documento,
            "3"=>$reg->num_documento,
            "4"=>$reg->telefono,
            "5"=>$reg->email
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