<?php

require '../vendor/autoload.php';

$result = Clients::query()->select('column')->execute();

for($i = 0; $i < $result->count(); $i ++) {
    echo $result->get('column');

    if(!$result->isLast())
        $result->next();
}