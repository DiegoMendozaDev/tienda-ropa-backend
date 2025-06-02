<?php
namespace App\Entity;

use App\Repository\ProductoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

 
 
#[ORM\Entity(repositoryClass: ProductoRepository::class)]
class Producto{
    #[ORM\Id]
    #[ORM\GeneratedValue()]
    #[ORM\Column(type: 'integer')]
    private ?int $id_producto = null;
    
    #[ORM\ManyToOne(targetEntity: Categoria::class)]
    #[ORM\JoinColumn(name: 'id_categoria', referencedColumnName: "id_categoria")]
    private ?Categoria $categoria = null;

    #[ORM\OneToMany(targetEntity:DetallePedido::class,mappedBy: 'producto')]
    private ?Collection $detalles = null;

    #[ORM\Column(type: "string", length: 150)]
    private ?string $nombre = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $descripcion = null;

    #[ORM\Column(type:"decimal", precision: 10, scale:2)]
    private ?float $precio = null;

    #[ORM\Column(type: "string", length: 100)]
    private ?string $marca = null;

    #[ORM\Column(type:"string", length:255)]
    private ?string $foto = null;

    #[ORM\Column(type: "integer")]
    private ?int $stock = null;

    #[ORM\Column(type: "integer")]
    private ?int $unidades_vendidas = null;

    #[ORM\Column(type: "string", length: 150)]
    private ?string $genero = null;
    public function __construct()
    {
        $this->detalles = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id_producto;
    }
    public function getNombre(): string
    {
        return $this->nombre;
    }
    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;
        return $this;
    }
    public function getCategoria(): ?Categoria
    {
        return $this->categoria;
    }
    public function setCategoria(?Categoria $categoria): static
    {
        $this->categoria = $categoria;
        return $this;
    }
    public function getDescripcion(): string
    {
        return $this->descripcion;
    }
    public function setDescripcion(string $descripcion): static
    {
        $this->descripcion = $descripcion;
        return $this;
    }
    public function getPrecio(): float
    {
        return $this->precio;
    }
    public function setPrecio(float $precio): static
    {
        $this->precio = $precio;
        return $this;
    }
    public function getMarca(): string
    {
        return $this->marca;
    }
    public function setMarca(string $marca): static
    {
        $this->marca = $marca;
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
    public function getStock(): int
    {
        return $this->stock;
    }
    public function setStock(int $stock): static
    {
        $this->stock = $stock;
        return $this;
    }
    public function getUnidades_vendidas(): int
    {
        return $this->unidades_vendidas;
    }
    public function setUnidades_vendidas(int $unidades_vendidas): static
    {
        $this->unidades_vendidas = $unidades_vendidas;
        return $this;
    }
        public function getGenero(): string
    {
        return $this->genero;
    }
    public function setGenero(string $genero): static
    {
        $this->genero = $genero;
        return $this;
    }
    public function getDetalle():Collection
    {
        return $this->detalles;
    }
    public function addDetalle(DetallePedido $detalle):static
    {
        if(!$this->detalles->contains($detalle)){
            $this->detalles->add($detalle);
            $detalle->setProducto($this);
        }
        return $this;
    }
    public function removeDetalle(DetallePedido $detalle):static
    {
        if($this->detalles->removeElement($detalle))
        {
            if($detalle->getPedido() === $this){
                $detalle->setProducto(null);
            }
        }
        return $this;
    }
}