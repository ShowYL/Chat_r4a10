export default async function getXHR(url, method="GET", data=null) {
    // Créer une promesse et permet donc l' utilisation de methode comme then et catch
    return new Promise((resolve, reject) => {
        // Créer un nouvel objet XMLHttpRequest
        let xhr = new XMLHttpRequest();

        // Initialiser la requête
        xhr.open(method, url);

        // Définir l'en-tête Content-Type pour les requêtes POST avec des données
        if (data && method === "POST") {
            xhr.setRequestHeader("Content-Type", "application/json");
        }
        
        // Envoyer la requête avec ou sans données
        data ? xhr.send(data) : xhr.send()

        // Gérer la réponse réussie
        xhr.onload = () => {
            if (xhr.status !== 200) {
                console.log(`Error ${xhr.status}: ${xhr.statusText}`);
                reject(`Error ${xhr.status}: ${xhr.statusText}`);
            } else {
                console.log(`Done, got ${xhr.response.length} bytes`);
                resolve(xhr.responseText);
            }
        };

        // Gérer les événements de progression du téléchargement
        xhr.onprogress = (event) => {
            if (event.lengthComputable) {
                console.log(`Received ${event.loaded} of ${event.total} bytes`);
            } else {
                console.log(`Received ${event.loaded} bytes`);
            }
        };

        // Gérer les erreurs de requête
        xhr.onerror = () => {
            console.error(`Request failed`);
            reject(`Request failed`);
        };
    });
}
