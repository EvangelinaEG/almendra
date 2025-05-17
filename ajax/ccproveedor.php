<?php 
require_once "../modelos/Ccproveedor.php";

if (strlen(session_id())<1) 
	session_start();

$ccproveedor=new Ccproveedor();

$idingreso=isset($_POST["idingreso"])? limpiarCadena($_POST["idingreso"]):"";
$idproveedor=isset($_POST["idproveedor"])? limpiarCadena($_POST["idproveedor"]):"";
$idprov=isset($_POST["prov"])? limpiarCadena($_POST["prov"]):"";
$idusuario=$_SESSION["idusuario"];
$tipo_comprobante=isset($_POST["tipo_comprobante"])? limpiarCadena($_POST["tipo_comprobante"]):"";
$serie_comprobante=isset($_POST["serie_comprobante"])? limpiarCadena($_POST["serie_comprobante"]):"";
$num_comprobante=isset($_POST["num_comprobante"])? limpiarCadena($_POST["num_comprobante"]):"";
$fecha_hora=isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]):"";
$impuesto=isset($_POST["impuesto"])? limpiarCadena($_POST["impuesto"]):"";
$monto=isset($_POST["monto"])? limpiarCadena($_POST["monto"]):"";
$total_compra=isset($_POST["total_compra"])? limpiarCadena($_POST["total_compra"]):"";
$codigo=isset($_POST["producto"])? limpiarCadena($_POST["producto"]):"";


