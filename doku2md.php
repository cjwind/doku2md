<?php
function doku2md($str)
{
    $patternAndReplacements = [
        '/====== (.*) ======/' => '# ${1}',
        '/===== (.*) =====/' => '## ${1}',
        '/==== (.*) ====/' => '### ${1}',
        '/=== (.*) ===/' => '#### ${1}',
        '/== (.*) ==/' => '##### ${1}',
    ];

    foreach ($patternAndReplacements as $pattern => $replacement) {
        $str = preg_replace($pattern, $replacement, $str);
    }

    return $str;
}

