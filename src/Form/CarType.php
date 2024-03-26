<?php

namespace App\Form;

use App\Entity\BrandsCar;
use App\Entity\Car;
use App\Entity\Departement;
use App\Entity\ModelsCar;
use App\Entity\Region;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class CarType extends AbstractType
{
    public function STATE(): array
    {
        return [
            'En vente' => 'En vente',
            'Vendu' => 'Vendu',
            'Réservé' => 'Réservé',
            'En attente' => 'En attente',
            'En réparation' => 'En réparation',
            'En panne' => 'En panne',
            'Louer' => 'Louer',
            'Inactive' => 'Inactive',
        ];
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image de la voiture',
                'help' => 'jpeg, png',
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'delete',
                'download_label' => 'download',
                'download_uri' => true,
                'image_uri' => true,
                'asset_helper' => true,
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
            ->add('departement', TextType::class, [
                'label' => 'Département de la voiture',
                'attr' => [
                    'placeholder' => 'Nom du département',
                    'data-autocomplete-target' => 'inputDep',
                    'data-action ' => 'input->autocomplete#onSearchInputDepartement',
                ],
            ])
            /*->add('carModel', EntityType::class, [
                'class' => ModelsCar::class,
                'choice_label' => 'name',
                'label' => 'Modèle de la voiture',
            ])*/
            ->add('model', TextType::class, [
                'label' => 'Modèle de la voiture',
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Nom du modèle',
                    'data-autocomplete-target' => 'input',
                    'data-action ' => 'input->autocomplete#onSearchInputModel',
                ],
            ])
            ->add('years', NumberType::class, [
                'label' => 'Année de fabrication',
                'mapped' => false,
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
            ->add('status', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => $this->STATE(),
            ])
            ->
            add('mileage', TextType::class, [
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
