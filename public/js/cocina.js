var comandas = [];
var seleccionada = null;
var limiteDemora = 900;
var tiempoMaximo = 1200;

function formatoTiempo(segundos) {
    var minutos = Math.floor(segundos / 60);
    var seg = segundos % 60;
    return minutos + ':' + (seg < 10 ? '0' + seg : seg);
}

function claseCabecera(c) {
    if (c.segundos > limiteDemora) {
        return 'k-demora';
    }

    return c.estado == 'pendiente' ? 'k-pendiente' : 'k-preparacion';
}

function claseBarra(segundos) {
    if (segundos > limiteDemora) {
        return 'bg-danger';
    }

    return segundos > 600 ? 'bg-warning' : 'bg-success';
}

function porcentaje(segundos) {
    return Math.min(100, Math.round(segundos * 100 / tiempoMaximo));
}

function dibujar() {
    var pendientes = 0;
    var preparando = 0;
    var demoradas = 0;

    $.each(comandas, function (i, c) {
        if (c.estado == 'pendiente') {
            pendientes++;
        } else {
            preparando++;
        }

        if (c.segundos > limiteDemora) {
            demoradas++;
        }
    });

    $('#n-pend').text(pendientes);
    $('#n-prep').text(preparando);
    $('#n-dem').text(demoradas);

    $('#comandas').empty();

    if (comandas.length == 0) {
        $('#comandas').append('<p class="text-muted">No hay comandas pendientes.</p>');
        return;
    }

    $.each(comandas, function (i, c) {
        var tarjeta = $('<div class="card h-100"></div>');

        if (c.id == seleccionada) {
            tarjeta.addClass('k-seleccionada');
        }

        var cabecera = $('<div class="k-cabecera d-flex justify-content-between align-items-start p-3"></div>').addClass(claseCabecera(c));
        var titulo = $('<div></div>');
        titulo.append($('<div class="fw-semibold fs-5"></div>').text('Mesa ' + c.mesa));
        titulo.append($('<div class="small opacity-75"></div>').text('Comanda #' + c.id));
        cabecera.append(titulo);

        var derecha = $('<div class="text-end"></div>');
        if (i < 9) {
            derecha.append($('<div class="fw-semibold"></div>').text('[' + (i + 1) + ']'));
        }
        derecha.append($('<div class="small"></div>').text(c.estado == 'pendiente' ? 'Pendiente' : 'En preparación'));
        cabecera.append(derecha);

        var cuerpo = $('<div class="card-body"></div>');

        var lista = $('<div></div>');
        $.each(c.detalle, function (j, d) {
            var linea = $('<div class="d-flex gap-2 mb-2"></div>');
            linea.append($('<span class="fw-semibold"></span>').text(d.cantidad + '×'));

            var texto = $('<div></div>');
            texto.append($('<div></div>').text(d.nombre_producto));

            if (d.observaciones) {
                texto.append($('<div class="k-nota mt-1"></div>').text('Nota: ' + d.observaciones));
            }

            linea.append(texto);
            lista.append(linea);
        });
        cuerpo.append(lista);

        var pie = $('<div class="px-3 pb-3"></div>');
        var barra = $('<div class="progress progress-delgada mb-1"></div>');
        barra.append($('<div class="progress-bar"></div>').attr('id', 'barra-' + c.id).addClass(claseBarra(c.segundos)).css('width', porcentaje(c.segundos) + '%'));
        pie.append(barra);

        var espera = $('<div class="d-flex justify-content-between small text-muted"></div>');
        espera.append($('<span></span>').text('Espera'));
        espera.append($('<span class="fw-semibold"></span>').attr('id', 'tiempo-' + c.id).text(formatoTiempo(c.segundos)));
        pie.append(espera);

        tarjeta.append(cabecera).append(cuerpo).append(pie);

        if (c.id == seleccionada) {
            var texto = c.estado == 'pendiente' ? 'Enter: pasar a En preparación' : 'Enter: marcar como Preparada';
            tarjeta.append($('<div class="card-footer text-white text-center k-pie-seleccion"></div>').text(texto));
        }

        $('#comandas').append($('<div class="col-md-6 col-xl-4"></div>').append(tarjeta));
    });
}

function cargar() {
    $.getJSON($('#comandas').attr('data-url-listar'), function (respuesta) {
        comandas = respuesta;

        var existe = false;
        $.each(comandas, function (i, c) {
            if (c.id == seleccionada) {
                existe = true;
            }
        });

        if (!existe) {
            seleccionada = null;
        }

        dibujar();
    }).fail(function () {
        $('#mensaje').text('No se pudo consultar las comandas');
    });
}

function cambiarEstado() {
    if (seleccionada == null) {
        return;
    }

    $.post($('#comandas').attr('data-url-estado'), { id: seleccionada }, function (respuesta) {
        $('#mensaje').text(respuesta.mensaje);
        seleccionada = null;
        cargar();
    }, 'json').fail(function (xhr) {
        var mensaje = 'No se pudo actualizar la comanda';

        if (xhr.responseJSON && xhr.responseJSON.mensaje) {
            mensaje = xhr.responseJSON.mensaje;
        }

        $('#mensaje').text(mensaje);
        cargar();
    });
}

document.addEventListener('keydown', function (e) {
    var indice = -1;

    for (var i = 0; i < comandas.length; i++) {
        if (comandas[i].id == seleccionada) {
            indice = i;
        }
    }

    if (e.key >= '1' && e.key <= '9') {
        var numero = parseInt(e.key) - 1;

        if (numero < comandas.length) {
            seleccionada = comandas[numero].id;
        }
    } else if (e.key == 'ArrowRight' || e.key == 'ArrowDown') {
        e.preventDefault();

        if (comandas.length > 0) {
            seleccionada = comandas[indice + 1 < comandas.length ? indice + 1 : 0].id;
        }
    } else if (e.key == 'ArrowLeft' || e.key == 'ArrowUp') {
        e.preventDefault();

        if (comandas.length > 0) {
            seleccionada = comandas[indice > 0 ? indice - 1 : comandas.length - 1].id;
        }
    } else if (e.key == 'Enter') {
        cambiarEstado();
        return;
    } else if (e.key == 'Escape') {
        seleccionada = null;
    } else {
        return;
    }

    dibujar();
});

setInterval(function () {
    $.each(comandas, function (i, c) {
        c.segundos = parseInt(c.segundos) + 1;
        $('#tiempo-' + c.id).text(formatoTiempo(c.segundos));
        $('#barra-' + c.id).removeClass('bg-success bg-warning bg-danger').addClass(claseBarra(c.segundos)).css('width', porcentaje(c.segundos) + '%');
    });
}, 1000);

setInterval(cargar, 5000);

cargar();
