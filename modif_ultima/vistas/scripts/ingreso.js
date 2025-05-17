var tabla;
var prods = [];


//funcion que se ejecuta al inicio
function init(){
	limpiar();
   mostrarform(false);
   $("#agregararticulo").hide();
   $("#monto").prop("disabled", true);

   listar();
var prods = [];
var p = 0;
   //$("#formulario").on("submit",function(){
  // 	agregarDetalle();
//});


$.post("../ajax/articulo.php?op=selectCategoria", function(r){
   	$("#idcategoria").html(r);
   	$("#idcategoria").selectpicker('refresh');
   });
  
   //cargamos los items al select proveedor
   $.post("../ajax/ingreso.php?op=selectProveedor", function(r){
   	$("#idproveedor").append(r);
   var fila="<option value='' selected='selected' disabled >Seleccione ..</option>";
$('#ifproveedor').append(fila);
   	$('#idproveedor').selectpicker('refresh');
   });

//cargamos los items al select proveedor
   $.post("../ajax/ingreso.php?op=selectProducto", function(r){
   	$("#idarticulo").html(r);
   	$("#idarticulo").val("Seleccione..");
   	$('#idarticulo').selectpicker('refresh');
   });

$("#codigon").autocomplete({ 
    source: "../ajax/ingreso.php?op=getProductos"+$("#codigon").val(),
        min_length: 1, 
   // delay: 300 
}); 


}


function validarcod(e) {
	$("#agregararticulo").hide();
	$("#codpro").val("");
	  tecla = (document.all) ? e.keyCode : e.which;
  if (tecla==13) agregarDetalle();
}

//funcion limpiar
function limpiar(){


	$("#idproveedor").val("");
	$("#proveedor").val("");
	$("#tipo_comprobante").prop("disabled", false);
	$("#serie_comprobante").val("");
	$("#num_comprobante").val("");
	$("#impuesto").val("0");
	$("#varios").val("0");
	$("#otros").val("0");
	$("#total_compra").val("");
	$(".filas").remove();
	$("#total").html("0");
	$("#monto").val("");
	$("#fila").val("");
	$("#codigon").show();
	$("#btnGuardar").show();
	$("#tit").show();
	//obtenemos la fecha actual
	var now = new Date();
	var day =("0"+now.getDate()).slice(-2);
	var month=("0"+(now.getMonth()+1)).slice(-2);
	var today=now.getFullYear()+"-"+(month)+"-"+(day);
	$("#fecha_hora").val(today);

	//marcamos el primer tipo_documento
	$("#tipo_comprobante").val("Presupuesto");
	$("#tipo_comprobante").selectpicker('refresh');

}

//funcion mostrar formulario
function mostrarform(flag){
	limpiar();
	if(flag){
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		$("#btnGuardar").prop("disabled",true);
		$("#btnagregar").hide();
		listarArticulos();

		$("#btnGuardar").hide();
		$("#btnCancelar").show();
		detalles=0;
		$("#btnAgregarArt").show();


	}else{
		prods = [];
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
		$("#btnagregar").show();
	}
}

