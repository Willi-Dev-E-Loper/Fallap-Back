<?php

namespace App\Entity;

use App\Repository\EventoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Persistence\ManagerRegistry;
use App\Service\FileUploader;
use phpDocumentor\Reflection\File;


#[ORM\Entity(repositoryClass: EventoRepository::class)]
class Evento
{

    private ManagerRegistry $doctrine;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column (name: 'id_evento')]
    private ?int $idEvento = null;

    #[ORM\Column(length: 100)]
    private ?string $titulo = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $contenido = null;

    #[ORM\Column(length: 255)]
    private ?string $fechaCreacion = null;

    #[ORM\Column(length: 255)]
    private ?string $fechaEvento = null;



    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imagen = null;


    #[ORM\Column(nullable: true)]
    private ?bool $tienePago = null;

    #[ORM\Column(nullable: true)]
    private ?int $contador = 0;

    #[ORM\Column(nullable: true)]
    private ?int $pago = null;

    #[ORM\ManyToOne(inversedBy: 'eventos')]
    #[ORM\JoinColumn(referencedColumnName: "id_falla")]
    private ?Falla $falla = null;

    #[ORM\OneToMany(mappedBy: 'evento', targetEntity: Usuario::class)]
    private Collection $participantes;

    #[ORM\OneToMany(mappedBy: 'pago', targetEntity: Usuario::class)]
    private Collection $pagadores;


    public function __construct(FileUploader $fileUploader, ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
        $this->participantes = new ArrayCollection();
        $this->pagadores = new ArrayCollection();

    }

    public function getIdEvento(): ?int
    {
        return $this->idEvento;
    }

    public function setIdEvento(int $idEvento): self
    {
        $this->idEvento = $idEvento;

        return $this;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getContenido(): ?string
    {
        return $this->contenido;
    }

    public function setContenido(string $contenido): self
    {
        $this->contenido = $contenido;

        return $this;
    }

    public function getFechaCreacion(): ?string
    {
        return $this->fechaCreacion;
    }

    public function setFechaCreacion(string $fechaCreacion): self
    {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    public function getFechaCaducidad(): ?string
    {
        return $this->fechaEvento;
    }

    public function setFechaCaducidad(string $fechaEvento): self
    {
        $this->fechaEvento = $fechaEvento;

        return $this;
    }



    public function getImagen()
    {
        return $this->imagen;
    }

    public function setImagen($imagen): self
    {
        $this->imagen = $imagen;

        return $this;
    }



    public function isTienePago(): ?bool
    {
        return $this->tienePago;
    }

    public function setTienePago(bool $tienePago): self
    {
        $this->tienePago = $tienePago;

        return $this;
    }

    public function getContador(): ?int
    {
        return $this->contador;
    }

    public function setContador(): self
    {
        $this->contador += 1;

        return $this;
    }

    public function getPago(): ?int
    {
        return $this->pago;
    }

    public function setPago(?int $pagos): self
    {
        $this->pago = $pagos;

        return $this;
    }

    public function getFalla(): ?Falla
    {
        return $this->falla;
    }

    public function setFalla(?Falla $falla): self
    {
        $this->falla = $falla;

        return $this;
    }

    /**
     * @return Collection<int, Usuario>
     */
    public function getParticipantes(): Collection
    {
        return $this->participantes;
    }

    public function addParticipante(Usuario $participante): self
    {
        if (!$this->participantes->contains($participante)) {
            $this->participantes->add($participante);
            $participante->setEvento($this);
        }

        return $this;
    }

    public function removeParticipante(Usuario $participante): self
    {
        if ($this->participantes->removeElement($participante)) {
            // set the owning side to null (unless already changed)
            if ($participante->getEvento() === $this) {
                $participante->setEvento(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Usuario>
     */
    public function getPagadores(): Collection
    {
        return $this->pagadores;
    }

    public function addPagadore(Usuario $pagadore): self
    {
        if (!$this->pagadores->contains($pagadore)) {
            $this->pagadores->add($pagadore);
            $pagadore->setPago($this);
        }

        return $this;
    }

    public function removePagadore(Usuario $pagadore): self
    {
        if ($this->pagadores->removeElement($pagadore)) {
            // set the owning side to null (unless already changed)
            if ($pagadore->getPago() === $this) {
                $pagadore->setPago(null);
            }
        }

        return $this;
    }
    public function toArray(): array
    {
        $pagaors=[];
        foreach ($this->pagadores as $pag){
            $pagaors[]= $pag->getId();
        }
        $participantes=[];
        foreach ($this->participantes as $par){
            $participantes[]= $par->getId();
        }
        return [
            'idEvento' => $this->idEvento,
            'titulo' => $this->titulo,
            'contenido' => $this->contenido,
            'fechaCreacion' => $this->fechaCreacion,
            'fechaEvento' => $this->fechaEvento,
            'imagen' => $this->imagen,
            'tienePago' => $this->tienePago,
            'contador' => $this->contador,
            'pagos' => $this->pago,
            'falla' => $this->falla?->getIdFalla(),
            'pagadores'=> $pagaors,
            'participantes'=> $participantes,
        ];
    }
    public function fromJson($content, ManagerRegistry $doctrine): void
    {
        //$content = json_decode($content, true);

        //$this->idEvento = $content['idEvento'] ?? null;
        $this->titulo = $content['tituloEvento'];
        $this->contenido = $content['descripcionEvento'] ?? null;
        $this->fechaCreacion = $content['fechaEvento'];
        //$this->fechaEvento = isset($content['fechaEvento']) ? new \DateTimeImmutable($content['fechaEvento']) : null;
        //$this->participantes = $content['participantes'] ?? null;
        //$this->tienePago = $content['tienePago'] ?? null;
        //$this->contador = $content['contador'] ?? null;

        //$entityManager = $doctrine->getManager();
        //$this->pagos = isset($content['pagos']) ? $entityManager->getRepository(Pago::class)->find($content['pagos']) : null;
        //$this->falla = isset($content['falla']) ? $entityManager->getRepository(Falla::class)->find($content['falla']) : null;
    }
    public function fromFormData($data, $file, $fileUploader): void
    {
        $this->titulo = $data['tituloEvento'] ;
        $this->contenido = $data['descripcionEvento'] ;
        $this->fechaCreacion = $data['fechaCreacion'] ;
        $this->fechaEvento = $data['fechaEvento'] ;
        $this->tienePago = filter_var($data['tienePago'], FILTER_VALIDATE_BOOLEAN);
        dump($this->tienePago);
        $importe = $data['importe'];
        if($importe){
            $this->pago = $importe;
        }
        if ($file) {
            $imagenPath = $fileUploader->upload($file);
            $this->imagen = $imagenPath;
        }
        if(isset($data['idFalla'])){
            $this->falla = $this->doctrine->getRepository(Falla::class)->find($data['idFalla']);
        }
        // $this->fechaEvento = $formData->get('fechaEvento') ? new \DateTimeImmutable($formData->get('fechaEvento')) : null;
       // $this->participantes = $formData->get('participantes') ?? null;
       // $this->tienePago = $formData->get('tienePago') ?? null;
       // $this->contador = $formData->get('contador') ?? null;

        //$entityManager = $doctrine->getManager();
        //$this->pagos = $formData->get('pagos') ? $entityManager->getRepository(Pago::class)->find($formData->get('pagos')) : null;
        //$this->falla = $formData->get('falla') ? $entityManager->getRepository(Falla::class)->find($formData->get('falla')) : null;
    }


}
