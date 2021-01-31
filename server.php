<?php declare(strict_types=1);

namespace App;

use Swoole\Constant;
use Swoole\Coroutine;
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

    while (true) {
        $pdo->beginTransaction();

        $stmt = $pdo->query('select * from foo where baz = 0 limit 2 for update');

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if (!empty($rows)) {
            $ids = array_map(static fn (array $row): string => $row['id'], $rows);
            $bar = array_map(static fn (array $row): string => $row['bar'], $rows);

            printf("Worker #%d => %s\n", $worker_id, implode(', ', $bar));

            $pdo->exec(sprintf('update foo set baz = 1 where id in (%s)', implode(',', $ids)));
        }

        $pdo->commit();

        Coroutine::sleep(0.5);
    }
});

$server->on('request', static function (Request $request, Response $response): void {});

$server->start();
