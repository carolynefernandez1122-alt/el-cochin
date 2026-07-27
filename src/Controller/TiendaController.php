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
        $productos = $productoRepository->findBy([], null, 12); // los primeros 18, por ejemplo

        return $this->render('tienda/index.html.twig', [
            'destacados' => $productoRepository->findDestacados(12),
            'productos' => $productos
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
        $categoriaId = $request->query->get('categoria');
        $criterios = [];
        if ($categoriaId) {
            $criterios['categoria'] = $categoriaId;
        }
        $totalProductos = $productoRepository->count($criterios);
        $totalPaginas = (int) ceil($totalProductos / $limite);

        $productos = $productoRepository->findBy(
            $criterios,                    // sin filtro
            ['id' => 'DESC'],      // orden (ajústalo si quieres otro)
            $limite,               // cuántos traer
            ($pagina - 1) * $limite // desde dónde empezar
        );

        return $this->render('tienda/catalogo.html.twig', [
            'productos' => $productos,
            'paginaActual' => $pagina,
            'totalPaginas' => $totalPaginas,
            'categoriaId' => $categoriaId,
        ]);

    }
    /**
     * @Route("/tienda/searchByName", name="app_producto_buscar", methods={"GET"})
     */
    public function searchByName(ProductoRepository $productoRepository, Request $request): Response
    {
        $q = trim($request->query->get('q', ''));
        $context = $request->query->get('context', 'catalogo');
        $categoriaId = $request->query->get('categoria');
        $limite = 20;
        $pagina = max(1, $request->query->getInt('page', 1));

        $criterios = [];
        if ($categoriaId) {
            $criterios['categoria'] = $categoriaId;
        }

        if ($q !== '') {
            $total = $productoRepository->countSearch($q);
            $totalPaginas = (int) ceil($total / $limite);
            $productos = $productoRepository->search($q, $limite, ($pagina - 1) * $limite);
        } elseif ($context === 'home') {
            $productos = $productoRepository->findDestacados();
            $totalPaginas = 0;
        } else {
            $total = $productoRepository->count($criterios);
            $totalPaginas = (int) ceil($total / $limite);
            $productos = $productoRepository->findBy(
                $criterios,
                ['id' => 'DESC'],
                $limite,
                ($pagina - 1) * $limite
            );
        }

        if ($request->isXmlHttpRequest()) {
            return $this->render('tienda/_lista.html.twig', [
                'productos' => $productos,
                'paginaActual' => $pagina,
                'totalPaginas' => $totalPaginas,
                'categoriaId' => $categoriaId,
                'q' => $q,
            ]);
        }

        return $this->render('tienda/catalogo.html.twig', [
            'productos' => $productos,
            'paginaActual' => $pagina,
            'totalPaginas' => $totalPaginas,
            'categoriaId' => $categoriaId,
            'q' => $q,
        ]);
    }
}