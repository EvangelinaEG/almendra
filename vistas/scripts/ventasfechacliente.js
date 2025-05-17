var tabla;

//funcion que se ejecuta al inicio
function init(){

   
    //cargamos los items al select cliente
   $.post("../ajax/consultas.php?op=selectCategoria", function(r){
   		var fila = '<option value="TODAS" >TODAS</option>';
   		$("#idcategoria").append(fila);
   	$("#idcategoria").append(r);
   	$('#idcategoria').selectpicker('refresh');
   });

   $.get("../ajax/ccproveedor.php?op=selectProveedor", function(r){
   		var fila = '<option value="TODOS" >TODOS</option>';
   		$("#idproveedor").append(fila);
   	$("#idproveedor").append(r);
   	$('#idproveedor').selectpicker('refresh');
   });
listar();
}

$('#idcategoria').on('change', function() {
  // lo que queramos hacer
 listar();
})

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
 var idcategoria = $("#idcategoria").val();


if(idcategoria ==null){
	idcategoria = "TODAS";
}

$.get("../ajax/consultas.php?op=mostrarTotales&&fei="+fecha_inicio+"&&fefi="+fecha_fin+"&&cat="+idcategoria+"",	function(dat,status)
		{

		
			data=JSON.parse(dat);

		
		$("#cantidadtotal").html(data.cantotal);
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
			url:'../ajax/consultas.php?op=productoscategoria',
			data:{fecha_inicio:fecha_inicio, fecha_fin:fecha_fin, idcategoria: idcategoria},
			type: "get",
			dataType : "json",
			error:function(e){
				console.log(e.responseText);
			}
		},
			"bDestroy":true,
		"iDisplayLength":15,//paginacion
		"order":[[3,"desc"]]//ordenar (columna, orden)
	}).DataTable();
}


init();  