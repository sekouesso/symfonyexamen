<?php

namespace App\Controller;

use App\Entity\Voiture;
use App\Form\VoitureType;
use App\Repository\VoitureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VoitureController extends AbstractController
{
    #[Route('/voiture', name: 'app_voiture_liste')]
    public function index(VoitureRepository $voitureRepository): Response
    {
        return $this->render('voiture/index.html.twig', [
            'voitures' => $voitureRepository->findAll()
        ]);
    }

    #[Route('/voiture/add', name: 'app_voiture_add')]
    #[Route('/voiture/edit/{id}', name: 'app_voiture_update')]
    public function add__edit_category(Voiture $voiture=null, Request $request, EntityManagerInterface $entityManager): Response
    {

        if (!$voiture) {
            $voiture = new Voiture();
        }

        $form = $this->createForm(VoitureType::class, $voiture);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($voiture);
            $entityManager->flush();

         return $this->redirectToRoute('app_voiture_liste');
        }
        return $this->render('voiture/add_voiture.html.twig', [
            'voitureForm' => $form->createView(),
            'editMode'=>$voiture->getId()!==null,
            'voitures'=>$voiture
        ]);
    }
}
