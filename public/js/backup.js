document.getElementById("backup").addEventListener("click", function() {
    // Mostrar el loader
    document.getElementById("loader").style.display = "block";

    // Realizar la solicitud AJAX
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "header.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            // Ocultar el loader
            document.getElementById("loader").style.display = "none";

            if (xhr.status === 200) {
                // Muestra el mensaje de éxito con SweetAlert
                Swal.fire({
                    icon: 'success',
                    title: 'Backup realizado',
                    text: xhr.responseText
                });
            } else {
                // Muestra el mensaje de error con SweetAlert
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo realizar el backup'
                });
            }
        }
    };

    // Envía la solicitud
    xhr.send("action=backup"); // Cambia esto según sea necesario
});
