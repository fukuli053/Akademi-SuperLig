<?php

namespace App\Controller;

use App\Service\FixtureService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FixtureController extends AbstractController
{
    /**
     * @Route("/fixture/{id<\d+>?1}", name="fixture")
     * @param int $id
     * @param FixtureService $fixtureService
     * @return Response
     */
    public function index(int $id, FixtureService $fixtureService)
    {
        //Generate fixture for id
        $fixture = $fixtureService->generate()->getFixture($id);

        return $this->render('fixture/index.html.twig', [
            'fixture' => $fixture
        ]);
    }

    /**
     * @Route("/fixture/play/{id<\d+>?1}", name="play-fixture")
     * @param int $id
     * @param FixtureService $fixtureService
     * @return Response
     */
    public function playFixture(int $id, FixtureService $fixtureService)
    {
        //Play fixture
        $fixtureService->playFixture($id);
        return $this->redirectToRoute('fixture', ['id' => $id]);
    }

}
