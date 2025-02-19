<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Annonce;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Nom d\'utilisateur',
                'attr' => ['class' => 'p-2 rounded-md border border-gray-300', 'placeholder' => 'Modifier un nom d\'utilisateur'],
            ])
            ->add('avatar', FileType::class, [
                'label' => 'Avatar',
                'attr' => ['class' => 'p-2 rounded-md border border-gray-300'],
                'mapped' => false,
                'required' => false,
            ])
            ->add('Valider', SubmitType::class, [
                'attr' => ['class' => 'p-2 bg-blue-500 text-white rounded-md'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
