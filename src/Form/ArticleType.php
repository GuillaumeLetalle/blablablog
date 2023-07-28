<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Categorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('contenu')
            ->add('titre')
            ->add('image', FileType::class, [
                'data_class' => null,
                'label' => 'Image',
                'required' => false,
                'help' =>'Fichier jpg, jpeg, png, ou webp ne dépassant pas 1Mo',
                'constraints' => [
                    new File([
                        //'maxSize' => '10M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image. '
                    ])
                ]
            ])
            ->add('date')
            ->add('fk_categorie', EntityType::class,[
                'label' => 'Categorie',
                'class' => Categorie::class,
                'choice_label' => 'name',
                'required' => true,
            ])
            ->add('fk_team')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
