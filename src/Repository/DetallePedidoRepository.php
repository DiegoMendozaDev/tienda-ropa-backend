<?php
namespace App\Repository;
use App\Entity\DetallePedido;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DetallePedidoRepository extends ServiceEntityRepository{
    public function __construct(ManagerRegistry $registry){
        parent::__construct($registry, DetallePedido::class);
    }
    public function comprobarDetalle($pedido, $producto)
    {
        return $this->getEntityManager()->createQuery('
            SELECT detalle.id_detalle
            FROM App\Entity\DetallePedido detalle
            WHERE detalle.pedido = :pedido
            AND detalle.producto = :producto
        ')->setParameter(':pedido', $pedido)->setParameter(':producto', $producto)->getResult();
    }
}
?>