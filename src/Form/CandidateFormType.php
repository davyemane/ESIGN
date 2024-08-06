<?php

namespace App\Form;

use App\Entity\Candidate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CandidateFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('sexe', ChoiceType::class, [
                'choices' => [
                    'Masculin' => 'M',
                    'Féminin' => 'F',
                ],
            ])
            ->add('region', ChoiceType::class, [
                'choices' => [
                    'Cameroun' => [
                        'Adamaoua' => 'Adamaoua',
                        'Centre' => 'Centre',
                        'Est' => 'Est',
                        'Extrême-Nord' => 'Extrême-Nord',
                        'Littoral' => 'Littoral',
                        'Nord' => 'Nord',
                        'Nord-Ouest' => 'Nord-Ouest',
                        'Ouest' => 'Ouest',
                        'Sud' => 'Sud',
                        'Sud-Ouest' => 'Sud-Ouest',
                    ],
                    'Congo' => [
                        'Bouenza' => 'Bouenza',
                        'Cuvette' => 'Cuvette',
                        'Cuvette-Ouest' => 'Cuvette-Ouest',
                        'Kouilou' => 'Kouilou',
                        'Lékoumou' => 'Lékoumou',
                        'Likouala' => 'Likouala',
                        'Niari' => 'Niari',
                        'Plateaux' => 'Plateaux',
                        'Pointe-Noire' => 'Pointe-Noire',
                        'Pool' => 'Pool',
                        'Sangha' => 'Sangha',
                    ],
                ],
            ])
            ->add('depertement', ChoiceType::class, [
                'choices' => [
                    'Cameroun' => [
                        // Adamaoua
                        'Djérem' => 'Djérem',
                        'Faro-et-Déo' => 'Faro-et-Déo',
                        'Mbéré' => 'Mbéré',
                        'Mayo-Banyo' => 'Mayo-Banyo',
                        'Vina' => 'Vina',
                        // Ajouter les autres départements pour chaque région
                    ],
                    'Congo' => [
                        // Bouenza
                        'Mouyondzi' => 'Mouyondzi',
                        'Loudima' => 'Loudima',
                        'Madingou' => 'Madingou',
                        'Mfouati' => 'Mfouati',
                        'Nkayi' => 'Nkayi',
                        'Yamba' => 'Yamba',
                        // Ajouter les autres départements pour chaque région
                    ],
                ],
            ])
            ->add('cni')
            ->add('field')
            ->add('examinationCenter', ChoiceType::class, [
                'choices' => [
                    'Sangmelima' => 'Sangmelima',
                    'Bafoussam' => 'Bafoussam',
                    // Ajouter d'autres centres d'examen si nécessaire
                ],
            ])
            ->add('certificate', FileType::class, [
                'label' => 'Certificate (Image file)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image document',
                    ])
                ],
            ])
            ->add('dateOfBirth', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('placeOfBirth')
            ->add('certificateYear')
            ->add('language', ChoiceType::class, [
                'choices' => [
                    'Français' => 'FR',
                    'Anglais' => 'EN',
                    // Ajoutez d'autres langues si nécessaire
                ],
            ])
            ->add('transactionNumber')
            ->add('payementReceipt', FileType::class, [
                'label' => 'Payment Receipt (Image file)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image document',
                    ])
                ],
            ])
            ->add('phoneNumber')
            ->add('email')
            ->add('nationality', ChoiceType::class, [
                'choices' => [
                    'Cameroun' => 'Cameroun',
                    'Congo' => 'Congo',
                ],
            ])
            ->add('photo', FileType::class, [
                'label' => 'Photo (Image file)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image document',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Candidate::class,
        ]);
    }
}
