<?php
require_once '../../php/recuperer.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header('Content-Type: text/html');

switch($_SERVER['REQUEST_METHOD']){
    case "GET":
        $messages = getMessages();
        echo formatMessagesAsHTML($messages);
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method Not Allowed']);
        break;
}
?>