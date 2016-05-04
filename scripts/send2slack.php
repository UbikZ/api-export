<?php

/*
 * Usage:
 * php send2slack.php "http://xx.com" "#xx" "xx" "https://xx/hook"
 */

main($argc, $argv);

function main($argc, $argv = array())
{
    try {
        $payload = [
            'channel' => $argv[2],
            'username' => $argv[3],
            'text' => 'Plop' . PHP_EOL,
            'icon_emoji' => ':underage:',
        ];
        $text = '';
        $apiUrl = $argv[1] . '/feed-item?approved=1&sent=0';
        $feeds = json_decode(file_get_contents($apiUrl), true);
        foreach ($feeds as $feed) {
            $text .= $feed['url'] . ' ' . $feed['comment'] . PHP_EOL;
        }
        $payload['text'] .= $text;
        $sendJson = json_encode($payload);
        var_dump($payload);
        exec("curl -X POST --data-urlencode 'payload=$sendJson' $argv[4]");
    } catch (\Exception $e) {
        echo $e->getMessage() . PHP_EOL;
        die;
    }
}