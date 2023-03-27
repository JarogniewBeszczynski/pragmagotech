<?php

declare(strict_types=1);


namespace PragmaGoTech\Interview;

use PragmaGoTech\Interview\Model\LoanProposal;
use PragmaGoTech\Interview\Service\ArrayFeeStructureStorage;
use PragmaGoTech\Interview\Service\FeeStructureStorageInterface;

class FeeCalculator
{

    private const FEE_STRUCTURE_FILE = __DIR__ . '/fee_structure.php';

    /**
     * @var ArrayFeeStructureStorage
     */
    private $feeStructure;

    /**
     *
     */
    public function __construct()
    {
        $this->feeStructure = new ArrayFeeStructureStorage();
    }

    /**
     * @param LoanProposal $loanProposal
     * @return float
     */
    public function calculate(LoanProposal $loanProposal): int
    {
        $amount = $loanProposal->amount();
        $term = $loanProposal->term();

        if ($amount < 1000 || $amount > 20000) {
            throw new \InvalidArgumentException('Loan amount must be between 1000 and 20000 PLN.');
        }

        if ($term !== 12 && $term !== 24) {
            throw new \InvalidArgumentException('Term must be either 12 or 24 months.');
        }

        $this->feeStructure->setTerm($term);
        $this->feeStructure->matchAmountToBreakPoints($amount);
        if(!$this->feeStructure->hasRequestedAmountMatchedBreakPoint()){
            $fee = $this->interpolateFee($amount, $this->feeStructure);
        } else {
            $fee = $this->feeStructure->getOne(intval($amount));
        }

        return $this->ceilUpToMultiple($fee+$amount, 5);
    }

    /**
     * @param float $requestedAmount
     * @param FeeStructureStorageInterface $feeStructure
     * @return float
     */
    private function interpolateFee(float $requestedAmount, FeeStructureStorageInterface $feeStructure): float
    {
        $feeRange = $feeStructure->getOne($feeStructure->getUpperBreakPoint()) - $feeStructure->getOne($feeStructure->getLowerBreakPoint());
        $amountRange = $feeStructure->getUpperBreakPoint() - $feeStructure->getLowerBreakPoint();
        $amountDiff = $requestedAmount - $feeStructure->getLowerBreakPoint();
        $slope = $feeRange / $amountRange;

        return $feeStructure->getOne($feeStructure->getLowerBreakPoint()) + $slope * $amountDiff;
    }

    /**
     * @param float $value
     * @param int $multiple
     * @return int
     */
    private function ceilUpToMultiple(float $value, int $multiple): int
    {
        if ($value % $multiple == 0) {
            return intval($value);
        }

        return intval(ceil($value / $multiple) * $multiple);
    }
}