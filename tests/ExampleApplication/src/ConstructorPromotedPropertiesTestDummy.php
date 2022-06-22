<?php

declare(strict_types=1);

namespace PeonDogFood;

class ConstructorPromotedPropertiesTestDummy
{
    private $a;
    protected $b;
    public $c;
    public array $d = [];
    public null|array $e;

    public function __construct(
        string $a,
        $b,
        bool $c,
        array $d,
        null|array $e = [],
    ) {
        $this->a = $a;
        $this->b = $b;
        $this->c = $c;
        $this->d = $d;
        $this->e = $e;
    }
}
