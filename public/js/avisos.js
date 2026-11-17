var contenedor = document.getElementById('avisos');
var urlListar = contenedor.getAttribute('data-url-listar');
var urlMesa = contenedor.getAttribute('data-url-mesa');

function leerAvisadas() {
    try {
        return JSON.parse(sessionStorage.getItem('comandasAvisadas')) || [];
    } catch (error) {
        return [];
    }
}

function guardarAvisadas(lista) {
    try {
        sessionStorage.setItem('comandasAvisadas', JSON.stringify(lista));
    } catch (error) {
    }
}

function sonar() {
    try {
        var audio = new (window.AudioContext || window.webkitAudioContext)();
        var oscilador = audio.createOscillator();
        var volumen = audio.createGain();

        oscilador.type = 'sine';
        oscilador.frequency.value = 880;
        volumen.gain.value = 0.15;

        oscilador.connect(volumen);
        volumen.connect(audio.destination);
        oscilador.start();
        oscilador.stop(audio.currentTime + 0.25);
    } catch (error) {
    }
}

function mostrarAviso(comanda) {
    var aviso = document.createElement('div');
    aviso.className = 'toast aviso-lista';
    aviso.setAttribute('role', 'alert');

    var cuerpo = document.createElement('div');
    cuerpo.className = 'toast-body d-flex justify-content-between align-items-center gap-3';

    var texto = document.createElement('div');
    var titulo = document.createElement('strong');
    titulo.textContent = 'Mesa ' + comanda.mesa;
    texto.appendChild(titulo);

    var detalle = document.createElement('div');
    detalle.className = 'small text-muted';
    detalle.textContent = 'Comanda #' + comanda.id + ' lista para retirar';
    texto.appendChild(detalle);

    var enlace = document.createElement('a');
    enlace.className = 'btn btn-soft-success btn-sm';
    enlace.href = urlMesa + '/' + comanda.id_mesa;
    enlace.textContent = 'Ver mesa';

    var cerrar = document.createElement('button');
    cerrar.type = 'button';
    cerrar.className = 'btn-close';
    cerrar.setAttribute('data-bs-dismiss', 'toast');

    cuerpo.appendChild(texto);
    cuerpo.appendChild(enlace);
    cuerpo.appendChild(cerrar);
    aviso.appendChild(cuerpo);
    contenedor.appendChild(aviso);

    aviso.addEventListener('hidden.bs.toast', function () {
        aviso.remove();
    });

    new bootstrap.Toast(aviso, { delay: 10000 }).show();
}

function actualizarMesas(comandas) {
    var etiquetas = document.getElementsByClassName('aviso-mesa');

    for (var i = 0; i < etiquetas.length; i++) {
        var idMesa = etiquetas[i].getAttribute('data-id-mesa');
        var cantidad = 0;

        for (var j = 0; j < comandas.length; j++) {
            if (comandas[j].id_mesa == idMesa) {
                cantidad++;
            }
        }

        etiquetas[i].classList.toggle('d-none', cantidad == 0);
        etiquetas[i].querySelector('.aviso-cantidad').textContent = cantidad > 1 ? '(' + cantidad + ')' : '';
    }
}

function consultar() {
    fetch(urlListar)
        .then(function (respuesta) {
            return respuesta.json();
        })
        .then(function (comandas) {
            var avisadas = leerAvisadas();
            var vigentes = [];
            var hayNuevas = false;

            for (var i = 0; i < comandas.length; i++) {
                vigentes.push(comandas[i].id);

                if (avisadas.indexOf(comandas[i].id) == -1) {
                    mostrarAviso(comandas[i]);
                    hayNuevas = true;
                }
            }

            if (hayNuevas) {
                sonar();
            }

            guardarAvisadas(vigentes);
            actualizarMesas(comandas);

            var contador = document.getElementById('contador-listas');
            if (contador != null) {
                contador.textContent = comandas.length;
                contador.classList.toggle('d-none', comandas.length == 0);
            }
        })
        .catch(function () {
        });
}

setInterval(consultar, 5000);
consultar();
