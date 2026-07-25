<?php

namespace App\Controller;

use App\Entity\Proveedor;
use App\Form\ProveedorType;
use App\Repository\ProveedorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/proveedor")
 */
class ProveedorController extends AbstractController
{
    /**
     * @Route("/", name="app_proveedor_index", methods={"GET"})
     */
    public function index(ProveedorRepository $proveedorRepository): Response
    {
        return $this->render('proveedor/index.html.twig', [
            'proveedors' => $proveedorRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_proveedor_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ProveedorRepository $proveedorRepository): Response
    {
        $proveedor = new Proveedor();
        $form = $this->createForm(ProveedorType::class, $proveedor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $proveedorRepository->add($proveedor, true);

            return $this->redirectToRoute('app_proveedor_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('proveedor/new.html.twig', [
            'proveedor' => $proveedor,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_proveedor_show", methods={"GET"})
     */
    public function show(Proveedor $proveedor): Response
    {
        return $this->render('proveedor/show.html.twig', [
            'proveedor' => $proveedor,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_proveedor_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Proveedor $proveedor, ProveedorRepository $proveedorRepository): Response
    {
        $form = $this->createForm(ProveedorType::class, $proveedor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $proveedorRepository->add($proveedor, true);

            return $this->redirectToRoute('app_proveedor_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('proveedor/edit.html.twig', [
            'proveedor' => $proveedor,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_proveedor_delete", methods={"POST"})
     */
    public function delete(Request $request, Proveedor $proveedor, ProveedorRepository $proveedorRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$proveedor->getId(), $request->request->get('_token'))) {
            $proveedorRepository->remove($proveedor, true);
        }

        return $this->redirectToRoute('app_proveedor_index', [], Response::HTTP_SEE_OTHER);
    }
}
