<?php

namespace App\EventListener;

use App\Contract\SlugSourceInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class SlugListener
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $this->handle($args);
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $this->handle($args);
    }

    private function handle(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof SlugSourceInterface) {
            return;
        }

        $source = $entity->getSlugSource();
        if (!$source) {
            return;
        }

        $slug = $this->slugger->slug($source)->lower();
        $entity->setSlug($slug);
    }
}
