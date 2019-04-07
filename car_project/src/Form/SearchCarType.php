<?php
/**
 * Created by PhpStorm.
 * User: garyluypaert
 * Date: 2019-03-28
 * Time: 01:54
 */

namespace App\Form;


use App\Entity\City;
use App\Faker\CarProvider;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchCarType extends AbstractType
{
    const PRICE = [ 500, 1000, 1500, 2000, 2500, 3000, 3500, 4000, 4500, 5000];

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('color', ChoiceType::class, [
                'choices' =>
                    array_combine(CarProvider::COLOR, CarProvider::COLOR)
            ])
            ->add('carburant', ChoiceType::class, [
                'choices' =>
                    array_combine(CarProvider::CARBURANT, CarProvider::CARBURANT)
            ])
            ->add('cities', EntityType::class, [
                'class' => City::class,
                'choice_label' => 'name',
            ])
            ->add('minimumPrice', ChoiceType::class, [
                'label' => 'Prix minimum',
                'choices' => array_combine(SearchCarType::PRICE, SearchCarType::PRICE),
            ])
            ->add('maximumPrice', ChoiceType::class, [
                'label' => 'Prix maximum',
                'choices' => array_combine(SearchCarType::PRICE, SearchCarType::PRICE),
            ])
            ->add('Rechercher', SubmitType::class);
    }
}