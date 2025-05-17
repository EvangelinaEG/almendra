<?php 
require_once "../modelos/Consultas.php";

$consulta = new Consultas();

switch ($_GET["op"]) {
	

    case 'comprasfecha':
    $fecha_inicio=$_REQUEST["fecha_inicio"];
    $fecha_fin=$_REQUEST["fecha_fin"];

		$rspta=$consulta->comprasfecha($fecha_inicio,$fecha_fin);
		$data=Array();

		while ($reg=$rspta->fetch_object()) {
			$data[]=array(
            "0"=>$reg->fecha,
            "1"=>$reg->usuario,
            "2"=>$reg->proveedor,
            "3"=>$reg->tipo_comprobante,
            "4"=>$reg->serie_comprobante.' '.$reg->num_comprobante,
            "5"=>number_format(round($reg->total_compra, 2),2,',',''),
            "6"=>$reg->impuesto,
            "7"=>($reg->estado=='Aceptado')?'<span class="label bg-green">Aceptado</span>':'<span class="label bg-red">Anulado</span>'
              );
		}
		$results=array(
             "sEcho"=>1,//info para datatables
             "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
             "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
             "aaData"=>$data); 
		echo json_encode($results);
		break;
        case 'productoscategoria':
    $fecha_inicio=$_GET["fecha_inicio"];
    $fecha_fin=$_GET["fecha_fin"];
   //$idcategoria=$_REQUEST["idcategoria"];


 $rspta=($_GET["idcategoria"] =="TODAS")?$consulta->productosfecha($fecha_inicio,$fecha_fin):$consulta->productosfechacat($fecha_inicio,$fecha_fin,$_GET["idcategoria"]);
       // $rspta=$consulta->productosfechacat($fecha_inicio,$fecha_fin,$idcategoria);
        $data=Array();

        while ($reg=$rspta->fetch_object()) {
            $data[]=array(
            "0"=>$reg->idarticulo,
            "1"=>$reg->nombre,
            "2"=>$reg->descripcion,
            "3"=>str_replace('.',',',$reg->cantidad),
            "4"=>number_format(round($reg->total,2),2,',','')
            
              );
        }
        $results=array(
             "sEcho"=>1,//info para datatables
             "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
             "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
             "aaData"=>$data); 
        echo json_encode($results);
        break;
        case 'mostrarTotales':
        $fi = $_GET['fei'];
        $ff = $_GET['fefi'];
       // $ca = $_GET['cat'];
    $total_general = 0;
    $cant_total =0;
    $data = array();

     $rspta=($_REQUEST["cat"] =="TODAS")?$consulta->productosfecha($fi,$ff):$consulta->productosfechacat($fi,$ff,$_REQUEST["cat"]);
      //  $rspta=$consulta->productosfechacat($fi,$ff,$ca);
         while ($reg=$rspta->fetch_object()) {
                $cant_total =  $cant_total + $reg->cantidad;
            
            $total_general = $total_general + $reg->total;
        }
        $cant_total = number_format($cant_total,3,',','');
         $totgeneral = number_format(round($total_general, 2),2,',','');
        $data = array('cantotal' => $cant_total, 'total' => $totgeneral);
        echo json_encode($data);
        break;
        case 'selectCategoria':
            require_once "../modelos/Categoria.php";
            $categoria=new Categoria();

            $rspta=$categoria->select();

            while ($reg=$rspta->fetch_object()) {
                echo '<option value=' . $reg->idcategoria.'>'.$reg->nombre.'</option>';
            }
            break;
            case 'rankingproductos':
    $fecha_inicio=$_REQUEST["fecha_inicio"];
    $fecha_fin=$_REQUEST["fecha_fin"];
    

        $rspta=$consulta->productosfecha($fecha_inicio,$fecha_fin);
        $data=Array();

        while ($reg=$rspta->fetch_object()) {
            $data[]=array(
            "0"=>$reg->idarticulo,
            "1"=>$reg->nombre,
            "2"=>$reg->descripcion,
            "3"=>str_replace('.',',',$reg->cantidad),
            "4"=>number_format(round($reg->total, 2),2,',','')
            
              );
        }
        $results=array(
             "sEcho"=>1,//info para datatables
             "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
             "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
             "aaData"=>$data); 
        echo json_encode($results);
        break;
        case 'mostrarTotalespr':
        $fi = $_GET['fei'];
        $ff = $_GET['fefi'];
       
    $total_general = 0;
    $cant_total =0;
    $data = array();
    $rspta=($_REQUEST["idcategoria"] =="TODAS")?$consulta->productosfecha($fi,$ff) :$consulta->productosfechacat($fecha_inicio,$fecha_fin,$_REQUEST["idcategoria"]);
       // $rspta=$consulta->productosfecha($fi,$ff);
         while ($reg=$rspta->fetch_object()) {
                $cant_total =  $cant_total + $reg->cantidad;
            
            $total_general = $total_general + $reg->total;
        }
        $cant_total = number_format(round(str_replace('.',',',$cant_total),2),3,',','');
         $totgeneral = number_format(round($total_general, 2),2,',','');
        $data = array('cantotal' => $cant_total, 'total' => $totgeneral);
        echo json_encode($data);
        break;
         case 'stockProductos':
   
  //$fecha_inicio=$_REQUEST["fecha_inicio"];
   // $fecha_fin=$_REQUEST["fecha_fin"];
    $rspta=($_REQUEST["idcategoria"] =="TODAS")?$consulta->productosStock() :$consulta->productosStockC($_REQUEST["idcategoria"]);
   
        $data=Array();

        while ($reg=$rspta->fetch_object()) {
            $data[]=array(
            "0"=>$reg->idarticulo,
            "1"=>$reg->nombre,
            "2"=>$reg->descripcion,
            "3"=>number_format($reg->stock,3,',',''),
            "4"=>number_format(round(($reg->stock * $reg->costo), 2),2,',','')
            
              );
        }
        $results=array(
             "sEcho"=>1,//info para datatables
             "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
             "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
             "aaData"=>$data); 
        echo json_encode($results);
        break;
        case 'mostrarTotaleStock':
     $rspta = ($_GET['cat'] == "TODAS")?$consulta->productosStock():$consulta->productosStockC($_GET['cat']);

    $total_general = 0;
    $cant_total =0;
    $data = array();
    
         while ($reg=$rspta->fetch_object()) {
                $cant_total =  $cant_total + $reg->stock;
            
            $total = $reg->stock * $reg->costo;
                $total_general = $total_general + $total;
            
        }
        $cant_total = number_format(str_replace('.',',',$cant_total),3,',','');
        $totgeneral = number_format(round($total_general, 2),2,',','');
        $data = array('cantotal' => $cant_total, 'total' => $totgeneral);
        echo json_encode($data);
        break;
         case 'utilidadproductos':
    $fecha_inicio=$_REQUEST["fecha_inicio"];
    $fecha_fin=$_REQUEST["fecha_fin"];
    

        $rspta=$consulta->productosUfecha($fecha_inicio,$fecha_fin);
        $data=Array();

        while ($reg=$rspta->fetch_object()) {
         
          $utilidad = $reg->precio - ($reg->costo * $reg->cant);
            $data[]=array(
            "0"=>$reg->idarticulo,
            "1"=>$reg->nombre,
            "2"=>$reg->descripcion,
            "3"=>round($utilidad, 2)
        
            
              );
        }
        $results=array(
             "sEcho"=>1,//info para datatables
             "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
             "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
             "aaData"=>$data); 
        echo json_encode($results);
        break;
        case 'mostrarTotUtilidad':
        $fi = $_GET['fi'];
        $ff = $_GET['fefi'];
       
    $total_general = 0;
    $cant_total =0;
    $data = array();
        $rspta=$consulta->productosUfecha($fi,$ff);
         while ($reg=$rspta->fetch_object()) {
               // $cant_total =  $cant_total + $reg->cantidad;
            //$cost = ($reg->cantidad * $reg->costo);
          //$precio =  ($reg->cantidad * $reg->precio_venta);
          $utilidad = $reg->precio - ($reg->costo * $reg->cant);
            $total_general = $total_general + $utilidad;
        }
        $data = array('total' => round($total_general, 2));
        echo json_encode($data);
        break;






        case 'utilidadproductoscat':
    $fecha_inicio=$_REQUEST["fecha_inicio"];
    $fecha_fin=$_REQUEST["fecha_fin"];
    //$idcategoria=$_REQUEST["idcategoria"];
 $rspta = ($_REQUEST["idcategoria"] == "TODAS")?$consulta->productosUfecha($fecha_inicio,$fecha_fin):$consulta->productosUfechacat($fecha_inicio,$fecha_fin,$_REQUEST["idcategoria"]);

       // $rspta=$consulta->productosUfechacat($fecha_inicio,$fecha_fin,$idcategoria);
        $data=Array();

        while ($reg=$rspta->fetch_object()) {
        
          //$utilidad = $reg->precio - ($reg->costo * $reg->cant);
            $data[]=array(
            "0"=>$reg->idarticulo,
            "1"=>$reg->nombre,
            "2"=>$reg->descripcion,
            "3"=>number_format($reg->cant,3,',',''),
            "4"=>number_format(round($reg->utilidad, 2),2,',','')
            
              );
        }
        $results=array(
             "sEcho"=>1,//info para datatables
             "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
             "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
             "aaData"=>$data); 
        echo json_encode($results);
        break;


        case 'mostrarTotUtilidadcat':
            $fi = $_GET['fei'];
        $ff = $_GET['fefi'];
       // $ca = $_GET['cat'];
    $total_general = 0;
    $cant_total =0;
    $data = array();

     $rspta=($_REQUEST["cat"] =="TODAS")?$consulta->productosUfecha($fi,$ff):$consulta->productosUfechacat($fi,$ff,$_REQUEST["cat"]);
      //  $rspta=$consulta->productosfechacat($fi,$ff,$ca);
         while ($reg=$rspta->fetch_object()) {
                $cant_total =  $cant_total + $reg->cant;
            
            $total_general = $total_general + $reg->utilidad;
        }
        $cant_total = number_format($cant_total,3,',','');//str_replace('.',',',$cant_total);
         $totgeneral = number_format(round($total_general, 2),2,',','');
        $data = array('cantotal' => $cant_total, 'total' => $totgeneral);
        echo json_encode($data);
        break;

}
 ?>