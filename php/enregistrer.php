<?php
    require_once('db_connection.php');

    
    function setMessage($pseudo, $message, $timeSend) {
        $db = new Connection();
        $conn = $db->getConnection();

        $query = $conn->prepare("INSERT INTO messages (pseudo, message, timeSend) VALUES ( :pseudo, :message, :timeSend)");
        $query->bindParam(':pseudo', $pseudo);
        $query->bindParam(':message', $message);
        $query->bindParam(':timeSend', $timeSend);
        $result = $query->execute();

        $db->closeConnection();

        return $result;
    }

    function deliverResponse($status_code, $status_message, $data=null){

        http_response_code($status_code);
        header('Content-Type:application/json; charset=utf-8');
    
        $response['status_code'] = $status_code;
        $response['data'] = $data;
        $response['status'] = $status_message;
        
        $json_response = json_encode($response);
        if ($json_response === false){
            die('json encode ERROR : '.json_last_error_msg());
        }
    
        echo $json_response;
    }
?>