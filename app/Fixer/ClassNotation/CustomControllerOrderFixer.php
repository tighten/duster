<?php

declare(strict_types=1);

namespace App\Fixer\ClassNotation;

use LogicException;
use PhpCsFixer\Tokenizer\Tokens;
use SplFileInfo;

class CustomControllerOrderFixer extends CustomOrderedClassElementsFixer
{
    public function getName(): string
    {
        return 'Tighten/custom_controller_order';
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
            'method_public_static',
            'method_protected_static',
            'method_private_static',
            'method:index',
            'method:create',
            'method:store',
            'method:show',
            'method:edit',
            'method:update',
            'method:destroy',
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

    public function isControllerClass(Tokens $tokens, int $index): bool
    {
        if (! $tokens[$index]->isGivenKind(T_NAMESPACE)) {
            throw new LogicException(sprintf('No "T_NAMESPACE" at given index %d, got "%s".', $index, $tokens[$index]->getName()));
        }

        $extendsIndex = $tokens->getNextTokenOfKind($index, ['{', [T_EXTENDS]]);

        if (! $tokens[$extendsIndex]->isGivenKind(T_EXTENDS)) {
            return false;
        }

        $namespace = [];
        while (null !== $index = $tokens->getNextMeaningfulToken($index)) {
            if ($tokens[$index]->equals(';')) {
                break; // end of namespace
            }

            $namespace[] = $tokens[$index]->getContent();
        }

        if (implode('', $namespace) !== 'App\Http\Controllers') {
            return false;
        }

        $index = $extendsIndex;

        while (null !== $index = $tokens->getNextMeaningfulToken($index)) {
            if ($tokens[$index]->equals('{')) {
                break; // end of class signature
            }

            if (! $tokens[$index]->isGivenKind(T_STRING)) {
                continue; // not part of extends nor part of implements; so continue
            }

            if ($tokens[$index]->getContent() === 'Controller') {
                return true;
            }
        }

        return false;
    }

    protected function applyFix(SplFileInfo $file, Tokens $tokens): void
    {
        for ($index = $tokens->count() - 1; $index > 0; $index--) {
            if ($tokens[$index]->isGivenKind(T_NAMESPACE) && $this->isControllerClass($tokens, $index)) {
                parent::applyFix($file, $tokens);
                break;
            }
        }
    }
}
