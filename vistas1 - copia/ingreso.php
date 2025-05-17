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
  <h1 class="box-title">Ingresos <button class="btn btn-success" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i>Agregar</button></h1>
  <div class="box-tools pull-right">
    
  </div>
</div>
<!--box-header-->
<!--centro-->
<div class="panel-body table-responsive" id="listadoregistros">
  <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
    <thead>
      <th>Opciones</th>
      <th>Fecha</th>
      <th>Proveedor</th>
      <th>Usuario</th>
      <th>Documento</th>
      <th>Serie</th>
      <th>Número</th>
      <th>Total Compra</th>
      <th>Estado</th>
    </thead>
    <tbody>
    </tbody>
    <tfoot>
      <th>Opciones</th>
      <th>Fecha</th>
      <th>Proveedor</th>
      <th>Usuario</th>
      <th>Documento</th>
      <th>Serie</th>
      <th>Número</th>
      <th>Total Compra</th>
      <th>Estado</th>
    </tfoot>   
  </table>
</div>
<div class="panel-body" style="height: 100%;" id="formularioregistros">
  <form action="" name="formulario" id="formulario" method="POST">
       <fieldset>
<legend>Datos del Proveedor</legend>
    <div class="form-group col-lg-8 col-md-8 col-xs-12">
      <label for="">Proveedor(*):</label>
      <input class="form-control" type="hidden" name="idingreso" id="idingreso">
      <select name="idproveedor" id="idproveedor" class="form-control selectpicker" data-live-search="true" required>
          <option value="" disabled="disabled" >Seleccione Opcion</option>
      </select>
    </div>
      <div class="form-group col-lg-4 col-md-4 col-xs-12">
      <label for="">Fecha(*): </label>
      <input class="form-control" type="date" name="fecha_hora" id="fecha_hora" required>
    </div>
     <div class="form-group col-lg-3 col-md-3 col-xs-12">
      <label for="">Tipo Comprobante(*): </label>
     <select name="tipo_comprobante" id="tipo_comprobante" class="form-control selectpicker" required>
      <option value="" disabled="disabled" >Seleccione Opcion</option>
      <option value="Presupuesto">Pago total</option>
     
       
       <option value="Ticket">Pago parcial</option>
     </select>
    </div>
    <div class="form-group col-lg-3 col-md-3 col-xs-12">
      <label for="" id="titmonto">Monto a pagar(*): </label>
      <input class="form-control" type="number" step="any" name="monto" id="monto" maxlength="7" required>
    </div>
     <div class="form-group col-lg-3 col-md-3 col-xs-12">
      <label for="">Serie: </label>
      <input class="form-control" type="text" name="serie_comprobante" id="serie_comprobante" maxlength="7" placeholder="Serie">
    </div>

     <div class="form-group col-lg-3 col-md-3 col-xs-12">
      <label for="">Número(*): </label>
      <input class="form-control" type="number" name="num_comprobante" id="num_comprobante" maxlength="10" placeholder="Número" required>
    </div>
    <div class="form-group col-lg-4 col-md-4 col-xs-12">
      <label for="">Impuesto : </label>
      <input class="form-control" onkeyup="calcularTotales()" type="number" step="any" name="impuesto" id="impuesto" value="0">
    </div>
     <div class="form-group col-lg-4 col-md-4 col-xs-12">
      <label for="">varios : </label>
      <input class="form-control" type="number"   onkeyup="calcularTotales()" step="any" name="varios" id="varios" value="0">
    </div>
     <div class="form-group col-lg-4 col-md-4 col-xs-12">
      <label for="">otros: </label>
      <input class="form-control" type="number"  onkeyup="calcularTotales()"  step="any"  name="otros" id="otros" value="0">
    </div>
   
   </fieldset>
    <fieldset>
<legend>Detalles del Pedido</legend>


     <div class="form-group col-lg-2 col-md-2 col-xs-2">
      <label for="" id="tit">Ingrese código de barras: </label>
    </div>
    <div class="form-group col-lg-3 col-md-3 col-xs-3">
     
    <input name="codigon" id="codigon" onkeypress="validarcod(event)" autofocus>
    <!-- <button name="buscar" type="button"  ><span class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span></button>       --></div>
    <!--  <div class="form-group col-lg-6 col-md-3 col-xs-6" id="agregararticulo" style="background-color: #CCCCCC; padding: 5px;">
      <div class="col-md-3">
      <input type="text" name="nombrepro" placeholder="Nombre"  style="text-transform: uppercase;" id="nombrepro" class="form-control">
      </div>
      <div class="col-md-3">
      <input type="text" name="codpro" id="codpro" placeholder="Codigo de Barras" class="form-control">
      </div>
       <div class="col-md-3">
        <select name="idcategoria" id="idcategoria" class="form-control selectpicker" data-Live-search="true"></select>
      </div>

      <div class="col-md-3">
      <button type="button" class="btn btn-primary" onclick="agregarprod()">Agregar</button>
      </div>

     </div> -->
   
<div class="form-group col-lg-12 col-md-12 col-xs-12">
     <table id="detalles" name="detalles" class="table table-striped table-bordered table-condensed table-hover">
       <thead style="background-color:#A9D0F5">
        <th>Opciones</th>
        <th>Articulo</th>
        <th>Cantidad</th>
        <th>Precio Compra</th>
       
        <th>Subtotal</th>
       </thead>
       <tfoot>
         <th>TOTAL</th>
         <th></th>
         <th></th>
         <th></th>
         
         <th><h4 id="total"> 0.00</h4><input type="hidden" name="total_compra" id="total_compra"></th>
       </tfoot>
       <tbody>
         
       </tbody>
     </table>


    </div>
    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <button class="btn btn-primary" type="button"  onclick="validarFormulario()" id="btnGuardar"><i class="fa fa-save"></i>  Guardar</button>
      <button class="btn btn-danger" onclick="cancelarform()" type="button" id="btnCancelar"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
    </div>
  </fieldset>
  </form>
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
 <script src="scripts/ingreso.js"></script>

 <?php 
}

ob_end_flush();
  ?>

