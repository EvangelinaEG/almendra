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
  <h1 class="box-title">Categoria <button class="btn btn-success" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i>Agregar</button></h1>
  <div class="box-tools pull-right">
    
  </div>
</div>
<!--box-header-->
<!--centro-->
<div class="panel-body table-responsive" id="listadoregistros">
  <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
    <thead>
      <th>Opciones</th>
      <th>Nombre</th>
      <th>Descripcion</th>
      <th>Estado</th>
    </thead>
    <tbody>
    </tbody>
    <tfoot>
       <th>Opciones</th>
      <th>Nombre</th>
      <th>Descripcion</th>
      <th>Estado</th>
    </tfoot>   
  </table>
</div>
<div class="panel-body" style="height: 400px;" id="formularioregistros">
  <form action="" name="formulario" id="formulario" method="POST">
    <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Nombre</label>
      <input class="form-control" type="hidden" name="idcategoria" id="idcategoria">
      <input class="form-control" type="text" name="nombre" id="nombre" maxlength="50"  style="text-transform: uppercase;"  placeholder="Nombre" required>
    </div>
        <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Descripcion</label>
      <input class="form-control" type="text" name="descripcion" id="descripcion" maxlength="256" placeholder="Descripcion">
    </div>
    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i>  Guardar</button>

      <button class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
    </div>
  </form>
</div>
<!--fin centro-->
<div class="panel-body" id="actualizarPrecioscat">
  <form action="" name="formactP" id="formactP" method="POST">
    <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Descripcion:</label>
      <input class="form-control" type="hidden" name="idcategoriau" id="idcategoriau">
      <input class="form-control" type="text" name="descripcionu" id="descripcionu" maxlength="100" placeholder="Descripcion" disabled="disabled">
    </div>
     <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Proveedor(*):</label>
  
      <select name="idproveedor" id="idproveedor" class="form-control selectpicker" data-live-search="true" required>
         
      </select>
    </div>
   <!--  <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Elija opción a actualizar(*): </label>
     <select name="tipo_act" id="tipo_act" class="form-control selectpicker" onchange="mostrarfa()" required>
      <option value="" disabled="disabled" >Seleccione Opcion</option>
      <option value="costo">Costo</option>
       <option value="utilidad">Utilidad</option>
     </select>
    </div>
       <div id="actutilidad" class="form-group col-lg-6 col-md-6 col-xs-12"> -->
 <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Utilidad Precio 1 (%)</label>
      <input class="form-control" type="number" step="any"  name="por1" id="por1" >
    </div>
     
    <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Utilidad Precio 2 (%)</label>
      <input class="form-control" type="number" step="any" name="por2" id="por2"  >
    </div>
    
     <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Utilidad Precio 3 (%)</label>
      <input class="form-control" type="number" step="any" name="por3" id="por3"  >
    </div>
   
   <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Utilidad precio 4 (%)</label>
      <input class="form-control" type="number" step="any" name="por4" id="por4"  >
    </div>
      
     
    
       <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Porcentaje del Costo (-/+)%</label>
      <input class="form-control" type="number" step="any"  name="porcost" id="porcost" >
    </div>
    <!--  </div> -->
     
    

       
   
    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <button class="btn btn-primary" type="submit" id="btnActualizar"><i class="fa fa-save"></i>  Actualizar</button>

      <button class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
    </div>
  </form>
</div>

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
 <script src="scripts/categoria.js"></script>
 <?php 
}

ob_end_flush();
  ?>

