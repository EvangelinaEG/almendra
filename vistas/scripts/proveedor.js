var tabla;

//funcion que se ejecuta al inicio
function init(){
   mostrarform(false);
      mostrarformactP(false);
       var sel = document.getElementById("idcategoria");
  sel.remove(sel.selectedIndex);
   listar();

   $("#formulario").on("submit",function(e){
   	guardaryeditar(e);
   });
   $("#formactP").on("submit",function(e){

    	actualizar(e);
   });
}

function buscarCats(prov){
	$("#idcategoria").empty();	
	$.post("../ajax/persona.php?op=selectCategoria&p="+prov, function(r){
	  
   	$("#idcategoria").append(r);
var fila="<option value='TODOS' selected='selected' >TODOS</option>";
$('#idcategoria').append(fila);
   	$('#idcategoria').selectpicker('refresh');
   });
}
//funcion limpiar
function limpiar(){

	$("#nombre").val("");
	$("#num_documento").val("");
	$("#direccion").val("");
	$("#telefono").val("");
	$("#email").val("");
	$("#idpersona").val("");
	$("#tipo_act").val("Seleccione una opcion");
	$("#tipo_act").selectpicker('refresh');

  $("#descripcion").val("");


								$("#idproveedoru").val("");
								$("#cost").val("");
								 $("#porcost").val("");
  $("#por1").val("");
	$("#por2").val("");
	$("#por3").val("");
	$("#por4").val("");
	$("#idcategoria").val("");
}

function actualizar(e){
	
	 	 e.preventDefault();//no se activara la accion predeterminada 
     $("#btnActualizar").prop("disabled",true);

     if(document.formactP.tipo_act.value == "costo")
     {
     	if(document.formactP.porcost.value == ""){
     	bootbox.alert("El porcentaje de costo no puede estar vacío");
     	}else{
     		var costo = document.formactP.porcost.value;
			var idprov = document.formactP.idproveedoru.value;     	
			var idcate = document.formactP.idcategoria.value;
     		bootbox.confirm("ATENCIÓN ! Se actualizaran TODOS los costos de los productos pertenecientes al proveedor y categoria seleccionados y los precios de venta con el nuevo costo. ¿Desea continuar?", function(result){
		if (result) {
						
	  						$.post("../ajax/persona.php?op=actCostoProv",{ idproveedoru : idprov, costou: costo, idcategoria: idcate},
							function(data,status)
							{
							
							$("#idcategoria").val("");
							$("#porcost").val("");
					   bootbox.alert(data);
					  mostrarformactP(false);


     		tabla.ajax.reload();					     });
     						
     				}
    		 });
     	}
     }else{

     	if(document.formactP.por1.value == "" && document.formactP.por2.value == "" && document.formactP.por3.value == "" && document.formactP.por4.value == ""){
     	bootbox.alert("Debe completar con al menos un porcentaje para actualizar los precios.");
     	}else{
     //var formData=new FormData($("#formactualizar")[0]);
 
 				var idprov =  document.formactP.idproveedoru.value;
 				var idcate =  document.formactP.idcategoria.value;
                var porca1 = document.formactP.por1.value;
                var porca2 = document.formactP.por2.value;
                var porca3 = document.formactP.por3.value;
                var porca4 = document.formactP.por4.value;

  $.post("../ajax/persona.php?op=actPreciosProv",{ idproveedoru: idprov, precioc1: porca1, precioc2: porca2, precioc3: porca3, precioc4: porca4, idcategoria : idcate },
		function(data,status)
		{
   bootbox.alert(data);
   mostrarformactP(false);
  

     		tabla.ajax.reload();


     });
}}
  $("#porcosto").val("");
  $("#por1").val("");
	$("#por2").val("");
	$("#por3").val("");
	$("#por4").val("");
	$("#idproveedoru").val("");
	$("#idproveedor").val("");
     limpiar();
}


function mostrarActP(idproveedor){

	$.post("../ajax/persona.php?op=mostrar",{idpersona: idproveedor},
		function(data,status)
		{
					
			data=JSON.parse(data);
			
			if(data.nombre)
			{
			mostrarformactP(true);
			$("#idproveedoru").val(data.idpersona);
			$("#nombreprov").val(data.nombre);
			
			//$("#idcategoriau").val(data.idcategoria);
			//generarbarcod();

				buscarCats(data.idpersona);

			
			}
			
			else{
			bootbox.alert("La proveedor no está activo.");
		
			}

			
		})
}


//funcion mostrar formulario
function mostrarform(flag){
	limpiar();
	if(flag){
		$("#listadoregistros").hide();
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
		$("#actualizarPreciosprov").show();
		$("#actutilidad").hide();
		$("#actcosto").hide();
			$("#listadoregistros").hide();
			$("#btnActualizar").hide();
			$("#btnActualizar").prop("disabled",true);
		$("#btnGuardar").prop("disabled",false);
		$("#btnagregar").hide();

	}else{
		$("#listadoregistros").show();

		$("#actualizarPreciosprov").hide();
		$("#actutilidad").hide();
		$("#actualizarPrecios").hide();
		$("#btnagregar").show();
	}

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
			url:'../ajax/persona.php?op=listarp',
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
     	url: "../ajax/persona.php?op=guardaryeditar",
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

function mostrar(idpersona){
	limpiar();
	$.post("../ajax/persona.php?op=mostrar",{idpersona : idpersona},
		function(data,status)
		{
			data=JSON.parse(data);
			mostrarform(true);

			$("#nombre").val(data.nombre);
			$("#tipo_documento").val(data.tipo_documento);
			$("#tipo_documento").selectpicker('refresh');
			$("#num_documento").val(data.num_documento);
			$("#direccion").val(data.direccion);
			$("#telefono").val(data.telefono);
			$("#email").val(data.email);
			$("#idpersona").val(data.idpersona);
		})
}


//funcion para desactivar
function eliminar(idpersona){
	bootbox.confirm("¿Esta seguro de eliminar este dato?", function(result){
		if (result) {

			$.post("../ajax/persona.php?op=eliminar", {idpersona : idpersona }, function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	})
}


init();