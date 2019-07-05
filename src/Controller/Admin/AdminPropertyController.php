<?php

namespace App\Controller\Admin;

use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class AdminPropertyController extends AbstractController
{
     /**
     * @Route("/admin", name="admin.property.index")
     */
    public function index(PropertyRepository $repo)
    {
        $properties = $repo->findAll();
        return $this->render ('admin/index.html.twig', ['properties' => $properties
        ]);
    }

    /**
     * @Route("/admin/{id}/edit", name="admin.property_edit", methods={"GET|POST"})
     */
    public function edit(Property $property, Request $request, ObjectManager $manager, CacheManager $cacheManager, UploaderHelper $helper)
    {



        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);
        if ($property->getImageFile() instanceof UploadedFile){
        $cacheManager->remove($helper->asset($property, 'imageFile'));
        }

        if($form->isSubmitted()&& $form->isValid()){
//            supprimer le cache (photo mise  jours)
//            if ($property->getImageFile() instanceof UploadedFile){
//                $cacheManager->remove($helper->asset($property, 'imageFile'));
//            }

            $manager->flush();
            //confirmation de la mise à jour
            $this->addFlash('success', 'Bien modifié avec succès');
            return $this->redirectToRoute('admin.property.index');
        }

        return $this->render('admin/edit.property.html.twig', [
            'form'=>$form->createView(),
            'property'=>$property,

        ]);

    }


    /**
     * @Route("/admin/new", name="admin.property_new")
     */
    public function new(Request $request, ObjectManager $manager)
    {
        $property = new Property();
        $form= $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $manager->persist($property);
            $manager->flush();
            $this->addFlash('success', 'Bien Ajouté avec succès');
            return $this->redirectToRoute('admin.property.index');
        }

        return $this->render('admin/new.property.html.twig', [
            'property'=>$property,
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/admin/{id}/edit", name="admin.property_delete", methods={"DELETE"})
     */
    public function delete (Property $property, ObjectManager $manager)
    {
        $manager->remove($property);
        $manager->flush();
        return $this->redirectToRoute('admin.property.index');
    }



}
