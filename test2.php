<?php
$response = file_get_contents('https://query1.finance.yahoo.com/v8/finance/chart/TCS.NS');
file_put_contents('out.json', $response);
