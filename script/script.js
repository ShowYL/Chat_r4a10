import getXHR from './utils.js';

const rafraichissement = 1000 // temps de rafraichissement en millisecondes

const pseudo = document.getElementById('pseudo');
const message = document.getElementById('message');
const button = document.getElementById('button-send');

let pseudoValue = ""; // valeur pseudo
let messageValue = ""; // valeur message

// synchroniser les valeurs des inputs et les variables
pseudo.addEventListener('input', (event) => {
    (pseudoValue = event.target.value);
});
message.addEventListener('input', (event) => {
    (messageValue = event.target.value);
});

// clic button action
button.addEventListener('click', envoyer);

// appuyer sur entrer quand l input message est focus pour envoyer un message
message.addEventListener('keydown', (event) => {
    if (event.key === 'Enter' && messageValue !== '') {
        envoyer();
    }
});

// fetch en continue les messages
document.addEventListener('DOMContentLoaded', (event) => {
    loadInitialMessages();
    setInterval(fetchMessages, rafraichissement);
});

function loadInitialMessages() {
    const initialMessagesContainer = $("#initial-messages");
    initialMessagesContainer.load('../api/recupererMessage/endpoint.php?limit=10', function(response, status, xhr) {
        if (status == "error") {
            console.log("Erreur: " + xhr.status + " " + xhr.statusText);
        } else {
            console.log("10 premiers messages chargés");
        }
    });
}
const lastFetchTime = Math.floor(Date.now() / 1000);

// function pour récupérer les messages
function fetchMessages(){
    const messagesContainer = $("#new-messages"); // récupérer la div
    if (messagesContainer.length) {
        messagesContainer.load(`../api/recupererMessage/endpoint.php?lastFetchTime=${lastFetchTime}`, function(response, status, xhr) {
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

// function pour envoyer un message
async function envoyer(){
    let toSend = {"pseudo":pseudoValue,"message":messageValue}
    getXHR('../api/envoyerMessage/endpoint.php',"POST",JSON.stringify(toSend))
    .then(data => JSON.parse(data))
    .then(data => console.log(data))
    .then(() => message.value = "")
    .catch(err => console.error(err))
}
