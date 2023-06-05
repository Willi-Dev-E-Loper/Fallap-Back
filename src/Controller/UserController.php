<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Repository\UsuarioRepository;
use Mailjet\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use \Mailjet\Resources;

#[Route('/api/user')]
class UserController extends AbstractController
{
    #[Rest\Get('/', name: 'user_api_list')]
    public function index(UsuarioRepository $userRepository): JsonResponse
    {
        $users = $userRepository->findAll();
        $usersList = [];
        if (count($users) > 0) {
            foreach($users as $user) {
                $usersList[] = $user->toArray();
            }
            $response = [
                'ok' => true,
                'users' => $usersList,
            ];
        } else {
            $response = [
                'ok' => false,
                'error' => 'No se han encontrado users',
            ];
        }

        return new JsonResponse($response);
    }
    #[Rest\Get('/{id}', name: 'get_user')]
    public function getUserbyId(ManagerRegistry $doctrine, UsuarioRepository $userRepository, Request $request, $id=''): JsonResponse {

        $user = $userRepository->findOneBy(['email'=>$id]);
        if(!empty($user)){
            $response = [
                'ok' => true,
                'user' => $user->toArray()
            ];
        } else {
            $response = [
                'ok' => false,
                'error' => 'No se han encontrado users',
            ];
        }

        return new JsonResponse($response);
    }

    #[Rest\Post('/new', name: 'app_user_new')]
    public function newUser( MailerInterface $mailer, Request $request, UsuarioRepository $userRepository, ManagerRegistry $doctrine, UserPasswordHasherInterface $userPasswordHasher): Response
    {

        try {
            $content = $request->getContent();
            $content = json_decode($content, true);
            $noticium = new Usuario();
            $noticium->fromJson($content, $doctrine);
            $pwd = bin2hex(openssl_random_pseudo_bytes(4));
            $noticium->setPassword($userPasswordHasher->hashPassword($noticium, $pwd));



            $entityManager = $doctrine->getManager();
            $entityManager->persist($noticium);
            $entityManager->flush();
            $mj = new Client($_ENV['MAILJET_API_KEY'],$_ENV['MAILJET_API_SECRET'],true,['version' => 'v3.1']);
            $body = [
                'Messages' => [
                    [
                        'From' => [
                            'Email' => "noreply@fallap.com",
                            'Name' => "Fallap"
                        ],
                        'To' => [
                            [
                                'Email' => $noticium->getEmail(),
                                'Name' => $noticium->getNombre()
                            ]
                        ],
                        'Subject' => "Hola follero, aqui tienes tus credenciales!!",
                        'TextPart' => "",
                        'HTMLPart' => '<p>¡Bienvenido/a! Tu cuenta ha sido creada exitosamente.</p>
                                        <p>Tus credenciales de inicio de sesión son:</p>
                                        <ul>
                                            <li>Correo electrónico: '.$noticium->getEmail().'</li>
                                            <li>Contraseña: '.$pwd.'</li>
                                        </ul>',
                        'CustomID' => "AppGettingStartedTest"
                    ]
                ]
            ];
            $res = $mj->post(Resources::$Email, ['body' => $body]);
            $res->success() && var_dump($res->getData());

            $response = [
                'ok' => true,
                'message' => $res->getStatus(),
            ];
        } catch (\Throwable $e) {
            $response = [
                'ok' => false,
                'error' => 'Failed to insert user: '.$e->getMessage(),
            ];
        }

        return new JsonResponse($response);
    }
    #[Rest\Post('/importCsv', name: 'app_user_import')]
    public function newUsersFromCSV(Request $request, UsuarioRepository $userRepository, ManagerRegistry $doctrine, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        try {
            $file = $request->files->get('csv');
            $idFalla= $request->request->get('idFalla');
            if (!$file) {
                throw new \Exception('No se ha proporcionado un archivo CSV');
            }

            $csvData = file_get_contents($file->getPathname());
            $lines = explode(PHP_EOL, $csvData);
            $headers = str_getcsv(array_shift($lines));
            $entityManager = $doctrine->getManager();

            foreach ($lines as $line) {
                $data = array_combine($headers, str_getcsv($line));
                $usuario = new Usuario();
                $usuario->fromJson($data, $doctrine);
                $pwd = bin2hex(openssl_random_pseudo_bytes(4));
                $usuario->setPassword($userPasswordHasher->hashPassword($usuario, $pwd));
                $usuario->setFalla($idFalla, $doctrine);
                $entityManager->persist($usuario);
                $mj = new Client($_ENV['MAILJET_API_KEY'],$_ENV['MAILJET_API_SECRET'],true,['version' => 'v3.1']);
                $body = [
                    'Messages' => [
                        [
                            'From' => [
                                'Email' => "noreply@fallap.com",
                                'Name' => "Fallap"
                            ],
                            'To' => [
                                [
                                    'Email' => $usuario->getEmail(),
                                    'Name' => $usuario->getNombre()
                                ]
                            ],
                            'Subject' => "Hola follero, aqui tienes tus credenciales!!",
                            'TextPart' => "",
                            'HTMLPart' => '<p>¡Bienvenido/a! Tu cuenta ha sido creada exitosamente.</p>
                                        <p>Tus credenciales de inicio de sesión son:</p>
                                        <ul>
                                            <li>Correo electrónico: '.$usuario->getEmail().'</li>
                                            <li>Contraseña: '.$pwd.'</li>
                                        </ul>',
                            'CustomID' => "AppGettingStartedTest"
                        ]
                    ]
                ];
                $res = $mj->post(Resources::$Email, ['body' => $body]);
                $res->success() && var_dump($res->getData());
            }

            $entityManager->flush();

            $response = [
                'ok' => true,
                'message' => 'Usuarios creados exitosamente',
            ];
        } catch (\Throwable $e) {
            $response = [
                'ok' => false,
                'error' => 'Error al crear los usuarios: ' . $e->getMessage(),
            ];
        }

        return new JsonResponse($response);
    }

