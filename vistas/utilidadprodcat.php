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
  <h1 class="box-title">Utilidad por fecha y Categoria</h1>
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
  <div class="form-inline col-lg-6 col-md-6 col-sm-6 col-xs-12">
    <label>Categoria</label>
    <select name="idcategoria" id="idcategoria" class="form-control selectpicker" data-live-search="true" required>
    </select>
  </div>
    <br>
   <!--  <button class="btn btn-success" onclick="listar()">
      Mostrar</button> -->
  
  
  <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover" border="1">
<div class="form-group  col-xs-12">
<button onclick="exportTableToExcel('tbllistado', 'Utilidades por categoria')">Exportar a Excel</button>
</div>
    <thead>
      <th style="color:#000;font-weight: bold; background-color: #B1B3B4">id Art</th>
      <th style="color:#000;font-weight: bold; background-color: #B1B3B4" width="300">Nombre</th>
      <th style="color:#000;font-weight: bold; background-color: #B1B3B4">Descripcion</th>
       <th style="color:#000;font-weight: bold; background-color: #B1B3B4">Cantidad</th>
      <th style="color:#000;font-weight: bold; background-color: #B1B3B4">Utilidad</th>
   
  
    </thead>
    <tbody >
    </tbody>
     <tfoot>
 <th><h4>TOTALES</h4></th>
         <th></th>
         <th></th>
        
         <th><h4 id="cantidadtotal" style="color:#000;font-weight: bold;"></h4></th>
         <th><h4 id="total" style="color:#000;font-weight: bold;"></h4></th>
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
 <script src="scripts/utilidadprodscat.js"></script>
 <script type="text/javascript">
   
   function exportTableToExcel(tableID, filename = ''){
    var downloadLink;
    var dataType = 'application/vnd.ms-excel';
    var tableSelect = document.getElementById(tableID);
  /* tableSelect = tableSelect + '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
 tableSelect = tableSelect + '<head><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet>';
 tableSelect = tableSelect + '<x:Name>Test Sheet</x:Name>';
 tableSelect = tableSelect + '<x:WorksheetOptions><x:Panes></x:Panes></x:WorksheetOptions></x:ExcelWorksheet>';
 tableSelect = tableSelect + '</x:ExcelWorksheets></x:ExcelWorkbook></xml></head><body>';
 tableSelect = tableSelect + "<table border='1px'>";*/
 
    var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
   /* tableHTML = tableHTML + '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
 tableHTML = tableHTML + '<head><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet>';
 tableHTML = tableHTML + '<x:Name>Test Sheet</x:Name>';
 tableHTML = tableHTML + '<x:WorksheetOptions><x:Panes></x:Panes></x:WorksheetOptions></x:ExcelWorksheet>';
 tableHTML = tableHTML + '</x:ExcelWorksheets></x:ExcelWorkbook></xml></head><body>';
 tableHTML = tableHTML + "<table border='1px'>";*/
    // Specify file name
    filename = filename?filename+'.xls':'excel_data.xls';
    
    // Create download link element
    downloadLink = document.createElement("a");
    
    document.body.appendChild(downloadLink);
    
    if(navigator.msSaveOrOpenBlob){
        var blob = new Blob(['ufeff', tableHTML], {
            type: dataType
        });
        navigator.msSaveOrOpenBlob( blob, filename);
    }else{
        // Create a link to the file
        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
    
        // Setting the file name
        downloadLink.download = filename;
        
        //triggering the function
        downloadLink.click();
    }
}
 </script>
 <?php 
}

ob_end_flush();
  ?>

