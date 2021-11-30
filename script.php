<?php

require __DIR__ . '/vendor/screenshotmachine/screenshotmachine-php/ScreenshotMachine.php';

$customer_key = "86bff0";
$secret_phrase = "";

$machine = new ScreenshotMachine($customer_key, $secret_phrase);

function slugify($text, $divider = '-')
{
    // replace non letter or digits by divider
    $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);

    // trim
    $text = trim($text, $divider);

    // remove duplicate divider
    $text = preg_replace('~-+~', $divider, $text);

    // lowercase
    $text = strtolower($text);

    if (empty($text)) {
        return 'n-a';
    }

    return $text;
}

$urls = array();

$file = fopen('Classeur1.csv', 'r');
$cpt = 0;
while (($line = fgetcsv($file)) !== FALSE) {
    $line = explode(';', $line[0]);
    $urls[$cpt]['title'] = slugify($line[0]);
    $urls[$cpt]['url'] = $line[1];
    $urls[$cpt]['dimension'] = "1920xfull";
    $urls[$cpt]['device'] = "desktop";
    $urls[$cpt]['format'] = "png";
    $urls[$cpt]['cacheLimit'] = "0";
    $urls[$cpt]['delay'] = "1000";
    $urls[$cpt]['zoom'] = "100";

    $api_url = $machine->generate_screenshot_api_url($urls[$cpt]);
    $output_file = 'img/'.$urls[$cpt]['title'].'.png';
    file_put_contents($output_file, file_get_contents($api_url));
    echo 'Screenshot saved as ' . $output_file . PHP_EOL;

    $cpt++;
}
fclose($file);

