<?php

namespace App\Entity;

use App\Repository\FallaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Persistence\ManagerRegistry;
use App\Service\FileUploader;

use http\Env\Request;


#[ORM\Entity(repositoryClass: FallaRepository::class)]
class Falla
{
    private FileUploader $fileUploader;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idFalla = null;

    #[ORM\Column(length: 100)]
    private ?string $nombre = null;

    #[ORM\Column(length: 255)]
    private ?string $direccion = null;


    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $descripcion = null;



    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private array $cargos = [];

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private array $premios = [];

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imagenPortada = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logo = null;

    #[ORM\OneToMany(mappedBy: 'falla', targetEntity: Comentario::class)]
    #[ORM\JoinColumn(referencedColumnName: "id_comentario")]
    private Collection $comentarios;

    #[ORM\OneToMany(mappedBy: 'falla', targetEntity: Encuesta::class)]
    private Collection $encuestas;

    #[ORM\OneToMany(mappedBy: 'falla', targetEntity: Evento::class)]
    private Collection $eventos;


    #[ORM\OneToMany(mappedBy: 'falla', targetEntity: Usuario::class)]
    private Collection $falleros;

    #[ORM\Column(length: 100)]
    private ?string $email = null;

    #[ORM\Column]
    private ?int $telefono = null;

    #[ORM\Column(length: 100)]
    private ?string $sitioWeb = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private array $llibrets = [];

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $fechaCreacion = null;

    #[ORM\OneToMany(mappedBy: 'falla', targetEntity: Noticia::class)]
    #[ORM\JoinColumn(referencedColumnName: "id_noticia")]
    private Collection $noticias;


    public function __construct(FileUploader $fileUploader)
    {
        $this->comentarios = new ArrayCollection();
        $this->encuestas = new ArrayCollection();
        $this->eventos = new ArrayCollection();
        $this->falleros = new ArrayCollection();
        $this->fileUploader = $fileUploader;
        $this->noticias = new ArrayCollection();

    }



    public function getIdFalla(): ?int
    {
        return $this->idFalla;
    }

    public function setIdFalla(int $idFalla): self
    {
        $this->idFalla = $idFalla;

        return $this;
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

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(string $direccion): self
    {
        $this->direccion = $direccion;

        return $this;
    }



    public function getdescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setdescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }



    public function getCargos(): array
    {
        return $this->cargos;
    }

    public function setCargos(?array $cargos): self
    {
        $this->cargos = $cargos;

        return $this;
    }

    public function getPremios(): array
    {
        return $this->premios;
    }

    public function setPremios(?array $premios): self
    {
        $this->premios[] = $premios;

        return $this;
    }
    public function unsetPremios(?array $premios): self
    {
        $this->premios = $premios;

        return $this;
    }
    public function getImagenPortada()
    {
        return $this->imagenPortada;
    }

    public function setImagenPortada($imagenPortada): self
    {
        $this->imagenPortada = $imagenPortada;

        return $this;
    }

    public function getLogo()
    {
        return $this->logo;
    }

