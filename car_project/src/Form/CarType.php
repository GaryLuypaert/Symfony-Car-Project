<?php

namespace App\Form;

use App\Entity\Car;
use App\Entity\City;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('model', TextType::class)
            ->add('price', NumberType::class)
            ->add('image', ImageType::class)
            ->add('keywords', CollectionType::class, [
                'entry_type' => KeywordType::class,
                'allow_add' => true,
                'by_reference' => false
            ])
            ->add('cities', EntityType::class, [
                'class' => City::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => false,
            ])
        ;

        $builder->addEventListener(FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($options) {

            $car = $event->getData();

            if (null === $car->getImage()->getFile()) {
                $car->setImage(null);
                return;
            }

            $image = $car->getImage();

            $image->setPath($options['path']);
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Car::class,
            'path' => null,
        ]);
    }
}
