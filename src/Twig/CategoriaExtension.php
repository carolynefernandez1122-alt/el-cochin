<?php

namespace App\Twig;

use App\Repository\CategoriaRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CategoriaExtension extends AbstractExtension
{
    private CategoriaRepository $categoriaRepository;

    public function __construct(CategoriaRepository $categoriaRepository)
    {
        $this->categoriaRepository = $categoriaRepository;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('categoriasMenu', [$this, 'getCategoriasMenu']),
        ];
    }

    public function getCategoriasMenu(): array
    {
        return $this->categoriaRepository->findAll();
    }
}
