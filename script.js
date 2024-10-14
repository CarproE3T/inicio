document.addEventListener('DOMContentLoaded', function () {
    const estudianteData = {
        nombre: '<?php echo $_SESSION["nombre_estudiante"]; ?>',
        codigo: '<?php echo $_SESSION["codigo_estudiante"]; ?>',
        correo1: '<?php echo $_SESSION["correo_personal"]; ?>',
        correo2: '<?php echo $_SESSION["correo_institucional"]; ?>',
        celular: '<?php echo $_SESSION["celular"]; ?>'
    };

    document.querySelectorAll('button.interes-button').forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault(); // Prevenir el envío del formulario

            Swal.fire({
                title: 'Confirmación de Interés',
                html: `
                    <input id="nombreEstudiante" class="swal2-input" value="${estudianteData.nombre}" placeholder="Nombre">
                    <input id="codigoEstudiante" class="swal2-input" value="${estudianteData.codigo}" placeholder="Código">
                    <input id="correoEstudiante1" class="swal2-input" value="${estudianteData.correo1}" placeholder="Correo Personal">
                    <input id="correoEstudiante2" class="swal2-input" value="${estudianteData.correo2}" placeholder="Correo Institucional (Opcional)">
                    <input id="celularEstudiante" class="swal2-input" value="${estudianteData.celular}" placeholder="Celular (Opcional)">
                `,
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: 'Enviar Interés',
                preConfirm: () => {
                    const nombre = document.getElementById('nombreEstudiante').value;
                    const codigo = document.getElementById('codigoEstudiante').value;
                    const correo1 = document.getElementById('correoEstudiante1').value;
                    const correo2 = document.getElementById('correoEstudiante2').value;
                    const celular = document.getElementById('celularEstudiante').value;

                    if (!nombre || !codigo || !correo1) {
                        Swal.showValidationMessage('Debes ingresar tu nombre, código y al menos un correo de contacto');
                        return false;
                    }
                    return { nombre, codigo, correo1, correo2, celular };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const { nombre, codigo, correo1, correo2, celular } = result.value;

                    // Enviar los datos a `webservicecorreo.php` mediante fetch
                    const formData = new FormData();
                    formData.append('proyecto_id', event.target.dataset.proyectoId);
                    formData.append('nombre_estudiante', nombre);
                    formData.append('codigo_estudiante', codigo);
                    formData.append('correo_estudiante1', correo1);
                    formData.append('correo_estudiante2', correo2);
                    formData.append('celular_estudiante', celular);
                    formData.append('nombre_proyecto', event.target.dataset.nombreProyecto);

                    fetch('webservicecorreo.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Interés Enviado', 'Tu interés ha sido enviado al proponente.', 'success');

                            // Cambiar el botón a "Seleccionado"
                            event.target.classList.remove('boton');
                            event.target.classList.add('boton', 'seleccionado');
                            event.target.innerText = 'Seleccionado';
                            event.target.disabled = true; // Deshabilitar el botón
                        } else {
                            Swal.fire('Error', data.message || 'No se pudo enviar el interés.', 'error');
                        }
                    })
                    .catch(error => {
                        Swal.fire('Error', 'Hubo un problema con la solicitud.', 'error');
                    });
                }
            });
        });
    });
});





