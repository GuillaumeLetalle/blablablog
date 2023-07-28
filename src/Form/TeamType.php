<?php

namespace App\Form;

use App\Entity\Team;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use function PHPSTORM_META\type;

class TeamType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',EmailType::class,[
                'label'=>'Email',
                'required'=> true,
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Administrateur' => 'ROLE_ADMIN',
                    'SuperAdmin' => 'ROLE_SUPERADMIN',
                ],
                'label' => 'Role',
                'required' => true
            ])
            ->add('password', RepeatedType::class,[
                'type'=> PasswordType::class,
                'invalid_message' => 'les mots de passe ne correspondent pas',
                'options' => ['attr' => ['class' => 'password-field',
                'autocomplete'=>'new-password']],
                'required' => is_null($builder->getData()->getId()),
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmation du mot de passe'],
            ])
            ->add('lastname', TextType::class,[
                'label'=>'nom',
                'required'=> true,
            ])
            ->add('firstname', TextType::class,[
                'label'=>'prénom',
                'required'=> true,
            ])
        ;

        $builder->get('roles')
        ->addModelTransformer(new CallbackTransformer(function ($rolesArray){
            return count($rolesArray) ? $rolesArray[0] : null;
                },
                function ($rolesString) {
                    return [$rolesString];
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Team::class,
        ]);
    }
}
