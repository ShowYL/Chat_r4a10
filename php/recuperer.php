<?php
require_once 'db_connection.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

class dateFormat {
    const SECONDE = 1;
    const MINUTE = 60 * self::SECONDE; // 1 minute
    const HEURE = 60 * self::MINUTE;   // 1 heure
    const JOUR = 24 * self::HEURE;     // 1 jour
    const SEMAINE = 7 * self::JOUR;    // 1 semaine
    const MOIS = 4 * self::SEMAINE;    // 1 mois

    private $date;

    public function __construct($timestamp) {
        $this->date = [];
        $diff = time() - $timestamp;
        $rest = $diff;

        if ($diff >= self::MOIS) {
            $this->date['string'] = 'envoyé le ' . date('d-m-Y H:i:s', $timestamp);

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

        } else if ($diff >= self::JOUR) {
            $this->date['jour'] = floor($rest / self::JOUR);
            $rest %= self::JOUR;
            $this->date['heure'] = floor($rest / self::HEURE);
            $rest %= self::HEURE;
            $this->date['minute'] = floor($rest / self::MINUTE);
            $rest %= self::MINUTE;
            $this->date['seconde'] = $rest;
            $this->date['string'] = 'Il y a ' . $this->date['jour'] . ' jour(s)';

        } else if ($diff >= self::HEURE) {
            $this->date['heure'] = floor($rest / self::HEURE);
            $rest %= self::HEURE;
            $this->date['minute'] = floor($rest / self::MINUTE);
            $rest %= self::MINUTE;
            $this->date['seconde'] = $rest;
            $this->date['string'] = 'Il y a ' . $this->date['heure'] . ' heure(s)';

        } else if ($diff >= self::MINUTE) {
            $this->date['minute'] = floor($rest / self::MINUTE);
            $rest %= self::MINUTE;
            $this->date['seconde'] = $rest;
            $this->date['string'] = 'Il y a ' . $this->date['minute'] . ' minute(s)';

        } else if ($diff >= self::SECONDE) {
            $this->date['seconde'] = $rest;
            $this->date['string'] = 'Il y a ' . $this->date['seconde'] . ' seconde(s)';
        } else {
            $this->date['string'] = 'Il y a moins d\' une seconde';
        }
    }

    public function getDate() {
        return $this->date;
    }
}

function getMessages(){
    $db = new Connection();
    $conn = $db->getConnection();

    $query = $conn->prepare("SELECT * FROM messages ORDER BY timeSend DESC");
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);

    $db->closeConnection();

    return $result;
}

function formatMessagesAsHTML($messages) {
    $html = '';
    for ($i = count($messages) - 1; $i >= 0; $i--) {
        $message = $messages[$i];
        $pseudo = htmlspecialchars($message['pseudo']);
        $messageText = htmlspecialchars($message['message']);
        $timeSend = htmlspecialchars($message['timeSend']);
        $date = (new dateFormat($timeSend))->getDate();

        $html .= "<div class='message-div'>";
        $html .= "<div class='pseudo'>{$pseudo}</div>";
        $html .= "<div class='message'>{$messageText}</div>";
        $html .= "<div class='date'>{$date['string']}</div>";
        $html .= "</div>";
    }
    return $html;
}
?>