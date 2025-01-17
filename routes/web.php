<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/currentdb', function () {
    try {
        $database = DB::select('SELECT DATABASE() as db_name');
        if (!empty($database)) {
            return "当前连接的数据库是: " . $database[0]->db_name;
        } else {
            return "无法确定当前数据库";
        }
    } catch (\Exception $e) {
        return '无法获取数据库名称：' . $e->getMessage();
    }
});
//注册
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])
->name('register');
Route::post('/register', [RegisterController::class, 'register'])
->name('register.submit');



//登录
Route::get('/', [AuthController::class, 'showLoginForm'])
->name('login');
Route::post('/', [AuthController::class, 'login'])
->name('login.submit');

// 用户注销
Route::post('/logout', [AuthController::class, 'logout'])
->name('logout');

//首页


Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile', [ProfileController::class, 'store'])->name('profile.store');
    Route::get('/profile/edit/{id}', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/{id}', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



// 显示修改资料的表单
Route::get('/profile/update', [ChangePasswordController::class, 'showUpdateForm'])
->name('profile.update.form');

// 提交修改资料的表单
Route::put('/profile/update/{id}', [ChangePasswordController::class, 'update'])
->name('user.update');