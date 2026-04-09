<?php

declare(strict_types=1);

$workerNum = 1;

$server = new Swoole\Http\Server('0.0.0.0', 9501);

$server->set([
    'worker_num'        => $workerNum,
    'log_level'         => SWOOLE_LOG_ERROR,
    'enable_coroutine'  => true,
    'http_compression'  => false,
]);

$server->on('start', function (Swoole\Http\Server $server) use ($workerNum): void {
    echo sprintf(
        "Swoole HTTP server started\n  PHP    : %s\n  Swoole : %s\n  Listen : http://0.0.0.0:9501\n  Workers: %d\n  io_uring: %s\n",
        PHP_VERSION,
        SWOOLE_VERSION,
        $workerNum,
        defined('SWOOLE_IOURING_DEFAULT') ? 'enabled (SWOOLE_IOURING_DEFAULT + SQPOLL available)' : 'not compiled in',
    );
});

$server->on('request', function (Swoole\Http\Request $request, Swoole\Http\Response $response): void {
    $response->header('Content-Type', 'text/plain');
    $response->end("Hello, World!\n");
});

$server->start();
