<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function __construct(private string $publicPath)
    {
        $this->publicPath = $publicPath;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('file_exists_in_public', [$this, 'fileExistsInPublic']),
        ];
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('duration_format', [$this, 'formatDuration']),
        ];
    }
    public function fileExistsInPublic(string $relativePath): bool
    {
        // Construct full path under public directory
        $fullPath = $this->publicPath . DIRECTORY_SEPARATOR . ltrim($relativePath, DIRECTORY_SEPARATOR);

        return is_file($fullPath) && file_exists($fullPath);
    }

    public function formatDuration(int $seconds): string
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $remainingSeconds = $seconds % 60;

        if ($hours > 0) {
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $remainingSeconds);
        }

        return sprintf('%02d:%02d', $minutes, $remainingSeconds);
    }
}