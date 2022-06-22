<?php

use Rector\Config\RectorConfig;
use Rector\Php74\Rector\Property\TypedPropertyRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(TypedPropertyRector::class);
};
