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
      <div class="">
<div class="box-header with-border">
  <h1 class="box-title">Articulo <button class="btn btn-success" onclick="mostrarform(true)" id="btnagregar"><i class="fa fa-plus-circle"></i>Agregar</button> 
  <a target="_blank" href="../reportes/rptarticulos.php"><button class="btn btn-info">Reporte</button></a></h1>
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
      <th>Categoria</th>
      <th>Codigo</th>
      <th>Desc.</th>
      <th>P. de Pedido</th>
      <th>Costo</th>
      <th>P.Vta 1</th>
      <th>P.Vta 2</th>
      <th>P.Vta 3</th>
      <th>P.Vta 4</th>
     
      
      <th>Estado</th>
    </thead>
    <tbody>
    </tbody>
    <tfoot>
      <th>Opciones</th>
      <th>Nombre</th>
      <th>Categoria</th>
      <th>Codigo</th>
      <th>Desc.</th>
      <th>P. de Pedido</th>
      <th>Costo</th>
      <th>P.Vta 1</th>
      <th>P.Vta 2</th>
      <th>P.Vta 3</th>
      <th>P.Vta 4</th>
     
  </table>
</div>
<div class="panel-body" id="formularioregistros">
  <form action="" name="formulario" id="formulario" method="POST">
    <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Nombre(*):</label>
      <input class="form-control" type="hidden" name="idarticulo" id="idarticulo">
      <input class="form-control" type="text" name="nombre" id="nombre"  style="text-transform: uppercase;" maxlength="100" placeholder="Nombre" required>
    </div>
         <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Descripcion</label>
      <input class="form-control" type="text" name="descripcion" id="descripcion" maxlength="256" placeholder="Descripcion">
    </div>
    <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Categoria(*):</label>
      <select name="idcategoria" id="idcategoria" class="form-control selectpicker" data-Live-search="true" required></select>
    </div>
     <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Proveedor:</label>
      <select name="idproveedor" id="idproveedor" class="form-control selectpicker" data-Live-search="true" ></select>
    </div>
       <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Stock</label>
      <input class="form-control" type="number" min="0" name="stock" id="stock" value="0">
    </div>
    <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Punto de pedido</label>
      <input class="form-control" type="number" min="0" name="puntopedido" id="puntopedido"  value="0">
    </div>
  
 
  
    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i>  Guardar</button>

      <button class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
    </div>
  </form>
</div>

<div class="panel-body" id="actualizarPrecios">
  <form action="" name="formactualizar" id="formactualizar" method="POST">
    <div class="form-group col-lg-12 col-md-12 col-xs-12">
      <label for="">Nombre(*):</label>
      <input class="form-control" type="hidden" name="idarticuloa" id="idarticuloa">
      <input class="form-control" type="text" name="nombre" id="nombrea" maxlength="100" placeholder="Nombre" disabled="disabled">
    </div>
  <!--   <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Elija opción a actualizar(*): </label>
     <select name="tipo_act" id="tipo_act" class="form-control selectpicker" onchange="mostrarfa()" required>
      <option value="" disabled="disabled" >Seleccione Opcion</option>
      <option value="costo">Costo</option>
       <option value="utilidad">Utilidad</option>
     </select>
    </div> -->
   
    
    
        
           <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Porcentaje del Costo (-/+)%</label>
      <input class="form-control" type="number" step="any" name="porcosto" id="porcosto" onkeyup="actprecio(this)">
    </div>
               <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Costo</label>
      <input class="form-control" type="number" step="any" min="0"  name="costoa" id="costoa" onkeyup="actprecio(this)"">
    </div>

 <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Utilidad % 1</label>
      <input class="form-control" type="number" step="any" min="0"  name="por1" id="por1" onkeyup="actprecio(this)">
    </div>
     <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">precio 1</label>
      <input class="form-control" type="number" step="any" min="0"  name="precio1" id="precio1" onkeyup="actprecio(this)">
    </div>
     <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Utilidad % 2</label>
      <input class="form-control" type="number" step="any" min="0" name="por2" id="por2" onkeyup="actprecio(this)" >
    </div>
    <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">precio 2</label>
      <input class="form-control" type="number" step="any" min="0"  name="precio2" id="precio2"  onkeyup="actprecio(this)">
    </div>
     <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Utilidad %  3</label>
      <input class="form-control" type="number" step="any" min="0" name="por3" id="por3" onkeyup="actprecio(this)" >
    </div>
    <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">precio 3</label>
      <input class="form-control" type="number" step="any" min="0" step="any"  name="precio3" id="precio3" onkeyup="actprecio(this)" >
    </div>
   <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Utilidad %  4</label>
      <input class="form-control" type="number" step="any" min="0" name="por4" id="por4" onkeyup="actprecio(this)"  >
    </div>
 <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">precio 4</label>
      <input class="form-control" type="number" step="any" min="0" name="precio4" id="precio4" onkeyup="actprecio(this)" >
  </div>     
     
     <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Coódigo de barras:</label>
      <input class="form-control" type="text" name="codigo" id="codigo" placeholder="codigo del producto" >
      <button class="btn btn-success" type="button" onclick="generarbarcode()">Generar</button>
      <button class="btn btn-info" type="button" onclick="imprimir()">Imprimir</button>
      <div id="print">
        <svg id="barcode"></svg>
      </div>
    </div>

       
    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <button class="btn btn-primary" type="submit" id="btnActualizar"><i class="fa fa-save"></i>  Actualizar</button>

      <button class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
    </div>
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
require 'footer.php'
 ?>
 <script src="../public/js/JsBarcode.all.min.js"></script>
 <script src="../public/js/jquery.PrintArea.js"></script>
 <script src="scripts/articulo.js"></script>

 <?php 
}

ob_end_flush();
  ?>