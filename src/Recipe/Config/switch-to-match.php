<?php

use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Switch_\ChangeSwitchToMatchRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(ChangeSwitchToMatchRector::class);
};
