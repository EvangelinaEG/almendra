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
  <h1 class="box-title">Ventas <button class="btn btn-success" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i>Agregar</button></h1>
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
      <th>Cliente</th>
      <th>Usuario</th>
      <th>Documento</th>
      <th>Tipo Factura</th>
      <th>Número</th>
      <th>Total</th>
      <th>Estado</th>
    </thead>
    <tbody>
    </tbody>
    <tfoot>
      <th>Opciones</th>
      <th>Fecha</th>
      <th>Cliente</th>
      <th>Usuario</th>
      <th>Documento</th>
      <th>Tipo Factura</th>
      <th>Número</th>
      <th>Total</th>
      <th>Estado</th>
    </tfoot>   
  </table>
</div>
<div class="panel-body" style="height: 100%;" id="formularioregistros">
  <form action="" name="formulario" id="formulario" method="POST">
       <fieldset>
<legend>Datos del Cliente</legend>
    <div class="form-group col-lg-12 col-md-12 col-xs-12">
      <label for="">Cliente(*):</label>
      <input class="form-control" type="hidden" name="idventa" id="idventa">
      <select name="idcliente" id="idcliente" class="form-control selectpicker" data-live-search="true" required>

    
      </select>
    </div>
     
     <div class="form-group col-lg-4 col-md-4 col-xs-12">
      <label for="">Tipo de Pago(*): </label>
     <select name="tipo_comprobante" id="tipo_comprobante" class="form-control selectpicker" required>
    
      <option value="Presupuesto">Pago total</option>
     
       
       <option value="Ticket">Pago parcial</option>
     </select>
    </div>
    <div class="form-group col-lg-2 col-md-2 col-xs-6">
      <label for="" id="titmonto">Monto a pagar (*): </label>
      <input class="form-control" type="num" step="any" name="monto" id="monto" maxlength="7" required>
    </div>
   
    <div class="form-group col-lg-2 col-md-2 col-xs-6">
      <label for="">Impuesto: </label>
      <input class="form-control" onkeyup="modificarSubtotales()" type="num" step="any" value="0" name="impuesto" id="impuesto">
    </div>
    <div class="form-group col-lg-2 col-md-2 col-xs-6">
      <label for="">Tipo de Presupuesto: </label>
     <select name="tipopresupuesto" id="tipopresupuesto" class="form-control selectpicker" required>
       <option value="" disabled="disabled" >Seleccione Opcion</option>
      <option value="CF">Consumidor Final</option>
      <option value="A">Factura A</option>
     
     </select>
    </div>
     <!--  <div class="form-group col-lg-2 col-md-2 col-xs-6">
      <label for="">Precio Venta: </label>
     <select name="precio" id="precio" class="form-control selectpicker" required>
       
      <option value="1" selected="selected">Precio de Venta 1</option>
      <option value="2">Precio de Venta 2</option>
      <option value="3">Precio de Venta 3</option>
      <option value="4">Precio de Venta 4</option>
     
     </select>
    </div>   -->
   </fieldset>
    <fieldset>
<legend>Detalles del Pedido</legend>


     <div class="form-group col-lg-2 col-md-2 col-xs-2">
      <label for="" id="tit">Ingrese código de barras: </label>
    </div>
    <div class="form-group col-lg-3 col-md-3 col-xs-3">
      
    <input name="codigon" id="codigon" onkeypress="validarcod(event)" >
    <!-- <button name="buscar" type="button"  ><span class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span></button>       --></div>
    
   
<div class="form-group col-lg-12 col-md-12 col-xs-12">
     <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
       <thead style="background-color:#A9D0F5">
        <th>Opciones</th>
        <th>Articulo</th>
        <th>Cantidad</th>
        <th>Precio</th>
       <th>Descuento</th>
        <th>Iva</th>
        <th>Subtotal</th>
       </thead>
       <tfoot>
         <th>TOTAL</th>
         <th></th>
         <th></th>
         <th></th>
         <th></th>
         <th></th>
         <th><h4 id="total"> 0.00</h4><input type="hidden" name="total_venta" id="total_venta"></th>
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
 <script src="scripts/venta.js"></script>

 <?php 
}

ob_end_flush();
  ?>

