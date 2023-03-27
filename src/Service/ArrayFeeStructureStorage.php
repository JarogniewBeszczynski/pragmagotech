<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Service;

class ArrayFeeStructureStorage implements FeeStructureStorageInterface
{
    /**
     *
     */
    private const FEE_STRUCTURE_FILE = __DIR__ . '/../fee_structure.php';
    /**
     * @var array|mixed
     */
    private array $wholeArray;

    /**
     * @var int
     */
    private int $term;

    /**
     * @var int
     */
    private int $lowerBreakPoint;
    /**
     * @var int
     */
    private int $upperBreakPoint;

    /**
     * @var bool
     */
    private bool $requestedAmountMatchedBreakPoint;

    /**
     *
     */
    public function __construct()
    {
        $this->wholeArray = require self::FEE_STRUCTURE_FILE;
        $this->requestedAmountMatchedBreakPoint = false;
    }

    /**
     * @return array|mixed
     */
    public function getAll()
    {
        return $this->wholeArray;
    }

    /**
     * @param int $loanAmount
     * @return int
     */
    public function getOne(int $loanAmount): int
    {
        return $this->getAll()[$loanAmount];
    }

    /**
     * @param float $requestedAmount
     * @return void
     */
    public function matchAmountToBreakPoints(float $requestedAmount): void
    {
        $fee = 0;
        $range = [
            'lower' => 0,
            'upper' => 0
        ];
        $feeStructure = $this->getAll();
        $i = 0;
        $keys = array_keys($feeStructure);
        foreach ($feeStructure as $breakPointAmount => $breakPointFee) {
            if ($breakPointAmount == $requestedAmount) {
                $this->setRequestedAmountMatchedBreakPoint();
                break;
            }
            if ($breakPointAmount < $requestedAmount && $keys[$i + 1] > $requestedAmount) {
                $this->setLowerBreakPoint($breakPointAmount);
                $this->setUpperBreakPoint($keys[$i + 1]);
                break;
            }
            $i++;
        }
    }

    /**
     * @param int $term
     * @return void
     */
    public function setTerm(int $term): void
    {
        $this->wholeArray = $this->wholeArray[$term];
    }

    /**
     * @return void
     */
    private function setRequestedAmountMatchedBreakPoint(): void
    {
        $this->requestedAmountMatchedBreakPoint = true;
    }

    /**
     * @return bool
     */
    public function hasRequestedAmountMatchedBreakPoint(): bool
    {
        return $this->requestedAmountMatchedBreakPoint;
    }

    /**
     * @return int
     */
    public function getLowerBreakPoint(): int
    {
        return $this->lowerBreakPoint;
    }

    /**
     * @return int
     */
    public function getUpperBreakPoint(): int
    {
        return $this->upperBreakPoint;
    }


    /**
     * @param int $lowerBreakPoint
     */
    public function setLowerBreakPoint(int $lowerBreakPoint): void
    {
        $this->lowerBreakPoint = $lowerBreakPoint;
    }

    /**
     * @param int $upperBreakPoint
     */
    public function setUpperBreakPoint(int $upperBreakPoint): void
    {
        $this->upperBreakPoint = $upperBreakPoint;
    }
}