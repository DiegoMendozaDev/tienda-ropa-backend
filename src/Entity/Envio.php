<?php

namespace App\Entity;

use App\Repository\EnvioRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EnvioRepository::class)]
class Envio
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id_envio = null;

    #[ORM\Column(length: 255)]
    private ?string $direccion = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fecha_envio = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fecha_entrega_estimada = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fecha_entrega_real = null;

    #[ORM\Column(length: 255)]
    private ?string $estado = null;

    public function getId(): ?int
    {
        return $this->id_envio;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(string $direccion): static
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getFechaEnvio(): ?\DateTimeInterface
    {
        return $this->fecha_envio;
    }

    public function setFechaEnvio(\DateTimeInterface $fecha_envio): static
    {
        $this->fecha_envio = $fecha_envio;

        return $this;
    }

    public function getFechaEntregaEstimada(): ?\DateTimeInterface
    {
        return $this->fecha_entrega_estimada;
    }

    public function setFechaEntregaEstimada(\DateTimeInterface $fecha_entrega_estimada): static
    {
        $this->fecha_entrega_estimada = $fecha_entrega_estimada;

        return $this;
    }

    public function getFechaEntregaReal(): ?\DateTimeInterface
    {
        return $this->fecha_entrega_real;
    }

    public function setFechaEntregaReal(\DateTimeInterface $fecha_entrega_real): static
    {
        $this->fecha_entrega_real = $fecha_entrega_real;

        return $this;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): static
    {
        $this->estado = $estado;

        return $this;
    }

}
