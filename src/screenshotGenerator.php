<?php

use Imgur\Client;

function uploadFile($link) {
    $client = new Client();
    $client->setOption('client_id', CONFIG['imgur']['client_id']);
    $client->setOption('client_secret', CONFIG['imgur']['client_secret']);
    $imageData = [
        'image' => $link,
        'type' => 'file'
    ];
    return $client->api('image')->upload($imageData);
}