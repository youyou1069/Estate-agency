<?php

namespace App\Form;

use App\Entity\Choix;
use App\Entity\Property;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Tests\Constraints\choice_callback;

class PropertyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('price')
            ->add('city')
            ->add('address')
            ->add('sold')
            ->add('postal_code')
            ->add('surface')
            ->add('rooms')
            ->add('imageFile', FileType::class, [
                'required' => false
            ])
            ->add('badrooms')
            ->add('floor')
            ->add('options', EntityType::class, [
                'required' => false,
                'class' => Choix::class,
                'choice_label'=> 'nom',
                'multiple'=> true,

            ])
            ->add('heat', ChoiceType::class, [
                'choices'=>$this->getChoices()
            ])
            ->add('image')

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Property::class,
        ]);
    }
    public function getChoices()
    {
        $choices= Property::HEAT;
        $output = [];
        foreach ($choices as $k=> $v){
            $output[$v]  = $k;

        }
        return $output;
    }


}
