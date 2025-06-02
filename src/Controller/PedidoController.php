<?php

namespace App\Controller;

use App\Entity\DetallePedido;
use App\Entity\Pedidos;
use App\Entity\Producto;
use App\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

#[Route('/api/pedido', name: '_pedido')]
class PedidoController extends AbstractController {
    #[Route('/ver', name: 'ver', methods:['GET'])]
    public function ver(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        $pedidos = $entityManager->getRepository(Pedidos::class)->findAll();
        $data = [];
        foreach ($pedidos as $pedido) {
            $data[] = [
                'id' => $pedido->getId(),
                'estado' => $pedido->getEstado(),
                'total' => $pedido->getTotal(),
                'cliente'=> $pedido->getCliente(),
                'detalle' => $pedido->getDetalles(),
            ];
        }
        return $this->json(['pedidos'=> $data], 200);
    }
    #[Route('/verCarrito', name: 'verCarrito', methods:['POST'])]
    public function verUno(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {  
        $data = json_decode($request->getContent(),true);
        if(!isset($data['estado'])){
            return $this->json(['Error'=>'Fatal Error'],400);
        }elseif(!isset($data['id_usuario'])){
            return $this->json(['Error'=>'Fatal Error'],400);
        }
        $usuario = $entityManager->getRepository(Usuario::class)->find($data['id_usuario']);
        $pedido = $entityManager->getRepository(Pedidos::class)->comprobarPedido($usuario, $data['estado']);
        $pedido = $entityManager->getRepository(Pedidos::class)->find($pedido[0]['id_pedido']);
        if(!$pedido){
            return $this->json(['message'=> "Pedido no encontrado"], 400);
        }

        $data = [];
        $detalles = $pedido->getDetalles()->getValues();
        foreach ($detalles as $detalle) {
            $data[]=[
                'id_detalle' => $detalle->getId_Detalle(),
                'nombre' => $detalle->getProducto()->getNombre(),
                'precio' => $detalle->getProducto()->getPrecio(),
                'cantidad' => $detalle->getCantidad()
            ];
        }

        return $this->json(['detalles'=> $data,'id_pedido'=>$pedido->getId()], 200);
    }
    #[Route('/create', name: 'crear', methods:['POST'])]
    public function crear(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(),true);
        if(!isset($data['estado'])){
            return $this->json(['Error'=>'Fatal Error'],400);
        }elseif(!isset($data['id_usuario'])){
            return $this->json(['Error'=>'Fatal Error'],400);
        }
        $usuario = $entityManager->getRepository(Usuario::class)->find($data['id_usuario']);
        $pedido = $entityManager->getRepository(Pedidos::class)->comprobarPedido($usuario, $data['estado']);
        if($pedido){;
            return $this->json(["id_pedido"=>$pedido[0]["id_pedido"]], 200);
        }else{
            $pedido = new Pedidos($data['estado'],0);
            $pedido->setCliente($usuario);
            $entityManager->persist($pedido);
            $entityManager->flush();
            return $this->json(["id_pedido"=>$pedido->getId()], 200);
        }
    }
    #[Route('/eliminar/{id}',name: 'eliminar', methods:['DELETE'])]
    public function eliminar(EntityManagerInterface $entityManager,Request $request, int $id):JsonResponse
    {
        $pedido = $entityManager->getRepository(Pedidos::class)->find($id);
        $detalles = $pedido->getDetalles()->getValues();
        foreach ($detalles as $detalle) {
            $producto = $detalle->getProducto();
            $cantidad = $detalle->getCantidad();
            $producto->setStock($producto->getStock()+ $cantidad);
            $entityManager->flush();
        }
        $entityManager->remove($pedido);
        $entityManager->flush();
        return $this->json(['message'=>'Pedido eliminado correctamente'],200);
    }
    
    #[Route('/editar/{id}', name:'editar', methods:['PUT'])]
    public function editar(EntityManagerInterface $entityManager, Request $request, int $id):JsonResponse
    {
        $pedido = $entityManager->getRepository(Pedidos::class)->find($id);
        $data = json_decode($request->getContent(),true);

        if(isset($data['estado'])){
            $pedido->setEstado($data['estado']);
        }elseif(isset($data['total'])){
            $pedido->setTotal($data['total']);
        }elseif(isset($data['id_detalle'])){
            $detalle = $entityManager->getRepository(DetallePedido::class)->find($data['id_detalle']);
            $pedido->addDetalle($detalle);
        }
        $entityManager->flush();
        return $this->json(["message" => "pedido actualizado correctamente"],200);
    }
}
