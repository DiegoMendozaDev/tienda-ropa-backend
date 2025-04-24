<?php
// src/Controller/AuthController.php
namespace App\Controller;

use App\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Attribute\Route;

class LoginController extends AbstractController
{
    
     #[Route("/api/login", name:"api_login", methods:["POST"])]
     
    public function login(Request $request, EntityManagerInterface $em,UserPasswordHasherInterface $passwordEncoder, JWTTokenManagerInterface $JWTManager)
    {
        // Obtener datos de la solicitud (como email y password)
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? null;
        $password = $data['contrasena'] ?? null;

        if (!$email || !$password) {
            return new JsonResponse(['error' => 'Missing credentials'], 400);
        }

        // Buscar el usuario por email
        $user = $em->getRepository(Usuario::class)->findOneBy(['email' => $email]);

        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], 404);
        }

        // Verificar si la contraseña es correcta
        if (!$passwordEncoder->isPasswordValid($user, $password)) {
            return new JsonResponse(['error' => 'Invalid credentials'], 401);
        }

        // Generar el token JWT
        $token = $JWTManager->create($user);

        // Retornar el token como respuesta
        return new JsonResponse(['token' => $token]);
    }
}
