<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType
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
                    'user' => 'ROLE_VISITOR',
                ],
                'label' => 'Role',
                'required' => true
            ])
            ->add('password', RepeatedType::class,[
                'type'=> PasswordType::class,
                'invalid_message' => 'les mots de passe ne correspondent pas',
                'options' => ['attr' => ['class' => 'password-field',
                'autocomplete'=>'new-password']],
                'required' => true,
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
            'data_class' => User::class,
        ]);
    }
}