    public function setLogo($logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * @return Collection<int, Comentario>
     */
    public function getComentarios(): Collection
    {
        return $this->comentarios;
    }

    public function addComentario(Comentario $comentario): self
    {
        if (!$this->comentarios->contains($comentario)) {
            $this->comentarios->add($comentario);
            $comentario->setFalla($this->idFalla);
        }

        return $this;
    }

    public function removeComentario(Comentario $comentario): self
    {
        if ($this->comentarios->removeElement($comentario)) {
            // set the owning side to null (unless already changed)
            if ($comentario->getFalla() === $this->idFalla) {
                $comentario->setFalla(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Encuesta>
     */
    public function getEncuestas(): Collection
    {
        return $this->encuestas;
    }

    public function addEncuesta(Encuesta $encuesta): self
    {
        if (!$this->encuestas->contains($encuesta)) {
            $this->encuestas->add($encuesta);
            $encuesta->setFalla($this);
        }

        return $this;
    }

    public function removeEncuesta(Encuesta $encuesta): self
    {
        if ($this->encuestas->removeElement($encuesta)) {
            // set the owning side to null (unless already changed)
            if ($encuesta->getFalla() === $this) {
                $encuesta->setFalla(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Evento>
     */
    public function getEventos(): Collection
    {
        return $this->eventos;
    }

    public function addEvento(Evento $evento): self
    {
        if (!$this->eventos->contains($evento)) {
            $this->eventos->add($evento);
            $evento->setFalla($this);
        }

        return $this;
    }

    public function removeEvento(Evento $evento): self
    {
        if ($this->eventos->removeElement($evento)) {
            // set the owning side to null (unless already changed)
            if ($evento->getFalla() === $this) {
                $evento->setFalla(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Usuario>
     */
    public function getFalleros(): Collection
    {
        return $this->falleros;
    }

    public function addFallero(Usuario $fallero): self
    {
        if (!$this->falleros->contains($fallero)) {
            $this->falleros->add($fallero);
            $fallero->setFalla($this);
        }

        return $this;
    }


    public function removeFallero(Usuario $fallero): self
    {
        if ($this->falleros->removeElement($fallero)) {
            // set the owning side to null (unless already changed)
            if ($fallero->getFalla() === $this) {
                $fallero->setFalla(null);
            }
        }

        return $this;
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

    public function getTelefono(): ?int
    {
        return $this->telefono;
    }

    public function setTelefono(int $telefono): self
    {
        $this->telefono = $telefono;

        return $this;
    }

    public function getSitioWeb(): ?string
    {
        return $this->sitioWeb;
    }

    public function setSitioWeb(string $sitioWeb): self
    {
        $this->sitioWeb = $sitioWeb;

        return $this;
    }

    public function getLlibrets(): array
    {
        return $this->llibrets;
    }

    public function setLlibrets(?array $llibrets): self
    {
        $this->llibrets[] = $llibrets;

        return $this;
    }
    public function toArray(): array
    {
        $coments=[];
        foreach ($this->comentarios as $com){
            $coments[]= $com->toArray();
        }
        $encuestas=[];
        foreach ($this->encuestas as $enc){
            $encuestas[]= $enc->toArray();
        }
        $events=[];
        foreach ($this->eventos as $evt){
            $events[]= $evt->toArray();
        }
        $noticias=[];
        foreach ($this->noticias as $evt){
            $noticias[]= $evt->toArray();
        }
        dump($this->getNoticias());
        $falleros=[];
        foreach ($this->falleros as $fall){
            $roles=[];
            foreach ($fall->getRoles() as $role){
                $roles[]= $role;
            }
            $falleros[]=  ['nombre' =>  $fall->getNombre(),
                            'apellidos' => $fall->getApellidos(),
                            'role' => $roles];
        }

        return [
            'idFalla' => $this->idFalla,
            'nombre' => $this->nombre,
            'direccion' => $this->direccion,
            'descripcion' => $this->descripcion,
            'cargos' => $this->cargos,
            'premios' => $this->premios,
            'llibrets' => $this->llibrets,
            'imagenPortada' => $this->imagenPortada,
            'logo' => $this->logo,
            'telefono' => $this->telefono,
            'email' => $this->email,
            'web' => $this->sitioWeb,
            'fechaCreacion' => $this->fechaCreacion,
            'comentarios' => $coments,
            'noticias' => $noticias,
            'encuestas' => $encuestas,
            'eventos' => $events,
            'falleros' => $falleros,

        ];
    }
    public function fromJson($content, ManagerRegistry $doctrine): void
    {
        $content = json_decode($content, true);

        //$this->idFalla = $content['idFalla'] ?? null;
        $this->nombre = $content['nombre'] ;
        $this->direccion = $content['direccion'] ;
        $this->descripcion = $content['descripcion'] ;
        $this->cargos = $content['cargos'] ?? [];
        $this->premios = $content['premios'] ?? [];
        $this->email = $content['email'];
        $this->telefono = $content['telefono'] ;
        $this->sitioWeb = $content['sitioWeb'] ;
        //$this->imagenPortada = $content['imagenPortada'] ?? null;
        //$this->logo = $content['logo'] ?? null;


        $entityManager = $doctrine->getManager();

        $comentariosIds = $content['comentarios'] ?? [];
        foreach ($comentariosIds as $comentarioId) {
            $coment = $entityManager->getRepository(Comentario::class)->find($comentarioId);
            $this->addComentario($coment);
        }

        $encuestasIds = $content['encuestas'] ?? [];
        foreach ($encuestasIds as $encuestaId) {
            $encuesta = $entityManager->getRepository(Encuesta::class)->find($encuestaId);
            $this->addEncuesta($encuesta);
        }

        $eventosIds = $content['eventos'] ?? [];
        foreach ($eventosIds as $eventoId) {
            $evento = $entityManager->getRepository(Evento::class)->find($eventoId);
            $this->addEvento($evento);
        }

        $fallerosIds = $content['falleros'] ?? [];
        foreach ($fallerosIds as $falleroId) {
            $fallero = $entityManager->getRepository(Usuario::class)->find($falleroId);
            $this->addFallero($fallero);
        }

    }
    public function fromFormData($content, $portada, $logo): void
    {


        $this->nombre = $content['nombreFalla'] ;
        $this->direccion = $content['direccionFalla'] ;
        $this->descripcion = $content['descripcionFalla'] ;
        $this->cargos =[$content['reinaMayor'],$content['reinaInfantil'],$content['presidente']] ?? [];
        $this->premios = $content['premios'] ?? [];
        $this->fechaCreacion = $content['fechaFalla'] ?? [];
        $this->email = $content['emailFalla'];
        $this->telefono = $content['telefonoFalla'] ;
        $this->sitioWeb = $content['webFalla'] ;



        if ($portada) {
            $imagenPath = $this->fileUploader->upload($portada);
            $this->imagenPortada = $imagenPath;
        }
        if ($logo) {
            $imagenPath = $this->fileUploader->upload($logo);
            $this->logo = $imagenPath;
        }

        // $this->fechaEvento = $formData->get('fechaEvento') ? new \DateTimeImmutable($formData->get('fechaEvento')) : null;
        // $this->participantes = $formData->get('participantes') ?? null;
        // $this->tienePago = $formData->get('tienePago') ?? null;
        // $this->contador = $formData->get('contador') ?? null;

        //$entityManager = $doctrine->getManager();
        //$this->pagos = $formData->get('pagos') ? $entityManager->getRepository(Pago::class)->find($formData->get('pagos')) : null;
        //$this->falla = $formData->get('falla') ? $entityManager->getRepository(Falla::class)->find($formData->get('falla')) : null;
    }

    public function getFechaCreacion(): ?string
    {
        return $this->fechaCreacion;
    }

    public function setFechaCreacion(?string $fechaCreacion): self
    {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    /**
     * @return Collection<int, Noticia>
     */
    public function getNoticias(): Collection
    {
        return $this->noticias;
    }

    public function addNoticia(Noticia $noticia): self
    {
        if (!$this->noticias->contains($noticia)) {
            $this->noticias->add($noticia);
            $noticia->setFalla($this);
        }

        return $this;
    }

    public function removeNoticia(Noticia $noticia): self
    {
        if ($this->noticias->removeElement($noticia)) {
            // set the owning side to null (unless already changed)
            if ($noticia->getFalla() === $this) {
                $noticia->setFalla(null);
            }
        }

        return $this;
    }

}
