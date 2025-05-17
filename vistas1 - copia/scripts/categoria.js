var tabla;

//funcion que se ejecuta al inicio
function init(){
	limpiar();
   mostrarform(false);
   mostrarformactP(false);
   listar();




   $("#formulario").on("submit",function(e){
   	guardaryeditar(e);
   })

   $("#formactP").on("submit",function(e){
   	 $("#btnActualizar").prop("disabled",true);
    	actualizar(e);
   })
}

//funcion limpiar
function limpiar(){
	$("#idcategoria").val("");
	$("#nombre").val("");
	$("#descripcion").val("");
	$("#tipo_act").val("Seleccione una opcion");
	$("#tipo_act").selectpicker('refresh');
	  

								$("#idcategoriau").val("");
								$("#cost").val("");
}


function buscarProvs(cat){
	$("#idproveedor").empty();	
	$.post("../ajax/categoria.php?op=selectProveedor&c="+cat, function(r){
   	$("#idproveedor").append(r);
var fila="<option value='TODOS' selected='selected' >TODOS</option>";
$('#idproveedor').append(fila);
   	$('#idproveedor').selectpicker('refresh');
   });
}
function mostrarfa() {

      var tipo = document.formactP.tipo_act.value;
	if(tipo == "costo"){
		$("#porcosto").val("");
		$("#actcosto").show();
		$("#actutilidad").hide();
	}else{
		$("#por1").val("");
		$("#por2").val("");
		$("#por3").val("");
		$("#por4").val("");

		$("#actcosto").hide();
		$("#actutilidad").show();
	}      
	$("#btnActualizar").show();
	$("#btnActualizar").prop("disabled",false);
      // tu codigo ajax va dentro de esta function...
    }
//funcion mostrar formulario
function mostrarform(flag){
	limpiar();
	if(flag){
		$("#listadoregistros").hide();
		$("#actualizarPrecioscat").hide();
		$("#formularioregistros").show();
	
		$("#btnGuardar").prop("disabled",false);
		$("#btnagregar").hide();
	}else{
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
		$("#btnagregar").show();
	}
}


function mostrarformactP(flag){
	limpiar();
	if(flag){
		$("#listadoregistros").hide();
		$("#actualizarPrecioscat").show();
		$("#actutilidad").hide();
		$("#actcosto").hide();
			$("#listadoregistros").hide();
			$("#btnActualizar").show();
	$("#btnActualizar").prop("disabled",false);
		$("#btnGuardar").prop("disabled",false);
		$("#btnagregar").show();
	}else{
		$("#listadoregistros").show();
		$("#actualizarPrecioscat").hide();
		$("#actutilidad").hide();
		$("#actualizarPrecios").hide();
		$("#btnagregar").show();
	}
}



function mostrarActP(idcategoria){
 limpiar();
	$.post("../ajax/categoria.php?op=mostrar",{idcategoria : idcategoria},
		function(data,status)
		{
					
			data=JSON.parse(data);
	
			if(data.nombre)
			{
		
			//$("#idcategoria").val(data.idcategoria);
			//$("#idcategoria").selectpicker('refresh');
			
			
			$("#descripcionu").val(data.nombre);

			//$("#stock").val(data.stock);
			//$("#descripcion").val(data.descripcion);
			//$("#imagenmuestra").show();
			//$("#imagenmuestra").attr("src","../files/articulos/"+data.imagen);
			//$("#imagenactual").val(data.imagen);
			

			buscarProvs(data.idcategoria);
			//generarbarcod();
				mostrarformactP(true);
				$("#idcategoriau").val(data.idcategoria);
				
			}
			
			else{
			bootbox.alert("La categoria no está activa.");
		
			}

			
		})
}