//cancelar form
function cancelarform(){

	limpiar();
	mostrarform(false);
	
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
			url:'../ajax/ingreso.php?op=listar',
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

function listarArticulos(){
	tabla=$('#tblarticulos').dataTable({
		"aProcessing": true,//activamos el procedimiento del datatable
		"aServerSide": true,//paginacion y filrado realizados por el server
		dom: 'Bfrtip',//definimos los elementos del control de la tabla
		buttons: [

		],
		"ajax":
		{
			url:'../ajax/ingreso.php?op=listarArticulos',
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

 function validarFormulario(){
 var idproveedor = document.getElementById('idproveedor').value;
    var tipoComprobante = document.getElementById('tipo_comprobante').value;
    var numero = document.getElementById('num_comprobante').value;
     var monto = document.getElementById('monto').value;
var cont = 0;
    
    if(idproveedor == null || idproveedor == 0){
  bootbox.alert('ERROR: Debe seleccionar un proveedor');
  cont++;
  return false;
}


    if(monto < 0){
  bootbox.alert('ERROR: El monto no puede ser nulo');
  cont++;
  return false;
}

        if(numero == null || numero.length == 0 || isNaN(numero)){
 bootbox.alert('ERROR: Debe ingresar un numero de comprobante');
 cont++;
  return false;
}

if(tipoComprobante == null || tipoComprobante == 0){
 bootbox.alert('ERROR: Debe seleccionar un tipo de comprobante');
 cont++;
  return false;
}
if(cont == 0){
	$("#btnGuardar").prop("disabled",true);
	guardaryeditar();
	
}

}

//funcion para guardaryeditar
function guardaryeditar(){

var monto = document.formulario.monto.value;
var varios = document.formulario.varios.value;
var otros = document.formulario.otros.value;


    // e.preventDefault();//no se activara la accion predeterminada 
     //$("#btnGuardar").prop("disabled",true);
     var formData=new FormData($("#formulario")[0]);
     formData.append('monto', monto);
     formData.append('varios', varios);
     formData.append('otros', otros);
	 $.ajax({
     	url: "../ajax/ingreso.php?op=guardaryeditar",
     	type: "POST",
     	data: formData,
     	contentType: false,
     	processData: false,

     	success: function(datos){
     		
     		bootbox.alert(datos);
     		mostrarform(false);
     		listar();
     	}
     });

     limpiar();

}

function mostrar(idingreso){



	$.post("../ajax/ingreso.php?op=mostrar",{idingreso : idingreso},
		function(data,status)
		{
			data=JSON.parse(data);
			mostrarform(true);

			$("#idproveedor").val(data.idproveedor);
			//$("#idproveedor").selectpicker('refresh');
			$("#tipo_comprobante").val(data.tipo_comprobante);
			$("#tipo_comprobante").prop("disabled", true);
			//$("#tipo_comprobante").selectpicker('refresh');
			$("#serie_comprobante").val(data.serie_comprobante);
			$("#serie_comprobante").prop("disabled", true);
			$("#num_comprobante").val(data.num_comprobante);
			$("#num_comprobante").prop("disabled", true);
			$("#fecha_hora").val(data.fecha);
			$("#impuesto").val(data.impuesto);
			$("#idingreso").val(data.idingreso);
			$("#codigon").hide();
			$("#tit").hide();
			//ocultar y mostrar los botones
			$("#btnGuardar").hide();
			$("#btnCancelar").show();
			$("#btnAgregarArt").hide();
		});
$.get("../ajax/ingreso.php?op=listarDetalle&id="+idingreso,function(r){
			
		//	document.getElementsByName("detalles").innerHTML = r;
		$("#detalles").html(r);
		$("#detalles").selectpicker('refresh');
	});
}


//funcion para desactivar
function anular(idingreso){
	bootbox.confirm("¿Esta seguro de desactivar este dato?", function(result){
		if (result) {
			$.post("../ajax/ingreso.php?op=anular", {idingreso : idingreso}, function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	})
}

//declaramos variables necesarias para trabajar con las compras y sus detalles
var impuesto=0;
var cont=0;
var detalles=0;
prods = [];
$("#btnGuardar").hide();
$("#tipo_comprobante").change(marcarImpuesto);

$('#tipo_comprobante').on('change', function() {
  // lo que queramos hacer
  var tipo_comprobante = $("#tipo_comprobante").val();
	if (tipo_comprobante == 'Presupuesto') {
		$("#monto").prop("disabled", true);
		}
		else{
			$("#monto").prop("disabled", false);
		}
});
function marcarImpuesto(){
	var tipo_comprobante = $("#tipo_comprobante option:selected").text();
	if (tipo_comprobante == 'Presupuesto') {
		$("#monto").prop("disabled", true);
		
		$("#impuesto").val(impuesto);
	}else{

$("#monto").prop("disabled", false);

		$("#monto").val("0");
		$("#impuesto").val("0");
	}
}

function agregarprod(){
	var nompro = document.formulario.nombrepro.value;
	var codpro = document.formulario.codpro.value;
	var catepro = document.formulario.idcategoria.value;
	if(nompro == "" || catepro == ""){
		bootbox.alert("No puede quedar los campos del nombre ni la categoría vacíos");
	}
	else{

		$.post("../ajax/ingreso.php?op=agregarArticulo", {nom: nompro, cod: codpro, cate: catepro}, function(e){
		data=JSON.parse(e);
		if(data){

	 		$("#agregararticulo").hide();
	 
	 		agregarDetalle(data.idarticulo);
		}
		console.log(data);
	});

	}
}

function agregarDetalle(){

			//if(document.formulario.codigon.value){
					var codigo = document.formulario.codigon.value;
			
			if(codigo === ""){

					bootbox.alert("El valor no puede ser nulo");
					document.getElementById("codigon").focus();

			}else{

				$.post("../ajax/ingreso.php?op=buscarArticulo", {cod: codigo}, function(e){
					data=JSON.parse(e);

					console.log(data);

				if(data == null)
						{
							bootbox.alert("Debe serleccionar un producto. En caso de no encontrarlo, debe cargarlo previamente.");
				//bootbox.alert("El producto no se encuentra cargado en la base de datos. Deberá cargarlo para poder realizar un nuevo ingreso del mismo.")
				
			        		/*bootbox.confirm("El producto no se encuentra cargado en la base de datos. ¿Desea cargarlo ahora?", function(result){
							if (result) {
								
										if(typeof codigo == "string"){
											
											$("#nombrepro").val(codigo);
										}else{
										 $("#codpro").val(codigo);
										 }
						 				$("#agregararticulo").show();

				 			 	}
				 			})*/
			  
				}else{
						

						var idarticulo = data.idarticulo;
						var nombre = data.nombre;
						
						if(data.precompra){
						var precom = data.precompra;	
						}else if(data.costo){
							var precom = data.costo;
							 
						}else{
							var precom = 0;
						}
					


						var p = 0;
						var pos = 0;
								for(var i in prods) {
							     
						        if(prods[i] == idarticulo)
						        	{  pos = i; p++;     }

						    }
			    
						    if(p > 0){
						    	
						    	//modificarSubtotales();
						   
										    var cant = document.getElementsByName("cantidad[]");
											var val = cant[pos].value;

											var nuevacant = parseInt(val) + 1;
											
										document.getElementsByName("cantidad[]")[pos].value = nuevacant;
										document.getElementsByName("precio_compra[]")[pos].value = precom;


										modificarSubtotales();
								    	 //var otra = document.getElementsByName("cantidad[]");

								    	
								//console.log(tds[44]); // Muestra el primer título h2
								//console.log(tds);
								//console.log(tds.length); // Muestra 3 (existen 3 elementos h2 en la página)
								    	
								    	//bootbox.alert("El producto ya está cargado, cambie su cantidad si lo requiere.");
				    		}else{
			    	
							prods.push(idarticulo);
								var cantidad=1;
								var precio_compra=0;
								var precio_venta=0;
								
							    	
									var subtotal=cantidad*precio_compra;
									var fila='<tr class="filas" id="fila'+cont+'">'+
							        '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+','+idarticulo+')">X</button></td>'+
							        '<td><input type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+nombre+'</td>'+
							        '<td><input type="number" min="0" step="any" onkeyup="modificarSubtotales()" name="cantidad[]" id="cantidad[]" value="'+cantidad+'"></td>'+
							        '<td><input type="number" min"0" step="any" onkeyup="modificarSubtotales()" name="precio_compra[]" id="precio_compra[]" value="'+precom+'"></td>'+
							        '<td><span id="subtotal'+cont+'" name="subtotal">'+subtotal+'</span></td>'+      
									'</tr>';
									cont++;
									detalles++;
									$('#detalles').append(fila);
									modificarSubtotales();
								}
					}
			});
		}

					$("#codigon").val("");
}

function modificarSubtotales(){

	var cant=document.getElementsByName("cantidad[]");
	var prec=document.getElementsByName("precio_compra[]");
	var sub=document.getElementsByName("subtotal");

	for (var i = 0; i < cant.length; i++) {
		var inpC=cant[i];
		var inpP=prec[i];
		var inpS=sub[i];

		inpS.value=(inpC.value*inpP.value).toFixed(2);

		document.getElementsByName("subtotal")[i].innerHTML=inpS.value;
	}

	calcularTotales();
}

function calcularTotales(){
	var sub = document.getElementsByName("subtotal");
	var impuesto = document.getElementById("impuesto");
	var otros = document.getElementById("otros");
	var varios = document.getElementById("varios");
	var total = 0;

	for (var i = 0; i < sub.length; i++) {
		total +=  parseFloat(document.getElementsByName("subtotal")[i].value);
	}

	var va1 = (impuesto.value * 0.01) * total;
	var va2 = (otros.value * 0.01) * total;
	var va3 = (varios.value * 0.01) * total;

	var sumtotal = total + va1 + va2 + va3;
	$("#total").html((sumtotal).toFixed(3));
	$("#total_compra").val(sumtotal);
	var comprobante = document.formulario.tipo_comprobante.value;
	if (comprobante == 'Presupuesto') {
		$("#monto").val(sumtotal);
	}
	evaluar(sumtotal);
}

function evaluar(total){

	if (detalles>0 && total >0) 
	{
		$("#btnGuardar").show();
		$("#btnGuardar").prop("disabled",false);
	}
	else
	{
		$("#btnGuardar").hide();
		cont=0;
	}
}

function eliminarDetalle(indice, id){
				$("#fila"+indice).remove();
				for(var i in prods){
					
							if(prods[i] == id){
								prods.splice(i, 1);
							}

				calcularTotales();
				detalles=detalles-1;

				}

}

init();