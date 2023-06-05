<?php

namespace App\Controller;

use App\Entity\Encuesta;
use App\Repository\EncuestaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/api/encuesta')]
class EncuestaController extends AbstractController
{
    #[Rest\Get('/', name: 'encuesta_api_list')]
    public function index(EncuestaRepository $encuestaRepository): JsonResponse
    {
        $encuestas = $encuestaRepository->findAll();
        $encuestasList = [];
        if (count($encuestas) > 0) {
            foreach($encuestas as $encuesta) {
                $encuestasList[] = $encuesta->toArray();
            }
            $response = [
                'ok' => true,
                'encuestas' => $encuestasList,
            ];
        } else {
            $response = [
                'ok' => false,
                'error' => 'No se han encontrado encuestas',
            ];
        }

        return new JsonResponse($response);
    }

    #[Rest\Post('/new', name: 'app_encuesta_new')]
    public function new(Request $request, EncuestaRepository $encuestaRepository, ManagerRegistry $doctrine): Response
    {

        try {
            $content = $request->getContent();
            $noticium = new Encuesta();
            $noticium->fromJson($content, $doctrine);

            $entityManager = $doctrine->getManager();
            $entityManager->persist($noticium);
            $entityManager->flush();

            $response = [
                'ok' => true,
                'message' => 'survey inserted',
            ];
        } catch (\Throwable $e) {
            $response = [
                'ok' => false,
                'error' => 'Failed to insert survey: '.$e->getMessage(),
            ];
        }

        return new JsonResponse($response);
    }

    #[Rest\Put('/{id<\d+>}', name: 'edit_encuesta')]
    public function editEncuesta(ManagerRegistry $doctrine, Request $request, $id=''): JsonResponse {

        try {
            $content = $request->getContent();

            $encuesta = $doctrine->getRepository(Encuesta::class)->find($id);
            $encuesta->clearRespuestas();
            $encuesta->fromJson($content, $doctrine);

            $entityManager = $doctrine->getManager();
            $entityManager->flush();

            $response = [
                'ok' => true,
                'message' => 'survey updated',
            ];
        } catch (\Throwable $e) {
            $response = [
                'ok' => false,
                'error' => 'Failed to update survey: '.$e->getMessage(),
            ];
        }

        return new JsonResponse($response);
    }
    #[Rest\Put('/react', name: 'react_encuesta')]
    public function reactEncuesta(ManagerRegistry $doctrine, Request $request): JsonResponse {

        try {
            $content = $request->getContent();
            $content = json_decode($content, true);
            $id = $content['id'];
            $respuesta = $content['respuesta'];
            $encuesta = $doctrine->getRepository(Encuesta::class)->find($id);
            $encuesta->setRespuestas($respuesta);
            $encuesta->setContador();

            $entityManager = $doctrine->getManager();
            $entityManager->flush();

            $response = [
                'ok' => true,
                'message' => 'survey updated',
            ];
        } catch (\Throwable $e) {
            $response = [
                'ok' => false,
                'error' => 'Failed to update survey: '.$e->getMessage(),
            ];
        }

        return new JsonResponse($response);
    }

    #[Rest\Delete('/{id<\d+>}', name: 'delete_encuesta')]
    public function deleteEncuesta(ManagerRegistry $doctrine, Request $request, $id=''): JsonResponse {
        $encuesta = $doctrine->getRepository(Encuesta::class)->find($id);
        if ($encuesta) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($encuesta);
            $entityManager->flush();

            $response = [
                'ok' => true,
                'message' => 'survey deleted',
            ];
        } else {
            $response = [
                'ok' => false,
                'error' => 'Delete failed: survey not found',
            ];
        }

        return new JsonResponse($response);
    }

}
