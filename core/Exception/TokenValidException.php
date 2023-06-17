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
namespace Core\Exception;

use Core\Constants\HttpErrorCode;
use Hyperf\Server\Exception\ServerException;

class TokenValidException extends ServerException
{
    public function __construct(string $message = '')
    {
        $code = HttpErrorCode::HTTP_BAD_REQUEST;
        if (empty($message)) {
            $message = HttpErrorCode::getMessage($code);
        }

        parent::__construct($message, $code);
    }
}
