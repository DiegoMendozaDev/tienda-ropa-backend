<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use App\Repository\DetallePedidoRepository;


#[ORM\Entity(repositoryClass: DetallePedidoRepository::class)]
class DetallePedido
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id_detalle = null;
    #[ORM\Column(type: 'integer')]
    private ?int $cantidad = null;
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $precio_unitario = null;
    #[ORM\Column(type: "string", length: 255)]
    private ?string $foto = null;

    #[ORM\ManyToOne(targetEntity: Pedidos::class, inversedBy: "detalles")]
    #[ORM\JoinColumn(name: "id_pedido", referencedColumnName: "id_pedido")]
    private ?Pedidos $pedido = null;
    #[ORM\ManyToOne(targetEntity: Producto::class, inversedBy: "detalles")]
    #[ORM\JoinColumn(name: "id_producto", referencedColumnName: "id_producto")]
    private ?Producto $producto = null;


    public function getId_Detalle(): ?int
    {
        return $this->id_detalle;
    }
    public function getCantidad(): ?int
    {
        return $this->cantidad;
    }
    public function getPrecio_Unitario(): ?string
    {
        return $this->precio_unitario;
    }
    public function getProducto(): ?Producto
    {
        return $this->producto;
    }
    public function getPedido(): ?Pedidos
    {
        return $this->pedido;
    }
    public function setCantidad(int $cantidad): static
    {
        $this->cantidad = $cantidad;
        return $this;
    }
    public function setPrecio_Unitario(string $precio_unitario): static
    {
        $this->precio_unitario = $precio_unitario;
        return $this;
    }
    public function setProducto(?Producto $producto): static
    {
        $this->producto = $producto;
        return $this;
    }
    public function setPedido(?Pedidos $pedido): static
    {
        $this->pedido = $pedido;
        return $this;
    }
    public function getFoto(): string
    {
        return $this->foto;
    }
    public function setFoto(string $foto): static
    {
        $this->foto = $foto;
        return $this;
    }
}
