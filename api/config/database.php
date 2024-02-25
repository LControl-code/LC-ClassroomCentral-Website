<?php

require_once __DIR__ . './../../vendor/autoload.php';

use Dotenv\Dotenv;
use MongoDB\Client;
use MongoDB\Driver\ServerApi;

function mongodbConnect()
{
  try {
    $dotenv = Dotenv::createImmutable(__DIR__ . './../../');
    $dotenv->load();

    $uri = $_ENV['MONGODB_URI'];

    $apiVersion = new ServerApi(ServerApi::V1);

    $client = new Client($uri, [], ['serverApi' => $apiVersion]);
  } catch (Exception $e) {
    printf($e->getMessage());
    return null;
  }
  return $client;
}
