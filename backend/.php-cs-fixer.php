<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in([
        __DIR__ . '/app',
        __DIR__ . '/config',
        __DIR__ . '/database',
        __DIR__ . '/routes',
        __DIR__ . '/tests',
    ])
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

$rules = [
    '@PSR12' => true,
    'array_syntax' => ['syntax' => 'short'],
    'strict_param' => true,
    'no_unused_imports' => true,
    'single_quote' => true,
    'no_trailing_whitespace' => true,
    'no_extra_blank_lines' => true,
    'blank_line_after_namespace' => true,
    'blank_line_after_opening_tag' => true,
    'no_spaces_around_offset' => true,
];

return (new Config())
    ->setRules($rules)
    ->setFinder($finder)
    ->setRiskyAllowed(true)
    ->setUsingCache(true);
