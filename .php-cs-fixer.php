<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in(__DIR__ . '/app/src')
    ->in(__DIR__ . '/app/tests');

return (new Config())
    ->setRules([
        '@PSR12' => true,
        'no_superfluous_phpdoc_tags' => true,
        'phpdoc_trim' => true,
        'concat_space' => ['spacing' => 'none'],
        'types_spaces' => true,
    ])
    ->setFinder($finder);
