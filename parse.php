<?php

if (empty($argv[1])) {
    echo "Передайте имя файла для анализа\n";
    exit(1);
}

require_once 'Parser.php';
require_once 'Utils.php';

mb_internal_encoding('UTF-8');

$p = new Parser();
$d = $p->getData($argv[1]);
$u = new Utils();

foreach ($d as $type => $items) {
    $costs = [];
    foreach ($items as $item) {
        $date = $item['date'];
        $unit = $item['unit'];
        $size = $item['size'];
        list($unit, $size) = $u->convertData($unit, $size);
        if (!$item['size']) {
            continue;
        }
        if (!isset($costs[$date][$unit])) {
            $costs[$date][$unit] = [
                'size' => 0,
                'cost' => 0
            ];
        }
        $costs[$date][$unit]['cost'] += $item['cost'];
        $costs[$date][$unit]['size'] += $size;
    }
    if (!empty($costs)) {
        printf("%s:\n", $type);
        foreach ($costs as $date => $summary) {
            printf("  %s\n", $date);
            foreach ($summary as $costType => $costInfo) {
                if ((int) $costInfo['size'] != $costInfo['size']) {
                    $vType = '%.2f';
                } else {
                    $vType = '%d';
                }
                printf(
                    "    {$vType} %s на %.2f %s\n",
                    $costInfo['size'],
                    $u->convertUnit($costInfo['size'], $costType),
                    $costInfo['cost'],
                    $u->convertUnit($costInfo['cost'], 'рубль')
                );
            }
        }
    }
}