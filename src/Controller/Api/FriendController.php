<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use App\Repository\UserRepository;
use App\Repository\UserRelationsRepository;
use App\Entity\UserRelations;

/**
 * @Route("/api", name="api_")
 */
class FriendController extends AbstractController
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
     * @Route("/friend", name="friend_index", methods={"GET"})
     */
    public function index(): Response
    {
        $currentUser = $this->security->getUser();

        $userRelations = $this->userRelationsRepository->findFriendsByUserId($currentUser->getId());

        $data = [];

        foreach ($userRelations as $friend) {
            $data[] = [
                'email' => $friend->getEmail()
            ];
        }

        return $this->json($data);
    }

    /**
     * @Route("/friend/search", name="friend_search", methods={"POST"})
     */
    public function search(Request $request): Response
    {
        if (!empty($request->request->get('searchEmail'))) {
            $users = $this->userRepository->findByEmailSimular($request->request->get('searchEmail'));

            return $this->json($users);
        }

        return $this->json('Request is empty');

    }

    /**
     * @Route("/friend", name="friend_new", methods={"POST"})
     */
    public function new(Request $request): Response
    {
        if (!empty($request->request->get('selectEmailUser'))) {
            $currentUser = $this->security->getUser();
            $friend = $this->userRepository->findByEmail($request->request->get('selectEmailUser'));

            $entityManager = $this->getDoctrine()->getManager();

            $project = new UserRelations();
            $project->setUserId($currentUser->getId());
            $project->setFriendId($friend[0]->getId());

            $entityManager->persist($project);
            $entityManager->flush();

            return $this->json('Add new friend successfully with id ' . $project->getId());
        }

        return $this->json('Request is empty');

    }

    /**
     * @Route("/friend/{id}", name="friend_delete", methods={"DELETE"})
     */
    public function delete(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $project = $entityManager->getRepository(Project::class)->find($id);

        if (!$project) {
            return $this->json('No project found for id' . $id, 404);
        }

        $entityManager->remove($project);
        $entityManager->flush();

        return $this->json('Deleted a project successfully with id ' . $id);
    }


}