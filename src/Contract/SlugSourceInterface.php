<?php

namespace App\Contract;

interface SlugSourceInterface
{
    public function getSlugSource(): ?string;
    public function setSlug(string $slug): void;
}
