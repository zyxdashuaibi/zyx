<?php
/**
 * Created by PhpStorm.
 * User: 晖晖
 * Date: 2022/10/19
 * Time: 15:55
 */
use think\facade\Route;

Route::rule('/pic','index/help/pic','get|post');
Route::rule('/qr','index/help/qr','get|post');
Route::rule('/picQr','index/help/picQr','get|post');
Route::rule('/test','index/help/test','get|post');
Route::rule('/preview','index/index/preview');
Route::get('/playPreview/:id','index/index/playPreview');
Route::get('/:id','index/index/index');
