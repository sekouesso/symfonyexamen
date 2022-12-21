<?php

namespace App\Controller\Api;


use App\Entity\Voiture;
use App\Repository\VoitureRepository;
use JMS\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;


class AppController extends AbstractController
{

    public function __construct(
        private SerializerInterface $serializer,
        private EntityManagerInterface $em
    ) {
    }

    #[Route('/api/voiture/create', name: 'app_api_voitures_create', methods: ['POST'])]
    #[OA\Post(
        description: "Ajouter une voiture dans la BD"
    )]
    #[OA\RequestBody(
        description: "payload",
        content: new Model(type: Voiture::class)
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new Model(type: Voiture::class)
    )]
   
    #[OA\Tag(name: 'Voiture')]
    #[Security(name: 'Bearer')]
    public function create_voiture(Request $request): Response
    {
        $data = $request->getContent();
        $voiture = $this->serializer->deserialize($data, Voiture::class, 'json');
        //dd($voiture);
        $this->em->persist($voiture);
        $this->em->flush();
        return new JsonResponse([
            "code" => 200,
            "message" => "success"
        ]);
    }

    #[Route('/api/voitures', name: 'app_api_voitures', methods: ['GET'])]
    #[OA\Get(
        description: "Recuperer les voitures de la BD"
    )]

    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new Model(type: Voiture::class)
    )]
    #[OA\Tag(name: 'Voiture')]
    #[Security(name: 'Bearer')]
    public function index(VoitureRepository $voitureRepository): Response
    {
        $voitures = $voitureRepository->findAll();

        if ($voitures) {
            return new JsonResponse(
                $this->serializer->serialize($voitures, 'json'),
                200,
                [],
                true
            );
        } else {
            return new JsonResponse(["Error" => "pas de donnees"]);
        }
    }

    #[Route('/api/voitures/{id}', name: 'app_api_voitures_all', methods: ['GET'])]
    #[OA\Get(
        description: "Recuperer une voiture de la BD"
    )]

    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new Model(type: Voiture::class)
    )]
    #[OA\Tag(name: 'Voiture')]
    #[Security(name: 'Bearer')]
    public function findOneVoiture(Voiture $voiture = null): Response
    {
        if ($voiture) {
            return new JsonResponse(
                $this->serializer->serialize($voiture, 'json'),
                200,
                [],
                true
            );
        } else {
            return new JsonResponse(["Error" => "pas de donnees"]);
        }
    }

    #[Route('/api/voitures/{id}', name: 'app_api_voitures_update', methods: ['PATCH'])]
    #[OA\Patch(
        description: "Ajouter une voiture dans la BD"
    )]
    #[OA\RequestBody(
        description: "payload",
        content: new Model(type: Voiture::class)
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new Model(type: Voiture::class)
    )]
    #[OA\Tag(name: 'Voiture')]
    #[Security(name: 'Bearer')]
    public function updateVoiture(Voiture $voiture = null, Request $request): Response
    {
        if ($voiture) {
            $data = $request->getContent();
            $v = $this->serializer->deserialize($data, Voiture::class, 'json');
            //  dd($voiture);
            $voiture->setCouleur($v->getCouleur());
            $voiture->setAnnee($v->getAnnee());
            $voiture->setKilometrage($v->getKilometrage());
            $voiture->setNombreSiege($v->getNombreSiege());
            $voiture->setNumeroChassi($v->getNumeroChassi());
            // dd($voiture);
            $this->em->persist($voiture);
            $this->em->flush();
            return new JsonResponse([
                "code" => 200,
                "message" => "Mise a jour effectuer avec success",
            ]);
        } else {
            return new JsonResponse(["Error" => "pas de donnees"]);
        }
    }

    #[Route('/api/voitures/{id}', name: 'app_voitures_delete', methods: ['DELETE'])]
    #[OA\Delete(
        description: "Recuperer une voiture de la BD"
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new Model(type: Voiture::class)
    )]
    #[OA\Tag(name: 'Voiture')]
    #[Security(name: 'Bearer')]
    public function deleteVoiture(Voiture $voiture = null): Response
    {
        //dd($voiture);
        $this->em->remove($voiture);
        $this->em->flush();
        return new JsonResponse([
            "code" => 200,
            "message" => "success"
        ]);
    }

    

}
