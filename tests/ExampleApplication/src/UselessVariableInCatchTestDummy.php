<?php

declare(strict_types=1);

namespace PeonDogFood;

final class UselessVariableInCatchTestDummy
{
    public function uselessTryCatch(): void
    {
        try {
            return;
        } catch (\Throwable $throwable) {
        }
    }
}
