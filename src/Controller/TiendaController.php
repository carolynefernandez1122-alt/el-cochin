<?php
// src/Controller/TiendaController.php
namespace App\Controller;

use App\Repository\ProductoRepository;
use App\Repository\CategoriaRepository;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TiendaController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function home(CategoriaRepository $categoriaRepository,ProductoRepository $productoRepository): Response
    {
        return $this->render('tienda/index.html.twig', [
            'destacados' => $productoRepository->findDestacados(12),
        ]);
    }

    /**
     * @Route("/producto/{id}", name="app_producto_publico_show")
     */
    public function verProducto(\App\Entity\Producto $producto): Response
    {
        return $this->render('tienda/producto_show.html.twig', [
            'producto' => $producto,
        ]);
    }

    /**
     * @Route("/productos", name="app_catalogo",methods={"GET"})
     */
    public function catalogo(ProductoRepository $productoRepository, Request $request): Response
    {
        $limite = 20;
        $pagina = max(1, $request->query->getInt('page', 1));

        $totalProductos = $productoRepository->count([]);
        $totalPaginas = (int) ceil($totalProductos / $limite);

        $productos = $productoRepository->findBy(
            [],                    // sin filtro
            ['id' => 'DESC'],      // orden (ajústalo si quieres otro)
            $limite,               // cuántos traer
            ($pagina - 1) * $limite // desde dónde empezar
        );

        return $this->render('tienda/catalogo.html.twig', [
            'productos' => $productos,
            'paginaActual' => $pagina,
            'totalPaginas' => $totalPaginas,
        ]);

    }
}