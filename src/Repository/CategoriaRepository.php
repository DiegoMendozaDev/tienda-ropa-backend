<?php

namespace App\Repository;

use App\Entity\Categoria;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CategoriaRepository extends ServiceEntityRepository{

    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry,Categoria::class);
    }

}