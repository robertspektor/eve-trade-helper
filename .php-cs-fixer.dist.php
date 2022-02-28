<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__.'/app')
    ->in(__DIR__.'/tests')
    ->in(__DIR__.'/routes')
    ->in(__DIR__.'/config')
    ->in(__DIR__.'/database');

$config = new PhpCsFixer\Config();

return $config->setRules([
    '@Symfony' => true,
    'declare_strict_types' => true,
    'php_unit_method_casing' => ['case' => 'snake_case'],
    'array_syntax' => ['syntax' => 'short'],
    'yoda_style' => ['equal' => false, 'identical' => false, 'less_and_greater' => false],
    'concat_space' => ['spacing' => 'one'],
])->setFinder($finder);
