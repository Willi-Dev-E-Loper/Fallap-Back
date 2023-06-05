<?php

namespace App\Controller;

use App\Entity\Evento;
use App\Repository\EventoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Service\FileUploader;



#[Route('/api/evento')]
class EventoController extends AbstractController
{
    #[Rest\Get('/', name: 'evento_api_list')]
    public function index(EventoRepository $eventoRepository): JsonResponse
    {
        $eventos = $eventoRepository->findAll();
        $eventosList = [];
        if (count($eventos) > 0) {
            foreach($eventos as $evento) {
                $eventosList[] = $evento->toArray();
            }
            $response = [
                'ok' => true,
                'eventos' => $eventosList,
            ];
        } else {
            $response = [
                'ok' => false,
                'error' => 'No se han encontrado eventos',
            ];
        }

        return new JsonResponse($response);
    }

    #[Rest\Post('/new', name: 'app_evento_new')]
    public function new(Request $request, EventoRepository $eventoRepository, ManagerRegistry $doctrine, FileUploader $fileUploader): Response
    {


       //$content = $request->getContent();
       //$content = json_decode($content, true);
       //$evento = new Evento();
       //$evento->fromJson($content, $doctrine);

        //$uploadedFile = $request->files->get('imagenEvento');
        //dump($uploadedFile);
        //dump($evento);
        //dump($content);
        try {

            $data = $request->request->all();
            $file = $request->files->get('imagenEvento');
           $evento = new Evento( $fileUploader,$doctrine);
           $evento->fromFormData($data, $file,$fileUploader,);

            $entityManager = $doctrine->getManager();
            $entityManager->persist($evento);
            $entityManager->flush();

            $response = [
                'ok' => true,
                'message' => 'event inserted',
            ];
        } catch (\Throwable $e) {
            $response = [
                'ok' => false,
                'error' => 'Failed to insert event: '.$e->getMessage(),
            ];
        }
        //$response['evento']= $content;
        return new JsonResponse($response);
    }

    #[Rest\Post('/{id<\d+>}', name: 'edit_evento')]
    public function editEvento(ManagerRegistry $doctrine, Request $request,FileUploader $fileUploader, $id=''): JsonResponse {

        try {
            $data = $request->request->all();
            $file = $request->files->get('imagenEvento');
            $evento = $doctrine->getRepository(Evento::class)->find($id);
            $evento->fromFormData($data, $file, $fileUploader);

            $entityManager = $doctrine->getManager();
            $entityManager->persist($evento);
            $entityManager->flush();


            $response = [
                'ok' => true,
                'message' => 'event updated',
            ];
        } catch (\Throwable $e) {
            $response = [
                'ok' => false,
                'error' => 'Failed to update event: '.$e->getMessage(),
            ];
        }

        return new JsonResponse($response);
    }
    #[Rest\Put('/setParticipante/{id<\d+>}', name: 'set_participante_evento')]
    public function setParticipante(ManagerRegistry $doctrine, Request $request, $id=''): JsonResponse {

        try {
            $content = $request->getContent();

            $evento = $doctrine->getRepository(Evento::class)->find($id);
            $idUsuario= json_decode($content, true);
            $evento->setParticipantes($idUsuario['idUsuario']);

            $entityManager = $doctrine->getManager();
            $entityManager->flush();

            $response = [
                'ok' => true,
                'message' => 'event updated',

            ];
        } catch (\Throwable $e) {
            $response = [
                'ok' => false,
                'error' => 'Failed to update event: '.$e->getMessage(),


            ];
        }

        return new JsonResponse($response);
    }

    #[Rest\Delete('/{id<\d+>}', name: 'delete_evento')]
    public function deleteEvento(ManagerRegistry $doctrine, Request $request, $id=''): JsonResponse {
        $evento = $doctrine->getRepository(Evento::class)->find($id);
        if ($evento) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($evento);
            $entityManager->flush();

            $response = [
                'ok' => true,
                'message' => 'event deleted',
            ];
        } else {
            $response = [
                'ok' => false,
                'error' => 'Delete failed: event not found',
            ];
        }

        return new JsonResponse($response);
    }

}
