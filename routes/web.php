<?php

use Illuminate\Support\Facades\Route;

// 既存のルート
Route::get('/', function () {
    return view('welcome');
});

// クイズページへの新しいルートを追加
Route::get('/quiz', function () {
    return view('hasegawa_quiz'); // 'hasegawa_quiz'はあなたが作成したビューの名前
});
