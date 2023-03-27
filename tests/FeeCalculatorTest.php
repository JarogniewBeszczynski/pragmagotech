<?php

declare(strict_types=1);


namespace PragmaGoTech\tests;

use PHPUnit\Framework\TestCase;
use PragmaGoTech\Interview\Model\LoanProposal;
use PragmaGoTech\Interview\FeeCalculator;
use InvalidArgumentException;

class FeeCalculatorTest extends TestCase
{
    private FeeCalculator $calculator;

    protected function setUp(): void
    {
        $this->calculator = new FeeCalculator();
    }

    public function testFeeForAmountWithinRangeAndTerm12Months(): void
    {
        $loanAmount = 4000.50;
        $term = 12;
        $application = new LoanProposal($term, $loanAmount);
        $fee = $this->calculator->calculate($application);
        $this->assertEquals(4115.0, $fee);
    }

    public function testFeeForAmountWithinRangeAndTerm24Months(): void
    {
        $loanAmount = 15000.00;
        $term = 24;
        $loanProposal = new LoanProposal($term, $loanAmount);
        $fee = $this->calculator->calculate($loanProposal);
        $this->assertEquals(15600.0, $fee);
    }

    public function testFeeForAmountBelowMinimum(): void
    {
        $loanAmount = 999.99;
        $term = 12;
        $loanProposal = new LoanProposal($term, $loanAmount);
        $this->expectException(InvalidArgumentException::class);
        $this->calculator->calculate($loanProposal);
    }

    public function testFeeForAmountAboveMaximum(): void
    {
        $loanAmount = 20000.01;
        $term = 24;
        $loanProposal = new LoanProposal($term, $loanAmount);
        $this->expectException(InvalidArgumentException::class);
        $this->calculator->calculate($loanProposal);
    }

    public function testFeeForInvalidTerm(): void
    {
        $loanAmount = 5000.0;
        $term = 6;
        $loanProposal = new LoanProposal($term, $loanAmount);
        $this->expectException(InvalidArgumentException::class);
        $this->calculator->calculate($loanProposal);
    }

    public function testFeeForAmountEqualToMinimumAndTerm12Months(): void
    {
        $loanAmount = 1000.0;
        $term = 12;
        $loanProposal = new LoanProposal($term, $loanAmount);
        $fee = $this->calculator->calculate($loanProposal);
        $this->assertEquals(1050.0, $fee);
    }

    public function testFeeForAmountEqualToMinimumAndTerm24Months(): void
    {
        $loanAmount = 1000.0;
        $term = 24;
        $loanProposal = new LoanProposal($term, $loanAmount);
        $fee = $this->calculator->calculate($loanProposal);
        $this->assertEquals(1070.0, $fee);
    }

    public function testFeeForAmountEqualToMaximumAndTerm12Months(): void
    {
        $loanAmount = 20000.0;
        $term = 12;
        $loanProposal = new LoanProposal($term, $loanAmount);
        $fee = $this->calculator->calculate($loanProposal);
        $this->assertEquals(20400.0, $fee);
    }

    public function testFeeForAmountEqualToMaximumAndTerm24Months(): void
    {
        $loanAmount = 20000.0;
        $term = 24;
        $loanProposal = new LoanProposal($term, $loanAmount);
        $fee = $this->calculator->calculate($loanProposal);
        $this->assertEquals(20800.0, $fee);
    }

    public function testFeeForAmountInBetweenBreakpointsAndTerm12Months(): void
    {
        $loanAmount = 4500.0;
        $term = 12;
        $loanProposal = new LoanProposal($term, $loanAmount);
        $fee = $this->calculator->calculate($loanProposal);
        $this->assertEquals(4610.0, $fee);
    }

    public function testFeeForAmountInBetweenBreakpointsAndTerm24Months(): void
    {
        $loanAmount = 7500.50;
        $term = 24;
        $calculator = new FeeCalculator();
        $application = new LoanProposal($term, $loanAmount);
        $fee = $calculator->calculate($application);
        $this->assertEquals(7800.0, $fee);
    }

    public function testFeeForAmountEqualToMinimumAmountAndTerm24Months(): void
    {
        $loanAmount = 1000.0;
        $term = 24;
        $calculator = new FeeCalculator();
        $application = new LoanProposal($term, $loanAmount);
        $fee = $calculator->calculate($application);
        $this->assertEquals(1070.0, $fee);
    }

    public function testFeeForAmountEqualToMaximumAmountAndTerm24Months(): void
    {
        $loanAmount = 20000.0;
        $term = 24;
        $calculator = new FeeCalculator();
        $application = new LoanProposal($term, $loanAmount);
        $fee = $calculator->calculate($application);
        $this->assertEquals(20800, $fee);
    }
}