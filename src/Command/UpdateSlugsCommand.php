<?php

namespace App\Command;

use App\Entity\User;
use App\Entity\Band;
use App\Entity\Musician;
use App\Entity\Album;
use App\Entity\Song;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:update-slugs',
    description: 'Regenerate slugs for entities using SlugSourceInterface.',
)]
class UpdateSlugsCommand extends Command
{
    public function __construct(private EntityManagerInterface $em)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $entities = [
            User::class,
            Band::class,
            Musician::class,
            Album::class,
            Song::class,
        ];

        foreach ($entities as $entityClass) {
            $repository = $this->em->getRepository($entityClass);
            $items = $repository->findAll();

            foreach ($items as $item) {
                // Force Doctrine to consider it as "changed"
                $this->em->persist($item);
            }

            $output->writeln("Updated slugs for: $entityClass");
        }

        $this->em->flush();

        $output->writeln('<info>Slugs updated successfully.</info>');

        return Command::SUCCESS;
    }
}