switch ($_GET["op"]) {
	case 'guardaryeditar':
	if (empty($idingreso)) {
		$rspta=$ingreso->insertar($idproveedor,$idusuario,$tipo_comprobante,$serie_comprobante,$num_comprobante,$fecha_hora,$impuesto,$total_compra,$monto, $_POST["idarticulo"],$_POST["cantidad"],$_POST["precio_compra"]);
		echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar los datos";
	}else{
        
	}
		break;
	

	case 'anular':
		$rspta=$ingreso->anular($idingreso);
		echo $rspta ? "Ingreso anulado correctamente" : "No se pudo anular el ingreso";
		break;
	
	case 'mostrar':
	$idprov = $_POST['prov'];
		$rspta=$ccproveedor->mostrar($idprov);
		echo json_encode($rspta);
		break;
	case 'mostrarTotales':
	$total_general = 0;
	$tot_saldo =0;
	$data = array();
		$rspta=$ccproveedor->listar();
		while ($reg=$rspta->fetch_object()) {
				$saldo =  $reg->suma - $reg->total;
			$tot_saldo = $tot_saldo + $saldo;
			$total_general = $total_general + $reg->total;
		}
		$sal = round($tot_saldo, 2); 
		$tot = round($total_general, 2);
		$data = array('saldo' => $sal, 'total' => $tot);
		echo json_encode($data);
		break;

	case 'listarDetalle':
		//recibimos el idingreso
		//$id=$_GET['id'];

		$rspta=$ccproveedor->listar();

		$total=0;
		echo ' <thead style="background-color:#A9D0F5">
        <th>Opciones</th>
      <th>Proveedor</th>
      <th>Monto Pagado</th>
      <th>Saldo</th>
      <th>Total</th>
       </thead>';
       $tot_saldo = 0;
		$total_general = 0;
		while ($reg=$rspta->fetch_object()) {
			$saldo =  $reg->suma - $reg->total;
			$tot_saldo = $tot_saldo + $saldo;
			$total_general = $total_general + $reg->total;
			$url = '../reportes/exTicketprov.php?id=';
			?>
			<tr class='filas'>
			
			<td><button class="btn btn-warning btn-xs" onclick="mostrar(<?php echo $reg->idproveedor ?>,<?php echo ($reg->total - $reg->suma) ?>)"><i class="fa fa-eye"></i></button></td>

			<td><?php echo $reg->proveedor ?></td>
			<td><?php echo $reg->suma ?></td>
<td><?php echo $saldo ?> </td>
<?php  if($saldo<0) {?> <p style="color:red;font-weight: bold;"> <?php echo  $saldo ?> </p><?php }else{ ?>
<p tyle="color:green;font-weight:bold;"><?php echo $saldo ?> </p> <?php } ?>
			<td> <?php echo $reg->total ?> </td>
			
			</tr> 
			
	<?php 	} 
		echo '<tfoot>
         <th><h4>TOTAL</h4></th>
         <th></th>
         <th></th>
        
         <th><h4 id="saldo" style="color:#000;font-weight: bold;">$ '.$tot_saldo.'</h4><input type="hidden" name="tot_saldo" id="tot_saldo"></th>
         <th><h4 id="total" style="color:#000;font-weight: bold;"">$ '.$total_general.'</h4><input type="hidden" name="total_general" id="total_general"></th>
       </tfoot>';
		break;

    case 'listar':
		$rspta=$ccproveedor->listar();
		$data=Array();
		 $tot_saldo = 0;
		$total_general = 0;
		$url = '../reportes/exTicketprov.php?id=';
		while ($reg=$rspta->fetch_object()) {
			$saldo =  $reg->suma - $reg->total;
			$tot_saldo = $tot_saldo + $saldo;
			$total_general = $total_general + $reg->total;
			$sal = $reg->total - $reg->suma;
			$data[]=array(
            "0"=>'<button class="btn btn-warning btn-xs" onclick="mostrar('.$reg->idproveedor.' ,'.$sal.')"><i class="fa fa-eye"></i></button>',
            "1"=>$reg->proveedor,
            "2"=>$reg->suma,
            "3"=>($saldo>0)?'<p style="color:green;font-weight: bold;">'.$saldo.'</p>':'<p style="color:red;font-weight: bold;">'.$saldo.'</p>',
            "4"=>$reg->total
       		
   
              );
		}
		$results=array(
             "sEcho"=>1,//info para datatables
             "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
             "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
             "aaData"=>$data,
				"totsaldo"=>$tot_saldo,
				"totalgeneral"=>$total_general);
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
			case 'registrar':
			
				$monto = $_POST['monto'];
				$proveedor = $_POST['proveedor'];
			require_once "../modelos/Ccproveedor.php";
			$ccproveedor = new Ccproveedor();
			$rspta=$ccproveedor->registrarP($monto, $proveedor, $idusuario);
			echo $rspta ? "El pago se registro correctamente" : "No se pudo registrar el pago";
			//echo json_encode($rspta);
			break;
			case 'listarPagos':
			$idproveedor= $_GET['id'];
			require_once "../modelos/Ccproveedor.php";
			$ccproveedor =new Ccproveedor();
			$cont =0;
			$rspta=$ccproveedor->listarPagos($idproveedor);
				while ($reg=$rspta->fetch_object()) {$cont ++;}
				$rspta=$ccproveedor->listarPagos($idproveedor);
		$data=Array();
	$url='../reportes/exTicketprov.php?id=';	
		while ($reg=$rspta->fetch_object()) {
			$data[]=array(
            "0"=>$reg->idcuentacorriente,
            "1"=>$reg->nombre,
            "2"=>date("d/m/Y H:i:s", strtotime($reg->fechahorapago)),
            "3"=>$reg->monto,
             "4"=> ($cont>1)?'<a target="_blank" href="'.$url.$idproveedor.'&&fecha='.$reg->fechahorapago.'"> <button class="btn btn-info btn-xs"><i class="fa fa-file"></i></button></a>'.' ' .' <button alt="ELiminar pago" class="btn btn-danger btn-xs" onclick="eliminar('.$reg->idcuentacorriente.')"><i class="fa fa-trash"></i></button>':'<a target="_blank" href="'.$url.$idproveedor.'&&fecha='.$reg->fechahorapago.'"> <button class="btn btn-info btn-xs"><i class="fa fa-file"></i></button></a>',       
              );
		}
		$results=array(
             "sEcho"=>1,//info para datatables
             "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
             "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
             "aaData"=>$data); 
		echo json_encode($results);

				break;

				case 'eliminar':
			$idcc = $_POST['idcuentacorriente'];
			require_once "../modelos/Ccproveedor.php";
			$ccproveedor = new Ccproveedor();
			$rspta = $ccproveedor->eliminarpago($idcc);
			echo $rspta ? "El pago se canceló correctamente" : "No se pudo cancelar el pago";
				break;
				case 'listarPagosProv':
			$proveedor= $_GET['prov'];
			require_once "../modelos/Ccproveedor.php";
			$ccproveedor =new Ccproveedor();

				$rspta=$ccproveedor->listarPagosProv($proveedor);
		$data=Array();

		while ($reg=$rspta->fetch_object()) {
			$data[]=array(
            "0"=>$reg->idcuentacorriente,
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