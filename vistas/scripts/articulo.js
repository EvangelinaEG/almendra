var tabla;

//funcion que se ejecuta al inicio
function init(){
   mostrarform(false);
   mostrarformact(false);
   listar();
   



   $("#formulario").on("submit",function(e){
   	$("#btnGuardar").prop("disabled", true);
   	guardaryeditar(e);
   })

    $("#formactualizar").on("submit",function(e){
    	 $("#btnActualizar").prop("disabled",true);
    	actualizar(e);
   })



 $.post("../ajax/articulo.php?op=selectProveedor", function(r){
   	$("#idproveedor").html(r);
   	$("#idproveedor").selectpicker('refresh');
   });


   //cargamos los items al celect categoria
   $.post("../ajax/articulo.php?op=selectCategoria", function(r){
   	$("#idcategoria").html(r);
   	$("#idcategoria").selectpicker('refresh');
   });
   $("#imagenmuestra").hide();
}
function actprecio(elemento){
	switch(elemento.name){
		case 'costoa':

		var costo = elemento.value;
      	//var co =  (costo * parseFloat(document.formactualizar.costoa.value))/100 + parseFloat(document.formactualizar.costoa.value);
    
    	  if(costo>0){
    	  	
		//$("#costoa").val((co).toFixed(2));
		if(document.formactualizar.por1.value > 0){var pre1 =  ( parseFloat(document.formactualizar.por1.value) * parseFloat(costo))/100 + parseFloat(costo);}
		
		if(document.formactualizar.por2.value > 0){var pre2 =  ( parseFloat(document.formactualizar.por2.value) * parseFloat(costo))/100 + parseFloat(costo);}
		if(document.formactualizar.por3.value > 0){var pre3 =  ( parseFloat(document.formactualizar.por3.value) * parseFloat(costo))/100 + parseFloat(costo);}
		if(document.formactualizar.por4.value > 0){var pre4 =  ( parseFloat(document.formactualizar.por4.value) * parseFloat(costo))/100 + parseFloat(costo);}
		$("#precio1").val((pre1).toFixed(2));
		$("#precio2").val((pre2).toFixed(2));
		$("#precio3").val((pre3).toFixed(2));
		$("#precio4").val((pre4).toFixed(2));
		}

		break;
		case 'porcosto':

		var porcosto = elemento.value;
      	var co =  (porcosto * parseFloat(document.formactualizar.costoa.value))/100 + parseFloat(document.formactualizar.costoa.value);
    
    	  if(co>0){

		$("#costoa").val((co).toFixed(2));
		var pre1 =  ( parseFloat(document.formactualizar.por1.value) * parseFloat(co))/100 + parseFloat(co);
		var pre2 =  ( parseFloat(document.formactualizar.por2.value) * parseFloat(co))/100 + parseFloat(co);
		var pre3 =  ( parseFloat(document.formactualizar.por3.value) * parseFloat(co))/100 + parseFloat(co);
		var pre4 =  ( parseFloat(document.formactualizar.por4.value) * parseFloat(co))/100 + parseFloat(co);
		$("#precio1").val((pre1).toFixed(2));
		$("#precio2").val((pre2).toFixed(2));
		$("#precio3").val((pre3).toFixed(2));
		$("#precio4").val((pre4).toFixed(2));
		}

		break;
		case 'por1':

		var por1 = elemento.value;
      	var pre1 =  (por1 * parseFloat(document.formactualizar.costoa.value))/100 + parseFloat(document.formactualizar.costoa.value);
    
      if(pre1>0){
		$("#precio1").val((pre1).toFixed(2));
		
		}
		break;
		case 'por2':
		var por2 = elemento.value;
      var pre2 =  (por2 * parseFloat(document.formactualizar.costoa.value))/100 + parseFloat(document.formactualizar.costoa.value);
       if(pre2>0){
		$("#precio2").val((pre2).toFixed(2));

		}
		break;
		case 'por3':
		  var por3 = elemento.value;
      var pre3 =  (por3 * parseFloat(document.formactualizar.costoa.value))/100 + parseFloat(document.formactualizar.costoa.value);
       if(pre3>0){
		$("#precio3").val((pre3).toFixed(2));
			}
		break;
		case 'por4':
		var por4 = elemento.value;
      var pre4 =  (por4 * parseFloat(document.formactualizar.costoa.value))/100 + parseFloat(document.formactualizar.costoa.value);
       if(pre4>0){
		$("#precio4").val((pre4).toFixed(2));
		}
		break;
		case 'precio1':

		var p1 = elemento.value;
      	var por1 =  (p1 - parseFloat(document.formactualizar.costoa.value))*100 / parseFloat(document.formactualizar.costoa.value);
    
      if(por1>0){
		$("#por1").val(Math.round(por1 * 100) / 100);
		
		}
		break;
		case 'precio2':
		var p2 = elemento.value;
      var por2 =  (p2 - parseFloat(document.formactualizar.costoa.value))*100 / parseFloat(document.formactualizar.costoa.value);
       if(por2>0){
		$("#por2").val(Math.round(por2 * 100) / 100);

		}
		break;
		case 'precio3':
		  var p3 = elemento.value;
      var por3 =  (p3 - parseFloat(document.formactualizar.costoa.value))*100 / parseFloat(document.formactualizar.costoa.value);
       if(por3>0){
		$("#por3").val(Math.round(por3 * 100) / 100);
			}
		break;
		case 'precio4':
		var p4 = elemento.value;
      var por4 =  (p4 - parseFloat(document.formactualizar.costoa.value))*100 / parseFloat(document.formactualizar.costoa.value);
       if(por4>0){
		$("#por4").val(Math.round(por4 * 100) / 100);
		}
		break;
			     }
}

