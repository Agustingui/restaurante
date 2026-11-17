var formulario = document.getElementById('formComanda');
var items = document.getElementsByClassName('producto');
var pastillas = document.getElementsByClassName('pastilla-categoria');
var buscador = document.getElementById('buscador');
var categoriaActual = '';

function nombreDe(item) {
    return item.querySelector('.nombre').textContent.toLowerCase();
}

function cantidadDe(item) {
    var cantidad = parseInt(item.querySelector('.cantidad').value);

    if (isNaN(cantidad)) {
        return 0;
    }

    return cantidad;
}

function filtrar() {
    var texto = buscador.value.trim().toLowerCase();
    var visibles = 0;

    for (var i = 0; i < items.length; i++) {
        var mostrar;

        if (texto != '') {
            mostrar = nombreDe(items[i]).indexOf(texto) != -1;
        } else {
            mostrar = items[i].getAttribute('data-categoria') == categoriaActual;
        }

        items[i].classList.toggle('d-none', !mostrar);

        if (mostrar) {
            visibles++;
        }
    }

    for (var j = 0; j < pastillas.length; j++) {
        var activa = texto == '' && pastillas[j].getAttribute('data-categoria') == categoriaActual;
        pastillas[j].classList.toggle('active', activa);
    }

    document.getElementById('sin-resultados').classList.toggle('d-none', visibles != 0);
}

function crearLinea(item, cantidad) {
    var linea = document.createElement('li');
    linea.className = 'd-flex justify-content-between align-items-start mb-2';

    var texto = document.createElement('div');
    var nombre = document.createElement('span');
    nombre.className = 'fw-medium';
    nombre.textContent = cantidad + '× ' + item.querySelector('.nombre').textContent;
    texto.appendChild(nombre);

    var observacion = item.querySelector('.nota').value.trim();
    if (observacion != '') {
        var nota = document.createElement('small');
        nota.className = 'd-block text-muted';
        nota.textContent = observacion;
        texto.appendChild(nota);
    }

    var quitar = document.createElement('button');
    quitar.type = 'button';
    quitar.className = 'btn-close btn-sm';
    quitar.addEventListener('click', function () {
        item.querySelector('.cantidad').value = 0;
        item.querySelector('.nota').value = '';
        actualizar();
    });

    linea.appendChild(texto);
    linea.appendChild(quitar);
    return linea;
}

function actualizar() {
    var lista = document.getElementById('resumen');
    var total = 0;

    lista.innerHTML = '';

    for (var i = 0; i < items.length; i++) {
        var cantidad = cantidadDe(items[i]);

        items[i].querySelector('.nota').classList.toggle('d-none', cantidad < 1);

        if (cantidad > 0) {
            total = total + cantidad;
            lista.appendChild(crearLinea(items[i], cantidad));
        }
    }

    document.getElementById('resumen-vacio').classList.toggle('d-none', total > 0);
    document.getElementById('total-items').textContent = total;
    document.getElementById('btn-enviar').disabled = total == 0;
}

formulario.addEventListener('click', function (e) {
    var boton = e.target.closest('.mas, .menos');

    if (boton == null) {
        return;
    }

    var entrada = boton.closest('.producto').querySelector('.cantidad');
    var valor = parseInt(entrada.value) || 0;

    if (boton.classList.contains('mas') && valor < 99) {
        valor++;
    }

    if (boton.classList.contains('menos') && valor > 0) {
        valor--;
    }

    entrada.value = valor;
    actualizar();
});

formulario.addEventListener('input', actualizar);

formulario.addEventListener('keydown', function (e) {
    if (e.key == 'Enter' && e.target.tagName == 'INPUT') {
        e.preventDefault();
    }
});

buscador.addEventListener('input', filtrar);

for (var k = 0; k < pastillas.length; k++) {
    pastillas[k].addEventListener('click', function () {
        categoriaActual = this.getAttribute('data-categoria');
        buscador.value = '';
        filtrar();
    });
}

formulario.addEventListener('submit', function (e) {
    var total = 0;

    for (var i = 0; i < items.length; i++) {
        var cantidad = cantidadDe(items[i]);

        if (cantidad < 0) {
            alert('Las cantidades no pueden ser negativas');
            e.preventDefault();
            return;
        }

        total = total + cantidad;
    }

    if (total == 0) {
        alert('Cargá al menos un producto');
        e.preventDefault();
    }
});

if (pastillas.length > 0) {
    categoriaActual = pastillas[0].getAttribute('data-categoria');
}

filtrar();
actualizar();
