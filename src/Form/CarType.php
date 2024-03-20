<?php

namespace App\Form;

use App\Entity\BrandsCar;
use App\Entity\Car;
use App\Entity\Departement;
use App\Entity\ModelsCar;
use App\Entity\Region;
use PHPStan\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Dropzone\Form\DropzoneType;

class CarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('img', DropzoneType::class, [
                'label' => 'Image de la voiture',
            ])
            ->add('brand', EntityType::class, [
                'class' => BrandsCar::class,
                'choice_label' => 'name',
                'label' => 'Marque de la voiture',
            ])
            ->add('region', EntityType::class, [
                'class' => Region::class,
                'choice_label' => 'name',
                'label' => 'Région de la voiture',
            ])
            ->add('departement', EntityType::class, [
                'class' => Departement::class,
                'choice_label' => 'name',
                'label' => 'Département de la voiture',
            ])
            ->add('carModel', EntityType::class, [
                'class' => ModelsCar::class,
                'choice_label' => 'name',
                'label' => 'Modèle de la voiture',
            ])
            ->add('yearOfManufacture', TextType::class, [
                'label' => 'Année de fabrication',
            ])
            ->add('color', TextType::class, [
                'label' => 'Couleur',
            ])
            ->add('price', NumberType::class, [
                'label' => 'Prix',
            ])
            ->add('fuelType', ChoiceType::class, [
                'choices' => [
                    'Essence' => 'Essence',
                    'Diesel' => 'Diesel',
                    'Hybride' => 'Hybride',
                    'Electrique' => 'Electrique',
                ],
                'label' => 'Type de carburant',
            ])
            ->add('mileage', TextType::class, [
                'label' => 'Kilométrage',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Car::class,
        ]);
    }
}
