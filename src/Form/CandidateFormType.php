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
                    'Masculin' => 'Masculin',
                    'Féminin' => 'Féminin',
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
                'attr' => ['class' => 'region-select'],

            ])
            ->add('depertement', ChoiceType::class, [
                'choices' => [
                    'Cameroun' => [
                        'Adamaoua' => [
                            'Djérem' => 'Djérem',
                            'Faro-et-Déo' => 'Faro-et-Déo',
                            'Mbéré' => 'Mbéré',
                            'Mayo-Banyo' => 'Mayo-Banyo',
                            'Vina' => 'Vina',
                        ],
                        'Centre' => [
                            'Haute-Sanaga' => 'Haute-Sanaga',
                            'Lekié' => 'Lekié',
                            'Mbam-et-Inoubou' => 'Mbam-et-Inoubou',
                            'Mbam-et-Kim' => 'Mbam-et-Kim',
                            'Méfou-et-Afamba' => 'Méfou-et-Afamba',
                            'Méfou-et-Akono' => 'Méfou-et-Akono',
                            'Mfoundi' => 'Mfoundi',
                            'Nyong-et-Kéllé' => 'Nyong-et-Kéllé',
                            'Nyong-et-Mfoumou' => 'Nyong-et-Mfoumou',
                            'Nyong-et-So\'o' => 'Nyong-et-So\'o',
                        ],
                        'Est' => [
                            'Boumba-et-Ngoko' => 'Boumba-et-Ngoko',
                            'Haut-Nyong' => 'Haut-Nyong',
                            'Lom-et-Djerem' => 'Lom-et-Djerem',
                            'Méfou-et-Afamba' => 'Méfou-et-Afamba',
                        ],
                        'Extrême-Nord' => [
                            'Diamaré' => 'Diamaré',
                            'Mayo-Danay' => 'Mayo-Danay',
                            'Mayo-Kani' => 'Mayo-Kani',
                            'Mayo-Sava' => 'Mayo-Sava',
                            'Logone-et-Chari' => 'Logone-et-Chari',
                            'Mandara' => 'Mandara',
                        ],
                        'Littoral' => [
                            'Wouri' => 'Wouri',
                            'Sanaga-Maritime' => 'Sanaga-Maritime',
                            'Littoral' => 'Littoral',
                            'Océan' => 'Océan',
                        ],
                        'Nord' => [
                            'Bénoué' => 'Bénoué',
                            'Faro' => 'Faro',
                            'Mayo-Louti' => 'Mayo-Louti',
                            'Mbéré' => 'Mbéré',
                        ],
                        'Nord-Ouest' => [
                            'Boyo' => 'Boyo',
                            'Donga-Mantung' => 'Donga-Mantung',
                            'Menchum' => 'Menchum',
                            'Momo' => 'Momo',
                            'Ngoketunjia' => 'Ngoketunjia',
                            'Bui' => 'Bui',
                            'Kumbo' => 'Kumbo',
                        ],
                        'Sud' => [
                            'Dja-et-Lobo' => 'Dja-et-Lobo',
                            'Océan' => 'Océan',
                            'Mvila' => 'Mvila',
                            'Nyong-et-So\'o' => 'Nyong-et-So\'o',
                        ],
                        'Sud-Ouest' => [
                            'Fako' => 'Fako',
                            'Kupe-Muanenguba' => 'Kupe-Muanenguba',
                            'Lebialem' => 'Lebialem',
                            'Manyu' => 'Manyu',
                            'Ndian' => 'Ndian',
                            'Southwest' => 'Southwest',
                        ],
                        'Ouest' => [
                            'Bamboutos' => 'Bamboutos',
                            'Haut-Nkam' => 'Haut-Nkam',
                            'Menoua' => 'Menoua',
                            'Mifi' => 'Mifi',
                            'Ndé' => 'Ndé',
                            'Noun' => 'Noun',
                            'Koung-Khi' => 'Koung-Khi',
                        ],
                    ],
                    'Congo' => [
                        'Bouenza' => [
                            'Mouyondzi' => 'Mouyondzi',
                            'Loudima' => 'Loudima',
                            'Madingou' => 'Madingou',
                            'Mfouati' => 'Mfouati',
                            'Nkayi' => 'Nkayi',
                            'Yamba' => 'Yamba',
                        ],
                        'Brazzaville' => [
                            'Brazzaville' => 'Brazzaville',
                            'Cuvette' => 'Cuvette',
                        ],
                        'Pool' => [
                            'Kinkala' => 'Kinkala',
                            'Dolisie' => 'Dolisie',
                        ],
                        'Cuvette' => [
                            'Ewo' => 'Ewo',
                            'Owando' => 'Owando',
                            'Tsiaki' => 'Tsiaki',
                        ],
                        'Lékoumou' => [
                            'Sibiti' => 'Sibiti',
                            'Yamba' => 'Yamba',
                        ],
                        'Likouala' => [
                            'Impfondo' => 'Impfondo',
                            'Dongou' => 'Dongou',
                            'Betou' => 'Betou',
                        ],
                        'Niari' => [
                            'Dolisie' => 'Dolisie',
                            'Nkayi' => 'Nkayi',
                        ],
                        'Plateaux' => [
                            'Djambala' => 'Djambala',
                            'Sibiti' => 'Sibiti',
                        ],
                        'Pointe-Noire' => [
                            'Pointe-Noire' => 'Pointe-Noire',
                        ],
                        'Sangha' => [
                            'Ouesso' => 'Ouesso',
                        ],
                    ],
                ],
                'attr' => ['class' => 'department-select'],
            
            ])
            ->add('cni')
            ->add('field', ChoiceType::class, [
                'choices' => [
                    'Création et Design Numerique' => 'Création et Design Numerique',
                    'Ingénierie des Systèmes Numériques' => 'Ingénierie des Systèmes Numériques',
                    'Ingénierie Numérique Sociotechnique' => 'Ingénierie Numérique Sociotechnique'
                ],
                'attr' => ['class' => 'field-select'],
            ])
            ->add('certificateType', ChoiceType::class, [
                'choices' => [
                        'Baccalauréat de l\'enseignement général' => [
                            'Baccalaureat A (Littéraire)' => 'Baccalaureat A (Littéraire)',
                            'Baccalaureat C (Mathématiques et Sciences Physiques)' => 'Baccalaureat C (Mathématiques et Sciences Physiques)',
                            'Baccalaureat D (Mathématiques et Sciences de la Nature)' => 'Baccalaureat D (Mathématiques et Sciences de la Nature)',
                            'Baccalaureat E (Mathématiques et Technique)' => 'Baccalaureat E (Mathématiques et Technique)',
                            'Baccalaureat TI (Techniques Industrielles)' => 'Baccalaureat TI (Techniques Industrielles)',
                        ],
                        'Baccalauréat de l\'enseignement technique' => [
                            'Baccalaureat F1 (Construction Mécanique)' => 'Baccalaureat F1 (Construction Mécanique)',
                            'Baccalaureat F2 (Électronique)' => 'Baccalaureat F2 (Électronique)',
                            'Baccalaureat F3 (Électrotechnique)' => 'Baccalaureat F3 (Électrotechnique)',
                            'Baccalaureat F4 (Génie Civil)' => 'Baccalaureat F4 (Génie Civil)',
                            'Baccalaureat F5 (Génie Chimique)' => 'Baccalaureat F5 (Génie Chimique)',
                        ],
                        'GCE Advanced Level' => [
                            'GCE A/L Sciences' => 'GCE A/L Sciences',
                            'GCE A/L Arts' => 'GCE A/L Arts',
                    ],
                ],
                
            ])->add('examinationCenter', ChoiceType::class, [
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
                        'maxSize' => '5120k',
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
                    'Français' => 'Français',
                    'Anglais' => 'Anglais',
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
                        'maxSize' => '5120k',
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
                'attr' => ['class' => 'nationality-select'],

            ])
            ->add('photo', FileType::class, [
                'label' => 'Photo (Image file)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5120k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image document',
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Candidate::class,
        ]);
    }
}
