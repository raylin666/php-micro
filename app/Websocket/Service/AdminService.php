<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Websocket\Service;

use Exception;
use App\Websocket\Logic\AdminLogic;
use Hyperf\Di\Annotation\Inject;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

class AdminService extends Service
{
    #[Inject]
    public AdminLogic $adminLogic;

    public function onMessage(Server $server, Frame $frame): void
    {
        $server->push($frame->fd, 'Recv: ' . $frame->data);
    }

    public function onOpen(Server $server, Request $request): void
    {
        // 验证 TOKEN
        $authorization = $request->get['token'] ?? '';
        try {
            $this->adminLogic->JWTMiddleware($authorization);
        } catch (Exception $e) {
            $server->push($request->fd, json_encode(['code' => $e->getCode(), 'message' => $e->getMessage()]));
            $server->close($request->fd);
            return;
        }

        // $server->push($request->fd, json_encode(['messageType' => 'notice', 'data' => ['type' => 'success', 'text' => '连接 WebSocket 服务成功']]));
    }

    public function onClose(Response|Server $server, int $fd, int $reactorId): void
    {
        $server->send($fd, json_encode(['messageType' => 'notice', 'data' => ['type' => 'success', 'text' => '连接 WebSocket 服务成功']]));
    }
}
