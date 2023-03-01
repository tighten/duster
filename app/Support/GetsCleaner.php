<?php

namespace App\Support;

use App\Actions\Clean;

trait GetsCleaner
{
    protected function getCleaner(string $mode, ?string $using): Clean
    {
        $using = $using
            ? explode(',', $using)
            : ['tlint', 'phpcs', 'php-cs-fixer', 'pint', ...array_keys(DusterConfig::loadLocal()['scripts'][$mode] ?? [])];

        $tools = collect($using)
            ->map(fn ($using): Tool => match (trim($using)) {
                'tlint' => resolve(TLint::class),
                'phpcs',
                'phpcodesniffer',
                'php-code-sniffer' => resolve(PhpCodeSniffer::class),
                'php-cs-fixer',
                'phpcsfixer' => resolve(PhpCsFixer::class),
                'pint' => resolve(Pint::class),
                default => $this->userScript($mode, $using),
            })
            ->filter()
            ->unique()
            ->toArray();

        return new Clean(
            mode: $mode,
            tools: $tools
        );
    }

    protected function userScript(string $mode, string $scriptName): ?UserScript
    {
        $userScript = DusterConfig::loadLocal()['scripts'][$mode][$scriptName] ?? null;

        return $userScript
            ? new UserScript($scriptName, $userScript, resolve(DusterConfig::class))
            : null;
    }
}
