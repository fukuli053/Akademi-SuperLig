<?php

namespace App\Controller;

use App\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeamController extends AbstractController
{
    /**
     * @Route("/teams", name="teams")
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function index(EntityManagerInterface $entityManager)
    {
        $teams = $entityManager->getRepository(Team::class)->findAll();
        $teamsCount = $entityManager->getRepository(Team::class)->teamsCount();
        return $this->render('team/index.html.twig', [
            'teams' => $teams,
            'teams_count' => $teamsCount
        ]);
    }

    /**
     * @Route("/teams/add", name="add-team")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function newTeam(Request $request, EntityManagerInterface $entityManager)
    {
        $teamRepo = $entityManager->getRepository(Team::class);
        $teamCount = $teamRepo->teamsCount();

        //Team count must be 18
        if($teamCount == 18){
            return $this->render('team/add.html.twig', [
                'message_type' => 'danger',
                'message' => 'Daha fazla takım ekleyemezsiniz.'
            ]);
        }

        $team = new Team();
        $team->setCreatedAt(new \DateTime('now'));
        $team->setPoints(0);

        //Generate Form
        $form = $this->createFormBuilder($team)
                ->add('name', TextType::class, [
                    'required' => true,
                    'label' => 'Takım Adı',
                    'attr' => [
                        'class' => 'form-control'
                    ]
                ])
                ->add('save', SubmitType::class,[
                    'label' => 'Kaydet',
                    'attr' => ['class' => 'btn btn-primary']
                ])->getForm();
        $form->handleRequest($request);

        //Form Validation and Submit
        if ($form->isSubmitted() && $form->isValid()){
            $team = $form->getData();

            $entityManager->persist($team);
            $entityManager->flush();

            return $this->redirectToRoute('teams');
        }

        return $this->render('team/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/teams/delete/{id<\d+>?1}", name="delete-team")
     * @param int $id
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse
     */
    public function deleteTeam(int $id, EntityManagerInterface $entityManager)
    {
        $teamRepo = $entityManager->getRepository(Team::class);
        $team = $teamRepo->find($id);

        $entityManager->remove($team);
        $entityManager->flush();

        return $this->redirectToRoute('teams');
    }
}
