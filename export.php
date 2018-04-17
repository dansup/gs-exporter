<?php

require 'vendor/autoload.php';

use \Zttp\Zttp;

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$host = getenv('GS_HOST');
$username = getenv('GS_USER');
$password = getenv('GS_PASS');
$exportDir = getenv('EXPORT_DIR');

if(!is_dir($exportDir)) {
  mkdir($exportDir);
}

$endpoints = [
  [
    'auth' => true,
    'name' => "{$exportDir}/friends.json",
    'url'  => "{$host}/api/statuses/friends.json"
  ],
  [
    'auth' => true,
    'name' => "{$exportDir}/followers.json",
    'url'  => "{$host}/api/statuses/followers.json"
  ],
  [
    'auth' => true,
    'name' => "{$exportDir}/groups.json",
    'url'  => "{$host}/api/statusnet/groups/list/{$username}.json"
  ],
  [
    'auth' => true,
    'name' => "{$exportDir}/lists.json",
    'url'  => "{$host}/api/{$username}/lists/memberships.json"
  ],
  [
    'auth' => true,
    'name' => "{$exportDir}/mentions.json",
    'url'  => "{$host}/api/statuses/mentions.json"
  ],
  [
    'auth' => true,
    'name' => "{$exportDir}/replies.json",
    'url'  => "{$host}/api/statuses/replies.json"
  ],
  [
    'auth' => true,
    'name' => "{$exportDir}/user.json",
    'url'  => "{$host}/api/users/show/{$username}.json"
  ],
  [
    'auth' => true,
    'name' => "{$exportDir}/user_timeline.json",
    'url'  => "{$host}/api/statuses/user_timeline.json"
  ]
];

foreach($endpoints as $endpoint) {
  if($endpoint['auth']) {
    $response = Zttp::withBasicAuth($username,$password);
  } else {
    $response = Zttp::new();
  }
  $response = $response->get($endpoint['url']);
  $data = $response->body();
  file_put_contents($endpoint['name'], $data);
}

