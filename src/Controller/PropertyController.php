<?php /** @noinspection ReturnTypeCanBeDeclaredInspection */

/** @noinspection ReturnTypeCanBeDeclaredInspection */

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Property;
use App\Entity\PropertySearch;
use App\Form\ContactType;
use App\Form\PropertySearchType;
use App\Notification\ContactNotification;
use App\Repository\PropertyRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class PropertyController extends AbstractController
{
    private $repo;
    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * @Route("/properties", name="properties")
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        // créer une Entity qui va représenter notre recherche (voir entity)
        // créer un formulaire (voir formulaire)
        // gérer le traitement et affichage par le contrôlleur

        $search = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class, $search);
        $form->handleRequest($request);


        $properties = $paginator->paginate(
            $this->repo->findAllVisibleQuery($search),
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('property/properties.html.twig', [

            'properties' => $properties,
            'form' => $form->createView()
        ]);
    }

    /**
     * PropertyController constructor.
     * @param PropertyRepository $repo
     * @param ObjectManager $sm
     */
    public function __construct(PropertyRepository $repo, ObjectManager $sm)
    {
        $this->repo = $repo;
        /** @noinspection UnusedConstructorDependenciesInspection */
        /** @noinspection UnusedConstructorDependenciesInspection */
        $this->em = $sm;
    }


    /**
     * @Route("/property/{id}", name="property_show")
     */
    public function show(Property $property, Request $request, ContactNotification $contactNotification): Response
    {
//        if ($property->getSlug() !== $slug){
//            return $this->redirectToRoute('property_show', [
//                'id' => $property->getId()];
//                'slug'=>$property->getSlug()
//            ], 301);
//        }
        $contact = new Contact();
        $contact->setProperty($property);
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
//
        if ($form->isSubmitted() && $form->isValid()) {
            $contactNotification->notify($contact);
            $this->addFlash('success', 'Votre email à bien été envoyé');

            return $this->redirectToRoute('property_show', [
                'id' => $property->getId()
                ]);
        }


        return $this->render('property/show.html.twig', [
            'property' => $property,
            'current_menu' => 'properties',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('property/home.html.twig', [
            'controller_name' => 'PropertyController',
        ]);
    }


}
