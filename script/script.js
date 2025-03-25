import getXHR from './utils.js';

const rafraichissement = 1000 // temps de rafraichissement en millisecondes

const pseudo = document.getElementById('pseudo');
const message = document.getElementById('message');
const button = document.getElementById('button-send');

const apiBaseUrl = "https://chatr410.alwaysdata.net/api"; // URL de l'API

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
button.addEventListener('click', ()  => {
    if (pseudoValue === '') {
        alert("Veuillez choisir un pseudo");
	return;
    }
    if (messageValue === '') {
        alert("Veuillez écrire un message");
	return;
    }
    if (pseudoValue !== '' && messageValue !== '') {
        envoyer();
    }
}
);  

// appuyer sur entrer quand l input message est focus pour envoyer un message
message.addEventListener('keydown', (event) => {
    if (event.key === 'Enter' && messageValue === '') {
        alert("Veuillez écrire un message");
	return;
    }
    if (event.key === 'Enter' && pseudoValue === '') {
        alert("Veuillez choisir un pseudo");
	return;
    }
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
    const initialMessagesContainer = $("#initial-messages"); // récupérer la div des 10 premiers messages
    // charger les messages depuis l'API en utilisant la fonction load 
    initialMessagesContainer.load(`${apiBaseUrl}/recupererMessage/endpoint.php?limit=10`, function(response, status, xhr) {
        if (status == "error") {
            console.log("Erreur: " + xhr.status + " " + xhr.statusText);
        } else {
            console.log("10 premiers messages chargés");
        }
    });
}


// function pour récupérer les messages
function fetchMessages(){
    const messagesContainer = $("#new-messages"); // récupérer la div des nouveau messages
    let lastFetchTime = Math.floor(Date.now() / 1000);
    if (messagesContainer.length) {
        // charger les messages depuis l'API en utilisant la fonction load 
        messagesContainer.load(`${apiBaseUrl}/recupererMessage/endpoint.php?lastFetchTime=${lastFetchTime}`, function(response, status, xhr) {
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
    getXHR(`${apiBaseUrl}/envoyerMessage/endpoint.php`,"POST",JSON.stringify(toSend)) // envoyer le message
    .then(data => JSON.parse(data))
    .then(data => console.log(data))
    .then(() => message.value = "")
    .catch(err => console.error(err))
}
