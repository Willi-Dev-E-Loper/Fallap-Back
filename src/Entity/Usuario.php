<?php

namespace App\Entity;

use App\Repository\UsuarioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Persistence\ManagerRegistry;


#[ORM\Entity(repositoryClass: UsuarioRepository::class)]
class Usuario implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 100)]
    private ?string $nombre = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $apellidos = null;


    #[ORM\Column(nullable: true)]
    private ?int $telefono = null;

    #[ORM\Column(nullable: true)]
    private ?int $idAdmin = null;

    #[ORM\ManyToOne(inversedBy: 'falleros')]
    #[ORM\JoinColumn(referencedColumnName: "id_falla")]
    private ?Falla $falla = null;

    #[ORM\ManyToOne(inversedBy: 'participantes')]
    #[ORM\JoinColumn(referencedColumnName: "id_evento")]
    private ?Evento $evento = null;

    #[ORM\ManyToOne(inversedBy: 'pagadores')]
    #[ORM\JoinColumn(referencedColumnName: "id_evento")]
    private ?Evento $pago = null;




    public function __construct()
    {

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
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

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }
    public function removeAdminRole(): void
    {
        $roles = $this->getRoles();
        $key = array_search('ROLE_ADMIN', $roles);

        if ($key !== false) {
            unset($roles[$key]);
        }

        $this->setRoles(array_values($roles));
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getApellidos(): ?string
    {
        return $this->apellidos;
    }

    public function setApellidos(string $apellidos): self
    {
        $this->apellidos = $apellidos;

        return $this;
    }




    public function getTelefono(): ?int
    {
        return $this->telefono;
    }

    public function setTelefono(?int $telefono): self
    {
        $this->telefono = $telefono;

        return $this;
    }

    public function getIdAdmin(): ?int
    {
        return $this->idAdmin;
    }

    public function setIdAdmin(?int $idAdmin): self
    {
        $this->idAdmin = $idAdmin;

        return $this;
    }

    public function getFalla(): ?Falla
    {
        return $this->falla;
    }

    public function setFalla(int $falla, ManagerRegistry $doctrine): self
    {
        $entityManager = $doctrine->getManager();
        $this->falla = $falla ? $entityManager->getRepository(Falla::class)->find($falla) : null;


        return $this;
    }
    public function toArray(): array
    {

        if($this->falla){
            $idfalla=$this->falla->toArray();
        }else{

            $idfalla="administrador";
        }
        return [
            'id' => $this->id,
            'email' => $this->email,
            'roles' => $this->roles,
            'nombre' => $this->nombre,
            'apellidos' => $this->apellidos,
            'telefono' => $this->telefono,
            'idAdmin' => $this->idAdmin,
            'falla' => $idfalla,
        ];
    }
    public function fromJson($content, ManagerRegistry $doctrine): void
    {



        $this->email = $content['email'] ?? null;
        $this->roles = ["ROLE_USER"];
        $this->nombre = $content['nombre'] ?? null;
        $this->apellidos = $content['apellidos'] ?? null;
        //$this->nombreUsuario = $content['nombreUsuario'] ?? null;
        //$this->descripcion = $content['descripcion'] ?? null;
        //$this->foto = $content['foto'] ?? null;
        //$this->telefono = $content['telefono'] ?? null;
        //$this->idAdmin = $content['idAdmin'] ?? null;

        $entityManager = $doctrine->getManager();
        $fallaId = $content['falla'] ?? null;
        $this->falla = $fallaId ? $entityManager->getRepository(Falla::class)->find($fallaId) : null;
    }

    public function getEvento(): ?Evento
    {
        return $this->evento;
    }

    public function setEvento(?Evento $evento): self
    {
        $this->evento = $evento;

        return $this;
    }

    public function getPago(): ?Evento
    {
        return $this->pago;
    }

    public function setPago(?Evento $pago): self
    {
        $this->pago = $pago;

        return $this;
    }




}
