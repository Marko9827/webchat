<?php



namespace marko9827\Webchat\WebSocket;

require_once __DIR__. "/../../vendor/autoload.php";

use \Ratchet\MessageComponentInterface;
use \Ratchet\ConnectionInterface;
use \Ratchet\Server\IoServer;
use \Ratchet\WebSocket\WsServer;

class WebSocketServer implements MessageComponentInterface
{
    private $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Kada se novi korisnik poveže, dodajemo ga u listu aktivnih klijenata
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onClose(ConnectionInterface $conn)
    {
        // Kada korisnik napusti, uklanjamo ga iz liste klijenata
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        // Procesiranje poruka
        $data = json_decode($msg, true);

        if (isset($data['type'])) {
            switch ($data['type']) {
                case 'offer':
                    $this->handleOffer($from, $data);
                    break;
                case 'answer':
                    $this->handleAnswer($from, $data);
                    break;
                case 'candidate':
                    $this->handleCandidate($from, $data);
                    break;
                default:
                    echo "Unknown message type\n";
            }
        }
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    private function handleOffer(ConnectionInterface $from, $data)
    {
        // Pronađi drugi korisnik (ako je u grupi)
        foreach ($this->clients as $client) {
            if ($client !== $from) {
                $client->send(json_encode([
                    'type' => 'offer',
                    'offer' => $data['offer'],
                    'from' => $from->resourceId
                ]));
                break;
            }
        }
    }

    private function handleAnswer(ConnectionInterface $from, $data)
    {
        // Pronađi korisnika koji je poslao ponudu
        foreach ($this->clients as $client) {
            if ($client->resourceId == $data['from']) {
                $client->send(json_encode([
                    'type' => 'answer',
                    'answer' => $data['answer'],
                    'from' => $from->resourceId
                ]));
                break;
            }
        }
    }

    private function handleCandidate(ConnectionInterface $from, $data)
    {
        // Pronađi korisnika koji je poslao ponudu
        foreach ($this->clients as $client) {
            if ($client->resourceId == $data['from']) {
                $client->send(json_encode([
                    'type' => 'candidate',
                    'candidate' => $data['candidate'],
                    'from' => $from->resourceId
                ]));
                break;
            }
        }
    }
}

// Pokretanje servera
$server = IoServer::factory(
    new WsServer(
        new WebSocketServer()
    ),
    8080
);

echo "WebSocket server is running on ws://localhost:8080\n";
$server->run();
