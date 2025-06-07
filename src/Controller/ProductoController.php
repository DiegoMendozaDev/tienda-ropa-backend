<?php

namespace App\Controller;

use App\Entity\Categoria;
use App\Entity\Producto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/api/productos', name: 'api_productos')]
class ProductoController extends AbstractController
{
    #[Route('/ver', name: '_ver', methods: ['get'])]
    public function index(EntityManagerInterface $entityManager,Request $request): JsonResponse
    {
    $page = $request->query->getInt('page', 1);
    $limit = 12;
    $offset = ($page - 1) * $limit;

    $productos = $entityManager->getRepository(Producto::class)
        ->findBy([], null, $limit, $offset);

    $data = [];
    foreach ($productos as $producto) {
        $data[] = [
            'id' => $producto->getId(),
            'nombre' => $producto->getNombre(),
            'descripcion' => $producto->getDescripcion(),
            'precio' => $producto->getPrecio(),
            'marca' => $producto->getMarca(),
            'id_categoria' => $producto->getCategoria()->getId(),
            'foto' => $producto->getFoto(),
            'stock' => $producto->getStock(),
            'genero' => $producto->getGenero(),
            'unidades_vendidas' => $producto->getUnidades_vendidas()
        ];
    }

    return $this->json($data, 200);
    }
    #[Route('/masVendidos', name: '_masVendidos', methods: ['get'])]
    public function masVendidos(EntityManagerInterface $entityManager): JsonResponse
    {
        $productos = $entityManager->getRepository(Producto::class)->masVendidos();
        $data = [];
        foreach ($productos as $producto) {
            $data[] = [
                'foto' => $producto->getFoto(),
                'nombre' => $producto->getNombre(),
                'descripcion' => $producto->getDescripcion(),
            ];
        }
        return $this->json($data, 200);
    }
    #[Route('/create', name: '_create', methods: ['post'])]
    public function create(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['nombre']) || !isset($data['precio'])) {
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
        $producto->setUnidades_vendidas($data['unidades_vendidas']);
        $producto->setGenero($data['genero']);
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
            'unidades_vendidas' => $producto->getUnidades_vendidas(),
            'genero' => $producto->getGenero()
        ];
        return $this->json($data, 201);
    }
    #[Route('/update/{id}', name: '_update', methods: ['PUT'])]
    public function update(EntityManagerInterface $entityManager, Request $request, int $id): JsonResponse
    {
        $producto = $entityManager->getRepository(Producto::class)->find($id);
        if (!$producto) {
            return $this->json('Producto no encontrado  ' . $id, 404);
        }
        $data = json_decode($request->getContent(), true);
        if (!isset($data['nombre']) || !isset($data['precio'])) {
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
    public function delete(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $producto = $entityManager->getRepository(Producto::class)->find($id);
        if (!$producto) {
            return $this->json('Producto no encontrado ' . $id, 404);
        }
        $entityManager->remove($producto);
        $entityManager->flush();
        return $this->json(["message" => 'Eliminado con exito el id p' . $id], 200);
    }
    #[Route('/categoria/{id}', name:'_categoria', methods: ['GET'])]
    public function categoria(EntityManagerInterface $entityManager, int $id, Request $request): JsonResponse{
        $page = $request->query->getInt('page', 1);
        $limit = 12;
        $offset = ($page - 1) * $limit;

        $categoria = $entityManager->getRepository(Categoria::class)->find($id);
        if (!$categoria) {
            // La categoría no existe
            return $this->json(
                ['error' => "Categoría con id {$id} no encontrada"],
                404
            );
        }
        $nombreCategoria = $categoria->getNombre();
        // Obtenemos los productos de esa categoría (puede devolver array vacío)
        $productos = $entityManager->getRepository(Producto::class)->verCategoria($id, $limit, $offset);

        $data = array_map(fn(Producto $p) => [
            'id'            => $p->getId(),
            'nombre'        => $p->getNombre(),
            'descripcion'   => $p->getDescripcion(),
            'precio'        => $p->getPrecio(),
            'marca'         => $p->getMarca(),
            'foto'          => $p->getFoto(),
            'stock'         => $p->getStock(),
            'unidades_vendidas' => $p->getUnidades_vendidas(),
            'id_categoria'  => $p->getCategoria()->getId(),
            'categoria' => $nombreCategoria
        ], $productos);
        return $this->json($data, 200);
    }

    #[Route('/genero/{genero}', name:'_genero', methods: ['GET'])]
    public function verGenero(EntityManagerInterface $entityManager, string $genero, Request $request): JsonResponse{
        $page = $request->query->getInt('page', 1);
        $limit = 12;
        $offset = ($page - 1) * $limit;

        $productos = $entityManager->getRepository(Producto::class)->verGenero($genero, $limit, $offset);

        // 2) Siempre devolvemos 200 OK, incluso si $productos es []
        $data = array_map(fn(Producto $p) => [
            'id'                 => $p->getId(),
            'nombre'             => $p->getNombre(),
            'descripcion'        => $p->getDescripcion(),
            'precio'             => $p->getPrecio(),
            'marca'              => $p->getMarca(),
            'foto'               => $p->getFoto(),
            'stock'              => $p->getStock(),
            'unidades_vendidas'  => $p->getUnidades_Vendidas(),
            'id_categoria'       => $p->getCategoria()->getId(),
            'genero'             => $p->getGenero(),
        ], $productos);

        return $this->json($data, 200);
    }
}
