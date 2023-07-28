<?php

namespace App\Form;

use App\Entity\Trick;
use App\Entity\Trickgroup;
use App\Repository\TrickRepository;
use App\Repository\TrickgroupRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CreateTrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'placeholder' => 'Nom de la figure',
                    'class' =>  'form-control mb-3'
                ],
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'Description de la figure',
                    'class' =>  'form-control mb-3'
                ],
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('picture', FileType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File (['maxSize' => '2M'
                    ])
                ]
            ])
            ->add('video', FileType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File (['maxSize' => '20M'
                    ])
                ]
            ])
            ->add('trick_group', EntityType::class, [
                'class' => Trickgroup::class,
                'multiple' => false,
                'expanded' => false,
                'choice_label' => fn(Trickgroup $trickgroup) => $trickgroup->getName(),
                'query_builder' => 
                    fn(TrickgroupRepository $trickgroupRepository) 
                        => $trickgroupRepository->createQueryBuilder('tg')
                        ->orderBy('tg.name', 'ASC'),
                'attr' => [
                    'class' => 'mb-3']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
