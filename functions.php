<?php

function chance($percentage): bool
{
    return rand() % 100 < $percentage;
}

function progressBar($done, $total) 
{
    $perc = floor(($done / $total) * 100);
    $half = round($perc/2);
    $left = 50 - $half;
    $write = sprintf("\033[0G\033[2K[%'={$half}s>%-{$left}s] - $perc%% - $done/$total", "", "");
    fwrite(STDOUT, $write);
}
