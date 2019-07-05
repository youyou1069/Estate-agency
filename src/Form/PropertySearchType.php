<?php

namespace App\Form;

use App\Entity\Choix;
use App\Entity\PropertySearch;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertySearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('maxPrice', IntegerType::class, [
               'required' =>  false,
                'label'   => false,
                'attr'    => [
                    'placeholder' => 'Prix maximum'
                ]
            ])

            ->add('minSurface', IntegerType::class, [
                'required' =>  false,
                'label'   => false,
                'attr'    => [
                    'placeholder' => 'Surface minimale'
                ]

            ])

            ->add('options', EntityType::class,[
                'class'=> Choix::class,
                'required' =>  false,
                'label'   => false,
                'choice_label'    => 'nom',
                'multiple'=> true,
            ])
        ;
    }
    //ajouter methode GET
    //desactiver la protection CSRF
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PropertySearch::class,
            'method' => 'get',
            'csrf_protection' => false
        ]);
    }
    //Créer une méthode pour alléger le contenu du URL
    /**
     * @return string|null
     */
    public function getBlockPrefix()
    {
        return '';
    }

}
