<?php 
require_once "../modelos/Ingreso.php";

if (strlen(session_id())<1) 
	session_start();

$ingreso=new Ingreso();

$idingreso=isset($_POST["idingreso"])? limpiarCadena($_POST["idingreso"]):"";
$idproveedor=isset($_POST["idproveedor"])? limpiarCadena($_POST["idproveedor"]):"";

$idusuario=$_SESSION["idusuario"];
$tipo_comprobante=isset($_POST["tipo_comprobante"])? limpiarCadena($_POST["tipo_comprobante"]):"";
$serie_comprobante=isset($_POST["serie_comprobante"])? limpiarCadena($_POST["serie_comprobante"]):"";
$num_comprobante=isset($_POST["num_comprobante"])? limpiarCadena($_POST["num_comprobante"]):"";
$fecha_hora=isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]):"";
$impuesto=isset($_POST["impuesto"])? limpiarCadena($_POST["impuesto"]):0;
$varios=isset($_POST["varios"])? limpiarCadena($_POST["varios"]):0;
$otros=isset($_POST["otros"])? limpiarCadena($_POST["otros"]):0;
$monto=isset($_POST["monto"])? limpiarCadena($_POST["monto"]):0;
$total_compra=isset($_POST["total_compra"])? limpiarCadena($_POST["total_compra"]):0;
$codigo=isset($_POST["producto"])? limpiarCadena($_POST["producto"]):"";

/*
$valor1 = floatval(floatval($total_compra) * ($impuesto * 0.01));
$valor2 = floatval(floatval($total_compra) * ($varios * 0.01));
$valor3 = floatval(floatval($total_compra) * ($otros * 0.01));
$total = floatval($total_compra + $valor1 + $valor2 + $valor3);*/
$impuestos = floatval($impuesto +$varios+$otros);

