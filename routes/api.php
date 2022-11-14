<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('v1')->middleware('auth')->group(function () {
    // Route::middleware('auth:api')->get('/user', function (Request $request) {
    //     return $request->user();
    // });
    Route::apiResource('assets', App\Http\Controllers\Api\AssetsController::class)->middleware('is_admin');
    Route::apiResource('currencies', App\Http\Controllers\Api\CurrenciesController::class)->middleware('is_admin');
    Route::get('exchange-info', [App\Http\Controllers\Api\ExchangeInfoController::class, 'index'])->name('exchange-info.index')->middleware('is_admin');
    Route::get('exchange-bot-log/count', [App\Http\Controllers\Api\ExchangeBotLogController::class, 'count'])->name('exchange-bot-log.count')->middleware('is_admin');
    Route::apiResource('exchange-bot-log', App\Http\Controllers\Api\ExchangeBotLogController::class)->middleware('is_admin');
    Route::get('transactions/count', [App\Http\Controllers\Api\TransactionsController::class, 'count'])->name('transactions.count')->middleware('is_admin');
    Route::apiResource('transactions', App\Http\Controllers\Api\TransactionsController::class)->middleware('is_admin');
    Route::get('notifications-log/count', [App\Http\Controllers\Api\NotificationsLogController::class, 'count'])->middleware('is_admin');
    Route::post('notifications-log/check-all', [App\Http\Controllers\Api\NotificationsLogController::class, 'checkAll'])->middleware('is_admin');
    Route::apiResource('notifications-log', App\Http\Controllers\Api\NotificationsLogController::class)->middleware('is_admin');  
    Route::post('/asset-pools/{assetPool}/restart', [App\Http\Controllers\Api\AssetPoolsController::class, 'restart'])->name('asset-pools.restart');  
    Route::post('asset-pools/{assetPool}/update-logo', [App\Http\Controllers\Api\AssetPoolsController::class, 'updateLogo'])->middleware('is_admin');
    Route::apiResource('asset-pools', App\Http\Controllers\Api\AssetPoolsController::class)->middleware('is_admin');
    Route::post('rate-pools/{ratePool}/update-logo', [App\Http\Controllers\Api\RatePoolsController::class, 'updateLogo'])->middleware('is_admin');    
    Route::apiResource('rate-pools', App\Http\Controllers\Api\RatePoolsController::class)->middleware('is_admin');
    Route::apiResource('asset-pool-items', App\Http\Controllers\Api\AssetPoolItemsController::class)->middleware('is_admin');  
    Route::apiResource('asset-pool-templates', App\Http\Controllers\Api\AssetPoolTemplatesController::class)->middleware('is_admin');  
    Route::get('asset-pool-groups', [App\Http\Controllers\Api\AssetPoolGroupsController::class, 'index'])->name('asset-pool-groups.index');
    Route::get('asset-pool-history', [App\Http\Controllers\Api\AssetPoolHistoryController::class, 'index'])->name('asset-pool-history.index');
    Route::get('asset-pool-history/statistics', [App\Http\Controllers\Api\AssetPoolHistoryController::class, 'statistics'])->name('asset-pool-history.statistics');
    Route::get('user-balance-history', [App\Http\Controllers\Api\UserBalanceHistoryController::class, 'index'])->name('user-balance-history.index');
    Route::get('user-balance-history/statistics', [App\Http\Controllers\Api\UserBalanceHistoryController::class, 'statistics'])->name('user-balance-history.statistics');
    Route::get('user-asset-pools', [App\Http\Controllers\Api\UserAssetPoolsController::class, 'index'])->name('user-asset-pools.index')->middleware('is_admin');
    // Route::get('user-pools-service', [App\Http\Controllers\Api\UserPoolsService::class, 'index']);
    Route::get('user-pools-service/asset-pools', [App\Http\Controllers\Api\UserPoolsService::class, 'assetPools'])->name('user-pools-service.asset-pools');
    Route::get('user-pools-service/rate-pools', [App\Http\Controllers\Api\UserPoolsService::class, 'ratePools'])->name('user-pools-service.rate-pools');
    Route::get('user-pools-service/user-asset-pools', [App\Http\Controllers\Api\UserPoolsService::class, 'userAssetPools'])->name('user-pools-service.user-asset-pools');
    Route::get('user-pools-service/user-rate-pools', [App\Http\Controllers\Api\UserPoolsService::class, 'userRatePools'])->name('user-pools-service.user-rate-pools');
    Route::get('user-pools-service/user-profile', [App\Http\Controllers\Api\UserPoolsService::class, 'userProfile'])->name('user-pools-service.user-profile');
    Route::post('user-pools-service/pool-balance-update', [App\Http\Controllers\Api\UserPoolsService::class, 'poolBalanceUpdate'])->name('user-pools-service.pool-balance-update');
    Route::post('user-info-service/update-ava', [App\Http\Controllers\Api\UserInfoServiceController::class, 'updateAva'])->name('user-info-service.update-ava');
    Route::post('user-info-service/delete-ava', [App\Http\Controllers\Api\UserInfoServiceController::class, 'deleteAva'])->name('user-info-service.delete-ava');
    Route::get('user-info-service/profile', [App\Http\Controllers\Api\UserInfoServiceController::class, 'profileShow'])->name('user-info-service.profile-show');
    Route::get('user-history-service', [App\Http\Controllers\Api\UserHistoryServiceController::class, 'index'])->name('user-history-service.index');
});

// Route::prefix('v1')->group(function () {    
//     Route::get('user-balance-history', [App\Http\Controllers\Api\UserBalanceHistoryController::class, 'index']); //->middleware('auth');    
// });
