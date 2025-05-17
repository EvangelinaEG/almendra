var tabla;

//funcion que se ejecuta al inicio
function init(){
   mostrarform(false);
    $("#monto").prop("disabled", true);
      $("#agregararticulo").hide();
   listar();

   $("#formulario").on("submit",function(e){
   	guardaryeditar(e);
   });

   //cargamos los items al select cliente
   $.post("../ajax/venta.php?op=selectCliente", function(r){
   	$("#idcliente").append(r);
   	/*var fila="<option value='42' selected='selected' >CONSUMIDOR</option>";
$('#idcliente').append(fila);
   	*/
   	$('#idcliente').selectpicker('refresh');
   });
   $("#codigon").autocomplete({ 
    source: "../ajax/venta.php?op=getProductos"+$("#codigon").val(),
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

/*function validarcant(e) {
	modificarSubtotales();
	  tecla = (document.all) ? e.keyCode : e.which;
  if (tecla==13){
  document.getElementById("codigon").focus();
  	
  }
}*/

//funcion limpiar
function limpiar(){

	$("#idcliente").val("");
	$("#cliente").val("");
	$("#serie_comprobante").val("");
	$("#num_comprobante").val("");
	$("#impuesto").val("");
	$("#tipoComprobante").val("");
	$("#monto").val("");
	$("#precio").val("");
	$("#codigon").val("");
	$("#codigon").show();
	$("#tit").show();
	$("#total_venta").val("");
	$(".filas").remove();
	$("#total").html("0");
	$("#detalles").val("");
	detalles=0;
	prods =[];
	//$("#fila").val("");
	//obtenemos la fecha actual
	/*var now = new Date();
	var day =("0"+now.getDate()).slice(-2);
	var month=("0"+(now.getMonth()+1)).slice(-2);
	var today=now.getFullYear()+"-"+(month)+"-"+(day);*/
	/*var d = new Date();
	$("#fecha_hora").val(d.getDate());*/

	//marcamos el primer tipo_documento
	$("#tipo_comprobante").val("Presupuesto");
	$("#tipo_comprobante").selectpicker('refresh');

}



$("#precio").change(function(){
            var valor = $('select[id=precio]').val();
           
            modificarSubtotales(valor);
            //$('#valor2').val($(this).val());
	});


//funcion mostrar formulario
function mostrarform(flag){
	limpiar();
	if(flag){
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		//$("#btnGuardar").prop("disabled",false);
		$("#btnagregar").hide();
		listarArticulos();
		$("#monto").val("");
		$("#btnGuardar").hide();
		$("#btnCancelar").show();
		detalles=0;
		$("#btnAgregarArt").show();


	}else{
		$("#listadoregistros").show();
		$("#btnGuardar").show();
		$("#formularioregistros").hide();
		$("#btnGuardar").prop("disabled",false);
		$("#btnagregar").show();
	}
}

//cancelar form
function cancelarform(){
	//location.href ="../mi_tienda/vistas/venta.php";
	limpiar();

	//tabla.ajax.reload();
	mostrarform(false);
	
;}

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
			url:'../ajax/venta.php?op=listar',
			type: "get",
			dataType : "json",
			error:function(e){
				console.log(e.responseText);
			}
		},
		"bDestroy":true,
		"iDisplayLength":15,//paginacion
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
			url:'../ajax/venta.php?op=listarArticulos',
			type: "get",
			dataType : "json",
			error:function(e){
				console.log(e.responseText);
			}
		},
		"bDestroy":true,
		"iDisplayLength":15,//paginacion
		"order":[[0,"desc"]]//ordenar (columna, orden)
	}).DataTable();
}

 function validarFormulario(){
 var cliente = document.getElementById('idcliente').value;
    var tipoComprobante = document.getElementById('tipo_comprobante').value;
    var impuesto = document.getElementById('impuesto').value;
     var monto = document.getElementById('monto').value;
var cont = 0;
    
  if(cliente == null || cliente == 0){
   document.getElementById('idcliente').value = 42; 	
  //bootbox.alert('ERROR: Debe seleccionar un cliente');
  //cont++;
  //return true;
}



    if(monto < 0){
  bootbox.alert('ERROR: El monto no puede ser negativo');
  cont++;
  return false;
}

/*        if(numero == null || numero.length == 0 || isNaN(numero)){
 bootbox.alert('ERROR: Debe ingresar un numero de comprobante');
 cont++;
  return false;
}*/

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
function guardaryeditar(e){

	var monto = document.formulario.monto.value;
	var tipopresupuesto = document.formulario.tipopresupuesto.value;

     //e.preventDefault();//no se activara la accion predeterminada 
     //$("#btnGuardar").prop("disabled",true);
     var formData = new FormData($("#formulario")[0]);
      formData.append('monto', monto);
      formData.append('tipopresupuesto', tipopresupuesto);
     $.ajax({
     	url: "../ajax/venta.php?op=guardaryeditar",
     	type: "POST",
     	data: formData,
     	contentType: false,
     	processData: false,

     	success: function(datos){
     		//bootbox.alert(datos);
     		mostrarform(false);
     		listar();
     		if(datos){
     			
/*var hoy = new Date();
var fecha = hoy.getFullYear()+'-'+(hoy.getMonth()+1)+'-'+hoy.getDate(); 
var hora = hoy.getHours()+':'+ hoy.getMinutes()+':'+ hoy.getSeconds();
alert(fecha+" "+hora);*/
if(tipopresupuesto == "A"){
window.open(window.location="../reportes/exFacturaA.php?id="+datos+"", "_blank");
}else{
	window.open(window.location="../reportes/exFactura.php?id="+datos+"", "_blank");
}
//window.location="../reportes/exFactura.php?id="+datos+"";
window.location="../vistas/venta.php";
}else{
     		bootbox.alert("No se pudo generar la venta");
     		}
     	}
     });
//location.href ="../vistas/venta.php";


     limpiar();
}

function mostrar(idventa){
	$.post("../ajax/venta.php?op=mostrar",{idventa : idventa},
		function(data,status)
		{
			data=JSON.parse(data);
			mostrarform(true);

			$("#idcliente").val(data.idcliente);
			//$("#nombre").val(data.cliente);
			$("#idcliente").selectpicker('refresh');
			$("#tipo_comprobante").val(data.tipo_comprobante);
			//$("#tipo_comprobante").selectpicker('refresh');
			//$("#serie_comprobante").val(data.serie_comprobante);
			$("#num_comprobante").val(data.num_comprobante);
			$("#tit").hide();
			$("#codigon").hide();
			$("#tipopresupuesto").val(data.tipo_presupuesto);
			$("#fecha_hora").val(data.fecha);
			$("#impuesto").val(parseInt(data.impuesto));
			//$("#impuesto").val(data.iva);
			$("#idventa").val(data.idventa);
			
			//ocultar y mostrar los botones
			$("#btnGuardar").hide();
			$("#btnCancelar").show();
			$("#btnAgregarArt").hide();
		});
	$.get("../ajax/venta.php?op=listarDetalle&id="+idventa,function(r){
		$("#detalles").html(r);
		$("#detalles").selectpicker('refresh');
	});

}


//funcion para desactivar
function anular(idventa){
	bootbox.confirm("¿Esta seguro de eliminar la venta?", function(result){
		if (result) {
			$.post("../ajax/venta.php?op=anular", {idventa : idventa}, function(e){
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


function marcarImpuesto(){
	var tipo_comprobante=$("#tipo_comprobante option:selected").text();
	if (tipo_comprobante=='Factura') {
		$("#impuesto").val(impuesto);
	}else{
		$("#impuesto").val("0");
	}
}


$('#tipo_comprobante').on('change', function() {
  // lo que queramos hacer
  var tipo_comprobante=$("#tipo_comprobante").val();
	if (tipo_comprobante=='Presupuesto') {
		$("#monto").prop("disabled", true);
		}
		else{
			$("#monto").prop("disabled", false);
		}
});




function agregarDetalle(){

	
	var codigo = document.formulario.codigon.value;

	if(codigo === ""){
		bootbox.alert("El valor no puede ser nulo");
		document.getElementById("codigon").focus();
	}
else {

	$.post("../ajax/venta.php?op=buscarArticulo", {cod: codigo}, function(e){
		data=JSON.parse(e);

		console.log(data);

if(data == null)
{
	bootbox.alert("El producto no se encuentra cargado en la base de datos o no hay Stock disponible");
	
       /* bootbox.confirm("El producto no se encuentra cargado en la base de datos. ¿Desea cargarlo ahora?", function(result){
	if (result) {
		 $("#codpro").val(codigo);
	 		$("#agregararticulo").show();

	 			 	}
	 })*/
  
}else{
			

		var idarticulo = data.idarticulo;
		var nombre = data.nombre;
		var precio1 = data.precioventa1;
		var precio2 = data.precioventa2;
		var precio3 = data.precioventa3;
		var precio4 = data.precioventa4;


var p = 0;
var pos = 0;

		/*for(var i in prods) {
	     
        if(prods[i] == idarticulo)
        	{  pos = i; p++;     }

    }*/
    
    //if(p > 0){
    	
    	//modificarSubtotales();
   
  /*  var cant = document.getElementsByName("cantidad[]");
	var val = cant[pos].value;

	var nuevacant = parseInt(val) + 1;
	
document.getElementsByName("cantidad[]")[pos].value = nuevacant;*/

//document.getElementsByName("precio_venta[]")[pos].innerHTML = '<option>'+precio1+'</option><option>'+precio2+'</option><option>'+precio3+'</option><option>'+precio4+'</option>';

//modificarSubtotales();
    	 //var otra = document.getElementsByName("cantidad[]");

    	
//console.log(tds[44]); // Muestra el primer título h2
//console.log(tds);
//console.log(tds.length); // Muestra 3 (existen 3 elementos h2 en la página)
    	
    	//bootbox.alert("El producto ya está cargado, cambie su cantidad si lo requiere.");
 //   }else{
prods.push(idarticulo);
	var cantidad=1;
	var precio_compra=0;
	//var precio_venta=0;
	//var precios = [];
	
precios = '<datalist id="data_'+idarticulo+'"><option value="'+precio1+'" selected="selected"  >'+precio1+'</option><option value="'+precio2+'">'+precio2+'</option><option value="'+precio3+'">'+precio3+'</option><option value="'+precio4+'">'+precio4+'</option>';

    	
		var subtotal=cantidad*precio_compra;
		var fila='<tr class="filas" id="fila'+cont+'">'+
        '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+','+idarticulo+')">X</button></td>'+
        '<td><input type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+nombre+'</td>'+
        '<td><input type="number" step="any"onkeyup="modificarSubtotales()" name="cantidad[]" id="cantidad[]" value="'+cantidad+'"></td>'+
         '<td><input onkeyup="modificarSubtotales()" name="precio_venta[]" id="precio_venta_'+idarticulo+'" value="'+precio1+'" class="form-control" list="data_'+idarticulo+'">'+precios+'</td>'+
        '<td><input type="number" step="any" min="0" onkeyup="modificarSubtotales()" name="descuento[]" size="4" id="descuento[]" value="0"></td>'+
       '<td style="width:4;"><input type="number" step="any" min="0" onkeyup="modificarSubtotales()" name="iva[]" id="iva[]" size="4" value="21"></td>'+
        '<td><span id="subtotal'+cont+'" name="subtotal">'+subtotal+'</span></td>'+      
		'</tr>';
		cont++;
		detalles++;
		$('#detalles').append(fila);
		modificarSubtotales();
	//}
	}
});
	}

		$("#codigon").val("");
}


function cargarPrecios(id){

	$.post("../ajax/venta.php?op=buscarProd", {cod: codigo}, function(e){
		data=JSON.parse(e);
		$("#precio_venta"+id).html(data);
	})
	modificarSubtotales();
}

function ShowSelected(valor, cont)
{
	
/* Para obtener el valor */
//var cod = document.getElementById("precio_venta[]");

 
/* Para obtener el texto */
var combo = document.getElementById("precio_venta[]");
var cant=document.getElementsByName("cantidad[]");
var iva=document.getElementsByName("iva[]");
var inpS=document.getElementsByName("subtotal");


inpS[cont].value = (cant[cont].value * valor).toFixed(2);

document.getElementsByName("subtotal")[cont].innerHTML = inpS[cont].value;

/*var selected = combo[i].options[combo.selectedIndex].text;
alert(selected);
alert(cant[i].value)
inpS[i].value = cant[i].value*select;
document.getElementsByName("subtotal")[i].innerHTML=inpS.value;*/

}

function modificarSubtotales(ind){

var elem = document.activeElement.name;

	var cant=document.getElementsByName("cantidad[]");
	var prev=document.getElementsByName("precio_venta[]");
	var desc=document.getElementsByName("descuento[]");
	var iva=document.getElementsByName("iva[]");
	var sub=document.getElementsByName("subtotal");
var precio = ind || "";
	for (var i = 0; i < cant.length; i++) {
		var inpV=cant[i];
		var inpP=prev[i];
		var inpS=sub[i];
		var des=desc[i];
		var ivaS=iva[i];
		/*if(elem === "codigon"){
		cant[i].focus();
	}*/
		//alert(des.value);
	if(precio){
		//alert("codigo "+prods[i]);
		//for(var j in prods) {
			
			codigo = prods[i];
			
	$.post("../ajax/venta.php?op=buscarProd", {cod: codigo}, function(e){


		data=JSON.parse(e);
		//alert("se ve el codigo"+codigo);

		//alert(data.precioventa1);
		if(precio == 1){
			
		var pre = data.precioventa1;
		
	}

		if(precio == 2){
			
		var pre = data.precioventa2;
		
		}
		if(precio == 3){
			
		var pre = data.precioventa3;
		
		}
		if(precio == 4){
			
		var pre = data.precioventa4;
		
		}
		console.log(data);
		
	var lp=document.getElementsByName("precio_venta[]");
	var ct=document.getElementsByName("cantidad[]");
var dc=document.getElementsByName("descuento[]");
	var ivS=document.getElementsByName("iva[]");
	var su=document.getElementsByName("subtotal");
		for(l=0; l<prods.length; l++){
			var cant=ct[l];
		var p=lp[l];
		var inp=su[l];
		var de=dc[l];
		var iva=ivS[l];
		//var inpP=prev[i];
		//$('#precio_venta')[i].append(pre);
		if(prods[l] == data.idarticulo){
		lp[l].value = pre;//= pre.toString();
		var pu = parseFloat(p.value) / ((parseFloat(iva.value) * 0.01) +1);//precio sin iva

		var iv = (parseFloat(cant.value) * pu) * ((parseFloat(iva.value) * 0.01)+1);//precio x cantidad mas iva
	

		var descu = (parseFloat(cant.value) * pu) * (parseFloat(de.value) * 0.01);//descuento sobre precio(sin iva) x cantidad
		
		inp.value = parseFloat(iv) - parseFloat(descu);
		inp.value = Math.round(inp.value * 100) / 100;


		
		document.getElementsByName("subtotal")[l].innerHTML = inp.value;

		calcularTotales();
		
		}
		}
	});

	//alert(pre);
	//console.log(pre);
	//$("#precio_venta[]")[i].html(pre);
		// document.getElementsByName("precio_venta[]")[i].value = pre.toString();
	 //var p = document.getElementsByName("cantidad[]")[i].value; //=  pre.toString();
	 //alert("nuevo valor"+p);
      
    //}
	}else{
		
			//document.getElementById("precio_venta[]")[i].innerHTML = inpP[valor].value;
			//document.getElementById("precio_venta[]")[i].innerHTML = inpP[valor].value;
			var pu = parseFloat(inpP.value) / ((parseFloat(ivaS.value) * 0.01) +1);//precio sin iva

		var iv = (parseFloat(inpV.value) * pu) * ((parseFloat(ivaS.value) * 0.01)+1);//precio x cantidad mas iva
	

		var descu = (parseFloat(inpV.value) * pu) * (parseFloat(des.value) * 0.01);//descuento sobre precio(sin iva) x cantidad
		
		inpS.value = parseFloat(iv) - parseFloat(descu);
		inpS.value = Math.round(inpS.value * 100) / 100;
	
		document.getElementsByName("subtotal")[i].innerHTML = inpS.value;
		calcularTotales();
		//alert(document.getElementsByName("precio_venta[]")[i][posicion]);
		//document.getElementById("precio_venta[]")[i][posicion] = inpP[posicion].value;
		}
	}
	
	
		//var posicion = inpP.selectedIndex;

		

	
	calcularTotales();
}

function calcularTotales(){
	var sub = document.getElementsByName("subtotal");
	var impu = document.getElementById("impuesto");
	
		var total=0;

	for (var i = 0; i < sub.length; i++) {
		total += parseFloat(document.getElementsByName("subtotal")[i].value);
	}
	if(impu.value == 0){ 
		var stotal = total ;
	
	} else{ 
		var stotal = total + total * (impu.value * 0.01);	
	}
	
	$("#total").html((stotal).toFixed(2));
	$("#total_venta").val(stotal);
	var comprobante= document.formulario.tipo_comprobante.value;
	if (comprobante=='Presupuesto') {

		$("#monto").val((stotal).toFixed(2));
	}
	evaluar();
}

function evaluar(){

	if (detalles>0) 
	{
		$("#btnGuardar").show();
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

}
calcularTotales();
detalles=detalles-1;

}

init();