<?php
//activamos almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.html");
}else{


require 'header.php';

if ($_SESSION['consultav']==1) {

 ?>
    <div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="row">
        <div class="col-md-12">
      <div class="box">
<div class="box-header with-border">
  <h1 class="box-title">Utilidad de Categorias por fecha</h1>
  <div class="box-tools pull-right">
    
  </div>
</div>
<!--box-header-->
<!--centro-->
<?php 
date_default_timezone_set('America/Argentina/Buenos_Aires');
  setlocale(LC_TIME,"es_ES");
?>
<div class="panel-body table-responsive" id="listadoregistros">
 <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
    <label>Fecha Inicio</label>
    <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" value="<?php echo date("Y-m-d"); ?>">
  </div>
  <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
    <label>Fecha Fin</label>
    <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" value="<?php echo date("Y-m-d"); ?>">
  </div>
  <!--   <div class="form-inline col-lg-6 col-md-6 col-sm-6 col-xs-12">
    <label>Categoria</label>
    <select name="idcategoria" id="idcategoria" class="form-control selectpicker" data-live-search="true" required>
    </select>
    <br>
  <button class="btn btn-success" onclick="listar()">
      Mostrar</button> 
  </div>-->
  <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
    <thead>
      <th>id</th>
      <th>Categoria</th>
       <th>Cantidad</th>
      <th>Utilidad</th>
   
  
    </thead>
    <tbody>
    </tbody>
     <tfoot>
 <th><h4>TOTALES</h4></th>
   
         <th></th>
        
         <th><h4 id="cantidadtotal" style="color:#000;font-weight: bold;"></h4></th>
         <th><h4 id="total" style="color:#000;font-weight: bold;""></h4></th>
    </tfoot>    
  </table>
</div>

<!--fin centro-->
      </div>
      </div>
      </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
<?php 
}else{
 require 'noacceso.php'; 
}

require 'footer.php';
 ?>
 <script src="scripts/utilidadcategorias.js"></script>
 <?php 
}

ob_end_flush();
  ?>

