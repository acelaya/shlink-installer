<?php

declare(strict_types=1);

namespace Shlinkio\Shlink\Installer\Config\Option\UrlShortener;

use Shlinkio\Shlink\Config\Collection\PathCollection;
use Shlinkio\Shlink\Installer\Config\Option\BaseConfigOption;
use Symfony\Component\Console\Style\StyleInterface;

class ShortDomainSchemaConfigOption extends BaseConfigOption
{
    public function getConfigPath(): array
    {
        return ['url_shortener', 'domain', 'schema'];
    }

    public function ask(StyleInterface $io, PathCollection $currentOptions): string
    {
        return $io->choice('Select schema for generated short URLs', ['http', 'https'], 'http');
    }
}
