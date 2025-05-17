 <?php 
 require_once "../config/global.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'backup') {
  // Aquí llamas a tu función backupDatabase
  $result = backupDatabase("dbsistemas3", "root", "", "D:/dbsistemas3_backup.sql");
  
  if ($result) {
      echo "Backup realizado exitosamente.";
  } else {
      echo "Hubo un error al realizar el backup.";
  }
  exit; // Termina el script para evitar que se ejecute el resto del código
}

function backupDatabase($dbname, $user, $password, $backup_file) {
  // Conectar a la base de datos
  $conn = new mysqli('localhost', $user, $password, $dbname);

  if ($conn->connect_error) {
      return "Error de conexión: " . $conn->connect_error;
  }

  // Obtener todas las tablas
  $tables = [];
  $result = $conn->query("SHOW TABLES");

  while ($row = $result->fetch_array()) {
      $tables[] = $row[0];
  }

  // Depuración: Imprimir tablas
  if (empty($tables)) {
      return "No se encontraron tablas en la base de datos.";
  }

  // Abrir el archivo para escribir el respaldo
  $backupFileHandle = fopen($backup_file, 'w');
  if ($backupFileHandle === false) {
      return "Error al abrir el archivo de backup.";
  }

  // Recorrer las tablas y crear el dump
  foreach ($tables as $table) {
      $result = $conn->query("SELECT * FROM $table");
      if (!$result) {
          fclose($backupFileHandle);
          return "Error en la consulta: " . $conn->error;
      }

      // Escribir DROP TABLE
      fwrite($backupFileHandle, "DROP TABLE IF EXISTS $table;\n");
      
      // Escribir CREATE TABLE
      $createTableResult = $conn->query("SHOW CREATE TABLE $table");
      $createTableRow = $createTableResult->fetch_row();
      fwrite($backupFileHandle, "\n" . $createTableRow[1] . ";\n\n");

      // Escribir INSERT INTO para cada fila
      if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              $sqlDump = "INSERT INTO $table VALUES(";
              foreach ($row as $value) {
                  $sqlDump .= $value === null ? "NULL," : "'" . $conn->real_escape_string($value) . "',";
              }
              $sqlDump = rtrim($sqlDump, ',') . ");\n";
              fwrite($backupFileHandle, $sqlDump);
          }
          fwrite($backupFileHandle, "\n");
      } else {
          fwrite($backupFileHandle, "-- La tabla $table está vacía.\n\n");
      }
  }

  fclose($backupFileHandle);
  $conn->close();
  return "Backup realizado exitosamente.";
}


  ?>
 <!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Alimentos Vitales | Escritorio</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="../public/css/bootstrap.min.css">
  <!-- Font Awesome -->

  <link rel="stylesheet" href="../public/css/font-awesome.min.css">
  <link rel="stylesheet" href="../public/css/styles.css">

  <link rel="stylesheet" href="../public/css/AdminLTE.min.css">
  <link rel="stylesheet" href="../public/css/_all-skins.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- Morris chart --><!-- Daterange picker -->
 <!-- <link rel="stylesheet" href="img/apple-touch-ico.png"> -->
 <!-- <link rel="stylesheet" href="img/favicon.ico"> -->
<!-- DATATABLES-->
 <link rel="stylesheet" href="../public/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="../public/datatables/buttons.dataTables.min.css"> 
<link rel="stylesheet" href="../public/datatables/responsive.dataTables.min.css">
<link rel="stylesheet" href="../public/css/bootstrap-select.min.css">
<link rel="stylesheet" href="../public/css/jquery-ui.css">
<link rel="stylesheet" href="../public/css/estilos.css">

 <style>
  .ui-autocomplete {
    max-height: 100px;
    overflow-y: auto;
    /* prevent horizontal scrollbar */
    overflow-x: hidden;
  }
  /* IE 6 doesn't support max-height
   * we use height instead, but this forces the menu to always be this tall
   */
  * html .ui-autocomplete {
    height: 100px;
  }
  </style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <div id="loader" style="display:none;">
        <i class="fas fa-spinner fa-spin" style="font-size: 24px;"></i>
        <p>Generando backup, por favor espera...</p>
    </div>
    <!-- Logo -->
    <a href="escritorio.php" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>A</b> V</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Alimentos</b> Vitales</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">NAVEGACIÓN</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">

          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="../files/usuarios/<?php echo $_SESSION['imagen']; ?>" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo $_SESSION['nombre']; ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="../files/usuarios/<?php echo $_SESSION['imagen']; ?>" class="img-circle" alt="User Image">

                
                  
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                <!--   <a href="#" class="btn btn-default btn-flat">Perfil</a> -->
                </div>
                <div class="pull-right">
                  <a href="../ajax/usuario.php?op=salir" class="btn btn-default btn-flat">Salir</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->

        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
     
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">

