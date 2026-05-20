// app.js

function updateClock() {

    const now = new Date();

    const options = {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    };

    const time = now.toLocaleTimeString('es-PE', options);

    document.getElementById("clock").innerHTML = time;
}

setInterval(updateClock,1000);
updateClock();