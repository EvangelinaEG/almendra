<?php
//activamos almacenamiento en el buffer
ob_start();

session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.html");
}else{


require 'header.php';

if ($_SESSION['ventas']==1) {

 ?>
    <div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="row">
        <div class="col-md-12">
      <div class="box">
<div class="box-header with-border">
  <h1 class="box-title">Cuenta Corriente de Clientes <!-- <button class="btn btn-success" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i>Agregar</button> --></h1>
  <div class="box-tools pull-right">
    
  </div> 
</div>
<!--box-header-->
<!--centro-->
<div class="panel-body table-responsive" id="listadoregistros">
  <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
    <thead>
      <th>Opciones</th>
      <th>Cliente</th>
      <th>Suma de Pagos</th>
      <th>Saldo</th>
      <th>Total</th>
      
    </thead>
    <tbody>
    </tbody>
    <tfoot>
 <th><h4>TOTALES</h4></th>
         <th></th>
         <th></th>
        
         <th><h4 id="saldo" style="color:#000;font-weight: bold;"></h4></th>
         <th><h4 id="total" style="color:#000;font-weight: bold;"></h4></th>
    </tfoot>   
  </table>
</div>
<div class="panel-body" style="height: 100%;" id="formularioregistros">
  <form action="" name="formulario" id="formulario" method="POST">
       <h4>Registro de pagos de <strong id="idcli"></strong></h4>
       <hr>
           <input class="form-control" type="hidden" name="idcliente" id="idcliente">
      
     <div id="agregarpago">
   <table class="table table-striped" >
      <h4>Agregar un pago </h4>
   <tr>
      <td>
      <label for="" id="titmonto">Monto (*): </label>
    </td>
    <td>
      <input class="form-control" type="num" step="any" name="monto" id="monto" required>
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
  <?php 
date_default_timezone_set('America/Argentina/Buenos_Aires');
  setlocale(LC_TIME,"es_ES");
?>
  <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
    <input type="hidden" name="idcliente" value="<?php echo $_SESSION['cliente']; ?>" id="idcliente" >
    <label>Fecha Inicio</label>
    <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" value="<?php echo date("Y-m-d"); ?>">
  </div>
  <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
    <label>Fecha Fin</label>
    <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" value="<?php echo date("Y-m-d"); ?>">
  </div>
  <table id="tblpagos" class="table table-striped table-bordered table-condensed table-hover">
    <thead>
      <th>N° de registro</th>
      <th>Usuario</th>
     <th>Fecha </th>
      <th>Deudor</th>
      <th>Acreedor</th>
      <th>Saldo</th>
        <th>Comprobante/Eliminar</th>
      
      
    </thead>
    <tbody>
    </tbody>
    <tfoot>
 <th>N° de Registro</th>
 <th>Usuario</th>
      <th>Fecha </th>
      <th>Deudor</th>
      <th>Acreedor</th>
      <th>Saldo</th>
        <th>Comprobante/Eliminar</th>
     
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
 <script src="scripts/ctactecliente.js"></script>

 <?php 
}

ob_end_flush();
  ?>

