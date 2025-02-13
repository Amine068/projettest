<?php

namespace App\Form;

use App\Entity\Annonce;
use App\Entity\Category;
use App\Entity\Subcategory;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use App\Form\FormExtention\HoneyPotType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class AnnonceType extends HoneyPotType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de l\'annonce',
                'attr' => ['class' => 'p-2 rounded-md border border-gray-300'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un titre pour votre annonce',
                    ]),
                ],
            ])
            ->add('description', TextType::class, [
                    'label' => 'Description de l\'annonce',
                    "attr" => ["class" => "p-2 rounded-md border border-gray-300"],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez saisir une description pour votre annonce',
                        ]),
                    ],
                ])
            ->add('price', MoneyType::class, [
                'label' => 'Prix de l\'annonce en ',
                'attr' => ['class' => 'p-2 rounded-md border border-gray-300'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un prix pour votre annonce',
                    ]),
                ],
            ])
            ->add('state', ChoiceType::class, [
                'attr' => ['class' => 'p-2 rounded-md border border-gray-300'],
                'choices' => [
                    'Neuf' => 'Neuf',
                    'Très bon état' => 'Très bon état',
                    'Bon état' => 'Bon état',
                    'Usé' => 'Usé',
                    'Occasion' => 'Occasion',
                ],
                'label' => 'Etat de l\'objet',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez choisir un état pour votre annonce',
                    ]),
                ],
            ])
            ->add('city', TextType::class, [
                'attr' => ['class' => 'p-2 rounded-md border border-gray-300'],
                'label' => 'Ville',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir une ville pour votre annonce',
                    ]),
                ],
            ])
            ->add('zipcode', TextType::class, [
                'attr' => ['class' => 'p-2 rounded-md border border-gray-300'],
                'label' => 'Code postal',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un code postal pour votre annonce',
                    ]),
                ],
            ])
            ->add('isVisible', CheckboxType::class, [
                'label' => 'Rendre l\'annonce visible',
                'required' => false,
            ])
            ->add('telephone', TextType::class, [
                'label' => 'Téléphone',
            ])
            ->add('Category', EntityType::class, [
                'attr' => ['class' => 'p-2 rounded-md border border-gray-300'],
                'label' => 'Choisissez une catégorie',
                'class' => Category::class,
                'placeholder' => 'Choisissez une catégorie',
                'choice_label' => 'name',
                'required' => true,
            ]);

            $formModifier = function (FormInterface $form, Category $category = null) {
                $subcategories = null === $category ? [] : $category->getSubcategory();
                $form->add('subcategory', EntityType::class, [
                    'class' => Subcategory::class,
                    'attr' => ['class' => 'p-2 rounded-md border border-gray-300'],
                    'label' => 'Choisissez une sous-catégorie',
                    'placeholder' => 'Choisissez une sous-catégorie',
                    'choices' => $subcategories,
                    'choice_label' => 'name',
                    'required' => true,
                    'disabled' => true,
                ]);
            };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                $data = $event->getData();
                $formModifier($event->getForm(), $data->getCategory());
            }
        );

        $builder->get('Category')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                $category = $event->getForm()->getData();
                $formModifier($event->getForm()->getParent(), $category);
            }
        );

        $builder->setAction($options['action']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }
}
