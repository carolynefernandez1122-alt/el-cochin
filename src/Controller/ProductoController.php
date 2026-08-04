<?php

namespace App\Controller;

use App\Entity\Producto;
use App\Form\ProductoType;
use App\Repository\ProductoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/producto")
 */
class ProductoController extends AbstractController
{
    /**
     * @Route("/", name="app_producto_index", methods={"GET"})
     */
    public function index(ProductoRepository $productoRepository, Request $request): Response
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

        return $this->render('admin/producto/index.html.twig', [
            'productos' => $productos,
            'paginaActual' => $pagina,
            'totalPaginas' => $totalPaginas,
        ]);
    }
    /**
     * @Route("/new", name="app_producto_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ProductoRepository $productoRepository,EntityManagerInterface $entityManager): Response
    {
        $producto = new Producto();
        $form = $this->createForm(ProductoType::class, $producto);
        $form->handleRequest($request);

        $imagenFile = $form->get('imagenFile')->getData();

        if ($imagenFile) {
            $nuevoNombre = uniqid() . '.' . $imagenFile->guessExtension();

            $imagenFile->move(
                $this->getParameter('productos_images_directory'),
                $nuevoNombre
            );

            $producto->setImagenUrl($nuevoNombre);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $productoRepository->add($producto, true);
            $entityManager->persist($producto);
            $entityManager->flush();

            return $this->redirectToRoute('app_producto_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/producto/new.html.twig', [
            'producto' => $producto,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_producto_show", methods={"GET"})
     */
    public function show(Producto $producto): Response
    {
        return $this->render('admin/producto/show.html.twig', [
            'producto' => $producto,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_producto_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Producto $producto, ProductoRepository $productoRepository,EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductoType::class, $producto);
        $form->handleRequest($request);

        $imagenFile = $form->get('imagenFile')->getData();

        if ($imagenFile) {
            $nuevoNombre = uniqid() . '.' . $imagenFile->guessExtension();

            $imagenFile->move(
                $this->getParameter('productos_images_directory'),
                $nuevoNombre
            );

            $producto->setImagenUrl($nuevoNombre);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $productoRepository->add($producto, true);
            $entityManager->persist($producto);
            $entityManager->flush();

            return $this->redirectToRoute('app_producto_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/producto/edit.html.twig', [
            'producto' => $producto,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_producto_delete", methods={"POST"})
     */
    public function delete(Request $request, Producto $producto, ProductoRepository $productoRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$producto->getId(), $request->request->get('_token'))) {
            $productoRepository->remove($producto, true);
        }

        return $this->redirectToRoute('app_producto_index', [], Response::HTTP_SEE_OTHER);
    }
}
