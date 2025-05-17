<?php 
require_once "../modelos/Venta.php";
date_default_timezone_set('America/Argentina/Buenos_Aires');
setlocale(LC_ALL,"es_ES");
if (strlen(session_id())<1) 
	session_start();

$venta = new Venta();

$idventa=isset($_POST["idventa"])? limpiarCadena($_POST["idventa"]):"";
$idcliente=isset($_POST["idcliente"])? limpiarCadena($_POST["idcliente"]):"";
$idusuario=$_SESSION["idusuario"];
$tipo_comprobante=isset($_POST["tipo_comprobante"])? limpiarCadena($_POST["tipo_comprobante"]):"";
$tipo_presupuesto=isset($_POST["tipopresupuesto"])? limpiarCadena($_POST["tipopresupuesto"]):"";
//$serie_comprobante=isset($_POST["serie_comprobante"])? limpiarCadena($_POST["serie_comprobante"]):"";
//$num_comprobante=isset($_POST["num_comprobante"])? limpiarCadena($_POST["num_comprobante"]):"";
$fecha_hora=DATE("Y-m-d H:m:s");
$impuesto=isset($_POST["impuesto"])? limpiarCadena($_POST["impuesto"]):"";
$total_venta=isset($_POST["total_venta"])? limpiarCadena($_POST["total_venta"]):"";
$num_comprobante = generarNumero();
$monto = isset($_POST["monto"])? limpiarCadena($_POST["monto"]):"";




