<?php
function doku2md($str)
{
    $lines = explode("\n", $str);

    $patternAndReplacements = [
        '/====== (.*) ======/' => '# ${1}',
        '/===== (.*) =====/' => '## ${1}',
        '/==== (.*) ====/' => '### ${1}',
        '/=== (.*) ===/' => '#### ${1}',
        '/== (.*) ==/' => '##### ${1}',
    ];

    foreach ($lines as &$line) {
        foreach ($patternAndReplacements as $pattern => $replacement) {
            $line = preg_replace($pattern, $replacement, $line);
        }
    }

    return implode("\n", $lines);
}

