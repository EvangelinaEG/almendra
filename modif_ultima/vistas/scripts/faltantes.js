var tabla;

//funcion que se ejecuta al inicio
function init(){
   mostrarform(false);
   mostrarformact(false);

   listar();



   $("#formulario").on("submit",function(e){
   	guardaryeditar(e);
   })

    $("#formactualizar").on("submit",function(e){
    	actualizar(e);
   })


$.post("../ajax/ingreso.php?op=selectProveedor", function(r){
	  	
   
 //$("#idproveedor").html(" <option value='TODOS' selected='selected' >TODOS</option>");
 $("#idproveedor").append(r);
var fila="<option value='TODOS' selected='selected' >TODOS</option>";
$('#ifproveedor').append(fila);
   	$('#idproveedor').selectpicker('refresh');
   });



   //cargamos los items al celect categoria
   $.post("../ajax/articulo.php?op=selectCategoria", function(r){
   	$("#idcategoria").html(r);
   	$("#idcategoria").selectpicker('refresh');
   });
   $("#imagenmuestra").hide();
}





//funcion limpiar
function limpiar(){
	$("#codigo").val("");
	$("#nombre").val("");
	$("#codigoa").val("");
	$("#nombrea").val("");
	$("#descripcion").val("");
	$("#stock").val("");
	$("#imagenmuestra").attr("src","");
	$("#imagenactual").val("");
	$("#print").hide();
	$("#idarticulo").val("");
	$("#puntopedido").val("");
	$("#por1").val("");
	$("#por2").val("");
	$("#por3").val("");
	$("#por4").val("");
	$("#precio1").val("");
	$("#precio2").val("");
	$("#precio3").val("");
	$("#precio4").val("");
	$("#costo").val("");
}


$("#idproveedor").change(cargartabla);


function cargartabla(){

	var proveedor = $("#idproveedor option:selected").text();
	if(proveedor == "TODOS"){
		listar();
	}else{
		
	listadoporprov(proveedor);
	}
	
}
//funcion mostrar formulario
function mostrarform(flag){

	limpiar();
	if(flag){
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		$("#actualizarPrecios").hide();
		$("#btnGuardar").prop("disabled",false);
		$("#btnagregar").hide();
	}else{
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
		$("#actualizarPrecios").hide();
		$("#btnagregar").show();
		$("#tipo_act").val("Seleccione una opcion");
	$("#tipo_act").selectpicker('refresh');

	}
}


function mostrarformact(flag){
	
	//limpiar();
	if(flag){
		$("#actualizarPrecios").show();
		$("#listadoregistros").hide();
		$("#formularioregistros").hide();
		$("#actcosto").hide();
		$("#datosact").hide();
		$("#btnGuardar").hide();
		$("#btnagregar").hide();
		$("#btnActualizar").prop("disabled",true);
		$("#btnActualizar").hide();
	}else{
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
		$("#actualizarPrecios").hide();
		$("#btnagregar").show();
	}
}

function mostrarfa() {

      var tipo = document.formactualizar.tipo_act.value;

	if(tipo == "costo"){
		$("#porcosto").val("");
		$("#actcosto").show();
		$("#datosact").hide();
	}else{
		$("#por1").val("");
		$("#por2").val("");
		$("#por3").val("");
		$("#por4").val("");

		$("#actcosto").hide();
		$("#datosact").show();
	}      
	$("#btnActualizar").show();
	$("#btnActualizar").prop("disabled",false);
      // tu codigo ajax va dentro de esta function...
    }

//cancelar form
function cancelarform(){
	limpiar();
	mostrarform(false);
	mostrarformact(false);

}

