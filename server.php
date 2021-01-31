<?php declare(strict_types=1);

namespace App;

use Swoole\Constant;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;

$server = new Server('0.0.0.0', 9501, SWOOLE_BASE, SWOOLE_TCP);

$server->set([
    Constant::OPTION_WORKER_NUM => 2,
]);

$server->on('workerStart', static function (Server $server, int $worker_id): void {
    echo "Starting worker $worker_id\n";

    $pdo = new \PDO('mysql:host=host.docker.internal;dbname=swoole_mysql_lock', 'root', 'secret');

    $stmt = $pdo->query('select * from foo where baz = 0');

    $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    echo "Worker #$worker_id => {$rows[0]['bar']}\n";
});

$server->on('request', static function (Request $request, Response $response): void {});

$server->start();
