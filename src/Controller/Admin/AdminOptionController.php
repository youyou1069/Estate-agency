<?php

namespace App\Controller\Admin;


use App\Repository\ChoixRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Choix;
use App\Form\ChoixType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class AdminOptionController extends AbstractController
{

    /**
     * @Route("admin/choix", name="choix_index", methods={"GET"})
     */
    public function index(ChoixRepository $choixRepository): Response
    {
        return $this->render('admin/choix/index.html.twig', [
            'choixs' => $choixRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/choix/new", name="choix_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $choix= new Choix();
        $form = $this->createForm(ChoixType::class, $choix);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($choix);
            $entityManager->flush();

            return $this->redirectToRoute('choix_index');
        }

        return $this->render('admin/Choix/new.choix.html.twig', [
            'choix' => $choix,
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("admin/choix/{id}/edit", name="choix_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Choix $choix): Response
    {
        $form = $this->createForm(OptionType::class, $choix);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('choix_index', [
                'id' => $choix->getId(),
            ]);
        }

        return $this->render('admin/Choix/edit.choix.html.twig', [
            'choix' => $choix,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("admin/choix/{id}", name="choix_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Choix $choix): Response
    {
        if ($this->isCsrfTokenValid('admin/delete'.$choix->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($choix);
            $entityManager->flush();
        }

        return $this->redirectToRoute('choix_index');
    }
}