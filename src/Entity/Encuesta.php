<?php

namespace App\Entity;

use App\Repository\EncuestaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Persistence\ManagerRegistry;


#[ORM\Entity(repositoryClass: EncuestaRepository::class)]
class Encuesta
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idEncuesta = null;

    #[ORM\Column(length: 100)]
    private ?string $titulo = null;

    #[ORM\Column(length: 255)]
    private ?string $fechaCreacion = null;

    #[ORM\Column(length: 255)]
    private ?string $fechaCaducidad = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $opciones = [];

    #[ORM\Column(nullable: true)]
    private ?int $contador = 0;

    #[ORM\ManyToOne(inversedBy: 'encuestas')]
    #[ORM\JoinColumn(referencedColumnName: "id_falla")]
    private ?Falla $falla = null;



    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private array $respuestas = [];





    public function getIdEncuesta(): ?int
    {
        return $this->idEncuesta;
    }

    public function setIdEncuesta(int $idEncuesta): self
    {
        $this->idEncuesta = $idEncuesta;

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

    public function getFechaCreacion(): string
    {
        return $this->fechaCreacion;
    }

    public function setFechaCreacion(string $fechaCreacion): self
    {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    public function getFechaCaducidad(): string
    {
        return $this->fechaCaducidad;
    }

    public function setFechaCaducidad(string $fechaCaducidad): self
    {
        $this->fechaCaducidad = $fechaCaducidad;

        return $this;
    }

    public function getOpciones(): array
    {
        return $this->opciones;
    }

    public function setOpciones(array $opciones): self
    {
        $this->opciones = $opciones;

        return $this;
    }
    public function getRespuestas(): array
    {
        return $this->respuestas;
    }

    public function setRespuestas(string $respuesta): self
    {
        $this->respuestas[] = $respuesta;

        return $this;
    }
    public function clearRespuestas(): self
    {
        $this->respuestas = [];

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

    public function getFalla(): ?Falla
    {
        return $this->falla;
    }

    public function setFalla(?Falla $falla): self
    {
        $this->falla = $falla;

        return $this;
    }
    public function toArray(): array {


        return [
            'idEncuesta' => $this->idEncuesta,
            'titulo' => $this->titulo,
            'fechaCreacion' => $this->fechaCreacion,
            'fechaCaducidad' => $this->fechaCaducidad,
            'opciones' => $this->opciones,
            'respuestas' => $this->respuestas,
            'contador' => $this->contador,
            'falla' => $this->falla?->getIdFalla(),
        ];
    }

    public function fromJson($content, ManagerRegistry $doctrine): void
    {
        $content = json_decode($content, true);

        $this->titulo = $content['pregunta'] ?? null;
        $this->fechaCreacion = $content['fechaCreacion'];
        $this->fechaCaducidad = $content['fechaCaducidad'];
        $this->opciones = $content['opciones'] ?? [];

        $entityManager = $doctrine->getManager();
        $this->falla = isset($content['falla']) ? $entityManager->getRepository(Falla::class)->find($content['falla']) : null;
    }



}
