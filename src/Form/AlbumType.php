<?php

namespace App\Form;

use App\Entity\Band;
use App\Entity\Genre;
use App\Entity\Album;
use App\Entity\Musician;
use App\Entity\Song;
use App\Repository\GenreRepository;
use App\Repository\SongRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class AlbumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Album Title',
                'attr' => ['class' => 'form-input']
            ])
            ->add('release_date', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'label' => 'Release Date',
                'html5' => true,
            ])
            ->add('duration', IntegerType::class, [
                'required' => false,
                'label' => 'Duration (seconds)',
                'attr' => [
                    'min' => 1,
                    'placeholder' => 'e.g. 3600 for 1 hour',
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => ['class' => 'form-textarea', 'rows' => 5]
            ])
            ->add('links', CollectionType::class, [
                'label' => 'Links',
                'required' => false,
                'entry_type' => UrlType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,
                'entry_options' => [
                    'attr' => [
                        'placeholder' => 'https://example.com',
                    ],
                    'constraints' => [
                        new Url([
                            'message' => 'Please enter a valid URL.',
                        ]),
                        new Regex([
                            'pattern' => '/^https?:\/\/[\w\-]+(\.[\w\-]+)+.*$/i',
                            'message' => 'The URL must contain a valid domain name (e.g., example.com).',
                        ]),
                    ],
                ],
            ])
            ->add('coverImageFile', FileType::class, [
                'label' => 'Cover Image',
                'required' => false,
                'mapped' => false
            ])
            ->add('genres', EntityType::class, [
                'class' => Genre::class,
                'label' => 'Genres',
                'choice_label' => 'displayName',
                'multiple' => true,
                'expanded' => false,
                'required' => false,
                'attr' => [
                    'class' => 'genre-select'
                ],
                'query_builder' => function (GenreRepository $repo) {
                    return $repo->createQueryBuilder('g')
                        ->orderBy('g.name', 'ASC');
                },
            ])
            ->add('bands', EntityType::class, [
                'class'        => Band::class,
                'choice_label' => 'name',
                'multiple'     => true,
                'required'     => false,
                'attr'         => [
                    'class' => 'band-select',
                ],
                'by_reference' => false,
                'choice_attr'  => function (Band $b) {
                    $raw = $b->getCoverImage();
                    if ($raw && preg_match('/^https?:\/\//', $raw)) {
                        $coverUrl = $raw;
                    } else {
                        $coverUrl = $raw
                            ? '/uploads/bands/' . $raw
                            : '/uploads/bands/default.png';
                    }
                    return [
                        'data-cover' => $coverUrl,
                    ];
                },
                'query_builder' => fn($repo) => $repo
                    ->createQueryBuilder('b')
                    ->orderBy('b.name', 'ASC'),
            ])
            ->add('musicians', EntityType::class, [
                'class'        => Musician::class,
                'choice_label' => 'name',
                'multiple'     => true,
                'required'     => false,
                'by_reference' => false,
                'attr'         => [
                    'class' => 'musician-select',
                ],
                'choice_attr'  => function (Musician $m) {
                    $raw = $m->getCoverImage();
                    if ($raw && preg_match('/^https?:\/\//', $raw)) {
                        $coverUrl = $raw;
                    } else {
                        $coverUrl = $raw
                            ? '/uploads/musicians/' . $raw
                            : '/uploads/musicians/default.png';
                    }
                    return [
                        'data-cover' => $coverUrl,
                    ];
                },
                'query_builder' => fn($repo) => $repo
                    ->createQueryBuilder('m')
                    ->orderBy('m.name', 'ASC'),
            ])
            ->add('songs', EntityType::class, [
                'class'         => Song::class,
                'choice_label'  => fn(Song $song) => $song->getTitle(),
                'multiple'      => true,
                'mapped'        => true,
                'by_reference'  => false,
                'required'      => false,
                'query_builder' => fn(SongRepository $r) => $r->createQueryBuilder('s')->orderBy('s.title', 'ASC'),
                'attr' => [
                    'class' => 'song-autocomplete w-full rounded-md bg-gray-900 text-white',
                    'data-autocomplete-url' => ''
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {

        $resolver->setDefaults([
            'data_class' => Album::class,
        ]);
    }
}
