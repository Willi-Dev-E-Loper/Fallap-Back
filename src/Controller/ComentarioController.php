<?php

namespace App\Controller;

use App\Entity\Comentario;
use App\Repository\ComentarioRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/api/comentario')]
class ComentarioController extends AbstractController
{
    #[Rest\Get('/', name: 'comentario_api_list')]
    public function index(comentarioRepository $comentarioRepository): JsonResponse
    {
        $comentarios = $comentarioRepository->findAll();
        $comentariosList = [];
        if (count($comentarios) > 0) {
            foreach($comentarios as $comentario) {
                $comentariosList[] = $comentario->toArray();
            }
            $response = [
                'ok' => true,
                'comentarios' => $comentariosList,
            ];
        } else {
            $response = [
                'ok' => false,
                'error' => 'No se han encontrado comentarios',
            ];
        }

        return new JsonResponse($response);
    }

    #[Rest\Post('/new', name: 'app_comentario_new')]
    public function new(Request $request, comentarioRepository $comentarioRepository, ManagerRegistry $doctrine, FileUploader $fileUploader): Response
    {

        try {
            $data = $request->request->all();
            $file = $request->files->get('file');
            $coment = new Comentario($doctrine);
            $coment->fromFormData($data, $file, $fileUploader);

            $entityManager = $doctrine->getManager();
            $entityManager->persist($coment);
            $entityManager->flush();

            $response = [
                'ok' => true,
                'message' => 'coment inserted',
            ];
        } catch (\Throwable $e) {
            $response = [
                'ok' => false,
                'error' => 'Failed to insert coment: '.$e->getMessage(),
            ];
        }

        return new JsonResponse($response);
    }

    #[Rest\Post('/{id<\d+>}', name: 'edit_comentario')]
    public function editcomentario(ManagerRegistry $doctrine, Request $request, FileUploader $fileUploader, $id=''): JsonResponse {

        try {
            $data = $request->request->all();
            $file = $request->files->get('file');
            $comentario = $doctrine->getRepository(comentario::class)->find($id);
            $comentario->fromFormData($data, $file, $fileUploader);

            $entityManager = $doctrine->getManager();
            $entityManager->flush();

            $response = [
                'ok' => true,
                'message' => 'coment updated',
            ];
        } catch (\Throwable $e) {
            $response = [
                'ok' => false,
                'error' => 'Failed to update coment: '.$e->getMessage(),
            ];
        }

        return new JsonResponse($response);
    }

    #[Rest\Delete('/{id<\d+>}', name: 'delete_comentario')]
    public function deletecomentario(ManagerRegistry $doctrine, Request $request, $id=''): JsonResponse {
        $comentario = $doctrine->getRepository(comentario::class)->find($id);
        if ($comentario) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($comentario);
            $entityManager->flush();

            $response = [
                'ok' => true,
                'message' => 'coment deleted',
            ];
        } else {
            $response = [
                'ok' => false,
                'error' => 'Delete failed: coment not found',
            ];
        }

        return new JsonResponse($response);
    }

}