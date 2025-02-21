import getXHR from './utils.js';


const pseudo = document.getElementById('pseudo');
const message = document.getElementById('message');
const button = document.getElementById('button-send');
let pseudoValue = "";
let messageValue = "";
pseudo.addEventListener('input', (event) => {
    (pseudoValue = event.target.value);
});
message.addEventListener('input', (event) => {
    (messageValue = event.target.value);
});
button.addEventListener('click', envoyer);




document.addEventListener('DOMContentLoaded', (event) => {
    setInterval(fetchMessages, 2000);
});

function fetchMessages(){
    const messagesContainer = $("#messages-container");
    if (messagesContainer.length) {
        messagesContainer.load('http://localhost:3001/api/recupererMessage/endpoint.php', function(response, status, xhr) {
            if (status == "error") {
                console.log("Erreur: " + xhr.status + " " + xhr.statusText);
            } else {
                console.log("Messages récupérés");
            }
        });
    } else {
        console.error("Element with ID 'messages-container' not found.");
    }
}

async function envoyer(){
    let toSend = {"pseudo":pseudoValue,"message":messageValue}
    getXHR('http://localhost:3001/api/envoyerMessage/endpoint.php',"POST",JSON.stringify(toSend))
    .then(data => JSON.parse(data))
    .then(data => console.log(data))
    .then(() => message.value = "")
    .catch(err => console.error(err))
}

function handleKeyDown(event) {
    if (event.key === 'Enter') {
        envoyer();
        const button = document.querySelector('button');
        button.classList.add('scale-120', '-rotate-90');
        setTimeout(() => {
            button.classList.remove('scale-120', '-rotate-90');
        }, 300);
    }
}