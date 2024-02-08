function mostrarSweetAlert(icono, titulo, texto) {
    Swal.fire({
        icon: icono,
        title: titulo,
        text: texto,
        confirmButtonText: 'OK',
    });
}