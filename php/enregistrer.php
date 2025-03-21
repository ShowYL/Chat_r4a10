<?php
    require_once('db_connection.php');

    header('Access-Control-Allow-Origin: *'); // Allow all origins
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');

    
    /**
     * Sets a message with the provided pseudo, message content, and timestamp.
     *
     * @param string $pseudo The username or identifier of the sender.
     * @param string $message The content of the message to be sent.
     * @param int $timeSend The timestamp indicating when the message was sent.
     *
     * @return void
     */
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

    /**
     * Sends a structured HTTP response.
     *
     * @param int $status_code The HTTP status code to send (e.g., 200 for success, 404 for not found).
     * @param string $status_message A message describing the status of the response.
     * @param mixed|null $data Optional data to include in the response body (e.g., an array or object).
     *
     * @return void
     */
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