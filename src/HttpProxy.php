<?php
/**
 * @desc HttpProxy.php 描述信息
 * @author Tinywan(ShaoBo Wan)
 * @date 2024/2/20 14:54
 */
declare(strict_types=1);


namespace Tinywan\Xhprof;


use Exception;
use support\Request;
use Workerman\Connection\AsyncTcpConnection;
use Workerman\Connection\TcpConnection;

class HttpProxy
{
    /**
     * @desc: onMessage 描述
     * @param TcpConnection $connection
     * @param Request $request
     * @return bool|null
     * @throws Exception
     * @author Tinywan(ShaoBo Wan)
     */
    public function onMessage(TcpConnection $connection, Request $request): ?bool
    {
        $replace = [
            'api.example.com' => 'api.ai.com',
            'discord.example.com' => 'discord.com',
            'cdn.example.com' => 'cdn.discordapp.com',
            'gateway.example.com' => 'gateway.discord.gg',
        ];
        $host = $request->host(true);
        if (!isset($replace[$host])) {
            return $connection->send(response('404 not found', 404));
        }
        $host = $replace[$host];
        $buffer = (string)$request;
        $con = new AsyncTcpConnection("tcp://$host:443", ['ssl' =>[
            'verify_peer' => false
        ]]);
        $buffer = preg_replace("/Host: ?(.*?)\r\n/", "Host: $host\r\n", $buffer);
        $con->transport = 'ssl';
        $connection->protocol = null;
        $con->send($buffer);
        $con->pipe($connection);
        $connection->pipe($con);
        $con->connect();
    }
}