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
}