<?php
require_once 'db_connection.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Permettre à n'importe quel site d'accéder à l'API
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

/**
 * Class dateFormat
 *
 * This class is responsible for handling date formatting operations.
 * Add detailed description of the class functionality here.
 *
 * @package Chat_r4a10
 */
class dateFormat {
    const SECONDE = 1;
    const MINUTE = 60 * self::SECONDE; // 1 minute
    const HEURE = 60 * self::MINUTE;   // 1 heure
    const JOUR = 24 * self::HEURE;     // 1 jour
    const SEMAINE = 7 * self::JOUR;    // 1 semaine
    const MOIS = 4 * self::SEMAINE;    // 1 mois

    private $date;

    /**
     * Constructor method to initialize the object with a timestamp.
     *
     * @param int $timestamp The timestamp to be used for initialization.
     */
    public function __construct($timestamp) {
        $this->date = []; 
        $diff = time() - $timestamp; // Calculer la différence entre le timestamp actuel et le timestamp fourni
        $rest = $diff; 

        // Formater la date en fonction de l'intervalle de temps
        if ($diff >= self::MOIS) {
            $this->date['string'] = 'envoyé le ' . date('d-m-Y H:i:s', $timestamp);

            // Calculer le nombre de mois, semaines, jours, heures, minutes et secondes
        } else if ($diff >= self::SEMAINE) {
            $this->date['semaine'] = floor($rest / self::SEMAINE);
            $rest %= self::SEMAINE;
            $this->date['jour'] = floor($rest / self::JOUR);
            $rest %= self::JOUR;
            $this->date['heure'] = floor($rest / self::HEURE);
            $rest %= self::HEURE;
            $this->date['minute'] = floor($rest / self::MINUTE);
            $rest %= self::MINUTE;
            $this->date['seconde'] = $rest;
            $this->date['string'] = 'Il y a ' . $this->date['semaine'] . ' semaine(s) et ' . $this->date['jour'] . ' jour(s)';

            // Calculer le nombre de jours, heures, minutes et secondes
        } else if ($diff >= self::JOUR) {
            $this->date['jour'] = floor($rest / self::JOUR);
            $rest %= self::JOUR;
            $this->date['heure'] = floor($rest / self::HEURE);
            $rest %= self::HEURE;
            $this->date['minute'] = floor($rest / self::MINUTE);
            $rest %= self::MINUTE;
            $this->date['seconde'] = $rest;
            $this->date['string'] = 'Il y a ' . $this->date['jour'] . ' jour(s)';

            // Calculer le nombre d'heures, minutes et secondes
        } else if ($diff >= self::HEURE) {
            $this->date['heure'] = floor($rest / self::HEURE);
            $rest %= self::HEURE;
            $this->date['minute'] = floor($rest / self::MINUTE);
            $rest %= self::MINUTE;
            $this->date['seconde'] = $rest;
            $this->date['string'] = 'Il y a ' . $this->date['heure'] . ' heure(s)';

            // Calculer le nombre de minutes et secondes
        } else if ($diff >= self::MINUTE) {
            $this->date['minute'] = floor($rest / self::MINUTE);
            $rest %= self::MINUTE;
            $this->date['seconde'] = $rest;
            $this->date['string'] = 'Il y a ' . $this->date['minute'] . ' minute(s)';

            // Calculer le nombre de secondes
        } else if ($diff >= self::SECONDE) {
            $this->date['seconde'] = $rest;
            $this->date['string'] = 'Il y a ' . $this->date['seconde'] . ' seconde(s)';
       
            // Si le message a été envoyé il y a moins d'une seconde
        } else {
            $this->date['string'] = 'Il y a moins d\' une seconde';
        }
    }

    /**
     * Retrieves the date.
     *
     * @return mixed The date value. The return type should be specified more clearly 
     *               based on the actual implementation (e.g., string, DateTime, etc.).
     */
    public function getDate() {
        return $this->date;
    }
}

/**
 * Retrieves new messages from the server that were created after the specified timestamp.
 *
 * @param string $lastFetchTime The timestamp of the last fetch in a format compatible with the database (e.g., 'Y-m-d H:i:s').
 * @return array An array of new messages retrieved from the server.
 */
function getNewMessages($lastFetchTime) {
    $db = new Connection();
    $conn = $db->getConnection();

    // Récupérer les messages envoyés après $lastFetchTime
    $query = $conn->prepare("SELECT * FROM messages WHERE timeSend > :lastFetchTime ORDER BY timeSend ASC");
    $query->bindParam(':lastFetchTime', $lastFetchTime, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);

    $db->closeConnection();

    return $result;
}

/**
 * Retrieves the initial set of messages.
 *
 * @param int $limit The maximum number of messages to retrieve. Defaults to 10.
 * @return array The list of messages retrieved.
 */
function getInitialMessages($limit = 10) {
    $db = new Connection();
    $conn = $db->getConnection();

    // Récupérer les 10 messages les plus récents
    $query = $conn->prepare("SELECT * FROM messages ORDER BY timeSend DESC LIMIT :limit");
    $query->bindParam(':limit', $limit, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);

    $db->closeConnection();

    return $result;
}

/**
 * Formats an array of messages as HTML.
 *
 * @param array $messages An array of messages to be formatted. Each message
 *                        should be an associative array or object containing
 *                        the necessary data for rendering.
 * 
 * @return string The formatted HTML string representing the messages.
 */
function formatMessagesAsHTML($messages) {
    $html = '';
    // Inverser l'ordre des messages pour les afficher du plus ancien au plus récent
    $messages = array_reverse($messages);

    // Formater chaque message
    foreach ($messages as $message) {
        $pseudo = htmlspecialchars($message['pseudo']);
        $messageText = htmlspecialchars($message['message']);
        $timeSend = htmlspecialchars($message['timeSend']);
        $date = (new dateFormat($timeSend))->getDate();

        // Génére le code HTML pour chaque message
        $html .= "<div class='message-div'>";
        $html .= "<div class='pseudo'>{$pseudo}</div>";
        $html .= "<div class='message'>{$messageText}</div>";
        $html .= "<div class='date'>{$date['string']}</div>";
        $html .= "</div>";
    }
    return $html;
}
?>