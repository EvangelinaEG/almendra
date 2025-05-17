$("#frmAcceso").on('submit', function(e)
{
    
	e.preventDefault();
	logina=$("#logina").val();
	clavea=$("#clavea").val();

	$.post("../ajax/usuario.php?op=verificar",
        {"logina":logina, "clavea":clavea},
        function(data)
        {
            data=JSON.parse(data);

                       if (data)
            {
                //alert(data.usuario);
                $(location).attr("href","escritorio.php");

          
            	
            }else{
                if(data =="null"){
                document.getElementById("mensaje").innerHTML = "Los datos no pueden ser nulos";    
                }else{
                document.getElementById("mensaje").innerHTML = "Los datos ingresados no son correctos";
                }
            	      //$("#mensaje").innerHTML("Los datos ingresados no son correctos");
                //alert("Usuario y/o Password incorrectos");
            }
        });
})