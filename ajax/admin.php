<?php

function backupDatabaseTables($dbHost,$dbUsername,$dbPassword,$dbName,$tables = '*'){
    //connect & select the database
    $db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName); 

    /*$files = glob('../backups/*'); // obtiene todos los archivos
    foreach($files as $file){
      if(is_file($file)) // si se trata de un archivo
        unlink($file); // lo elimina
    }*/
    //get all of the tables
    if($tables == '*'){
        $tables = array();
        $result = $db->query("SHOW TABLES");
        while($row = $result->fetch_row()){
            $tables[] = $row[0];
        }
    }else{
        $tables = is_array($tables)?$tables:explode(',',$tables);
    }

    $return = '';
    foreach($tables as $table){

        $result = $db->query("SELECT * FROM $table");
        $numColumns = $result->field_count;
       
        $return .= "DROP TABLE $table;";

        $result2 = $db->query("SHOW CREATE TABLE $table");
        $row2 = $result2->fetch_row();

        $return .= "".$row2[1].";";

        for($i = 0; $i < $numColumns; $i++){
            while($row = $result->fetch_row()){
                $return .= "INSERT INTO $table VALUES(";
                for($j=0; $j < $numColumns; $j++){
                    $row[$j] = addslashes($row[$j]);
                    //$row[$j] = ereg_replace("n","n",$row[$j]);
                    if (isset($row[$j])) { $return .= '"'.$row[$j].'"' ; } else { $return .= '""'; }
                    if ($j < ($numColumns-1)) { $return.= ','; }
                }
                $return .= ");";
            }
        }

        $return .= "";
    }
    $fecha = date("Ymd-His");
    //save file
    $handle = fopen('db-backup-'.$fecha.'.sql','w+');
    fwrite($handle,$return);
    fclose($handle);
}
	backupDatabaseTables('localhost','root','','dbsistemas');
	 $fecha = date("Ymd-His");
	 //Obtenemos la fecha y hora para identificar el respaldo
 
	// Construimos el nombre de archivo SQL Ejemplo: mibase_20170101-081120.sql
	$salida_sql = 'db-backup-'.$fecha.'.sql'; 
	
	//Comando para genera respaldo de MySQL, enviamos las variales de conexion y el destino
	//$dump = "mysqldump --h$db_host -u$db_user -p$db_pass --opt $db_name > $salida_sql";
	//system($dump, $output); //Ejecutamos el comando para respaldo
	
	$zip = new ZipArchive(); //Objeto de Libreria ZipArchive
	
	//Construimos el nombre del archivo ZIP Ejemplo: mibase_20160101-081120.zip
	$salida_zip = 'db-backup-'.$fecha.'.zip';
	
	$destino = '../backups/'.$salida_zip;
	if($zip->open($salida_zip,ZIPARCHIVE::CREATE)===true) { //Creamos y abrimos el archivo ZIP
		$zip->addFile($salida_sql); //Agregamos el archivo SQL a ZIP
		$zip->close(); //Cerramos el ZIP
		unlink($salida_sql); //Eliminamos el archivo temporal SQL
		//header ("Location: $salida_zip"); // Redireccionamos para descargar el Arcivo ZIP
		copy($salida_zip, $destino);
		unlink($salida_zip);
		echo $destino;		
		} else {
		echo 'Error'; //Enviamos el mensaje de error
	}
?>