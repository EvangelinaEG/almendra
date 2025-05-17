function backup(e){
    // e.preventDefault();//no se activara la accion predeterminada 
     //$("#backup").prop("disabled",true);
     //var formData = new FormData($("#formulario")[0]);

     $.ajax({
     	url: "../ajax/admin.php",
     	type: "json",
     	

     	success: function(response){
               //console.log(response);
     		//bootbox.alert(datos);
               //$('#backup').attr("target","_blank", function(d) { return d.example.url; })
               window.open(response, "_blank");
               
     		//mostrarform(false);
     		//tabla.ajax.reload();
     	}
     });

     //limpiar();
}