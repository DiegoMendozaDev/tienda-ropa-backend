<?php

namespace App\Repository;

use App\Entity\Producto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProductoRepository extends ServiceEntityRepository{

    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Producto::class);
    }
    public function masVendidos()
    {
        return $this->createQueryBuilder('p')
        ->orderBy('p.unidades_vendidas', 'DESC')
        ->setMaxResults(3)
        ->getQuery()
        ->getResult();
    }
    public function verCategoria(int $id, int $limit, int $offset): array
    {
        return $this->getEntityManager()
        ->createQuery('
            SELECT p
            FROM App\Entity\Producto p
            WHERE p.categoria = :catId
        ')
        ->setParameter('catId', $id)
        ->setMaxResults($limit)
        ->setFirstResult($offset)
        ->getResult();
    }
        public function verGenero($genero,$limit,$offset): array
    {
        return $this->getEntityManager()
        ->createQuery('
            SELECT p
            FROM App\Entity\Producto p
            WHERE p.genero = :genero
        ')
        ->setParameter('genero', $genero)        
        ->setMaxResults($limit)
        ->setFirstResult($offset)
        ->getResult();
    }
}