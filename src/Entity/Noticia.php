<?php

namespace App\Entity;

use App\Repository\NoticiaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NoticiaRepository::class)]
class Noticia
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idNoticia = null;

    #[ORM\Column(length: 100)]
    private ?string $titulo = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $contenido = null;

    #[ORM\Column(length: 255)]
    private ?string $imagen = null;





    public function getIdNoticia(): ?int
    {
        return $this->idNoticia;
    }

    public function setIdNoticia(int $idNoticia): self
    {
        $this->idNoticia = $idNoticia;

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

    public function getImagen()
    {
        return $this->imagen;
    }

    public function setImagen($imagen): self
    {
        $this->imagen = $imagen;

        return $this;
    }
    public function toArray(): array
    {
        return [
            'idNoticia' => $this->idNoticia,
            'titulo' => $this->titulo,
            'contenido' => $this->contenido,
            'imagen' => $this->imagen,
        ];
    }
    public function fromJson($content): void
    {
        $content = json_decode($content, true);
        $this->idNoticia = $content['idNoticia'] ?? null;
        $this->titulo = $content['titulo'] ?? null;
        $this->contenido = $content['contenido'] ?? null;
        $this->imagen = $content['imagen'] ?? null;
    }




}