//funcion limpiar
function limpiar(){
	$("#codigo").val("");
	$("#nombre").val("");
	$("#codigoa").val("");
	$("#nombrea").val("");
	$("#descripcion").val("");
	$("#stock").val("");
	//$("#imagenmuestra").attr("src","");
	//$("#imagenactual").val("");
	//$("#print").hide();
	$("#idarticulo").val("");
	$("#idproveedor").val("");
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
	$("#cost").val("");
	$("#tipo_act").val("Seleccione una opcion");
	$("#tipo_act").selectpicker('refresh');
	 $("#btnActualizar").prop("disabled",false);

}

//funcion mostrar formulario
function mostrarform(flag){

	limpiar();
	if(flag){
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		$('#stock').val("0");
		$('#puntopedido').val("0");
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
	 $("#btnActualizar").prop("disabled",false);

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
		//$("#btnActualizar").prop("disabled",true);
		$("#btnActualizar").show();
	}else{
		$("#listadoregistros").show();
		$("#actualizarPrecios").hide();
		$("#formularioregistros").hide();
		$("#btnActualizar").prop("disabled",false);
		
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
		/*$("#por1").val("");
		$("#por2").val("");
		$("#por3").val("");
		$("#por4").val("");*/

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

//funcion listar
function listar(){
	tabla=$('#tbllistado').dataTable({
		"aProcessing": true,//activamos el procedimiento del datatable
		"aServerSide": true,//paginacion y filrado realizados por el server
		dom: 'Bfrtip',//definimos los elementos del control de la tabla
		buttons: [
                  'copyHtml5',
                  'excelHtml5',
                  'csvHtml5'
                  //'pdf'
		],
		"ajax":
		{
			url:'../ajax/articulo.php?op=listar',
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

function listarfaltantes(){
	tabla=$('#tbllistadofaltantes').dataTable({
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
	mostrarformact(false);
     		//tabla.ajax.reload();
     	}
     });

     limpiar();
}

//funcion para guardaryeditar
function actualizar(e){

	

	 e.preventDefault();//no se activara la accion predeterminada 
   	/*if(document.formactualizar.tipo_act.value == "costo")
     {*/
		     	if(document.formactualizar.costoa.value == ""){
		     	bootbox.alert("El valor del costo no puede estar vacío");
		     	}else{


		     			var p1 = document.formactualizar.precio1.value; 
	var p2 = document.formactualizar.precio2.value;
	var p3 = document.formactualizar.precio3.value;
	var p4 =document.formactualizar.precio4.value;
	if(p1<0 || p2<0 || p3<0 || p4<0){
		bootbox.alert("No puede ingresar valores negativos para los precios");
	}else{
		     	
		   
		     //var formData=new FormData($("#formactualizar")[0]);

		 				
		 				var idarta = document.formactualizar.idarticuloa.value;
		 				var coda = document.formactualizar.codigo.value;
		                var prec1 = document.formactualizar.precio1.value;
		                var prec2 = document.formactualizar.precio2.value;
		                var prec3 = document.formactualizar.precio3.value;
		                var prec4 = document.formactualizar.precio4.value;
		                 var idarticuloa = document.formactualizar.idarticuloa.value;
						var costou = document.formactualizar.costoa.value;

bootbox.confirm("ATENCIÖN ! Se actualizará el costo del producto seleccionado y los precios de venta segun utilidad. ¿Desea continuar?", function(result){
						if (result) {

								     	
				     	
							$.post("../ajax/articulo.php?op=actCosto",{ idarticuloa: idarticuloa, costou: costou },
											function(data,status)
											{
												
									   if(data=="correcto"){
									   //
									   
	$.post("../ajax/articulo.php?op=actualizarP",{ idarticuloa: idarta, precio1: prec1, precio2: prec2, precio3: prec3, precio4: prec4, codigoa: coda },
												function(data,status)
												{
													//alert(data);
										  alert(data);
										   //mostrarformact(false);
										     		//tabla.ajax.reload();
										     		$("#porcosto").val("");
										$("#cost").val("");
										     		$("#codigo").val("");
										$("#precio1").val("");
											$("#precio2").val("");
											$("#precio3").val("");
											$("#precio4").val("");
											tabla.ajax.reload( null, false);
											
     										mostrarform(false);
											mostrarformact(false);
											//limpiar();
										     });
}else{bootbox.alert("No pudieron actualizarse los datos");}
							mostrarform(false);
											mostrarformact(false);
										
								    // limpiar();
											//tabla.ajax.reload();
									     });

				     				}else{$("#btnActualizar").prop("disabled",false);}
				    		 });
		}
 

		
 					}
 }


function mostrar(idarticulo){
	limpiar();
	$.post("../ajax/articulo.php?op=mostrar",{idarticulo : idarticulo},
		function(data,status)
		{
			data=JSON.parse(data);
			mostrarform(true);


			$("#idcategoria").val(data.idcategoria);
			$("#idcategoria").selectpicker('refresh');
			$("#idproveedor").val(data.idproveedor);
			$("#idproveedor").selectpicker('refresh');
			$("#codigo").val(data.codigo);
			$("#nombre").val(data.nombre);
			$("#stock").val(data.stock);
			$("#puntopedido").val(data.puntopedido);
			$("#descripcion").val(data.descripcion);
			$("#costo").val(data.costo);
			$("#imagenactual").val(data.imagen);
			$("#idarticulo").val(data.idarticulo);
			$("#btnGuardar").show();
			generarbarcode();
		})
}


function mostrarArt(idarticulo){
 limpiar();
	$.post("../ajax/articulo.php?op=mostrar",{idarticulo : idarticulo},
		function(data,status)
		{
					
			data=JSON.parse(data);
					if(data.nombre)
			{
					mostrarformact(true);
			//$("#idcategoria").val(data.idcategoria);
			//$("#idcategoria").selectpicker('refresh');
			var po1 = ((data.precioventa1 - data.costo)*100)/data.costo;
			var po2 = ((data.precioventa2 - data.costo)*100)/data.costo;
			var po3 = ((data.precioventa3 - data.costo)*100)/data.costo;
			var po4 = ((data.precioventa4 - data.costo)*100)/data.costo;

			if(po1<=0){po1 = 0;}
			if(po2<=0){po2 = 0;}
			if(po3<=0){po3 = 0;}
			if(po4<=0){po4 = 0;}
			$("#por1").val(Math.round(po1 * 100) / 100);
			
			$("#por2").val(Math.round(po2 * 100) / 100);
			$("#por3").val(Math.round(po3 * 100) / 100);
			$("#por4").val(Math.round(po4 * 100) / 100);

			$("#codigoa").val(data.codigo);
			$("#nombrea").val(data.nombre);
			$("#precio1").val(data.precioventa1);
			$("#precio2").val(data.precioventa2);
			$("#precio3").val(data.precioventa3);
			$("#precio4").val(data.precioventa4);
			$("#costoa").val(data.costo);
			$("#cost").val(data.costo);
			$("#codigo").val(data.codigo);

			
						

			//$("#stock").val(data.stock);
			//$("#descripcion").val(data.descripcion);
			//$("#imagenmuestra").show();
			//$("#imagenmuestra").attr("src","../files/articulos/"+data.imagen);
			//$("#imagenactual").val(data.imagen);
			$("#idarticuloa").val(data.idarticulo);
			generarbarcod();
			
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