function listadoporprov(prov){
	$("#tbllistado").children("tr").remove()
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
			url:'../ajax/articulo.php?op=listarProv&prov='+prov,
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
			url:'../ajax/articulo.php?op=listarStock',
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

//listar faltantes


//funcion para guardaryeditar
function guardaryeditar(e){
     e.preventDefault();//no se activara la accion predeterminada 
     $("#btnGuardar").prop("disabled",true);
     var formData=new FormData($("#formulario")[0]);

     $.ajax({
     	url: "../ajax/articulo.php?op=guardaryeditar",
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

//funcion para guardaryeditar
function actualizar(e){

	 e.preventDefault();//no se activara la accion predeterminada 
     $("#btnActualizar").prop("disabled",true);
	if(document.formactualizar.tipo_act.value == "costo")
     {
		     	if(document.formactualizar.porcosto.value == ""){
		     		bootbox.alert("El porcentaje de costo no puede estar vacío");
		     	}else{

				     		bootbox.confirm("ATENCIÖN ! Se actualizara el costo del producto seleccionado. ¿Desea continuar?", function(result){
						if (result) {

								     	var parametros = {
								     		"idarticuloa" : document.formactualizar.idarticuloa.value,
								     		"costou" : document.formactualizar.porcosto.value
								     	}
				     	
							$.post("../ajax/articulo.php?op=actCosto",{ idarticuloa: parametros.idarticuloa, costou: parametros.costou },
											function(data,status)
											{
									   bootbox.alert(data);
									   mostrarformact(false);
									     		//tabla.ajax.reload();

									     });
				     				}
				    		 });
		     		}
     }else{

		     	if(document.formactualizar.por1.value == "" && document.formactualizar.por2.value == "" && document.formactualizar.por3.value == "" && document.formactualizar.por4.value == ""){
		     		bootbox.alert("Debe completar con al menos un porcentaje para actualizar los precios.");
		     	}else{
		     
		     //var formData=new FormData($("#formactualizar")[0]);
		 var parametros = {
		 				"idarticuloa" : document.formactualizar.idarticuloa.value,
		 				"codigoa" : document.formactualizar.codigoa.value,
		                "precio1" : document.formactualizar.precio1.value,
		                "precio2" : document.formactualizar.precio2.value,
		                "precio3" : document.formactualizar.precio3.value,
		                "precio4" : document.formactualizar.precio4.value
		        };

		       
		  $.post("../ajax/articulo.php?op=actualizarP",{ idarticuloa: parametros.idarticuloa, precio1: parametros.precio1, precio2: parametros.precio2, precio3: parametros.precio3, precio4: parametros.precio4, codigoa: parametros.codigoa },
				function(data,status)
				{
		   bootbox.alert(data);
		   mostrarformact(false);
		     		//tabla.ajax.reload();
		$("#precio1").val("");
			$("#precio2").val("");
			$("#precio3").val("");
			$("#precio4").val("");

		     });
		}
	}

     limpiar();
}


function mostrar(idarticulo){
	$.post("../ajax/articulo.php?op=mostrar",{idarticulo : idarticulo},
		function(data,status)
		{
			data=JSON.parse(data);
			mostrarform(true);

			$("#idcategoria").val(data.idcategoria);
			$("#idcategoria").selectpicker('refresh');
			$("#codigo").val(data.codigo);
			$("#nombre").val(data.nombre);
			$("#stock").val(data.stock);
			$("#puntopedido").val(data.puntopedido);
			$("#descripcion").val(data.descripcion);
			$("#costo").val(data.costo);
			$("#imagenactual").val(data.imagen);
			$("#idarticulo").val(data.idarticulo);
			generarbarcode();
		})
}


function mostrarArt(idarticulo){
 limpiar();
	$.post("../ajax/articulo.php?op=mostrarart",{idarticulo : idarticulo},
		function(data,status)
		{
					
			data=JSON.parse(data);
			
			if(data.nombre)
			{
		
			//$("#idcategoria").val(data.idcategoria);
			//$("#idcategoria").selectpicker('refresh');
			
			$("#codigoa").val(data.codigo);
			$("#nombrea").val(data.nombre);
			$("#precio1").val(data.precioventa1);
			$("#precio2").val(data.precioventa2);
			$("#precio3").val(data.precioventa3);
			$("#precio4").val(data.precioventa4);
			$("#costoa").val(data.costo);


			//$("#stock").val(data.stock);
			//$("#descripcion").val(data.descripcion);
			//$("#imagenmuestra").show();
			//$("#imagenmuestra").attr("src","../files/articulos/"+data.imagen);
			//$("#imagenactual").val(data.imagen);
			$("#idarticuloa").val(data.idarticulo);
			generarbarcod();
				mostrarformact(true);
			}
			
			else{
			bootbox.alert("EL producto no tiene ingresos de stock");
		
			}

			
		})
}


//funcion para desactivar
function desactivar(idarticulo){
	bootbox.confirm("¿Esta seguro de desactivar este dato?", function(result){
		if (result) {
			$.post("../ajax/articulo.php?op=desactivar", {idarticulo : idarticulo}, function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	})
}

function activar(idarticulo){
	bootbox.confirm("¿Esta seguro de activar este dato?" , function(result){
		if (result) {
			$.post("../ajax/articulo.php?op=activar" , {idarticulo : idarticulo}, function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	})
}

function generarbarcode(){
	codigo=$("#codigo").val();
	JsBarcode("#barcode",codigo);
	$("#print").show();

}

function generarbarcod(){
	codigo=$("#codigoa").val();
	JsBarcode("#barcode",codigo);
	$("#print").show();

}



function imprimir(){
	$("#print").printArea();
}

init();