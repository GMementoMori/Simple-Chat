<?php
namespace App\Service;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use App\Entity\Message;
use App\Entity\User;

class Chat implements MessageComponentInterface
{
    protected $clients;

    protected $doctrine;

    public function __construct($doctrine) {
        $this->clients = new \SplObjectStorage;
        $this->doctrine = $doctrine;
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        echo "Новое подключение ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg, true);
        $this->setMessage($data);

        foreach ($this->clients as $client) {
            $client->send($msg);
        }
    }

    public function setMessage($data)
    {
        $entityManager = $this->doctrine->getManager();
        $sender = $this->doctrine->getRepository(User::class)->findByEmail($data['sender_email']);
        $recipient = $this->doctrine->getRepository(User::class)->findByEmail($data['recipient_email']);
        $message = new Message();
        $message->setMessage($data['message']);
        $message->setIdSender($sender[0]->getId());
        $message->setIdRecipient($recipient[0]->getId());
        $message->setEmailSender($data['sender_email']);
        $message->setIsChecked(0); //$data['is_checked']
        $message->setDate(new \DateTime("now"));

        $entityManager->persist($message);

        $entityManager->flush();
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);

        echo "Отключение пользователя {$conn->resourceId} \n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "Есть ошибка: {$e->getMessage()}\n";

        $conn->close();
    }
}