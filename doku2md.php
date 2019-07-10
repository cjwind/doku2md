<?php
function doku2md($str)
{
    $lines = explode("\n", $str);

    foreach ($lines as &$line) {
        $line = convertHeader($line);
        $line = convertItalic($line);
        $line = convertMonospaced($line);
        $line = convertCodeBlock($line);

        if (isUnorderedList($line)) {
            $line = convertUnorderedList($line);
        }

        if (isOrderedList($line)) {
            $line = convertOrderedList($line);
        }
    }

    return implode("\n", $lines);
}

function isUnorderedList($line) {
    return (preg_match('/^  ( *)*/', $line) > 0);
}

function isOrderedList($line) {
    return (preg_match('/^  ( -)*/', $line) > 0);
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

function convertMonospaced($line)
{
    $line = str_replace('\'\'%%', '`', $line);
    $line = str_replace('%%\'\'', '`', $line);
    $line = str_replace('\'\'', '`', $line);
    return $line;
}

function convertCodeBlock($line)
{
    $line = preg_replace('/<code( *)(.*)>/', '```${2}', $line);
    $line = str_replace('</code>', '```', $line);
    $line = preg_replace('/<sxh( *)(.*)>/', '```${2}', $line);
    $line = str_replace('</sxh>', '```', $line);
    return $line;
}

if ($argc >= 3) {
    $inputFile = $argv[1];
    $outputFile = $argv[2];

    $fp = fopen($inputFile, 'r');
    $content = fread($fp, filesize($inputFile));
    fclose($fp);

    $fp = fopen($outputFile, 'w');
    fwrite($fp, doku2md($content));
    fclose($fp);
} else {
    echo 'php ' . $argv[0] . " <input file> <output file>\n";
}