<?php

declare(strict_types=1);

namespace Shlinkio\Shlink\Installer\Config\Plugin;

use Shlinkio\Shlink\Installer\Config\Util\ExpectedConfigResolverInterface;
use Shlinkio\Shlink\Installer\Model\CustomizableAppConfig;
use Shlinkio\Shlink\Installer\Util\AskUtilsTrait;
use Symfony\Component\Console\Style\SymfonyStyle;

use function array_diff;
use function array_keys;
use function count;
use function Functional\contains;

class UrlShortenerConfigCustomizer implements ConfigCustomizerInterface
{
    use AskUtilsTrait;

    public const SCHEMA = 'SCHEMA';
    public const HOSTNAME = 'HOSTNAME';
    public const CHARS = 'CHARS';
    public const VALIDATE_URL = 'VALIDATE_URL';
    private const ALL_EXPECTED_KEYS = [
        self::SCHEMA,
        self::HOSTNAME,
        self::VALIDATE_URL,
    ];

    /** @var array */
    private $expectedKeys;

    public function __construct(ExpectedConfigResolverInterface $resolver)
    {
        $this->expectedKeys = $resolver->resolveExpectedKeys(__CLASS__, self::ALL_EXPECTED_KEYS);
    }

    public function process(SymfonyStyle $io, CustomizableAppConfig $appConfig): void
    {
        $urlShortener = $appConfig->getUrlShortener();
        $doImport = $appConfig->hasUrlShortener();
        $keysToAskFor = $doImport ? array_diff($this->expectedKeys, array_keys($urlShortener)) : $this->expectedKeys;

        if (empty($keysToAskFor)) {
            return;
        }

        $io->title('URL SHORTENER');
        foreach ($keysToAskFor as $key) {
            $urlShortener[$key] = $this->ask($io, $key);
        }
        $appConfig->setUrlShortener($urlShortener);
    }

    private function ask(SymfonyStyle $io, string $key)
    {
        switch ($key) {
            case self::SCHEMA:
                return $io->choice(
                    'Select schema for generated short URLs',
                    ['http', 'https'],
                    'http'
                );
            case self::HOSTNAME:
                return $this->askRequired($io, 'domain', 'Default domain for generated short URLs');
            case self::VALIDATE_URL:
                return $io->confirm('Do you want to validate long urls by 200 HTTP status code on response');
        }

        return '';
    }
}
