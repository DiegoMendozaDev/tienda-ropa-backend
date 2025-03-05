<?php

namespace App\Controller;

use App\Entity\Categoria;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/categoria', name: "api_categoria")]
class CategoriaController extends AbstractController{
    #[Route('/ver', name : "_ver", methods: ['get'])]
    public function index(EntityManagerInterface $entityManager) : JsonResponse{
        $categorias = $entityManager->getRepository(Categoria::class)->findAll();
        $data = [];
        foreach($categorias as $categoria){
            $data[] = [
                'id_categoria' => $categoria->getId(),
                'nombre' => $categoria->getNombre(),
                'descripcion' => $categoria->getDescripcion()
            ];
        }
        return $this->json($data,200);
    }
    #[Route('/create', name:"_create", methods: ['post'])]
    public function create(EntityManagerInterface $entityManager, Request $request) : JsonResponse{
        $data = json_decode($request->getContent(), true);
        if(!isset($data['nombre'])){
            return $this->json(['message' => 'Error: Invalid Data', 400]);
        }
        $categoria = new Categoria();
        $categoria->setNombre($data['nombre']);
        $categoria->setDescripcion($data['descripcion'] ?? '');
        $entityManager->persist($categoria);
        $entityManager->flush();
        $data = [
            'id' => $categoria->getId(),
            'nombre' => $categoria->getNombre(),
            'descripcion' => $categoria->getDescripcion()
        ];
        return $this->json($data, 201);
    }
    #[Route('/update/{id}', name: "_update", methods: ['put', 'patch'])]
    public function update(EntityManagerInterface $entityManager, Request $request, int $id) : JsonResponse{
        $categoria = $entityManager->getRepository(Categoria::class)->find($id);
        if(!$categoria){
            return $this->json(["message" => "Error: No existe Objeto ". $categoria]);
        }
        $data = json_decode($request->getContent(), true);
        if(!isset($data['nombre'])){
            return $this->json(["message" => "Error: Invalid Data"]);
        }
        $categoria->setNombre($data['nombre']);
        $categoria->setDescripcion($data['descripcion']);
        $entityManager->flush();
        return $this->json([$data, 200]);
        
    }
    #[Route('/delete/{id}', name: "_delete", methods:['delete'])]
    public function delete(EntityManagerInterface $entityManager, int $id):JsonResponse{
        $categoria = $entityManager->getRepository(Categoria::class)->find($id);
        if(!$categoria){
            return $this->json(["message" => "Error: No existe objeto". $id]);
        }
        $entityManager->remove($categoria);
        $entityManager->flush();
        return $this->json(["message" => "Eliminada la categoria con id ". $id]);
    }   
}