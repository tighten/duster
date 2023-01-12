<?php

declare(strict_types=1);

namespace App\Fixer\ClassNotation;

use PhpCsFixer\Indicator\PhpUnitTestCaseIndicator;
use PhpCsFixer\Tokenizer\Tokens;
use SplFileInfo;

class CustomPhpUnitOrderFixer extends CustomOrderedClassElementsFixer
{
    public function getName(): string
    {
        return 'Tighten/custom_phpunit_order';
    }

    public function configure(array $configuration): void
    {
        $configuration['order'] = [
            'use_trait',
            'property_public_static',
            'property_protected_static',
            'property_private_static',
            'constant_public',
            'constant_protected',
            'constant_private',
            'property_public',
            'property_protected',
            'property_private',
            'construct',
            'method:__invoke',
            'phpunit',
            'method_public_static',
            'method_protected_static',
            'method_private_static',
            'method_public',
            'method_protected',
            'method_private',
            'magic',
        ];

        parent::configure($configuration);
    }

    /**
     * {@inheritdoc}
     *
     * Must run after CustomOrderedClassElementsFixer.
     */
    public function getPriority(): int
    {
        return 64;
    }

    protected function applyFix(SplFileInfo $file, Tokens $tokens): void
    {
        $phpUnitTestCaseIndicator = new PhpUnitTestCaseIndicator;

        for ($index = $tokens->count() - 1; $index > 0; $index--) {
            if ($tokens[$index]->isGivenKind(T_CLASS) && $phpUnitTestCaseIndicator->isPhpUnitClass($tokens, $index)) {
                parent::applyFix($file, $tokens);
                break;
            }
        }
    }
}
