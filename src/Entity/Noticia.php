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

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imagen = null;

    #[ORM\Column(length: 255)]
    private ?string $fechaNoticia = null;

    #[ORM\ManyToOne(inversedBy: 'noticias')]
    #[ORM\JoinColumn(referencedColumnName: "id_falla")]
    private ?Falla $falla = null;




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
    public function getFechaNoticia():string
    {
        return $this->fechaNoticia;
    }

    public function setFechaNoticia(string $fechaNoticia): self
    {
        $this->fechaNoticia = $fechaNoticia;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'idNoticia' => $this->idNoticia,
            'titulo' => $this->titulo,
            'contenido' => $this->contenido,
            'imagen' => $this->imagen,
            'fechaNoticia'=>$this->fechaNoticia
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
    public function fromFormData($data, $file, $fileUploader, $doctrine): void
    {
        dump($data);
        $this->contenido = $data['descripcionNoticia'] ;
        $this->titulo = $data['tituloNoticia'] ;
        $this->fechaNoticia = $data['fechaCreacion'] ;
        dump($data['descripcionNoticia']);
        if(isset($data['idFalla'])){
            $this->falla = $doctrine->getRepository(Falla::class)->find($data['idFalla']);
        }


        if ($file) {
            $imagenPath = $fileUploader->upload($file);
            $this->imagen = $imagenPath;
        }

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



}
