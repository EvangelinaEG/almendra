var tabla;

//funcion que se ejecuta al inicio
function init(){

   
   
    //cargamos los items al select cliente
   $.post("../ajax/articulo.php?op=selectCategoria", function(r){
   	
   	var fila = '<option value="TODAS" >TODAS</option>';
   		$("#idcategoria").append(fila);
   	$("#idcategoria").append(r);
   
   	$("#idcategoria").selectpicker('refresh');
   });
listar();
}


$("#idcategoria").change(listar);

//funcion listar
function listar(){
//var  fecha_inicio = $("#fecha_inicio").val();
//var fecha_fin = $("#fecha_fin").val();
//var categ = $("#idcategoria option:selected").text();
//var categ = document.idcategoria.value; 
var categ = $( "#idcategoria option:selected" ).val();
 //var categ = $("#idcategoria").val();
 if(typeof categ === 'undefined'){categ = "TODAS"}

$.get("../ajax/consultas.php?op=mostrarTotaleStock&&cat="+categ,	function(dat,status)
		{

		
			data=JSON.parse(dat);
		
		//var to = (data.total).toFixed(2);
		$("#cantidadtotal").html(data.cantotal);
		$("#total").html("$"+data.total);
	});
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
			url:'../ajax/consultas.php?op=stockProductos',
			data:{idcategoria:categ},
			type: "get",
			dataType : "json",
			error:function(e){
				console.log(e.responseText);
			}
		},
		"bDestroy":true,
		"iDisplayLength":5,//paginacion
		"order":[[3,"desc"]]//ordenar (columna, orden)
	}).DataTable();
}


init();  