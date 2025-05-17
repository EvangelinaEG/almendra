<?php 
//activamos almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.html");
}else{

require 'header.php';
if ($_SESSION['almacen']==1) {
 ?>
    <div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="row">
        <div class="col-md-12">
      <div class="box">
<div class="box-header with-border">
  <h1 class="box-title">Articulo 
   <!--  <button class="btn btn-success" onclick="mostrarform(true)" id="btnagregar"><i class="fa fa-plus-circle"></i>Agregar</button> --> 
<!--   <a target="_blank" href="../reportes/rptarticulos.php"><button class="btn btn-info">Reporte</button></a></h1>
  <div class="box-tools pull-right"> -->
    
  </div>
</div>

<!--box-header-->
<!--centro-->
<div class="panel-body table-responsive" id="listadoregistros">
  <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
   
   
        <div class="form-group col-lg-8 col-md-8 col-xs-12">
      Proveedor:
      <select name="idproveedor" id="idproveedor" class="form-control selectpicker" data-live-search="true" required>
          <option value="TODOS" selected="selected" >TODOS</option>
      </select>
    </div>
   
   <thead>   
      <th>Nombre</th>
      <th>Categoria</th>
      <th>Codigo</th>
      <th>Descripcion</th>
      <th>P. de Pedido</th>
      <th>Stock</th>
         <th >Proveedor</th>
        <th >Tel Prov.</th>
        <th >Estado</th>
    </thead>
    <tbody>
    </tbody>
    <tfoot>
      <th>Nombre</th>
      <th>Categoria</th>
      <th>Codigo</th>
      <th>Descripcion</th>
      <th>P. de Pedido</th>
      <th >Stock</th>
       <th >Proveedor</th>
        <th >Tel Prov.</th>
          <th >Estado</th>
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
require 'footer.php'
 ?>
 <script src="../public/js/JsBarcode.all.min.js"></script>
 <script src="../public/js/jquery.PrintArea.js"></script>
 <script src="scripts/faltantes.js"></script>

 <?php 
}

ob_end_flush();
  ?>