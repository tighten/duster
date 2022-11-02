<?php

namespace Tests\Fixtures\TlintProjectConfig;

use Tighten\TLint\Linters\SpacesAroundBladeRenderContent;
use Tighten\TLint\Linters\UseAuthHelperOverFacade;
use Tighten\TLint\Presets\PresetInterface;

class CustomPreset implements PresetInterface
{
  public function getLinters() : array
  {
    return [
      UseAuthHelperOverFacade::class,
      SpacesAroundBladeRenderContent::class,
    ];
  }

  public function getFormatters() : array
  {
    return [];
  }
}
