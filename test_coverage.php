<?php

require __DIR__.'/vendor/autoload.php';
$coverage = require_once __DIR__.'/coverage_report.php';
$report = $coverage->getReport();
$lines  = $report->getNumExecutableLines();
$linesExec  = $report->getNumExecutedLines();
$coveragePercent = $linesExec / $lines;

echo "Coverage is ".((int)($coveragePercent*100))."%\n";

if ($coveragePercent < 1.00) {
    exit(1);
}

exit(0);