<br>
 <?php 
if ($_SESSION['escritorio']==1) {
  echo ' <li><a href="escritorioadmin.php"><i class="fa  fa-dashboard (alias)"></i> <span>Escritorio</span></a>
        </li>';
}
        ?> 
     
               <?php 
if ($_SESSION['almacen']==1) {
  echo ' <li class="treeview">
          <a href="#">
            <i class="fa fa-laptop"></i> <span>Almacen</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="articulo.php"><i class="fa fa-circle-o"></i> Articulos</a></li>
             <li><a href="faltantes.php"><i class="fa fa-circle-o"></i> Faltantes de stock</a></li>

            <li><a href="categoria.php"><i class="fa fa-circle-o"></i> Categorias</a></li>
            <li><a href="descargarprecios.php" ><i class="fa fa-circle-o"></i> Descargar lista de precios</a></li>
          </ul>
        </li>';
}
        ?>
               <?php 
if ($_SESSION['compras']==1) {
  echo ' <li class="treeview">
          <a href="#">
            <i class="fa fa-th"></i> <span>Compras</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="ingreso.php"><i class="fa fa-circle-o"></i> Ingresos</a></li>
            <li><a href="proveedor.php"><i class="fa fa-circle-o"></i> Proveedores</a></li>
            <li><a href="ctacteproveedor.php"><i class="fa fa-circle-o"></i> Cta Cte Proveedores</a></li>
          </ul>
        </li>';
}
        ?>
        
               <?php 
if ($_SESSION['ventas']==1) {
  echo '<li class="treeview">
          <a href="#">
            <i class="fa fa-shopping-cart"></i> <span>Ventas</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="venta.php"><i class="fa fa-circle-o"></i> ventas</a></li>
            <li><a href="cliente.php"><i class="fa fa-circle-o"></i> clientes</a></li>
            <li><a href="ctactecliente.php"><i class="fa fa-circle-o"></i> Cta Cte Clientes</a></li>
          </ul>
        </li>';
}
        ?>

                             <?php 
if ($_SESSION['acceso']==1) {
  echo '  <li class="treeview">
          <a href="#">
            <i class="fa fa-folder"></i> <span>Acceso</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="usuario.php"><i class="fa fa-circle-o"></i> Usuarios</a></li>
            <li><a href="permiso.php"><i class="fa fa-circle-o"></i> Permisos</a></li>
          </ul>
        </li>';
}
        ?>  
                                     <?php 
if ($_SESSION['consultac']==1) {
  echo '     <li class="treeview">
          <a href="#">
            <i class="fa fa-bar-chart"></i> <span>Reportes de Ingresos</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="stockprods.php"><i class="fa fa-circle-o"></i>Stock productos</a></li>
          </ul>
        </li>';
}
        ?>  
              
                                                <?php 
if ($_SESSION['consultav']==1) {
  echo '<li class="treeview">
          <a href="#">
            <i class="fa fa-bar-chart"></i> <span>Reportes de Ventas</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
           
            <li><a href="rankingprodcat.php"><i class="fa fa-circle-o"></i>Ranking de Productos</a></li>
       
              <li><a href="utilidadprodcat.php"><i class="fa fa-circle-o"></i>Utilidad de productos</a></li>
              <li><a href="utilidadcategoria.php"><i class="fa fa-circle-o"></i>Utilidad por categoría</a></li>
              
          </ul>
        </li>';
}
        ?>     
            
        
        <li><a href="#"><i class="fa fa-question-circle"></i> <span>Ayuda</span><small class="label pull-right bg-yellow">PDF</small></a></li>
        <li><a href="#"><i class="fa  fa-exclamation-circle"></i> <span>Ayuda</span><small class="label pull-right bg-yellow">IT</small></a></li>
        <li><a href="#" class="btn btn-primary" id="backup"><i class="fa  fa-exclamation-circle"></i>Backup</a></li> 
        <div id="backupMessage"></div>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="../public/js/backup.js"></script>