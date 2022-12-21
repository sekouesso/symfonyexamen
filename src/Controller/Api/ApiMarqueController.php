<?php

namespace App\Controller\Api;


use App\Entity\Marque;
use App\Repository\MarqueRepository;
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


class ApiMarqueController extends AbstractController
{

    public function __construct(
        private SerializerInterface $serializer,
        private EntityManagerInterface $em
    ) {
    }

    #[Route('/api/marque/create', name: 'app_api_marques_create', methods: ['POST'])]
    #[OA\Post(
        description: "Ajouter une marque dans la BD"
    )]
    #[OA\RequestBody(
        description: "payload",
        content: new Model(type: Marque::class)
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new Model(type: Marque::class)
    )]
    // #[OA\Parameter(
    //     name: 'order',
    //     in: 'query',
    //     description: 'The field used to order rewards',
    //     schema: new OA\Schema(type: 'string')
    // )]
    #[OA\Tag(name: 'Marque')]
    #[Security(name: 'Bearer')]
    public function create_marque(Request $request): Response
    {
        $data = $request->getContent();
        $marque = $this->serializer->deserialize($data, Marque::class, 'json');
        //dd($marque);
        $this->em->persist($marque);
        $this->em->flush();
        return new JsonResponse([
            "code" => 200,
            "message" => "success"
        ]);
    }

    #[Route('/api/marques', name: 'app_api_marques', methods: ['GET'])]
    #[OA\Get(
        description: "Recuperer les marques de la BD"
    )]

    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new Model(type: Marque::class)
    )]
    // #[OA\Parameter(
    //     name: 'order',
    //     in: 'query',
    //     description: 'The field used to order rewards',
    //     schema: new OA\Schema(type: 'string')
    // )]
    #[OA\Tag(name: 'Marque')]
    #[Security(name: 'Bearer')]
    public function index(MarqueRepository $marqueRepository): Response
    {
        $marques = $marqueRepository->findAll();

        if ($marques) {
            return new JsonResponse(
                $this->serializer->serialize($marques, 'json'),
                200,
                [],
                true
            );
        } else {
            return new JsonResponse(["Error" => "pas de donnees"]);
        }
    }

    #[Route('/api/marques/{id<\d+>}', name: 'app_api_marques_one', methods: ['GET'])]
    #[OA\Get(
        description: "Recuperer une marque de la BD avec son ID"
    )]

    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new Model(type: Marque::class)
    )]
    #[OA\Tag(name: 'Marque')]
    #[Security(name: 'Bearer')]
    public function findOnemarque(Marque $marque = null): Response
    {
        if ($marque) {
            return new JsonResponse(
                $this->serializer->serialize($marque, 'json'),
                200,
                [],
                true
            );
        } else {
            return new JsonResponse(["Error" => "pas de donnees"]);
        }
    }

    #[Route('/api/marques/{id<\d+>}', name: 'app_api_marques_update', methods: ['PATCH'])]
    #[OA\Post(
        description: "Ajouter une marque dans la BD"
    )]
    #[OA\RequestBody(
        description: "payload",
        content: new Model(type: Marque::class)
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new Model(type: Marque::class)
    )]
    #[OA\Tag(name: 'Marque')]
    #[Security(name: 'Bearer')]
    public function updatemarque(Marque $marque = null, Request $request): Response
    {
        if ($marque) {
            $data = $request->getContent();
            $m = $this->serializer->deserialize($data, Marque::class, 'json');
            //  dd($marque);
            $marque->setLibelle($m->getLibelle());
            // dd($marque);
            $this->em->persist($marque);
            $this->em->flush();
            return new JsonResponse([
                "code" => 200,
                "message" => "Mise a jour effectuer avec success",
            ]);
        } else {
            return new JsonResponse(["Error" => "pas de donnees"]);
        }
    }

    #[Route('/api/marques/{id<\d+>}', name: 'app_marques_delete', methods: ['DELETE'])]
    #[OA\Delete(
        description: "Recuperer une marque de la BD"
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new Model(type: Marque::class)
    )]
    #[OA\Tag(name: 'Marque')]
    #[Security(name: 'Bearer')]
    public function deletemarque(Marque $marque = null): Response
    {
        //dd($marque);
        $this->em->remove($marque);
        $this->em->flush();
        return new JsonResponse([
            "code" => 200,
            "message" => "success"
        ]);
    }

}
