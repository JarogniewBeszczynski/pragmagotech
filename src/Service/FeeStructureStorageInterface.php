<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Service;

interface FeeStructureStorageInterface
{
    public function getAll();

    public function getOne(int $loanAmount): int;

    public function getUpperBreakPoint(): int;

    public function getLowerBreakPoint(): int;
}