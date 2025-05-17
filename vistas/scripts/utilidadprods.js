var tabla;

//funcion que se ejecuta al inicio
function init(){

   listar();
    //cargamos los items al select cliente
   

}



$('#fecha_inicio').on('change', function() {
  // lo que queramos hacer
 listar();
})

$('#fecha_fin').on('change', function() {
  // lo que queramos hacer
 listar();
})

//funcion listar
function listar(){
var  fecha_inicio = $("#fecha_inicio").val();
 var fecha_fin = $("#fecha_fin").val();


$.get("../ajax/consultas.php?op=mostrarTotUtilidad&&fei="+fecha_inicio+"&&fefi="+fecha_fin+"",	function(dat,status)
		{

		
			data=JSON.parse(dat);
		
		
		//$("#cantidadtotal").html(data.cantotal);
		$("#total").html("$"+data.total);
	});
		tabla=$('#tbllistado').dataTable({
		"aProcessing": true,//activamos el procedimiento del datatable
		"aServerSide": true,//paginacion y filrado realizados por el server
		dom: 'Bfrtip',//definimos los elementos del control de la tabla
		buttons: [
                  'copyHtml5',
                 
                  'csvHtml5',
                  'pdf'
		],
		"ajax":
		{
			url:'../ajax/consultas.php?op=utilidadproductos',
			data:{fecha_inicio:fecha_inicio, fecha_fin:fecha_fin},
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