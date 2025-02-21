<?php
require_once '../../php/enregistrer.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

switch($_SERVER['REQUEST_METHOD']){
    case "POST":
        $postedData = file_get_contents('php://input');
        $data = json_decode($postedData, true);
        if (isset($data["pseudo"]) && isset($data['message'])){
            if(setMessage($data["pseudo"], $data["message"], time())){
                deliverResponse(200, 'Sucessfull');
            }else{
                deliverResponse(500, "Internal Error");
            }
        }else{
            deliverResponse(400,"Le pseudo et le message n'est pas spécifié");
        }
}
?>