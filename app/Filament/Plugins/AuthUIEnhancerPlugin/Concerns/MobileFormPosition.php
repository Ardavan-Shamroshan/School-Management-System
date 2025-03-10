<?php

namespace App\Filament\Plugins\AuthUIEnhancerPlugin\Concerns;

trait MobileFormPosition
{
    public string $mobileFormPanelPosition = 'top';

    public function mobileFormPanelPosition(string $position = 'top'): self
    {
        if (! in_array($position, ['top', 'bottom'])) {
            throw new \InvalidArgumentException("Form position must be 'top' or 'bottom'.");
        }

        $this->mobileFormPanelPosition = $position;

        return $this;
    }

    public function getMobileFormPanelPosition(): string
    {
        return $this->mobileFormPanelPosition;
    }
}
