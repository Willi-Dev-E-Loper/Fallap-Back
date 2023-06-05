<?php

namespace App\Controller;

use App\Entity\Evento;
use App\Entity\Falla;
use App\Entity\Usuario;
use App\Repository\FallaRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/api/falla')]
class FallaController extends AbstractController
{
    #[Rest\Get('/', name: 'falla_api_list')]
    public function index(FallaRepository $fallaRepository): JsonResponse
    {
        $fallas = $fallaRepository->findAll();
        $fallasList = [];
        if (count($fallas) > 0) {
            foreach($fallas as $falla) {
                $fallasList[] = $falla->toArray();
            }
            $response = [
                'ok' => true,
                'fallas' => $fallasList,
            ];
        } else {
            $response = [
                'ok' => false,
                'error' => 'No se han encontrado fallas',
            ];
        }

        return new JsonResponse($response);
    }
    #[Rest\Get('/select', name: 'falla_api_select')]
    public function fallasToSelect(FallaRepository $fallaRepository): JsonResponse
    {
        $fallas = $fallaRepository->findFallasToSelect();
        $fallasList = [];
        if (count($fallas) > 0) {
            foreach($fallas as $falla) {
                $fallasList[] = ['idFalla' => $falla['idFalla'],
                                'nombre' => $falla['nombre']];
            }
            $response =  $fallasList;
        } else {
            $response = [
                'ok' => false,
                'error' => 'No se han encontrado fallas',
            ];
        }

        return new JsonResponse($response);
    }
    #[Rest\Get('/falleros/{id<\d+>}', name: 'falla_api_falleros')]
    public function getFalleros(FallaRepository $fallaRepository, $id=''): JsonResponse
    {
        $fallas = $fallaRepository->find($id);
        $falleros = $fallas->getFalleros() ;
        $fallerosList = [];
        if (count($falleros) > 0) {
            foreach($falleros as $fallero) {
                $fallerosList[] = ['idFallero' => $fallero->getId(),
                    'nombre' => $fallero->getNombre(),
                    'role'=>$fallero->getRoles()];
            }
            $response = $fallerosList;
        } else {
            $response = [
                'ok' => false,
                'error' => 'No se han encontrado fallas',
            ];
        }

        return new JsonResponse($response);
    }

    #[Rest\Post('/new', name: 'app_falla_new')]
    public function new(Request $request, FallaRepository $fallaRepository, ManagerRegistry $doctrine, FileUploader $fileUploader): Response
    {

        try {

            $data = $request->request->all();
            $portada = $request->files->get('imagenPortada');
            $logo = $request->files->get('logoFalla');
            $falla = new Falla($fileUploader, $doctrine);
            $falla->fromFormData($data, $portada, $logo);

            $entityManager = $doctrine->getManager();
            $entityManager->persist($falla);
            $entityManager->flush();

            $response = [
                'ok' => true,
                'message' => 'falla inserted',
            ];
        } catch (\Throwable $e) {
            $response = [
                'ok' => false,
                'error' => 'Failed to insert falla: '.$e->getMessage(),
            ];
        }
        //$response['evento']= $content;
        return new JsonResponse($response);


    }

    #[Rest\Put('/{id<\d+>}', name: 'edit_falla')]
    public function editFalla(ManagerRegistry $doctrine, Request $request, $id=''): JsonResponse {

        try {
            $content = $request->getContent();

            $falla = $doctrine->getRepository(Falla::class)->find($id);
            $falla->fromJson($content, $doctrine);

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
    #[Rest\Put('/setPremios/{id<\d+>}', name: 'set_premios_falla')]
    public function setPremios(ManagerRegistry $doctrine, Request $request, $id=''): JsonResponse {

        try {
            $content = $request->getContent();

            $falla = $doctrine->getRepository(Falla::class)->find($id);
            $premios= json_decode($content, true);
            $falla->setPremios($premios['premios']);

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
    #[Rest\Post('/llibret', name: 'set_llibret_falla')]
    public function setLlibret(ManagerRegistry $doctrine, Request $request,FileUploader $fileUploader): JsonResponse {

        try {
            $llibret = $request->files->get('llibret');
            $id = $request->get('falla');
            $nombre = $request->get('nombreLlibret');
            $fecha = $request->get('fecha');

            $falla = $doctrine->getRepository(Falla::class)->find($id);


            if ($llibret) {
                $pdfPath = $fileUploader->upload($llibret);;
                $llibretArr=[$nombre, $fecha, $pdfPath];
                $falla->setLlibrets($llibretArr);
            }


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
    #[Rest\Post('/premio', name: 'set_premio_falla')]
    public function setPremio(ManagerRegistry $doctrine, Request $request,FileUploader $fileUploader): JsonResponse {

        try {
            $content = $request->getContent();
            $content = json_decode($content, true);
            $id = $content['falla'];
            $falla = $doctrine->getRepository(Falla::class)->find($id);

            $premio=[$content['premio'],$content['seccion'],$content['monument'],$content['posicion']];
            $falla->setPremios($premio);


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
    #[Rest\Post('/delete-premio', name: 'delete_premio_falla')]
    public function unsetPremio(ManagerRegistry $doctrine, Request $request,FileUploader $fileUploader): JsonResponse {

        try {
            $content = $request->getContent();
            $content = json_decode($content, true);
            $id = $content['falla'];
            $indice = $content['indice'];
            $falla = $doctrine->getRepository(Falla::class)->find($id);

            $premios = $falla->getPremios();
            dump($premios);

            if (isset($premios[$indice])) {
                array_splice($premios, $indice, 1);
            }

            dump($premios);

            $falla->unsetPremios($premios);


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
    #[Rest\Delete('/{id<\d+>}', name: 'delete_falla')]
    public function deleteFalla(ManagerRegistry $doctrine, Request $request, $id=''): JsonResponse {
        $falla = $doctrine->getRepository(Falla::class)->find($id);
        if ($falla) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($falla);
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
