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

/* ================= CHATBOT ================= */
function toggleChatbot(){

    let chatbot = document.getElementById("chatbot");

    if(chatbot.style.display === "flex"){
        chatbot.style.display = "none";
    }else{
        chatbot.style.display = "flex";
    }
}

function sendMessage(){

    let input = document.getElementById("userInput");
    let message = input.value.trim();

    if(message === ""){
        return;
    }

    let chatbox = document.getElementById("chatbox");

    // MENSAJE USUARIO

    let userDiv = document.createElement("div");

    userDiv.classList.add("message");
    userDiv.classList.add("user-message");

    userDiv.innerHTML = message;

    chatbox.appendChild(userDiv);

    // RESPUESTA BOT

    let botDiv = document.createElement("div");

    botDiv.classList.add("message");
    botDiv.classList.add("bot");

    let response = "";

    let texto = message.toLowerCase();

    if(texto.includes("hola")){

        response = " ¡Hola! ¿Deseas ver el menú o realizar un pedido?";

    }else if(texto.includes("menu")){

        response = `
             MENÚ DISPONIBLE:<br><br>

            🍔 Big Mac<br>
            🍟 Papas Fritas<br>
            🥤 Coca Cola<br>
            🍗 Nuggets<br>
            🍦 McFlurry
        `;

    }else if(texto.includes("big mac")){

        response = "🍔 La Big Mac cuesta S/ 18.90";

    }else if(texto.includes("pedido")){

        response = "🛒 Claro, indícame qué deseas ordenar.";

    }else if(texto.includes("gracias")){

        response = "😊 Gracias por visitar McDonald's.";

    }else{

        response = " No entendí tu mensaje.";
    }

    setTimeout(() => {

        botDiv.innerHTML = response;

        chatbox.appendChild(botDiv);

        chatbox.scrollTop = chatbox.scrollHeight;

    }, 500);

    input.value = "";
}

document.querySelectorAll('.card').forEach(card => {
    card.addEventListener('click', function() {
        const tipoReporte = this.getAttribute('data-reporte');
        if (tipoReporte) {
            // Salimos de principal/ y entramos a reportes/
            window.open(`../reportes/generar_reporte.php?tipo=${tipoReporte}`, '_blank');
        }
    });
});
// 1. ACTUALIZAR CONTADORES EN TIEMPO REAL
function actualizarContadores() {
    // Al estar dashboard.php en 'principal', obtener_conteos.php debe estar en la misma carpeta
    fetch('obtener_conteos.php')
        .then(response => {
            if (!response.ok) {
                throw new Error("Error en la respuesta del servidor");
            }
            return response.json();
        })
        .then(data => {
            // Buscamos cada ID num-cliente, num-categoria, etc. y pintamos el valor
            for (const clave in data) {
                const elemento = document.getElementById(`num-${clave}`);
                if (elemento) {
                    elemento.textContent = data[clave];
                }
            }
        })
        .catch(error => console.error("Error al mapear el JSON:", error));
}

// Cargar al iniciar y configurar el intervalo de 5 segundos
document.addEventListener('DOMContentLoaded', () => {
    actualizarContadores();
    setInterval(actualizarContadores, 5000);
});