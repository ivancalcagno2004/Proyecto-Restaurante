import './bootstrap';
let timeout;

// Función para refrescar la página
function refrescarPagina() {
    location.reload();
}

// Función para reiniciar el temporizador de inactividad
function reiniciarTemporizador() {
    clearTimeout(timeout);
    timeout = setTimeout(refrescarPagina, 60000); // 300000 ms = 5 minutos
}

// Escuchar eventos de actividad del usuario
window.onload = reiniciarTemporizador;
document.onmousemove = reiniciarTemporizador;
document.onkeypress = reiniciarTemporizador;
document.ontouchstart = reiniciarTemporizador;
document.onclick = reiniciarTemporizador;