switch ($_GET["op"]) {
	case 'guardaryeditar':
	if (empty($idingreso)) {
		$rspta=$ingreso->insertar($idproveedor,$idusuario,$tipo_comprobante,$serie_comprobante,$num_comprobante,$fecha_hora,$impuestos,$total_compra, $monto, $_POST["idarticulo"],$_POST["cantidad"],$_POST["precio_compra"]);
		echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar los datos";
	}else{
        
	}
		break;
	

	case 'anular':
		$rspta=$ingreso->anular($idingreso);
		echo $rspta ? "Ingreso anulado correctamente" : "No se pudo anular el ingreso";
		break;
	
	case 'mostrar':
		$rspta=$ingreso->mostrar($idingreso);
		echo json_encode($rspta);
		break;

	case 'listarDetalle':
		//recibimos el idingreso
		$id = $_GET['id'];

		$rspta=$ingreso->listarDetalle($id);
		$total=0;
		echo ' <thead style="background-color:#A9D0F5">
        <th>Opciones</th>
        <th>Articulo</th>
        <th>Cantidad</th>
        <th>Precio Compra</th>
       
        <th>Subtotal</th>
       </thead>';
		while ($reg=$rspta->fetch_object()) {
			$impu = $reg->impuesto;
			$total=$total+($reg->precio_compra*$reg->cantidad);
			echo '<tr class="filas">
			<td></td>
			<td>'.$reg->nombre.'</td>
			<td>'.number_format($reg->cantidad, 3, ',', '').'</td>
			<td>'.number_format($reg->precio_compra, 2, ',', '').'</td>
			
			<td>'.number_format($reg->precio_compra*$reg->cantidad, 2, ',', '').'</td>
		
			</tr>';
			
		}
		$imp = number_format(round(($total *  $impu * 0.01), 2), 2, ',', '');
		$sumtotal = $total + $imp;
		echo '<tfoot>
         <th>TOTAL</th>
         <th></th>
         <th></th>
         <th></th>
      
         <th><h4 id="total">$ '.number_format($sumtotal, 2, ',','').'</h4><input type="hidden" name="total_compra" id="total_compra"></th>
       </tfoot>';
		break;

    case 'listar':
		$rspta=$ingreso->listar();
		$data=Array();

		while ($reg=$rspta->fetch_object()) {
			$total = $reg->total_compra + ($reg->total_compra * $reg->impuesto * 0.01);
			$data[]=array(
            "0"=>($reg->estado=='Aceptado')?'<button class="btn btn-warning btn-xs" onclick="mostrar('.$reg->idingreso.')"><i class="fa fa-eye"></i></button>'.' '.'<button class="btn btn-danger btn-xs" onclick="anular('.$reg->idingreso.')"><i class="fa fa-close"></i></button>':'<button class="btn btn-warning btn-xs" onclick="mostrar('.$reg->idingreso.')"><i class="fa fa-eye"></i></button>',
            "1"=>date("d/m/Y", strtotime($reg->fecha)),
            "2"=>$reg->proveedor,
            "3"=>$reg->usuario,
            "4"=>$reg->tipo_comprobante,
            "5"=>$reg->serie_comprobante,
            "6"=>$reg->num_comprobante,
            "7"=>number_format(round($reg->total_compra, 2), 2, ',', ''),
            "8"=>($reg->estado=='Aceptado')?'<span class="label bg-green">Aceptado</span>':'<span class="label bg-red">Anulado</span>'
              );
		}
		$results=array(
             "sEcho"=>1,//info para datatables
             "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
             "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
             "aaData"=>$data); 
		echo json_encode($results);
		break;

		case 'selectProveedor':
			require_once "../modelos/Persona.php";
			$persona = new Persona();

			$rspta = $persona->listarp();

			while ($reg = $rspta->fetch_object()) {
				echo '<option value='.$reg->idpersona.'>'.$reg->nombre.'</option>';
			}
			break;
			case 'getProductos':
			$valor = $_GET['term'];
			require_once "../modelos/Articulo.php";
			$articulo = new Articulo();
			$rspta = $articulo->filtrar($valor);
		
			while ($reg = $rspta->fetch_object()) {
				if($reg->codigo)
				$data[] = $reg->codigo .'- '.$reg->nombre;
				else
				$data[] = $reg->idarticulo.'- '.$reg->nombre;
			}

			  echo json_encode($data);

			break;

			case 'listarArticulos':
			require_once "../modelos/Articulo.php";
			$articulo=new Articulo();
			$rspta=$articulo->listarActivos();
		$data=Array();

		while ($reg=$rspta->fetch_object()) {
			$data[]=array(
            "0"=>'<button class="btn btn-warning" onclick="agregarDetalle('.$reg->idarticulo.',\''.$reg->nombre.'\',\''.$reg->codigo.'\')"><span class="fa fa-plus"></span></button>',
            "1"=>$reg->nombre,
            "2"=>$reg->categoria,
            "3"=>$reg->codigo,
            "4"=>$reg->stock,
            "5"=>$reg->costo
          
              );
		}
		$results=array(
             "sEcho"=>1,//info para datatables
             "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
             "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
             "aaData"=>$data); 
		echo json_encode($results);

				break;

				case 'buscarArticulo':
						require_once "../modelos/Articulo.php";
			$articulo=new Articulo();

			$codigo = $_POST["cod"];
		//$reg = Array();
			$signo   = '-';
			$pos = strpos($codigo, $signo);
				
			if($pos){

			if($pos < 5){
				 //buscar por nombre de producto
			$codi = substr($codigo, 0, $pos);
			$rspta = $articulo->buscaridActivo($codi);

			} else {
			// buscar por codigo
				$codi = substr($codigo, 0, $pos);
				$rt=$articulo->buscarActivo($codi);
				$id=$rt->fetch_object();
				$rspta=$articulo->buscaridActivo($id->idarticulo);
			}
}else{
		if(is_numeric($codigo)){
			$rt = $articulo->buscarActivo($codigo);
			$id=$rt->fetch_object();
			$rspta = $articulo->buscaridActivo($id->idarticulo);
		}
		else{
			$rspta = null;
		}
}


 				
		//$data=Array();

					/*$reg=$rspta->fetch_object();
						$data[]= array("id"=>$reg->idarticulo,"nombre"=>$reg->nombre, "categoria"=>$reg->categoria, "codigo"=>$reg->codigo, "stock"=>$reg->stock, "costo"=> $reg->costo);
					*/
			echo json_encode($rspta);
				//echo $respta ? "Datos registrados correctamente" : "No se pudo registrar los datos";
			
				
				//echo json_encode($reg);
			break;
			case 'agregarArticulo':

			require_once "../modelos/Articulo.php";
			$articulo=new Articulo();
			$nombre = $_POST["nom"];
			$codigo = $_POST["cod"];
			$categoria = $_POST["cate"];

			$rspta=$articulo->agregarActivo($nombre, $codigo, $categoria);
			echo json_encode($rspta);
			break;

}


 ?>