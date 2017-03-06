<?php

$collection = (new MongoDB\Client)->demo->search;

$document = $collection->findOne(['_id' => '94301']);

var_dump($document);

$collection = (new MongoDB\Client)->demo->search;

$cursor = $collection->find(['' => '', 'state' => '']);

foreach ($cursor as $document) {
    echo $document['_id'], "\n";
}