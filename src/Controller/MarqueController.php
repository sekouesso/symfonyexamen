<?php

namespace App\Controller;

use App\Entity\Marque;
use App\Form\MarqueType;
use App\Repository\MarqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MarqueController extends AbstractController
{
    #[Route('/marque', name: 'app_marque_liste')]
    public function index(MarqueRepository $marqueRepository): Response
    {
        return $this->render('marque/index.html.twig', [
            'marques' => $marqueRepository->findAll()
        ]);
    }

    #[Route('/marque/add', name: 'app_marque_add')]
    #[Route('/marque/edit/{id}', name: 'app_marque_update')]
    public function add__edit_marque(Marque $marque=null, Request $request, EntityManagerInterface $entityManager): Response
    {

        if (!$marque) {
            $marque = new Marque();
        }

        $form = $this->createForm(MarqueType::class, $marque);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($marque);
            $entityManager->flush();

         return $this->redirectToRoute('app_marque_liste');
        }
        return $this->renderForm('marque/add_marque.html.twig', [
            'marqueForm' => $form,
            'editMode'=>$marque->getId()!==null,
            'marques'=>$marque
        ]);
    }
}
