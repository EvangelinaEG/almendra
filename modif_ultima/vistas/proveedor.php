<?php 
//activamos almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.html");
}else{

require 'header.php';
if ($_SESSION['compras']==1) {
 ?>
    <div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="row">
        <div class="col-md-12">
      <div class="box">
<div class="box-header with-border">
  <h1 class="box-title">Proveedor <button class="btn btn-success" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i>Agregar</button></h1>
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
      <th>Documento</th>
      <th>Numero</th>
      <th>Telefono</th>
      <th>Email</th>
    </thead>
    <tbody>
    </tbody>
    <tfoot>
      <th>Opciones</th>
      <th>Nombre</th>
      <th>Documento</th>
      <th>Numero</th>
      <th>Telefono</th>
      <th>Email</th>
    </tfoot>   
  </table>
</div>
<div class="panel-body" style="height: 400px;" id="formularioregistros">
  <form action="" name="formulario" id="formulario" method="POST">
    <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Nombre</label>
      <input class="form-control" type="hidden" name="idpersona" id="idpersona">
      <input class="form-control" type="hidden" name="tipo_persona" id="tipo_persona" value="Proveedor">
      <input class="form-control" type="text" name="nombre" id="nombre" maxlength="100"  style="text-transform: uppercase;" placeholder="Nombre del proveedor" required>
    </div>
     <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Tipo Dcumento</label>
     <select class="form-control select-picker" name="tipo_documento" id="tipo_documento" required>
       <option value="DNI">DNI</option>
       <option value="CUIT">CUIT</option>
          </select>
    </div>
     <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Número Documento</label>
      <input class="form-control" type="text" name="num_documento" id="num_documento" maxlength="20" placeholder="Número de Documento">
    </div>
    <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Direccion</label>
      <input class="form-control" type="text" name="direccion" id="direccion" maxlength="70" placeholder="Direccion">
    </div>
    <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Telefono</label>
      <input class="form-control" type="text" name="telefono" id="telefono" maxlength="20" placeholder="Número de Telefono">
    </div>
        <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Email</label>
      <input class="form-control" type="email" name="email" id="email" maxlength="50" placeholder="Email">
    </div>
    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i>  Guardar</button>

      <button class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
    </div>
  </form>
</div>
<!--fin centro-->
<div class="panel-body" id="actualizarPreciosprov">
  <form action="" name="formactP" id="formactP" method="POST">
    <div class="form-group col-lg-3 col-md-3 col-xs-12">
      <label for="">Proveedor:</label>
      <input class="form-control" type="hidden" name="idproveedoru" id="idproveedoru">
      <input class="form-control" type="text" name="nombreprov" id="nombreprov" maxlength="100" placeholder="Proveedor" disabled="disabled">
    </div>
      <div class="form-group col-lg-3 col-md-3 col-xs-12">
      <label for="">Categoria:</label>
      <select name="idcategoria" id="idcategoria" class="form-control selectpicker" data-Live-search="true" required></select>
    </div>
    <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Elija opción a actualizar(*): </label>
     <select name="tipo_act" id="tipo_act" class="form-control selectpicker" onchange="mostrarfa()" required>
      <option value="" disabled="disabled" >Seleccione Opcion</option>
      <option value="costo">Costo</option>
       <option value="utilidad">Utilidad</option>
     </select>
    </div>
       <div id="actutilidad" class="form-group col-lg-6 col-md-6 col-xs-12">
 <div class="form-group col-lg-12 col-md-12 col-xs-12">
      <label for="">Utilidad Precio 1 (%)</label>
      <input class="form-control" type="number"  name="por1" id="por1" >
    </div>
     
     <div class="form-group col-lg-12 col-md-12 col-xs-12">
      <label for="">Utilidad Precio 2 (%)</label>
      <input class="form-control" type="number" name="por2" id="por2"  >
    </div>
    
     <div class="form-group col-lg-12 col-md-12 col-xs-12">
      <label for="">Utilidad Precio 3 (%)</label>
      <input class="form-control" type="number" name="por3" id="por3"  >
    </div>
   
   <div class="form-group col-lg-12 col-md-12 col-xs-12">
      <label for="">Utilidad precio 4 (%)</label>
      <input class="form-control" type="number" name="por4" id="por4"  >
    </div>
      
     
     </div>
     <div id="actcosto">
       <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Porcentaje del Costo (-/+)%</label>
      <input class="form-control" type="number"  name="porcost" id="porcost" >
    </div>
     </div>
     
    

       
   
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
 <script src="scripts/proveedor.js"></script>
 <?php 
}

ob_end_flush();
  ?>
