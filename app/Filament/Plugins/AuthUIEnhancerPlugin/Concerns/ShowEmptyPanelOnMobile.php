<?php

namespace App\Filament\Plugins\AuthUIEnhancerPlugin\Concerns;

trait ShowEmptyPanelOnMobile
{
    public bool $showEmptyPanelOnMobile = true;

    public function showEmptyPanelOnMobile(bool $show = true): self
    {

        $this->showEmptyPanelOnMobile = $show;

        return $this;
    }

    public function getShowEmptyPanelOnMobile(): bool
    {
        return $this->showEmptyPanelOnMobile;
    }
}
