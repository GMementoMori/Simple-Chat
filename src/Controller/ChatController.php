<?php

namespace App\Controller;

use App\Repository\UserRelationsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use App\Repository\UserRepository;
use App\Repository\MessageRepository;
use Doctrine\Persistence\ManagerRegistry;

class ChatController extends AbstractController
{
    /**
     * @var Security
     */
    private $security;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var ManagerRegistry
     */
    private $doctrine;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var MessageRepository
     */
    private $messageRepository;

    /**
     * @var UserRelationsRepository
     */
    private $userRelationsRepository;

    public function __construct(
        Security          $security,
        ManagerRegistry   $doctrine,
        UserRepository    $userRepository,
        MessageRepository $messageRepository,
        RequestStack      $requestStack,
        UserRelationsRepository $userRelationsRepository

    )
    {
        $this->security = $security;
        $this->userRepository = $userRepository;
        $this->messageRepository = $messageRepository;
        $this->doctrine = $doctrine;
        $this->requestStack = $requestStack;
        $this->userRelationsRepository = $userRelationsRepository;
    }

    /**
     * @Route("/chat", name="chat")
     */
    public function index(Request $request): Response
    {
        $user = $this->security->getUser();
        $recipientEmail = $request->request->get('recipientEmail');

        if ($recipientEmail) {
            $session = $this->requestStack->getSession();
            $session->set('recipientEmail', $recipientEmail);
            $recipient = $this->userRepository->findByEmail($recipientEmail);

            $this->readMessages($user, $recipient[0]);
            $massages = $this->messageRepository->findBySenderRecipient($user->getId(), $recipient[0]->getId());
        }

//        $userList = $this->userRepository->findAll();
        $userList = $this->userRelationsRepository->findFriendsByUserId($user->getId());

        $countMessage = $this->messageRepository->findUnreadByRecipient($user->getId());

        $arrayUsersMessage = [];
        foreach ($userList as $currentUserFromList) {
            if ($currentUserFromList->getEmail() !== $user->getUserIdentifier()) {
                $arrayUsersMessage[$currentUserFromList->getEmail()] = '';
            }
        }
        foreach ($countMessage as $currentMessage) {
            $arrayUsersMessage[$currentMessage['email_sender']] = $currentMessage['cnt'];
        }

        return $this->render('chat/body.html.twig', [
            'current_user' => $user->getUserIdentifier(),
            'massages' => $massages ?? [],
            'users' => $userList,
            'email_recipient' => $recipientEmail,
            'users_messages' => $arrayUsersMessage ?? null,
        ]);

    }

    public function readMessages($user, $recipient)
    {
        $entityManager = $this->doctrine->getManager();
        $messagesForCheck = $this->messageRepository->findBy(['id_recipient' => $user->getId(), 'id_sender' => $recipient->getId()]);
        foreach ($messagesForCheck as $messageForCheck) {
            $messageForCheck->setIsChecked(1);
            $entityManager->persist($messageForCheck);
        }
        $entityManager->flush();

    }
}
