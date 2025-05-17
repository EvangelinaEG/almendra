
<?php
//activamos almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.html");
}else{

 
require 'header.php';

      if ($_SESSION['almacen']==1) {

require "../config/Conexion.php";



$id = (isset($_POST['id']))?$_POST['id']:"";
$codigo = (isset($_POST['cod']))?$_POST['cod']:"";
$precio1 = (isset($_POST['pl1']))?$_POST['pl1']:"";
$precio2 = (isset($_POST['pl2']))?$_POST['pl2']:"";
$precio3 = (isset($_POST['pl3']))?$_POST['pl3']:"";
$precio4 = (isset($_POST['pl4']))?$_POST['pl4']:"";
$categoria = (isset($_POST['idcategoria']))?$_POST['idcategoria']:"TODAS";
$proveedor = (isset($_POST['idproveedor']))?$_POST['idproveedor']:"TODOS";



$cadena = "";
  
if($id != ""){
  $cadena.= "idarticulo,";
}
if($codigo != ""){
  $cadena.=" codigo,";
}
if($precio1 != ""){
  $cadena.=" precioventa1,";
}
if($precio2 != ""){
  $cadena.=" precioventa2,";
}

if($precio3 != ""){
  $cadena.=" precioventa3,";
}

if($precio4 != ""){
  $cadena.=" precioventa4,";
}


if($categoria != "TODAS" && $proveedor != "TODOS"){

  $query = "SELECT ".$cadena." nombre, idarticulo FROM articulo WHERE idcategoria = ".$categoria." AND idproveedor = ".$proveedor." AND condicion = '1'";
 $resultado = ejecutarConsulta($query);
 }
 else if($categoria != "TODAS" && $proveedor == "TODOS"){

  $query = "SELECT ".$cadena." nombre, idarticulo FROM articulo WHERE idcategoria = ".$categoria." AND condicion = '1'";
 $resultado = ejecutarConsulta($query);

 }else if($categoria == "TODAS" && $proveedor != "TODOS"){
    $query = "SELECT ".$cadena." nombre, idarticulo FROM articulo WHERE idproveedor = ".$proveedor." AND condicion = '1'";
 $resultado = ejecutarConsulta($query);
 }else if($categoria =="TODAS" && $proveedor == "TODOS"){
    $query = "SELECT ".$cadena." nombre, idarticulo FROM articulo WHERE condicion = '1'";
 $resultado = ejecutarConsulta($query) ;

 }
  $total = cantidadRegistros($resultado);
?>
 <div class="content-wrapper">
                <!-- Main content -->
                <section class="content">

                  <!-- Default box -->
                  <div class="row">
                    <div class="col-md-12">
                  <div class="box">
            <div class="box-header with-border">
              <h3>Seleccione los datos que desea mostrar de los productos</h3>
<form action="exportarPDF.php" name="formulario" id="formulario" method="POST" target="_blank">
               <!--  <div class="form-inline col-lg-6 col-md-6 col-sm-6 col-xs-12">
                 <label>Categoria</label>
                <select name="idcategoria" id="idcategoria" class="form-control selectpicker" data-live-search="true" required>
                </select> 
                <br>-->
               <!--  <button class="btn btn-success" onclick="listar()">
                  Mostrar</button> 
              </div>
               <div class="form-inline col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <!-- <label>Proveedor</label>
                <select name="idproveedor" id="idproveedor" class="form-control selectpicker" data-live-search="true" required>
                </select>
                <br> -->
               <!--  <button class="btn btn-success" onclick="listar()">
                  Mostrar</button>
              </div> -->
              <div class="form-inline col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <input type="checkbox" id="selectall" onclick="javascript:validar(this);"> TODOS
                <br>
              <?php
$i = 0;

              while($row = mysqli_fetch_array($resultado)){
                ?>
              
<input type="checkbox" id="nombre" name="nombre[]" class="case" value="<?php echo $row['nombre']?>"><?php echo $row['nombre']?> <br>
<input type="hidden" id="id" name="idarticulo[]" value="<?php echo $row['idarticulo']; ?>"><br>

<!-- <input type="hidden" name="ide[]" value="<?php echo $i; ?>">-->
<?php if($precio1!=""){ ?>
<input type="hidden" name="precio1" value="precio1">
<?php }if($precio2!=""){ ?>
<input type="hidden" name="precio2" value="precio2">
<?php }if($precio3!=""){ ?>
<input type="hidden" name="precio3" value="precio3">
<?php }if($precio4!=""){ ?>
<input type="hidden" name="precio4" value="precio4">
<?php }?> 
 <br/> 
            <!--   <input type="checkbox" id="cod" name="$row['cod']" value="1"> CODIGO DE BARRA <br>
              <input type="checkbox" id="pl1" name="$row['pl1" checked value="1"> PRECIO DE LISTA 1 <br>
              <input type="checkbox" id="pl2" name="$row['pl2" checked value="1"> PRECIO DE LISTA 2 <br>
              <input type="checkbox" id="pl3" name="pl3" value="1"> PRECIO DE LISTA 3 <br>
              <input type="checkbox" id="pl4" name="pl4" value="1"> PRECIO DE LISTA 4 <br> -->
            <?php 

            $i++;}  ?>
          </div>
  
   <input type="hidden" id="<?php echo $cadena ?>" name="cadena" value="<?php echo $cadena?>" >  
  <input type="hidden" id="<?php echo $categoria ?>" name="categoria" value="<?php echo $categoria ?>" >
  <input type="hidden" id="<?php echo $proveedor ?>" name="proveedor" value="<?php echo $proveedor ?>" >
  <button type="submit" name="enviar" id="boton" disabled >DESCARGAR</button>
              </form>
</div>
</div>
</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript">
  $(".case").on("change", function(){

  //Agregamos una variable fija para comprobar la cantidad de elementos chekeados.
  
  var checked = $(".case:checked").length;
  
  //Condicionamos de manera que se ejecute si hay un elemento seleccionado o la cantidad de elementos en check no es igual a 0.

  if (checked != 0) {
  
    //Agregamos un poco de css.
  
    $("#boton").css({"color": "black"});
    
    // Quitamos el atributo disabled.
    
    $("#boton").removeAttr("disabled");
    //$("h5").html("El boton esta activo.");
  
  }else{
  
    $("#boton").css({"color": "#d1d1e0"});
    
    //Agregamos el atributo disabled y lo seteamos en disabled.
    
    $("#boton").attr("disabled", "disabled");
    //$("h5").html("El boton esta inactivo.");
  
  }

});


</script>
</section>
</div>
<script src="scripts/ventasfechacliente.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>
 $("#selectall").on("click", function() {  
  $(".case").prop("checked", this.checked);  
 //document.getElementById("boton").removeAttribute("disabled");

});  

// if all checkbox are selected, check the selectall checkbox and viceversa  
$(".case").on("click", function() {  
  if ($(".case").length == $(".case:checked").length) {  
    $("#selectall").prop("checked", true);  

  } else {  
    $("#selectall").prop("checked", false);  
  
  }  
});
</script>
<script>
function validar(obj){
  var d = document.formulario;
  if(obj.checked==true){
    d.enviar.disabled = false;
  }else{
    d.enviar.disabled= true;
  }
}
</script>
<?php


            require 'footer.php';

      }else{
       require 'noacceso.php'; 
      }

 
}
ob_end_flush();
  ?>


 