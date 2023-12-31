<?php

namespace App\Form;

use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\Video;
use App\Form\VideoType;
use App\Entity\Trickgroup;
use App\Repository\TrickgroupRepository;
use App\Repository\VideoRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class TrickType extends AbstractType
{
    public function __construct(
        private RequestStack $requestStack
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if ($this->requestStack->getCurrentRequest()->get("_route") !== "trick_edit") {

            $builder
                ->add('name', TextType::class, [
                    'attr' => [
                        'placeholder' => 'Nom de la figure',
                        'class' =>  'form-control mb-3'
                    ],
                    'constraints' => [
                        new NotBlank()
                    ]
                ]);
        }
        $builder
            ->add('description', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'Description de la figure',
                    'class' =>  'form-control mb-3'
                ],
                'constraints' => [
                    new NotBlank()
                ]
            ])

            ->add('trick_group', EntityType::class, [
                'class' => Trickgroup::class,
                'multiple' => false,
                'expanded' => false,
                'choice_label' => fn (Trickgroup $trickgroup) => $trickgroup->getName(),
                'query_builder' =>
                fn (TrickgroupRepository $trickgroupRepository)
                => $trickgroupRepository->createQueryBuilder('tg')
                    ->orderBy('tg.name', 'ASC'),
                'attr' => [
                    'class' => 'mb-3'
                ]
            ])
            ->add('images', CollectionType::class, [
                'entry_type' => ImageType::class,
                'entry_options' => [
                    'label' => false,
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' =>
                function (Image $image = null) {
                    return null === $image || empty($image->getName());
                },
                'by_reference' => false,
                'label' => false,
                'mapped' => false,
            ])
            ->add('videos', CollectionType::class, [
                'entry_type' => VideoType::class,
                'entry_options' => [
                    'label' => false,
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' =>
                function (Video $video = null) {
                    return null === $video || empty($video->getName());
                },
                'by_reference' => false,
                'label' => false,
                'mapped' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
            'attr' => ['novalidate' => 'novalidate'],
        ]);
    }
}
