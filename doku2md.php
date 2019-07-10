<?php
function doku2md($str)
{
    $lines = explode("\n", $str);

    foreach ($lines as &$line) {
        $line = convertHeader($line);
        $line = convertItalic($line);
        $line = convertCodeBlock($line);

        $line = convertUnorderedList($line);
        $line = convertOrderedList($line);
    }

    return implode("\n", $lines);
}

function convertUnorderedList($line): string
{
    $line = preg_replace('/  \*( *)(.*)/', '* ${2}', $line);
    $spaceCount = strpos($line, '*');
    return str_repeat(' ', $spaceCount) . $line;
}

function convertOrderedList($line): string
{
    $line = preg_replace('/  -( *)(.*)/', '1. ${2}', $line);
    $spaceCount = strpos($line, '1. ');
    return str_repeat(' ', $spaceCount) . $line;
}

function convertHeader($line)
{
    $patternAndReplacements = [
        '/====== (.*) ======/' => '# ${1}',
        '/===== (.*) =====/' => '## ${1}',
        '/==== (.*) ====/' => '### ${1}',
        '/=== (.*) ===/' => '#### ${1}',
        '/== (.*) ==/' => '##### ${1}',
    ];

    foreach ($patternAndReplacements as $pattern => $replacement) {
        $line = preg_replace($pattern, $replacement, $line);
    }

    return $line;
}

function convertItalic($line)
{
    return preg_replace('/\/\/(.*)\/\//', '*${1}*', $line);
}

function convertCodeBlock($line)
{
    $line = preg_replace('/<code( *)(.*)>/', '```${2}', $line);
    $line = str_replace('</code>', '```', $line);
    return $line;
}

