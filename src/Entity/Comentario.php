<?php

namespace App\Entity;

use App\Repository\ComentarioRepository;
use App\Service\FileUploader;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Persistence\ManagerRegistry;

#[ORM\Entity(repositoryClass: ComentarioRepository::class)]
class Comentario
{

    private ManagerRegistry $doctrine;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idComentario = null;

    #[ORM\Column(length: 255)]
    private ?string $contenido = null;

    #[ORM\Column(length: 255)]
    private ?string $fechaComentario = null;


    #[ORM\Column(nullable: true)]
    private ?int $contador = null;

    #[ORM\ManyToOne(inversedBy: 'comentarios')]
    #[ORM\JoinColumn(referencedColumnName: "id_falla")]
    private ?Falla $falla = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imagenComentario = null;




    public function __construct( ManagerRegistry $doctrine)
    {

        $this->doctrine = $doctrine;
    }


    public function getIdComentario(): ?int
    {
        return $this->idComentario;
    }

    public function setIdComentario(int $idComentario): self
    {
        $this->idComentario = $idComentario;

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

    public function getFechaComentario():string
    {
        return $this->fechaComentario;
    }

    public function setFechaComentario(string $fechaComentario): self
    {
        $this->fechaComentario = $fechaComentario;

        return $this;
    }

    public function getContador(): ?int
    {
        return $this->contador;
    }

    public function setContador(int $contador): self
    {
        $this->contador = $contador;

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
    public function getImagenComentario(): ?string
    {
        return $this->imagenComentario;
    }

    public function setImagenComentario(?string $imagenComentario): self
    {
        $this->imagenComentario = $imagenComentario;

        return $this;
    }
    public function toArray(): array
    {
        return [
            'idComentario' => $this->idComentario,
            'contenido' => $this->contenido,
            'fechaComentario' => $this->fechaComentario,
            'contador' => $this->contador,
            'imagen' => $this->imagenComentario,
            'falla' => $this->falla?->getIdFalla(),
        ];
    }
    public function fromJson($content, ManagerRegistry $doctrine): void
    {
        $content = json_decode($content, true);
        $this->contenido = $content['contenido'] ?? null;
        $this->fechaComentario = isset($content['fechaComentario']) ? new \DateTimeImmutable($content['fechaComentario']) : null;
        $this->contador = $content['contador'] ?? null;
        $entityManager = $doctrine->getManager();
        $this->falla = isset($content['falla']) ? $entityManager->getRepository(Falla::class)->find($content['falla']) : null;
    }
    public function fromFormData($data, $file, $fileUploader): void
    {
        dump($data);
        $this->contenido = $data['descripcionComentario'] ;
        $this->fechaComentario = $data['fechaCreacion'] ;
        dump($data['descripcionComentario']);
        if(isset($data['idFalla'])){
            $this->falla = $this->doctrine->getRepository(Falla::class)->find($data['idFalla']);
        }


        if ($file) {
            $imagenPath = $fileUploader->upload($file);
            $this->imagenComentario = $imagenPath;
        }

    }






}
