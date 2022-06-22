<?php

use Rector\Config\RectorConfig;
use Rector\Php80\Rector\FuncCall\ClassOnObjectRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(ClassOnObjectRector::class);
};
