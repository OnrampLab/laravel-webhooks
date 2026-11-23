<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Empty_\SimplifyEmptyCheckOnEmptyArrayRector;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector;
use Rector\EarlyReturn\Rector\If_\ChangeIfElseValueAssignToEarlyReturnRector;
use Rector\Php74\Rector\Property\RestoreDefaultNullToNullableTypePropertyRector;
use Rector\TypeDeclaration\Rector\StmtsAwareInterface\SafeDeclareStrictTypesRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withCache('./build/cache/rector')
    // Keep the PHP 7.4 level: src/ must stay installable on PHP 8.0.
    ->withPhpSets(php74: true)
    // codeQuality already includes InlineConstructorDefaultToPropertyRector.
    ->withPreparedSets(codeQuality: true, deadCode: true)
    // Rules added after rector 0.15 that flag existing code. Skipped so the
    // Laravel 13 compatibility release does not change runtime behaviour
    // (strict_types changes scalar coercion); revisit in a separate change.
    ->withSkip([
        SafeDeclareStrictTypesRector::class,
        RestoreDefaultNullToNullableTypePropertyRector::class,
        RemoveUselessParamTagRector::class,
        SimplifyEmptyCheckOnEmptyArrayRector::class,
        ChangeIfElseValueAssignToEarlyReturnRector::class,
    ]);
