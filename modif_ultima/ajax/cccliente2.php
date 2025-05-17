<?php 
require_once "../modelos/Cccliente.php";

if (strlen(session_id())<1) 
	session_start();

$cccliente=new Cccliente();

$idventa=isset($_POST["idventa"])? limpiarCadena($_POST["idventa"]):"";
$idcliente=isset($_POST["idcliente"])? limpiarCadena($_POST["idcliente"]):"";

$idusuario=$_SESSION["idusuario"];
$tipo_comprobante=isset($_POST["tipo_comprobante"])? limpiarCadena($_POST["tipo_comprobante"]):"";
//$serie_comprobante=isset($_POST["serie_comprobante"])? limpiarCadena($_POST["serie_comprobante"]):"";
$num_comprobante=isset($_POST["num_comprobante"])? limpiarCadena($_POST["num_comprobante"]):"";
$fecha_hora=isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]):"";
$impuesto=isset($_POST["impuesto"])? limpiarCadena($_POST["impuesto"]):"";
$monto=isset($_POST["monto"])? limpiarCadena($_POST["monto"]):"";
$total_venta=isset($_POST["total_venta"])? limpiarCadena($_POST["total_venta"]):"";
$codigo=isset($_POST["producto"])? limpiarCadena($_POST["producto"]):"";


switch ($_GET["op"]) {
	case 'guardaryeditar':
	if (empty($idventa)) {
		$rspta=$venta->insertar($idventa,$idusuario,$tipo_comprobante,$num_comprobante,$fecha_hora,$impuesto,$total_venta,$monto, $_POST["idarticulo"],$_POST["cantidad"],$_POST["precio_venta"]);
		echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar los datos";
	}else{
        
	}
		break;
	

	case 'anular':
		$rspta=$venta->anular($idventa);
		echo $rspta ? "Ingreso anulado correctamente" : "No se pudo anular la venta";
		break;
	
	case 'mostrar':
		$rspta=$cccliente->mostrar($idventa);
		echo json_encode($rspta);
		break;

	case 'listarDetalle':
		//recibimos el idingreso
		$id=$_GET['id'];

		$rspta=$venta->listarDetalle($id);
		$total=0;
		echo ' <thead style="background-color:#A9D0F5">
        <th>Opciones</th>
        <th>Articulo</th>
        <th>Cantidad</th>
        <th>Precio Venta</th>
        <th>Subtotal</th>
       </thead>';
		while ($reg=$rspta->fetch_object()) {
			echo '<tr class="filas">
			<td></td>
			<td>'.$reg->nombre.'</td>
			<td>'.$reg->cantidad.'</td>
			<td>'.$reg->precio_venta.'</td>
			<td>'.$reg->precio_venta*$reg->cantidad.'</td>
			<td></td>
			</tr>';
			$total=$total+($reg->precio_venta*$reg->cantidad);
		}
		echo '<tfoot>
         <th>TOTAL</th>
         <th></th>
         <th></th>
         <th></th>
         <th></th>
         <th><h4 id="total">$ '.$total.'</h4><input type="hidden" name="total_venta" id="total_venta"></th>
       </tfoot>';
		break;

    case 'listar':
		$rspta=$cccliente->listar();
		$data=Array();

		while ($reg=$rspta->fetch_object()) {
			$saldo = $reg->total_venta - $reg->suma;
			$data[]=array(
            "0"=>($reg->estado=='Aceptado')?'<button class="btn btn-warning btn-xs" onclick="mostrar('.$reg->idventa.','.$saldo.')"><i class="fa fa-eye"></i></button>'.' '.'<button class="btn btn-danger btn-xs" onclick="anular('.$reg->idventa.')"><i class="fa fa-close"></i></button>':'<button class="btn btn-warning btn-xs" onclick="mostrar('.$reg->idventa.')"><i class="fa fa-eye"></i></button>',
            "1"=>$reg->cliente,
            "2"=>$reg->idventa,
            "3"=>$reg->num_comprobante,
            "4"=>$reg->estado,
            "5"=>$reg->suma,
            "6"=>($saldo>0)?'<p style="color:red;font-weight: bold;">(-)'.$saldo.'</p>':'<p style="color:green;font-weight: bold;">'.$saldo.'</p>',
            "7"=>$reg->total_venta,
       
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

		case 'selectCliente':
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
			case 'registrar':
				$id = $_POST['id'];
				$monto = $_POST['monto'];
			
			require_once "../modelos/Cccliente.php";
			$cccliente = new Cccliente();
			$rspta=$cccliente->registrarP($id, $monto, $idusuario);
			echo $rspta ? "El pago se registro correctamente" : "No se pudo registrar el pago";
			//echo json_encode($rspta);
			break;
			case 'listarPagos':
			$idventa= $_GET['id'];
			require_once "../modelos/Cccliente.php";
			$cccliente =new Cccliente();

				$rspta=$cccliente->listarPagos($idventa);
		$data=Array();

		while ($reg=$rspta->fetch_object()) {
			$data[]=array(
            "0"=>$reg->idcccliente,
            "1"=>$reg->nombre,
            "2"=>date("d/m/Y", strtotime($reg->fecha)),
            "3"=>$reg->pago,
                      
              );
		}
		$results=array(
             "sEcho"=>1,//info para datatables
             "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
             "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
             "aaData"=>$data); 
		echo json_encode($results);

				break;
				case 'listarPagosCli':
			$cliente= $_GET['cli'];
			require_once "../modelos/Cccliente.php";
			$cccliente =new Cccliente();

				$rspta=$cccliente->listarPagosCli($cliente);
		$data=Array();

		while ($reg=$rspta->fetch_object()) {
			$data[]=array(
            "0"=>$reg->idcccliente,
            "1"=>$reg->nombre,
            "2"=>date("d/m/Y", strtotime($reg->fecha)),
            "3"=>$reg->monto,
                      
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
if($pos === false){
		$rspta=$articulo->buscarActivo($codigo);

}
else{
if($pos < 3){
	 //buscar por nombre de producto
$codi = substr($codigo, 0, $pos);

$rspta=$articulo->buscaridActivo($codi);
} else {
// buscar por codigo
	$codi = substr($codigo, 0, $pos);
	$rspta=$articulo->buscarActivo($codi);
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