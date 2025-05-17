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
<!-- <div class="box-header with-border">
  <h1 class="box-title">Ingresos <button class="btn btn-success" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i>Agregar</button></h1>
  <div class="box-tools pull-right">
    
  </div> -->
</div>
<!--box-header-->
<!--centro-->
<div class="panel-body table-responsive" id="listadoregistros">
  <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
    <thead>
      <th>Opciones</th>
      <th>Proveedor</th>
      <th>Id Ingreso</th>
      <th>Nro Comprobante</th>
      <th>Estado</th>
      <th>Monto Pagado</th>
      <th>Saldo</th>
      <th>Total</th>
      
    </thead>
    <tbody>
    </tbody>
    <tfoot>
 <th>Opciones</th>
      <th>Proveedor</th>
      <th>Id Ingreso</th>
      <th>Nro Comprobante</th>
      <th>Estado</th>
      <th>Monto Pagado</th>
      <th>Saldo</th>
      <th>Total</th>
    </tfoot>   
  </table>
</div>
<div class="panel-body" style="height: 100%;" id="formularioregistros">
  <form action="" name="formulario" id="formulario" method="POST">
          <input class="form-control" type="hidden" name="idingreso" id="idingreso">
           <input class="form-control" type="hidden" name="idproveedor" id="idproveedor">
       <fieldset>
<legend>Datos del Pedido</legend>
            <table class="table table-striped">
                <tbody id="tablita">

            </tbody>
    </table>
 
   </fieldset>
  
     <div id="agregarpago">
   <table class="table table-striped" >
      <h4>Agregar un pago </h4>
   <tr>
      <td>
      <label for="" id="titmonto">Monto (*): </label>
    </td>
    <td>
      <input class="form-control" type="num" step="any" name="monto" id="monto" maxlength="7" required>
    </td>
    <td>
      <button type="button" onclick="registrarPago()" class="btn btn-primary" id="btnregistrar" >Registrar</button>
    </td>
    <td>
      <button class="btn btn-danger" onclick="cancelarform()" type="button" id="btnCancelar"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
      </td>
    </tr>

 </table>
</div>
</form>
  <fieldset>
      <legend> Listado de pagos</legend>
<div class="panel-body table-responsive" >
  <table id="tblpagos" class="table table-striped table-bordered table-condensed table-hover">
    <thead>
      <th>N° de registro</th>
      <th>Usuario</th>
      <th>Fecha </th>
      <th>Monto</th>
      
      
    </thead>
    <tbody>
    </tbody>
    <tfoot>
 <th>N° de Registro</th>
 <th>Usuario</th>
      <th>Fecha </th>
      <th>Monto</th>
      
    </tfoot>   
  </table>
</fieldset>
<button class="btn btn-danger" onclick="cancelarform()" type="button" id="btnCancelar"><i class="fa fa-arrow-circle-left"></i> Volver</button>
</div>
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
 <script src="scripts/ctacteproveedor.js"></script>

 <?php 
}

ob_end_flush();
  ?>

