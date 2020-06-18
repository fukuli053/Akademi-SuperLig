<?php

namespace App\Controller;

use App\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function index(EntityManagerInterface $entityManager)
    {
        $renderData = [];

        $teamRepo = $entityManager->getRepository(Team::class);
        $teamsCount = $teamRepo->teamsCount();

        if ($teamsCount == 18){
            //Teams sorted by points and goal difference
            $teams = $teamRepo->findBy([], ["points" => "desc", "goalDifference" => "desc"]);
            $renderData = [
                'teams' => $teams
            ];

        }else{
            $renderData = [
                'message_type' => 'warning',
                'message' => 'Lütfen 18 adet takım ekleyiniz.'
            ];
        }

        return $this->render('index/index.html.twig', $renderData);
    }
}
