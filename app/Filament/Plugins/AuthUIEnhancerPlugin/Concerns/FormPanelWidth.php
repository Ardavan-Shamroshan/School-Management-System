<?php

namespace App\Filament\Plugins\AuthUIEnhancerPlugin\Concerns;

trait FormPanelWidth
{
    public string $formPanelWidth = '50%';

    public function formPanelWidth(string $width = '50%'): self
    {
        if (! $this->isValidWidth($width)) {
            throw new \InvalidArgumentException('Sizes must be expressed in rem, %, px, em, vw, vh, pt');
        }

        $this->formPanelWidth = $width;

        return $this;
    }

    protected function isValidWidth(string $formPanelWidth): bool
    {
        $pattern = '/^\d+(\.\d+)?(rem|%|px|em|vw|vh|pt)$/';

        return preg_match($pattern, $formPanelWidth) === 1;

    }

    public function getFormPanelWidth(): string
    {
        return $this->formPanelWidth;
    }
}
