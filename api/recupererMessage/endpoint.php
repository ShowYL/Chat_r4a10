<?php
require_once '../../php/recuperer.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: text/html');


switch($_SERVER['REQUEST_METHOD']){
    case "GET":
        if (isset($_GET['limit'])) {
            // Récupérer les messages les plus récents
            $limit = intval($_GET['limit']);
            $messages = getInitialMessages($limit);
        } elseif (isset($_GET['lastFetchTime'])) {
            // Récupérer les nouveaux messages après un certain timestamp
            $lastFetchTime = intval($_GET['lastFetchTime']);
            $messages = getNewMessages($lastFetchTime);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid parameters']);
            exit;
        }
        echo formatMessagesAsHTML($messages);
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method Not Allowed']);
        break;
}
?>