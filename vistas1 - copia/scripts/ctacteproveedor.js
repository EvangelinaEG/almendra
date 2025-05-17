var tabla;
var prods = [];


//funcion que se ejecuta al inicio
function init(){
   mostrarform(false);
   $("#agregararticulo").hide();
  $("#agregarpago").show();

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
   	$("#idproveedor").html(r);
   	$("#idproveedor").val("Seleccione..");
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


/*function validarcod(e) {
	$("#agregararticulo").hide();
	$("#codpro").val("");
	  tecla = (document.all) ? e.keyCode : e.which;
  if (tecla==13) agregarDetalle();
}
*/
//funcion limpiar
function limpiar(){

	//fila = "";
	$("#tablita").val("");
	$("#idproveedor").val("");
	$("#proveedor").val("");
	$("#serie_comprobante").val("");
	$("#num_comprobante").val("");
	$("#impuesto").val("");

	$("#total_compra").val("");
	$(".filas").remove();
	$("#total").html("0");

	//obtenemos la fecha actual
	/*var now = new Date();
	var day =("0"+now.getDate()).slice(-2);
	var month=("0"+(now.getMonth()+1)).slice(-2);
	var today=now.getFullYear()+"-"+(month)+"-"+(day);
	$("#fecha_hora").val(today);*/

	//marcamos el primer tipo_documento
	/*$("#tipo_comprobante").val("Presupuesto");
	$("#tipo_comprobante").selectpicker('refresh');*/

}


function eliminar(id){
	bootbox.confirm("¿Esta seguro de eliminar el pago?", function(result){
		if (result) {

			$.post("../ajax/ccproveedor.php?op=eliminar", {idcuentacorriente : id }, function(res){
				
				bootbox.alert(res);
				//tabla.ajax.reload();
			});
		}
	})
}


//funcion mostrar formulario
function mostrarform(flag){
	//$("#tablita").children("tr").remove();
	limpiar();
	if(flag){
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		$("#btnGuardar").prop("disabled",true);
		$("#btnagregar").hide();
		//listarArticulos();


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

function registrarPago(){
	//$(btnregistrar).prop("disabled", true);
	var nuevomonto = document.formulario.monto.value;
	
	if(nuevomonto == ""){
		bootbox.alert("El Monto no puede ser nulo");
	}else{

	bootbox.confirm("¿Esta seguro que desea registrar el pago de $"+nuevomonto+" ?", function(result){
		if (result) {
			
		
	var prov = document.formulario.idproveedor.value;
	

	$.post("../ajax/ccproveedor.php?op=registrar",{monto: nuevomonto, proveedor: prov},
		function(data,status)
		{
			bootbox.alert(data);
			listarPagos(prov);
			$(btnregistrar).prop("disabled", false);
			//tabla.ajax.reload();
			//data=JSON.parse(data);
			//alert(data);
			//listar();
		});
	
		}
	})


	}
			
     		//mostrarform(false);
		
}
//cancelar form
function cancelarform(){

$("#tablita").children("tr").remove();
	limpiar();
	
	mostrarform(false);
	listar();

}

//funcion listar
function listar(){
		/*$.get("../ajax/ccproveedor.php?op=listar",function(r){
			
		$("#detalles").html(r);
	});*/
		$.post("../ajax/ccproveedor.php?op=mostrarTotales",	function(dat,status)
		{

		
			data=JSON.parse(dat);
		
		//alert($data);
		$("#saldo").html(data.saldo);
		$("#total").html(data.total);
	});
	tabla=$('#detalles').dataTable({
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
			url:'../ajax/ccproveedor.php?op=listar',
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

function listarPagos(idproveedor){

	tabla=$('#tblpagos').dataTable({
		"aProcessing": true,//activamos el procedimiento del datatable
		"aServerSide": true,//paginacion y filrado realizados por el server
		dom: 'Bfrtip',//definimos los elementos del control de la tabla
		buttons: [

		],
		"ajax":
		{
			url:'../ajax/ccproveedor.php?op=listarPagos&id='+idproveedor,
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

    // e.preventDefault();//no se activara la accion predeterminada 
     //$("#btnGuardar").prop("disabled",true);
     var formData =new FormData($("#formulario")[0]);
     formData.append('monto', monto);
	 $.ajax({
     	url: "../ajax/ingreso.php?op=guardaryeditar",
     	type: "POST",
     	data: formData,
     	contentType: false,
     	processData: false,

     	success: function(datos){
     		
     		bootbox.alert(datos);
     		mostrarform(false);
     		tabla.ajax.reload();
     		listar();

     	}
     });

     limpiar();

}


function formato(texto){
  return texto.replace(/^(\d{4})-(\d{2})-(\d{2})$/g,'$3/$2/$1');
}

function mostrar(idproveedor, saldo){

	$.post("../ajax/ccproveedor.php?op=mostrar",{prov : idproveedor},
		function(data,status)
		{
			data=JSON.parse(data);
			mostrarform(true);
		
			$("#idprove").html(data.proveedor);
			if(saldo>0){
			$("#monto").val(saldo);
		}else{$("#monto").val("");}
			$("#idproveedor").val(idproveedor);
			listarPagos(idproveedor);
/*			
			$("#idproveedor").val(idproveedor);		

var fechamod = formato(data.fecha);

			

	 fila="<tr><td><strong>CtaCte: </strong>"+data.idcuentacorriente+
			"</td><td><strong>Proveedor: </strong>"+data.proveedor+
			"</td><td><strong>Monto Pagado: </strong>"+data.monto+
			"</td><td><strong> Fecha Pago: </strong>"+fechamod+
			"</td><td><strong> Usuario: </strong>"+data.usuario+
			"</td></tr>";

    var btn = document.createElement("TR");
   	btn.innerHTML=fila;
   	document.getElementById("tablita").appendChild(btn);

			$("#idproveedor").val(data.idproveedor);
			$("#idproveedor").selectpicker('refresh');
			$("#tipo_comprobante").val(data.tipo_comprobante);
			$("#tipo_comprobante").selectpicker('refresh');
			$("#serie_comprobante").val(data.serie_comprobante);
			$("#num_comprobante").val(data.num_comprobante);
			$("#fecha_hora").val(data.fecha);
			$("#impuesto").val(data.impuesto);
			$("#idingreso").val(data.idingreso);
			$("#codigon").hide();
			$("#tit").hide();*/
			//ocultar y mostrar los botones
			$("#btnGuardar").hide();
			$("#btnCancelar").show();
			$("#btnAgregarArt").hide();
		});
	/*$.post("../ajax/ingreso.php?op=listarDetalle&id="+idingreso,function(r){
		$("#detalles").html(r);
	});*/

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
  var tipo_comprobante=$("#tipo_comprobante").val();
	if (tipo_comprobante =='Presupuesto') {
		$("#monto").prop("disabled", true);
		}
		else{
			$("#monto").prop("disabled", false);
		}
});
function marcarImpuesto(){
	var tipo_comprobante=$("#tipo_comprobante option:selected").text();
	if (tipo_comprobante=='Presupuesto') {
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
	 
	 		agregarDetalle();
		}
		console.log(data);
	});

	}
}

function agregarDetalle(){

	if(document.formulario.codigon.value){
	var codigo = document.formulario.codigon.value;
}else{
	var codigo = document.formulario.codpro.value;
}
	if(codigo === ""){
		bootbox.alert("El valor no puede ser nulo");
		document.getElementById("codigon").focus();
	}
else {
	$.post("../ajax/ingreso.php?op=buscarArticulo", {cod: codigo}, function(e){
		data=JSON.parse(e);

		console.log(data);

if(data == null)
{
	//bootbox.alert("El producto no se encuentra cargado en la base de datos. Deberá cargarlo para poder realizar un nuevo ingreso del mismo.")
	
        bootbox.confirm("El producto no se encuentra cargado en la base de datos. ¿Desea cargarlo ahora?", function(result){
	if (result) {
		 $("#codpro").val(codigo);
	 		$("#agregararticulo").show();

	 			 	}
	 })
  
}else{
			

		var idarticulo = data.idarticulo;
		var nombre = data.nombre;


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
        '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+')">X</button></td>'+
        '<td><input type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+nombre+'</td>'+
        '<td><input type="number" onkeyup="modificarSubtotales()" name="cantidad[]" id="cantidad[]" value="'+cantidad+'"></td>'+
        '<td><input type="number" onkeyup="modificarSubtotales()" name="precio_compra[]" id="precio_compra[]" value="'+precio_compra+'"></td>'+
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

		inpS.value=inpC.value*inpP.value;
		document.getElementsByName("subtotal")[i].innerHTML=inpS.value;
	}

	calcularTotales();
}

function calcularTotales(){
	var sub = document.getElementsByName("subtotal");
	var total=0.0;

	for (var i = 0; i < sub.length; i++) {
		total += document.getElementsByName("subtotal")[i].value;
	}
	$("#total").html( total);
	$("#total_compra").val(total);
	var comprobante= document.formulario.tipo_comprobante.value;
	if (comprobante=='Presupuesto') {
		$("#monto").val(total);
	}
	evaluar(total);
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

function eliminarDetalle(indice){
$("#fila"+indice).remove();
prods.splice(indice, 1);
calcularTotales();
detalles=detalles-1;

}

init();