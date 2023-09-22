<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class,[
                "attr"=>["class"=>"form-control"]
            ])
            ->add('email', EmailType::class, [
                "attr"=>["class"=>"form-control"],
                'label' => 'Email',
                'required' => true, // ajustez cela en fonction de vos besoins
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'required' => false, // This makes the password field optional
                'first_options' => ['label' => 'Mot de passe', 'attr' => ['class' =>'form-control']],
                'second_options' => ['label' => 'Répéter le mot de passe', 'attr' => ['class' =>'form-control']]
            ]);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
