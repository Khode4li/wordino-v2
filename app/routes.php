<?php
use Pecee\SimpleRouter\SimpleRouter as Router;
use Pecee\Http\Request;

Router::get('/',function (){
    \system\service\Helper::response('working',200,'ok',true);
});

Router::group(['prefix' => '/user'], function (){
    Router::post('/login',[\app\controllers\user::class, 'login']);
    Router::get('/check',[\app\controllers\user::class, 'check'])->addMiddleware(\app\middleware\auth::class);
    Router::post('/new',[\app\controllers\user::class, 'new'])->addMiddleware(\app\middleware\auth::class);
    Router::get('/all',[\app\controllers\user::class, 'all'])->addMiddleware(\app\middleware\auth::class);
    Router::get('/me',[\app\controllers\user::class, 'me'])->addMiddleware(\app\middleware\auth::class);
    Router::get('/changeRole/{uid}/{rid}',[\app\controllers\user::class, 'changeRole'])->addMiddleware(\app\middleware\auth::class);
    Router::get('/userPermissions/{uid}',[\app\controllers\user::class, 'userPermissions'])->addMiddleware(\app\middleware\auth::class);
    Router::get('/delete/{uid}',[\app\controllers\user::class, 'delete'])->addMiddleware(\app\middleware\auth::class);
});

Router::group(['prefix' => '/role', 'middleware' => \app\middleware\auth::class], function (){
    Router::get('/all',[\app\controllers\role::class, 'all']);
    Router::get('/allPerms',[\app\controllers\role::class, 'allPerms']);
    Router::post('/new',[\app\controllers\role::class, 'new']);
    Router::post('/edit/{rid}',[\app\controllers\role::class, 'edit']);
    Router::delete('/delete/{rid}',[\app\controllers\role::class, 'delete']);
});

Router::group(['prefix' => '/wordlist', 'middleware' => \app\middleware\auth::class], function (){
    Router::post('/create', [\app\controllers\wordlist::class, 'create']);
    Router::delete('/delete/{wlid}', [\app\controllers\wordlist::class, 'delete']);
    Router::get('/words/{wlid}', [\app\controllers\wordlist::class, 'words']);
    Router::get('/info/{wlid}', [\app\controllers\wordlist::class, 'info']);
    Router::get('/all', [\app\controllers\wordlist::class, 'all']);
});

Router::group(['prefix' => '/word', 'middleware' => \app\middleware\auth::class], function (){
    Router::post('/add/{wlid}', [\app\controllers\word::class, 'add']);
    Router::post('/multipleAdd/{wlid}', [\app\controllers\word::class, 'multipleAdd']);
    Router::post('/addFromUrl/{wlid}', [\app\controllers\word::class, 'fromUrl']);
});

Router::group(['prefix' => '/group', 'middleware' => \app\middleware\auth::class], function (){
    Router::post('/create', [\app\controllers\group::class, 'create']);
    Router::delete('/delete/{gid}', [\app\controllers\group::class, 'delete']);
    Router::get('/addWordlist/{gid}/{wlid}', [\app\controllers\group::class, 'addWordlist']);
    Router::get('/removeWordlist/{gid}/{wlid}', [\app\controllers\group::class, 'removeWordlist']);
    Router::get('/all', [\app\controllers\group::class, 'all']);
    Router::get('/wordlists/{gid}', [\app\controllers\group::class, 'wordlists']);
    Router::get('/words/{gid}', [\app\controllers\group::class, 'words']);
    Router::get('/wordCount/{gid}', [\app\controllers\group::class, 'wordsCount']);
});

Router::error(function(Request $request, \Exception $exception) {
    switch($exception->getCode()) {
        // Page not found
        case 404:
            \system\service\Helper::response('404 not found :D', 404, 'not ok', 200);
        // Forbidden
        case 403:
            response()->redirect('/403');
    }
});