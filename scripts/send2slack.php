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
            'text' => '',
            'icon_emoji' => ':underage:',
        ];
        $apiUrl = $argv[1] . '/feed-item?approved=1&sent=0';
        $feeds = json_decode(file_get_contents($apiUrl), true);
        $count = count($feeds),
        $text = 'Plop bitches (*' . $count . ' links*)' . PHP_EOL;
        if ($count > 0) {
            foreach ($feeds as $feed) {
                $id = $feed['id'];
                $text .= $feed['url'] . ' ' . $feed['comment'] . PHP_EOL;
                exec("curl --data \"id=$id&sent=1\" $argv[1]/update-item");
            }
            $payload['text'] .= $text;
            $sendJson = json_encode($payload);
            var_dump($payload);
            exec("curl -X POST --data-urlencode 'payload=$sendJson' $argv[4]");
        }
    } catch (\Exception $e) {
        echo $e->getMessage() . PHP_EOL;
        die;
    }
}