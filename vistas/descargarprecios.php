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
							<script src="scripts/ventasfechacliente.js"></script>
							 <div class="content-wrapper">
						    <!-- Main content -->
						    <section class="content">

						      <!-- Default box -->
						      <div class="row">
						        <div class="col-md-12">
						      <div class="box">
						<div class="box-header with-border">
							<h3>Seleccione los datos que desea mostrar de los productos</h3>

							<form action="listadoArticulos.php" name="formulario" id="formulario" method="POST" target="_self">
								 <div class="form-inline col-lg-6 col-md-6 col-sm-6 col-xs-12">
						    <label>Categoria</label>
						    <select name="idcategoria" id="idcategoria" class="form-control selectpicker" data-live-search="true" required>
						    </select>
						    <br>
						   <!--  <button class="btn btn-success" onclick="listar()">
						      Mostrar</button> -->
						  </div>
						   <div class="form-inline col-lg-6 col-md-6 col-sm-6 col-xs-12">
						    <label>Proveedor</label>
						    <select name="idproveedor" id="idproveedor" class="form-control selectpicker" data-live-search="true" required>
						    </select>
						    <br>
						   <!--  <button class="btn btn-success" onclick="listar()">
						      Mostrar</button> -->
						  </div>
							<!-- <input type="checkbox" id="id" name="id" checked value="1"> ID <br> 
						 	<input type="checkbox" id="cod" name="cod" value="1"> CODIGO DE BARRA <br> -->
							<input type="checkbox" id="pl1" name="pl1" checked value="1"> PRECIO DE LISTA 1 <br>
							<input type="checkbox" id="pl2" name="pl2" checked value="1"> PRECIO DE LISTA 2 <br>
							<input type="checkbox" id="pl3" name="pl3" value="1"> PRECIO DE LISTA 3 <br>
							<input type="checkbox" id="pl4" name="pl4" value="1"> PRECIO DE LISTA 4 <br>

							<input type="submit" name="enviar" value="MOSTRAR LISTADO">
							</form>
						</div>
						</div>
						</div>
						</div>
						</section>
						</div>
						
						<?php 
						require 'footer.php';

			}else{
			 require 'noacceso.php'; 
			}
			?>
<script src="scripts/ventasfechacliente.js"></script>
<?php
}
 

ob_end_flush();
  ?>

