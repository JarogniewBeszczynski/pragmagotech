<?php

declare(strict_types=1);

require (__DIR__. '/vendor/autoload.php');

use PragmaGoTech\Interview\Model\LoanProposal;
use PragmaGoTech\Interview\FeeCalculator;


$calculator = new FeeCalculator();
$app = new LoanProposal(12, 4500);
$fee = $calculator->calculate($app);
var_dump($fee);