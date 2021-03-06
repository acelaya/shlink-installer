<?php

declare(strict_types=1);

namespace Shlinkio\Shlink\Installer\Config\Option;

use Shlinkio\Shlink\Config\Collection\PathCollection;

abstract class AbstractWithDeprecatedPathConfigOption extends BaseConfigOption
{
    abstract protected function getDeprecatedPath(): array;

    public function shouldBeAsked(PathCollection $currentOptions): bool
    {
        // If the config contains the deprecated path, set its value in the new path, and unset the deprecated one
        $deprecatedPath = $this->getDeprecatedPath();
        if ($currentOptions->pathExists($deprecatedPath)) {
            $currentOptions->setValueInPath($currentOptions->getValueInPath($deprecatedPath), $this->getConfigPath());
            $currentOptions->unsetPath($deprecatedPath);
        }

        return parent::shouldBeAsked($currentOptions);
    }
}