function actualizar(e){

	 e.preventDefault();//no se activara la accion predeterminada 
     $("#btnActualizar").prop("disabled",true);

    // if(document.formactP.porcost.value == "")     {
     	if(document.formactP.porcost.value == ""){

     		if(document.formactP.por1.value == "" && document.formactP.por2.value == "" && document.formactP.por3.value == "" && document.formactP.por4.value == ""){
     		bootbox.alert("Debe completar con al menos un porcentaje para actualizar los precios o el costo.");
     		 $("#btnActualizar").prop("disabled",false);
     		}else{

     			var idcate = document.formactP.idcategoriau.value;
 				var idprov = document.formactP.idproveedor.value;
                var porca1 = document.formactP.por1.value;
                var porca2 = document.formactP.por2.value;
                var porca3 = document.formactP.por3.value;
                var porca4 = document.formactP.por4.value;
   
				  $.post("../ajax/categoria.php?op=actPreciosCat",{ idcategoriau: idcate, precioc1: porca1, precioc2: porca2, precioc3: porca3, precioc4: porca4, prov: idprov },
						function(data,status)
						{
				   bootbox.alert(data);
				   mostrarformactP(false);
				     	tabla.ajax.reload();


				     });
				}
		}else{
     	
     	
     		var costo = document.formactP.porcost.value;

				     		var idcate = document.formactP.idcategoriau.value;
				     		var idproveedor = document.formactP.idproveedor.value;
     		bootbox.confirm("ATENCIÓN ! Se actualizaran TODOS los costos de los productos pertenecientes a la categoria y proveedor seleccionados y los precios de venta con el nuevo costo. ¿Desea continuar?", function(result){
		if (result) {
						
   	
				     		
     						
					     	$.post("../ajax/categoria.php?op=actCostoCat",{ idcategoriau: idcate, costou: costo, prov : idproveedor},
							function(data,status)
							{
							
								
					   bootbox.alert(data);
					   limpiar();
					   mostrarformactP(false);
					     		tabla.ajax.reload();


					     });
     				}else{$("#btnActualizar").prop("disabled",false);}
    		 });
     	}
     //}else{

     	/*if(document.formactP.por1.value == "" && document.formactP.por2.value == "" && document.formactP.por3.value == "" && document.formactP.por4.value == ""){
     		bootbox.alert("Debe completar con al menos un porcentaje para actualizar los precios.");
     	}else{*/
     //var formData=new FormData($("#formactualizar")[0]);
 				//}
  $("#porcosto").val("");
  $("#por1").val("");
	$("#por2").val("");
	$("#por3").val("");
	$("#por4").val("");

     limpiar();
}

//cancelar form
function cancelarform(){
	limpiar();
	mostrarform(false);
	mostrarformactP(false);

}

//funcion listar
function listar(){
	tabla=$('#tbllistado').dataTable({
		"aProcessing": true,//activamos el procedimiento del datatable
		"aServerSide": true,//paginacion y filrado realizados por el server
		dom: 'Bfrtip',//definimos los elementos del control de la tabla
		buttons: [
                  'copyHtml5',
                  'excelHtml5',
                  'csvHtml5',
                  'pdf'
		],
		"ajax":
		{
			url:'../ajax/categoria.php?op=listar',
			type: "get",
			dataType : "json",
			error:function(e){
				console.log(e.responseText);
			}
		},
		"bDestroy":true,
		"iDisplayLength":5,//paginacion
		"order":[[0,"desc"]]//ordenar (columna, orden)
	}).DataTable();
}
//funcion para guardaryeditar
function guardaryeditar(e){
     e.preventDefault();//no se activara la accion predeterminada 
     $("#btnGuardar").prop("disabled",true);
     var formData=new FormData($("#formulario")[0]);

     $.ajax({
     	url: "../ajax/categoria.php?op=guardaryeditar",
     	type: "POST",
     	data: formData,
     	contentType: false,
     	processData: false,

     	success: function(datos){
     		bootbox.alert(datos);
     		mostrarform(false);
     		tabla.ajax.reload();
     	}
     });

     limpiar();
}

function mostrar(idcategoria){
	$.post("../ajax/categoria.php?op=mostrar",{idcategoria : idcategoria},
		function(data,status)
		{
			data=JSON.parse(data);
			mostrarform(true);

			$("#nombre").val(data.nombre);
			$("#descripcion").val(data.descripcion);
			$("#idcategoria").val(data.idcategoria);
		})
}


//funcion para desactivar
function desactivar(idcategoria){
	bootbox.confirm("¿Esta seguro de desactivar este dato?", function(result){
		if (result) {
			$.post("../ajax/categoria.php?op=desactivar", {idcategoria : idcategoria}, function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	})
}

function activar(idcategoria){
	bootbox.confirm("¿Esta seguro de activar este dato?" , function(result){
		if (result) {
			$.post("../ajax/categoria.php?op=activar" , {idcategoria : idcategoria}, function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	})
}

init();