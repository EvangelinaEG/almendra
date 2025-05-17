<?php 
require_once "../modelos/Articulo.php";

$articulo=new Articulo();

$idarticulo=isset($_POST["idarticulo"])? limpiarCadena($_POST["idarticulo"]):"";
$idcategoria=isset($_POST["idcategoria"])? limpiarCadena($_POST["idcategoria"]):"";
$codigo=isset($_POST["codigo"])? limpiarCadena($_POST["codigo"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$stock=isset($_POST["stock"])? limpiarCadena($_POST["stock"]):"";
$puntopedido=isset($_POST["puntopedido"])? limpiarCadena($_POST["puntopedido"]):"";
$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
$imagen=isset($_POST["imagen"])? limpiarCadena($_POST["imagen"]):"";
$costo = isset($_POST["costo"])? limpiarCadena($_POST["costo"]):"";
$idarticuloa=isset($_POST["idarticuloa"])? limpiarCadena($_POST["idarticuloa"]):"";
$costou=isset($_POST["costou"])? limpiarCadena($_POST["costou"]):"";
$proveedor=isset($_POST["idproveedor"])? limpiarCadena($_POST["idproveedor"]):"";

switch ($_GET["op"]) {
	case 'guardaryeditar':

	/*if (!file_exists($_FILES['imagen']['tmp_name'])|| !is_uploaded_file($_FILES['imagen']['tmp_name'])) {
		$imagen=$_POST["imagenactual"];
	}else{
		$ext=explode(".", $_FILES["imagen"]["name"]);
		if ($_FILES['imagen']['type']=="image/jpg" || $_FILES['imagen']['type']=="image/jpeg" || $_FILES['imagen']['type']=="image/png") {
			$imagen=round(microtime(true)).'.'. end($ext);
			move_uploaded_file($_FILES["imagen"]["tmp_name"], "../files/articulos/".$imagen);
		}
	}*/
	if (empty($idarticulo)) {

		$rspta=$articulo->insertar($idcategoria,$nombre,$stock,$puntopedido,$descripcion,$proveedor);
		echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar los datos";
	}else{
         $rspta=$articulo->editar($idarticulo,$idcategoria,$nombre,$stock,$puntopedido,$descripcion, $proveedor);
		echo $rspta ? "Datos actualizados correctamente" : "No se pudo actualizar los datos";
	}
		break;

	case 'actualizarP':

	$p1 = $_POST["precio1"];
	$p2 = $_POST["precio2"];
	$p3 = $_POST["precio3"];
	$p4 = $_POST["precio4"];
	$idarticuloa = $_POST["idarticuloa"];
	$codigo = $_POST["codigoa"];
	
/*	$p1=isset($_POST["precio1"])? limpiarCadena($_POST["precio1"]):"";
$p2=isset($_POST["precio2"])? limpiarCadena($_POST["precio2"]):"";
$p3=isset($_POST["precio3"])? limpiarCadena($_POST["precio3"]):"";
$p4=isset($_POST["precio4"])? limpiarCadena($_POST["precio4"]):"";

$idarticuloa=isset($_POST["idarticuloa"])? limpiarCadena($_POST["idarticuloa"]):"";*/
$rspta = $articulo->actualizarPrecios($idarticuloa,$p1,$p2,$p3,$p4, $codigo);
echo $rspta ? "Los datos fueron actualizados correctamente" : "No se pudo actualizar los datos";
	
		break;
	case 'actCosto':
	$rspta = $articulo->actualizarCosto($idarticuloa, $costou);
		echo $rspta ? "correcto" : "incorrecto";
		break;	

	case 'desactivar':
		$rspta=$articulo->desactivar($idarticulo);
		echo $rspta ? "Datos desactivados correctamente" : "No se pudo desactivar los datos";
		break;
	case 'activar':
		$rspta=$articulo->activar($idarticulo);
		echo $rspta ? "Datos activados correctamente" : "No se pudo activar los datos";
		break;
	
	case 'mostrar':
		$rspta=$articulo->mostrar($idarticulo);
		echo json_encode($rspta);
		break;



    case 'listar':
		$rspta=$articulo->listar();
		$data=Array();

		while ($reg=$rspta->fetch_object()) {
			$data[]=array(
            "0"=>($reg->condicion)?'<button title="Editar" class="btn btn-warning btn-xs" onclick="mostrar('.$reg->idarticulo.')"><i class="fa fa-pencil"></i></button>'.' '.'<button title="Actualizar Precios" class="btn btn-warning btn-xs" onclick="mostrarArt('.$reg->idarticulo.')"><i class="fa fa-edit"></i></button>'.' '.'<button title="Desactivar" class="btn btn-danger btn-xs" onclick="desactivar('.$reg->idarticulo.')"><i class="fa fa-close"></i></button>':'<button title="Activar" class="btn btn-warning btn-xs" onclick="mostrar('.$reg->idarticulo.')"><i class="fa fa-pencil"></i></button>'.' '.'<button class="btn btn-primary btn-xs" onclick="activar('.$reg->idarticulo.')"><i class="fa fa-check"></i></button>',
            "1"=>$reg->nombre,
            "2"=>$reg->categoria,
            "3"=>$reg->codigo,
            "4"=>$reg->descripcion,
            "5"=>$reg->puntopedido,
            "6"=>$reg->costo,
            "7"=>$reg->precioventa1,
            "8"=>$reg->precioventa2,
            "9"=>$reg->precioventa3,
            "10"=>$reg->precioventa4,
            
            "11"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':'<span class="label bg-red">Desactivado</span>'
              );
		}
		$results=array(
             "sEcho"=>1,//info para datatables
             "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
             "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
             "aaData"=>$data); 
		echo json_encode($results);
		break;
		case 'listarProv':
		$prov = $_GET['prov'];
		$rspta=$articulo->listarProv($prov);
		$data=Array();

		while ($reg=$rspta->fetch_object()) {
			$data[]=array(
            
            "0"=>$reg->nombre,
            "1"=>$reg->categoria,
            "2"=>$reg->codigo,
            "3"=>$reg->descripcion,
            "4"=>$reg->puntopedido,
            "5"=>'<strong style="color:red;">'.$reg->stock.'</strong>',
            "6"=>$reg->nombreprov,
            "7"=>$reg->telefono,
            "8"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':'<span class="label bg-red">Desactivado</span>'
			          
              );
		}
		$results=array(
             "sEcho"=>1,//info para datatables
             "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
             "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
             "aaData"=>$data); 
		echo json_encode($results);
		break;

		case 'listarStock':
		$rspta=$articulo->listarStock();
		$data=Array();

		while ($reg=$rspta->fetch_object()) {
			$data[]=array(
            
            "0"=>$reg->nombre,
            "1"=>$reg->categoria,
            "2"=>$reg->codigo,
            "3"=>$reg->descripcion,
            "4"=>$reg->puntopedido,
            "5"=>'<strong style="color:red;">'.$reg->stock.'</strong>',

            
            "6"=>$reg->nombreprov,
            "7"=>$reg->telefono,
            "8"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':'<span class="label bg-red">Desactivado</span>'
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
			require_once "../modelos/Categoria.php";
			$categoria=new Categoria();

			$rspta=$categoria->select();

			while ($reg=$rspta->fetch_object()) {
				echo '<option value=' . $reg->idcategoria.'>'.$reg->nombre.'</option>';
			}
			break;
		case 'selectProveedor':
			require_once "../modelos/Persona.php";
			$proveedor=new Persona();

			$rspta=$proveedor->listarp();

			while ($reg=$rspta->fetch_object()) {
				echo '<option value=' . $reg->idpersona.'>'.$reg->nombre.'</option>';
			}
			break;
}
 ?>