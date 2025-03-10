<?php

namespace App\Filament\Plugins\AuthUIEnhancerPlugin\Concerns;

trait FormPosition
{
    public string $formPanelPosition = 'right';

    public function formPanelPosition(string $position = 'right'): self
    {
        if (! in_array($position, ['left', 'right'])) {
            throw new \InvalidArgumentException("Form position must be 'left' or 'right'.");
        }

        $this->formPanelPosition = $position;

        return $this;
    }

    public function getFormPanelPosition(): string
    {
        return $this->formPanelPosition;
    }
}
