<?php

namespace App\Controller;

use App\Entity\DetallePedido;
use App\Entity\Pedidos;
use App\Entity\Producto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

#[Route('/api/detalle', name: '_api')]
class DetalleController extends AbstractController {
    #[Route('/ver', name:'_ver', methods : 'get')]
    public function index(EntityManagerInterface $entityManager) : JsonResponse{
        $detalles = $entityManager->getRepository(DetallePedido::class)->findAll();
        $data = [];
        foreach($detalles as $detalle){
            $data[] = [
                'id_pedido' => $detalle->getId_pedido(),
                'id_producto' => $detalle->getId_producto(),
                'cantidad' => $detalle->getCantidad(),
                'precio_unitario' => $detalle->getPrecio_Unitario()
            ];
        }
        return $this->json($data,200);
    }
    #[Route('/create', name:'_create', methods:['post'])]
    public function create(EntityManagerInterface $entityManager, Request $request): JsonResponse{
        $data = json_decode($request->getContent(), true);
        if(!isset($data['id_producto']) || !isset($data['id_pedido'])){
            return $this->json(["message" => "Error: Invalid data"]);
        }

        $pedido = $entityManager->getRepository(Pedidos::class)->find($data['id_pedido']);
        $producto = $entityManager->getRepository(Producto::class)->find($data['id_producto']);
        $detalle = $entityManager->getRepository(DetallePedido::class)->comprobarDetalle($pedido,$producto);
        if($detalle){
            $detalle = $entityManager->getRepository(DetallePedido::class)->find($detalle[0]['id_detalle']);
            $detalle->setCantidad($detalle->getCantidad() + 1);
            $producto->setStock($producto->getStock()-1);
            $entityManager->flush();
        }else{
            $detalle = new DetallePedido();
            $detalle->setPedido($pedido);
            $detalle->setProducto($producto);
            $detalle->setCantidad($data['cantidad']);
            $detalle->setPrecio_Unitario($producto->getPrecio());
            $producto->setStock($producto->getStock()-1);
            $entityManager->persist($detalle);
            $entityManager->flush();
        }
        $data = [
            'id' => $detalle->getId_Detalle(),
            'id_pedido' => $detalle->getPedido(),
            'id_producto' => $detalle->getProducto(),
            'cantidad' => $detalle->getCantidad(),
            'precio_unitario' => $detalle->getPrecio_Unitario()
        ];
        return $this->json($data, 201);
    }
    #[Route('/update/{id}', name:'_update', methods: ['put'])]
    public function update(EntityManagerInterface $entityManager, Request $request, int $id): JsonResponse{
        $detalle = $entityManager->getRepository(DetallePedido::class)->find($id);
        if(!$detalle){
            return $this->json(["detalle" => "Error: "]);
        }
        $data = json_decode($request->getContent(), true);
        
        $producto = $entityManager->getRepository(Producto::class)->find($data['id_producto']);
        $detalle->setProducto($producto);
        $detalle->setCantidad($data['cantidad']);
        $detalle->getPrecio_Unitario($data['precio_unitario']);
        $entityManager->flush();
        return $this->json($data, 200);

    }
    #[Route('/delete/{id}', name : '_delete', methods : ['delete'])]
    public function delete(EntityManagerInterface $entityManager, int $id): JsonResponse{
        $detalle = $entityManager->getRepository(DetallePedido::class)->find($id);
        if(!$detalle){
            return $this->json(['Message' => 'Error']);
        }
        $entityManager->remove($detalle);
        $entityManager->flush();
        return $this->json(['message' => 'Eliminado correctamente el id'. $id]);
    }
}
