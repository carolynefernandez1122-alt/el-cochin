<?php
namespace App\Entity;
use App\Repository\ProductoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass=ProductoRepository::class)
 */
class Producto
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="El nombre no puede estar vacío.")
     */
    private $nombre;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $descripcion = ' ';
    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Assert\NotBlank(message="El precio no puede estar vacío.")
     * @Assert\Positive(message="El precio debe ser mayor que 0.")
     */
    private $precio;
    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="El stock no puede estar vacío.")
     * @Assert\PositiveOrZero(message="El stock no puede ser negativo.")
     */
    private $stock;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $imagen_url = ' ';
    /**
     * @ORM\Column(type="boolean")
     */
    private $activo = true;
    /**
     * @ORM\Column(type="boolean")
     */
    private $destacado;
    /**
     * @ORM\ManyToOne(targetEntity=Categoria::class, inversedBy="productos")
     * @Assert\NotNull(message="Selecciona una categoría.")
     */
    private $categoria;
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getNombre(): ?string
    {
        return $this->nombre;
    }
    public function setNombre(?string $nombre): self
    {
        $this->nombre = $nombre;
        return $this;
    }
    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }
    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;
        return $this;
    }
    public function getPrecio(): ?string
    {
        return $this->precio;
    }
    public function setPrecio(?string $precio): self
    {
        $this->precio = $precio;
        return $this;
    }
    public function getStock(): ?int
    {
        return $this->stock;
    }
    public function setStock(?int $stock): self
    {
        $this->stock = $stock;
        return $this;
    }
    public function getImagenUrl(): ?string
    {
        return $this->imagen_url;
    }
    public function setImagenUrl(?string $imagen_url): self
    {
        $this->imagen_url = $imagen_url;
        return $this;
    }
    public function isActivo(): ?bool
    {
        return $this->activo;
    }
    public function setActivo(bool $activo): self
    {
        $this->activo = $activo;
        return $this;
    }
    public function isDestacado(): ?bool
    {
        return $this->destacado;
    }
    public function setDestacado(bool $destacado): self
    {
        $this->destacado = $destacado;
        return $this;
    }
    public function getCategoria(): ?Categoria
    {
        return $this->categoria;
    }
    public function setCategoria(?Categoria $categoria): self
    {
        $this->categoria = $categoria;
        return $this;
    }
}