<?php

declare(strict_types=1);

namespace Shlinkio\Shlink\Installer\Config\Option\Tracking;

use Shlinkio\Shlink\Config\Collection\PathCollection;
use Shlinkio\Shlink\Installer\Config\Option\ConfigOptionInterface;
use Shlinkio\Shlink\Installer\Config\Option\DependentConfigOptionInterface;

abstract class AbstractDisableTrackingDependentConfigOption implements
    ConfigOptionInterface,
    DependentConfigOptionInterface
{
    public function getDependentOption(): string
    {
        return DisableTrackingConfigOption::class;
    }

    public function shouldBeAsked(PathCollection $currentOptions): bool
    {
        $disableTracking = $currentOptions->getValueInPath(DisableTrackingConfigOption::CONFIG_PATH);
        return ! $disableTracking && ! $currentOptions->pathExists($this->getConfigPath());
    }
}
