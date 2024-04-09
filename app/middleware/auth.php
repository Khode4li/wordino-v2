<?php
namespace app\middleware;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;
use system\registry\registry;
use system\service\Helper;
use system\service\http;

class auth implements IMiddleware
{
    public function handle(Request $request): void
    {
        $user = http::Authenticate();
        if ($user === false){
            Helper::response('user is not authenticated', 401,'not ok', true);
        }
        registry::set('user', $user);
    }
}