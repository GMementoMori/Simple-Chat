<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use App\Repository\UserRepository;
use App\Repository\UserRelationsRepository;

class HomeController extends AbstractController
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
     * @var UserRelationsRepository
     */
    private $userRelationsRepository;


    public function __construct(
        Security          $security,
        UserRepository    $userRepository,
        UserRelationsRepository $userRelationsRepository
    )
    {
        $this->security = $security;
        $this->userRepository = $userRepository;
        $this->userRelationsRepository = $userRelationsRepository;
    }

    /**
     * @Route("/", name="home")
     */
    public function index(Request $request): Response
    {
        $currentUser = $this->security->getUser();
//        $userList = $this->userRepository->findAll();


        return $this->render('home/body.html.twig', [
            'current_user' => $currentUser->getUserIdentifier(),
//            'user_list' => $userList,
        ]);

    }

}
