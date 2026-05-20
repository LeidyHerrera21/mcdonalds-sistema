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
