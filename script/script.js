let messages = $state([]);
let pseudo = $state('');
let message = $state('');

document.addEventListener('DOMContentLoaded', (event) => {
    setInterval(fetchMessages, 2000);
});

function fetchMessages(){
    $("#messages-container").load('/api/recupererMessage', function(response, status, xhr) {
        if (status == "error") {
            console.log("Erreur: " + xhr.status + " " + xhr.statusText);
        } else {
            console.log("Messages récupérés");
        }
    });
}

async function envoyer(){
    let toSend = {"pseudo":pseudo,"message":message}
    getXHR('/api/envoyerMessage',"POST",JSON.stringify(toSend))
    .then(data => JSON.parse(data))
    .then(data => console.log(data))
    .then(() => message="")
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