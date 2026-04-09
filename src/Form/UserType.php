<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username')
            ->add('password', PasswordType::class, [
        'mapped' => false,     
        'required' => false,  
        'attr' => [
            'placeholder' => 'Nouveau mot de passe',         ]
    ])
            -> add('photoprofile', FileType::class, [
                'label' => 'Photo de profil',

                'mapped' => false,

                'required' => false,
                
                'constraints' => [
                            new File([
                                'maxSize' => '2M',
                                'mimeTypes' => [
                                    'image/png',
                                    'image/jpeg', 
                                    'image/jpg',  
                                    'image/webp'  
                                ],
                                'mimeTypesMessage' => 'Formats autorisés : PNG, JPG, WEBP',
                            ])
                        ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
