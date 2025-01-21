<?php
require __DIR__ . '/../vendor/autoload.php';

$redis = new Predis\Client([
    'scheme' => 'tcp',
    'host'   => '127.0.0.1',
    'port'   => 6379,
]);

try {
    $redis->ping();
} catch (Exception $e) {
    echo 'Redis connection failed: ' . $e->getMessage();
    exit;
}
?>