switch ($_GET["op"]) {
	case 'guardaryeditar':


	if (empty($idventa)) {
		$rspta=$venta->insertar($idcliente,$idusuario,$tipo_comprobante,$tipo_presupuesto, $num_comprobante,$fecha_hora,$impuesto,$total_venta,$monto, $_POST["idarticulo"],$_POST["cantidad"],$_POST["precio_venta"],$_POST["descuento"],$_POST["iva"]); 
		echo $rspta ? $rspta : "no";
	}else{
        
	}
		break;
	

	case 'anular':

		$rspta=$venta->anular($idventa);
		echo $rspta ? "Ingreso eliminado correctamente" : "No se pudo eliminar la venta";

		break;
	
	case 'mostrar':
		$rspta=$venta->mostrar($idventa);
		echo json_encode($rspta);
		break;

	case 'listarDetalle':
		//recibimos el idventa
		$id=$_GET['id'];

		$rspta=$venta->listarDetalle($id);
		$total=0;
		echo ' <thead style="background-color:#A9D0F5">
        <th>Opciones</th>
        <th>Articulo</th>
        <th>Cantidad</th>
        <th>Precio Venta</th>
        <th>Descuento</th>
        <th>IVA</th>
        <th>Subtotal</th>
       </thead>';
		while ($reg=$rspta->fetch_object()) {
			$precioiva = ($reg->precio_venta * $reg->cantidad) + (($reg->precio_venta * $reg->cantidad) * ($reg->iva * 0.01));
			$subtotal =  $precioiva - ($precioiva * ($reg->descuento * 0.01));
			echo '<tr class="filas">
			<td></td>
			<td>'.$reg->nombre.'</td>
			<td>'.$reg->cantidad.'</td>
			<td>'.$reg->precio_venta.'</td>
			<td>'.$reg->descuento.'</td>
			<td>'.$reg->iva.'</td>
			<td>'.$subtotal.'</td></tr>';
			$total=$total+$subtotal;
		}
		echo '<tfoot>
         <th>TOTAL</th>
         <th></th>
         <th></th>
         <th></th>
         <th></th>
          <th></th>
         <th><h4 id="total"> '.$total.'</h4><input type="hidden" name="total_venta" id="total_venta"></th>
       </tfoot>';
		break;
		case 'getProductos':
			$valor = $_GET['term'];
			require_once "../modelos/Articulo.php";
			$articulo = new Articulo();
		$rspta = $articulo->filtrarVenta($valor);
		
			while ($reg = $rspta->fetch_object()) {
				if($reg->codigo)
				$data[] = $reg->codigo .'- '.$reg->nombre;
				else
				$data[] = $reg->idarticulo.'- '.$reg->nombre;
			}

			  echo json_encode($data);

			break;


    case 'listar':
		$rspta=$venta->listar();
		$data=Array();

		while ($reg=$rspta->fetch_object()) {
                if ($reg->tipo_presupuesto=='A') {
                 	$url='../reportes/exFacturaA.php?id=';
                 }else{
                   $url='../reportes/exFactura.php?id=';
                }

			$data[]=array(
            "0"=>(($reg->estado=='Aceptado')?'<button class="btn btn-danger btn-xs" onclick="anular('.$reg->idventa.')"><i class="fa fa-close"></i></button>':'<button class="btn btn-warning btn-xs" onclick="mostrar('.$reg->idventa.')"><i class="fa fa-eye"></i></button>').
            '<a target="_blank" href="'.$url.$reg->idventa.'&&fecha='.$reg->fecha.'"> <button class="btn btn-info btn-xs"><i class="fa fa-file"></i></button></a>',
            "1"=>date("d/m/Y", strtotime($reg->fecha)),
            "2"=>$reg->cliente,
            "3"=>$reg->usuario,
            "4"=>$reg->tipo_comprobante,
            "5"=>$reg->tipo_presupuesto,
            "6"=>$reg->num_comprobante,
            "7"=>number_format($reg->total_venta,2,',',''),
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
		case 'selectPrecios':
			require_once "../modelos/Articulo.php";
			$Articulo = new Articulo();

			$rspta = $articulo->listarprecio();

			while ($reg = $rspta->fetch_object()) {
				echo '<option value='.$reg->precioventa1.'>'.$reg->precioventa1.'</option>';
				echo '<option value='.$reg->precioventa2.'>'.$reg->precioventa2.'</option>';
				echo '<option value='.$reg->precioventa3.'>'.$reg->precioventa3.'</option>';
				echo '<option value='.$reg->precioventa4.'>'.$reg->precioventa4.'</option>';
			}
			break;
		case 'selectCliente':
			require_once "../modelos/Persona.php";
			$persona = new Persona();

			$rspta = $persona->listarc();

			while ($reg = $rspta->fetch_object()) {
				echo '<option value='.$reg->idpersona.'>'.$reg->nombre.'</option>';
			}
			break;

			case 'listarArticulos':
			require_once "../modelos/Articulo.php";
			$articulo=new Articulo();

				$rspta=$articulo->listarActivosVenta();
		$data=Array();

		while ($reg=$rspta->fetch_object()) {
			$data[]=array(
            "0"=>'<button class="btn btn-warning" onclick="agregarDetalle('.$reg->idarticulo.',\''.$reg->nombre.'\')"><span class="fa fa-plus"></span></button>',
            "1"=>$reg->nombre,
            "2"=>$reg->categoria,
            "3"=>$reg->codigo,
            "4"=>$reg->stock
          // "0"=>'<button class="btn btn-warning" onclick="agregarDetalle('.$reg->idarticulo.',\''.$reg->nombre.'\','.$reg->precio_venta.')"><span class="fa fa-plus"></span></button>',
          
              );
		}
		$results=array(
             "sEcho"=>1,//info para datatables
             "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
             "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
             "aaData"=>$data); 
		echo json_encode($results);

				break;
				case 'buscarProd':
				require_once "../modelos/Articulo.php";
			$articulo=new Articulo();
			$codigo = $_POST["cod"];
			//$codi = substr($codigo, 0, $pos);
			$rspta = $articulo->buscaridActivo($codigo);
			echo json_encode($rspta);
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
}

function generarNumero(){
	//Se crea el arreglo
$valor = array();
 
//Se crea el primer número aleatorio
$valorRandomPrimero = mt_rand(1,50);
//Se inserta
array_push($valor, $valorRandomPrimero);
 
//Se crea variable para iterar
$x = 1;
 
//El if describe el paso 6. y else describe el paso 7
while ($x <= 3) {
    $siguienteValorRadom = mt_rand(1, 50);
    if(in_array($siguienteValorRadom, $valor)){
        continue;
    }else{
    array_push($valor, $siguienteValorRadom);
    $x++;
    }
}
 return implode($valor);
}

 ?>