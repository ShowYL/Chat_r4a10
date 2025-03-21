<?php
require_once '../../php/recuperer.php';

header("Access-Control-Allow-Origin: *"); // Permettre à n'importe quel site d'accéder à l'API
header("Access-Control-Allow-Methods: GET"); // Accepter seulement les requêtes de type GET
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Accepter les en-têtes HTTP
header('Content-Type: text/html'); // Réponse au format HTML


switch($_SERVER['REQUEST_METHOD']){
    case "GET":
        if (isset($_GET['limit'])) {
            // Récupérer les messages les plus récents
            $limit = intval($_GET['limit']);
            $messages = getInitialMessages($limit);
            deliverResponse(200, 'Success');
        } elseif (isset($_GET['lastFetchTime'])) {
            // Récupérer les nouveaux messages après un certain timestamp
            $lastFetchTime = intval($_GET['lastFetchTime']);
            $messages = getNewMessages($lastFetchTime);
            deliverResponse(200, 'Success');
        } else {
            http_response_code(400); // mauvaise requête
            echo json_encode(['error' => 'Invalid parameters']); // Afficher un message d'erreur
            exit;
        }
        echo formatMessagesAsHTML($messages); // Afficher les messages sous forme de HTML
        break;
    default:
        http_response_code(405); // Méthode non autorisée
        echo json_encode(['error' => 'Method Not Allowed']);
        break;
}
?>