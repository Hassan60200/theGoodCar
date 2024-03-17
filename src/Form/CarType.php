<?php

namespace App\Form;

use App\Entity\BrandsCar;
use App\Entity\Car;
use PHPStan\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Dropzone\Form\DropzoneType;

class CarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('img', DropzoneType::class)
            ->add('brand', EntityType::class, [
                'class' => BrandsCar::class,
                'choice_label' => 'name',
            ])
            ->add('model')
            ->add('yearOfManufacture', TextType::class, [
                'label' => 'Année de fabrication',
            ])
            ->add('color', TextType::class, [
                'label' => 'Couleur',
            ])
            ->add('price', IntegerType::class, [
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
            ->add('mileage');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Car::class,
        ]);
    }
}
