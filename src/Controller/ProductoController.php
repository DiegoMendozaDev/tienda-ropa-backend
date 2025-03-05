<?php

namespace App\Controller;

use App\Entity\Categoria;
use App\Entity\Producto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/api/productos', name:'api_productos')]
class ProductoController extends AbstractController{
    #[Route('/ver', name:'_ver', methods: ['get'])]
    public function index(EntityManagerInterface $entityManager): JsonResponse{
        $productos = $entityManager->getRepository(Producto::class)
        ->findAll();
        $data = [];
        foreach ($productos as $producto){
            $data[] = ['id' => $producto->getId(),
                        'nombre' => $producto->getNombre(),
                        'descripcion' => $producto->getDescripcion(),
                        'precio' => $producto->getPrecio(),
                        'marca' => $producto->getMarca(),
                        'id_categoria' => $producto->getCategoria()->getId(),
                        'foto' => $producto->getFoto(),
                        'stock' => $producto->getStock()
        ];
        }
        return $this->json($data, 200);
    }
    #[Route('/create', name: '_create', methods: ['post'])]
    public function create(EntityManagerInterface $entityManager, Request $request): JsonResponse{
        $data = json_decode($request->getContent(), true);
        if(!isset($data['nombre']) ||!isset($data['precio'])){
            return $this->json(['error' => 'invalid data'], 400);
        }
        $producto = new Producto();
        $producto->setNombre($data['nombre']);
        $producto->setDescripcion($data['descripcion']);
        $producto->setPrecio($data['precio']);
        $producto->setMarca($data['marca']);
        $categoria = $entityManager->getRepository(Categoria::class)->find($data['id_categoria']);
        $producto->setCategoria($categoria);
        $producto->setFoto($data['foto']);
        $producto->setStock($data['stock']);
        $entityManager->persist($producto);
        $entityManager->flush();
        $data = [
            'id' => $producto->getId(),
            'nombre' => $producto->getNombre(),
            'descripcion' => $producto->getDescripcion(),
            'precio' => $producto->getPrecio(),
            'id_categoria' => $producto->getCategoria()->getId(),
            'marca' => $producto->getMarca(),
            'foto' => $producto->getFoto(),
            'stock' => $producto->getStock(),
        ];
        return $this->json($data, 201);
    }
    #[Route('/update/{id}', name:'_update', methods: ['PUT'])]
    public function update(EntityManagerInterface $entityManager, Request $request, int $id) : JsonResponse{
        $producto = $entityManager->getRepository(Producto::class)->find($id);
        if(!$producto){
            return $this->json('Producto no encontrado  '. $id, 404);
        }
        $data = json_decode($request->getContent(), true);
        if(!isset($data['nombre']) || !isset($data['precio'])){
            return $this->json(["error" => "Invalid data"], 400);
        }
        $producto->setNombre($data['nombre']);
        $producto->setDescripcion($data['descripcion']);
        $producto->setPrecio($data['precio']);
        $categoria = $entityManager->getRepository(Categoria::class)->find($data['id_categoria']);
        $producto->setCategoria($categoria);
        $producto->setMarca($data['marca']);
        $producto->setFoto($data['foto']);
        $producto->setStock($data['stock']);
        $entityManager->flush();
        return $this->json($data, 200);
    }
    #[Route('/delete/{id}', name: '_delete', methods: ['delete'])]
    public function delete(EntityManagerInterface $entityManager, int $id): JsonResponse{
        $producto = $entityManager->getRepository(Producto::class)->find($id);
        if(!$producto){
            return $this->json('Producto no encontrado '. $id, 404);
        }
        $entityManager->remove($producto);
        $entityManager->flush();
        return $this->json(["message" => 'Eliminado con exito el id p'. $id],200);
    }
}
