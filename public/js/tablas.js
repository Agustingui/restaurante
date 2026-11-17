$(document).ready(function () {
    var opciones = {
        order: [],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json'
        }
    };

    if ($('#tabla thead th:last').text().trim() == 'Acciones') {
        opciones.columnDefs = [{ orderable: false, targets: -1 }];
    }

    $('#tabla').DataTable(opciones);
});
