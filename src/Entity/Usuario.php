<?php

namespace App\Entity;

use App\Repository\UsuarioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UsuarioRepository::class)]
class Usuario implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:'integer')]
    private ?int $id_usuario = null;

    #[ORM\Column(length: 100)]
    private ?string $nombre = null;

    #[ORM\Column(length: 150,unique:true)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column()]
    private array $roles = [];
    /**
     * @var string The hashed password
     */
    #[ORM\Column(length: 255)]
    private ?string $contrasena = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fecha_registro = null;

    #[ORM\Column(length: 255)]
    private ?string $direccion = null;

    #[ORM\Column(length: 20)]
    private ?string $codigo_postal = null;

    #[ORM\OneToMany(targetEntity: Pedidos::class, mappedBy:'pedidos')]
    private ?Collection $pedidos = null;

    public function __construct()
    {
        $this->pedidos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id_usuario;
    }
    public function getEmail(): ?string
    {
        return $this->email;
    }
    public function setEmail(String $email): static
    {
        $this->email = $email;
        return $this;
    }
        /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }
    public function getNombre(): string
    {
        return $this->nombre;
    }
    public function setNombre(string $nombre):static
    {
        $this->nombre = $nombre;
        return $this;
    }
    /**
     * @see UserInterface
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }
    /**
     * @param list<string> $roles
     */
    public function setRoles(Array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }
    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->contrasena;
    }
    public function setPassword(string $contrasena): static
    {
        $this->contrasena = $contrasena;
        return $this;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha_registro;
    }
    public function setFecha(\DateTimeImmutable $fechaRegistro): static
    {
        $this->fecha_registro = $fechaRegistro;
        return $this;
    }
    public function getDireccion(): string
    {
        return $this->direccion;
    }
    public function setDireccion(string $direccion): static
    {
        $this->direccion = $direccion;
        return $this;
    }
    public function getPostal(): string
    {
        return $this->codigo_postal;
    } 
    public function setPostal(string $postal):static
    {
        $this->codigo_postal = $postal;
        return $this;
    }
    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
    public function getPedidos():Collection
    {
        return $this->pedidos;
    }

}