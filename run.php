<?php

if (!ini_get("auto_detect_line_endings")) {
  ini_set("auto_detect_line_endings", '1');
}

if (!$loader = include __DIR__.'/vendor/autoload.php') {
  die('You must set up the project dependencies.');
}

$app = new \Cilex\Application('Cilex');
$app->command(new \Cilex\Command\GreetCommand());
$app->command(new \Gollyg\SolrSyntaxParser\Command\SynonymCommand());
$app->run();