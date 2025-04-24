<?php
namespace App\Controller;

use App\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/usuario', name:'api_usuarios')]
class UsuarioController extends AbstractController
{
    #[Route('/crear', name:'crear', methods:['POST'])]
    public function create(EntityManagerInterface $entityManager, Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {

        $data = json_decode($request->getContent(), true);
        $requiredFields = ['nombre', 'email', 'contrasena', 'repeatContrasena', 'direccion', 'codigo_postal', 'terms'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                return $this->json(['error' => "El campo ". $field ." es obligatorio"], 400);
            }
        }
        if($data['repeatContrasena']!=$data['contrasena']){
            return $this->json(['error' => "Las contraseñas tienen que coincidir."], 400);
        }
        $comprobacionEmail=$entityManager->getRepository(Usuario::class)->comprobarEmail($data['email']);
        if($comprobacionEmail){
            return $this->json(['error' => "Ya hay un usuario regitrado con ese email."], 400);
        }
        $usuario = new Usuario();
        $usuario->setNombre($data['nombre']);
        $usuario->setEmail($data['email']);
        $hashedPassword = $passwordHasher->hashPassword($usuario, $data['contrasena']);
        $usuario->setPassword($hashedPassword); 
        $usuario->setDireccion($data['direccion']);
        $usuario->setPostal($data['codigo_postal']);

        $entityManager->persist($usuario);
        $entityManager->flush();

        return $this->json(["message" => "Usuario creado correctamente"], 201);
    }

    #[Route('/ver', name:"ver", methods:['GET'])]
    public function verTodos(EntityManagerInterface $entityManager): JsonResponse
    {
        $usuarios = $entityManager->getRepository(Usuario::class)->findAll();
        $data = [];
        foreach ($usuarios as $usuario) {
            $data[] = [
                'id'              => $usuario->getId(),
                'nombre'          => $usuario->getNombre(),
                'email'           => $usuario->getEmail(),
                'roles'           => $usuario->getRoles(),
                // No se retorna la contraseña por seguridad
                'fecha_registro'  => $usuario->getFecha(),
                'direccion'       => $usuario->getDireccion(),
                'codigo_postal'   => $usuario->getPostal()
            ];
        }
        return $this->json($data, 200);
    }

    #[Route('/eliminar/{id}', name:"eliminar", methods:['DELETE'])]
    public function eliminar(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $usuario = $entityManager->getRepository(Usuario::class)->find($id);
        if (!$usuario) {
            return $this->json(['error' => "Usuario con id {$id} no encontrado"], 404);
        }
        
        $entityManager->remove($usuario);
        $entityManager->flush();

        return $this->json(["message" => "Usuario eliminado correctamente"], 200);
    }

    #[Route('/editar/{id}', name:"editar", methods:["PUT"])]
    public function editar(EntityManagerInterface $entityManager, Request $request, int $id,UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $usuario = $entityManager->getRepository(Usuario::class)->find($id);
        if (!$usuario) {
            return $this->json(['error' => "Usuario con id {$id} no encontrado"], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['nombre'])) {
            $usuario->setNombre($data['nombre']);
        }
        if (isset($data['email'])) {
            $usuario->setEmail($data['email']);
        }
        if (isset($data['contrasena'])) {
            $hashedPassword = $passwordHasher->hashPassword($usuario, $data['contrasena']);
            $usuario->setPassword($hashedPassword); 
            $usuario->setPassword($hashedPassword);
        }
        if (isset($data['direccion'])) {
            $usuario->setDireccion($data['direccion']);
        }
        if (isset($data['codigo_postal'])) {
            $usuario->setPostal($data['codigo_postal']);
        }

        $entityManager->flush();
        return $this->json(["message" => "Usuario actualizado correctamente"], 200);
    }
    #[Route('/comprobar_usuario', name : "_comprobar", methods:["POST"])]
    public function comprobar(EntityManagerInterface $entityManager,UserPasswordHasherInterface $passwordHasher, Request $request): JsonResponse{
        $data = json_decode($request->getContent(), true);
        $usuario = $entityManager->getRepository(Usuario::class)->findOneBy(['email' => $data["email"]]);
        $pass = $passwordHasher->isPasswordValid($usuario, $data["contrasena"]);
        return $this->json($pass);
        
    }
    #[Route('/ver_usuario', name : "ver_usuario", methods:["POST"])]
    public function ver_usuario(EntityManagerInterface $entityManager, Request $request): JsonResponse{
        $data = json_decode($request->getContent(), true);
        if(!$data['email']){
            return $this->json(['message'=> 'El email es obligatorio'],400);
        }
        $usuario = $entityManager->getRepository(Usuario::class)->findOneBy(['email' => $data["email"]]);
        if(!$usuario){
            return $this->json(['message'=> 'usuario no emcontrado'],400);
        }else{
            return $this->json([
                "id" => $usuario->getId()
               ],200);
        }
    }

    
}