    #[Rest\Put('/{id<\d+>}', name: 'edit_user')]
    public function editUser(ManagerRegistry $doctrine, Request $request, $id=''): JsonResponse {

        try {
            $content = $request->getContent();

            $user = $doctrine->getRepository(Usuario::class)->find($id);
            $user->fromJson($content, $doctrine);

            $entityManager = $doctrine->getManager();
            $entityManager->flush();

            $response = [
                'ok' => true,
                'message' => 'user updated',
            ];
        } catch (\Throwable $e) {
            $response = [
                'ok' => false,
                'error' => 'Failed to update user: '.$e->getMessage(),
            ];
        }

        return new JsonResponse($response);
    }
    #[Rest\Post('/setRoles', name: 'set_premios_falla')]
    public function changeRoles(ManagerRegistry $doctrine, Request $request): JsonResponse {

        try {
            $content = $request->getContent();

            $roles= json_decode($content, true);
            $user = $doctrine->getRepository(Usuario::class)->find($roles['idFallero']);
            $status= $roles['status'];
            if ($status === true){
                $user->setRoles(['ROLE_ADMIN']);
            }else{
                $user->removeAdminRole();
            }

            $entityManager = $doctrine->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $response = [
                'ok' => true,
                'message' => 'user updated',

            ];
        } catch (\Throwable $e) {
            $response = [
                'ok' => false,
                'error' => 'Failed to update user: '.$e->getMessage(),


            ];
        }

        return new JsonResponse($response);
    }

    #[Rest\Delete('/{id<\d+>}', name: 'delete_user')]
    public function deleteUser(ManagerRegistry $doctrine, Request $request, $id=''): JsonResponse {
        $user = $doctrine->getRepository(Usuario::class)->find($id);
        if ($user) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($user);
            $entityManager->flush();

            $response = [
                'ok' => true,
                'message' => 'user deleted',
            ];
        } else {
            $response = [
                'ok' => false,
                'error' => 'Delete failed: user not found',
            ];
        }

        return new JsonResponse($response);
    }



}
