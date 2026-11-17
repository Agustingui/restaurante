function actualizarReloj() {
    var ahora = new Date();
    var h = ahora.getHours();
    var m = ahora.getMinutes();

    document.getElementById('reloj').textContent = (h < 10 ? '0' + h : h) + ':' + (m < 10 ? '0' + m : m);
    document.getElementById('fecha').textContent = ahora.toLocaleDateString('es-AR', { weekday: 'long', day: 'numeric', month: 'long' });
}

actualizarReloj();
setInterval(actualizarReloj, 30000);
