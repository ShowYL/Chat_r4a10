<?php
require_once '../../php/enregistrer.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST"); // Accepter seulement les requêtes de type POST
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type:application/json; charset=utf-8'); // Réponse au format JSON

switch($_SERVER['REQUEST_METHOD']){
    case "POST":
        $postedData = file_get_contents('php://input');
        $data = json_decode($postedData, true);
        // Vérifier si les données sont valides
        if (isset($data["pseudo"]) && isset($data['message'])){
            // Enregistrer le message dans la base de données
            if(setMessage($data["pseudo"], $data["message"], time())){
                deliverResponse(200, 'Sucessfull', header('Access-Control-Allow-Origin: *'));
            }else{
                deliverResponse(500, "Internal Error", header('Access-Control-Allow-Origin: *'));
            }
        }else{
            deliverResponse(400,"Le pseudo et le message n'est pas spécifié", header('Access-Control-Allow-Origin: *'));
        }
}
?>