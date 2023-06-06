<?php

namespace App\Controller;

use App\Entity\Comentario;
use App\Entity\Noticia;
use App\Repository\ComentarioRepository;
use App\Repository\NoticiaRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/api/noticia')]
class NoticiaController extends AbstractController
{
    #[Rest\Get('/', name: 'noticia_api_list')]
    public function index(NoticiaRepository $noticiaRepository): JsonResponse
    {
        $noticias = $noticiaRepository->findAll();
         $noticiasList = [];
        if (count($noticias) > 0) {
            foreach($noticias as $noticia) {
                $noticiasList[] = $noticia->toArray();
         }
            $response = [
                'ok' => true,
                'noticias' => $noticiasList,
            ];
         } else {
            $response = [
                'ok' => false,
                'error' => 'No se han encontrado noticias',
            ];
         }

        return new JsonResponse($response);
    }

    #[Rest\Post('/new', name: 'app_noticia_new')]
    public function new(Request $request, comentarioRepository $comentarioRepository, ManagerRegistry $doctrine, FileUploader $fileUploader): Response
    {

        try {
            $data = $request->request->all();
            $file = $request->files->get('file');
            $notice = new Noticia($doctrine);
            $notice->fromFormData($data, $file, $fileUploader, $doctrine);

            $entityManager = $doctrine->getManager();
            $entityManager->persist($notice);
            $entityManager->flush();

            $response = [
                'ok' => true,
                'message' => 'notice inserted',
            ];
        } catch (\Throwable $e) {
            $response = [
                'ok' => false,
                'error' => 'Failed to insert notice: '.$e->getMessage(),
            ];
        }

        return new JsonResponse($response);
    }

    #[Rest\Put('/{id<\d+>}', name: 'edit_noticia')]
    public function editNoticia(ManagerRegistry $doctrine, Request $request, $id=''): JsonResponse {

        try {
            $content = $request->getContent();

            $noticia = $doctrine->getRepository(Noticia::class)->find($id);
            $noticia->fromJson($content);

            $entityManager = $doctrine->getManager();
            $entityManager->flush();

            $response = [
                'ok' => true,
                'message' => 'contact updated',
            ];
         } catch (\Throwable $e) {
            $response = [
                'ok' => false,
                'error' => 'Failed to update contact: '.$e->getMessage(),
            ];
         }

         return new JsonResponse($response);
     }

    #[Rest\Delete('/{id<\d+>}', name: 'delete_noticia')]
    public function deleteNoticia(ManagerRegistry $doctrine, Request $request, $id=''): JsonResponse {
        $noticia = $doctrine->getRepository(Noticia::class)->find($id);
        if ($noticia) {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($noticia);
        $entityManager->flush();
        
        $response = [
            'ok' => true,
            'message' => 'contact deleted',
            ];
        } else {
           $response = [
            'ok' => false,
            'error' => 'Delete failed: contact not found',
            ];
        }
        
        return new JsonResponse($response);
    }

}
