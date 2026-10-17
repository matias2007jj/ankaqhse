// Reproductor del módulo: habilita "Completar módulo" solo al terminar el video
// y evita adelantar el video en módulos aún no completados.
document.addEventListener('DOMContentLoaded', function () {
    var video = document.getElementById('video-modulo');
    var boton = document.getElementById('btn-completar-modulo');
    var aviso = document.getElementById('aviso-video');
    if (!video) return;

    if (video.dataset.bloquear === '1') {
        var maxVisto = 0;

        video.addEventListener('timeupdate', function () {
            if (!video.seeking && video.currentTime > maxVisto) {
                maxVisto = video.currentTime;
            }
        });

        video.addEventListener('seeking', function () {
            if (video.currentTime > maxVisto + 1.5) {
                video.currentTime = maxVisto;
            }
        });
    }

    video.addEventListener('ended', function () {
        if (boton) boton.disabled = false;
        if (aviso) aviso.textContent = '¡Video terminado! Ya puedes continuar.';
    });
});
