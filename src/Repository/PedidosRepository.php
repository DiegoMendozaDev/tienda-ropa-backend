<?php
namespace App\Repository;
use App\Entity\Pedidos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PedidosRepository extends ServiceEntityRepository{
    public function __construct(ManagerRegistry $registry){
        parent::__construct($registry, Pedidos::class);
    }
    public function comprobarPedido($usuario, $estado)
    {
        return $this->getEntityManager()->createQuery('
            SELECT pedidos.id_pedido
            FROM App\Entity\Pedidos pedidos
            WHERE pedidos.usuario = :usuario
            AND pedidos.estado = :estado
        ')->setParameter(':usuario', $usuario)->setParameter(':estado', $estado)->getResult();
    }
}

?>