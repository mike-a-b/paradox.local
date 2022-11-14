<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExchangeBotLog;
use App\Models\Transaction;
use App\Models\UserAssetPoolHistory;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
// use App\Models\AssetPool;
// use App\Models\RatePool;
// use App\Models\Currency;
use App\Models\UserAssetPool;
use App\Models\UserAssetPoolItem;
use App\Models\UserProfile;
use App\Models\ExchangeInfo;
use App\Models\UserBalanceHistory;
use App\Models\AssetPoolGroup;
use App\Services\UserBaseInfo;
// use App\Services\UserRatePool;
use App\Services\UserPoolOperationsService;
// use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //$dataList = User::all();
        $search = $request->get('search');
        //dd($search);

        $dataList = DB::table('users as u')
            ->leftJoin('user_profiles as up', 'u.id', '=', 'up.user_id')
            ->selectRaw('u.*, up.balance_usd, up.balance_usdt')
            ->where('is_admin', 0)
            ->where(function($query) use ($search) {
                if (!empty($search)) {
                    $qs = "%$search%";
                    $query->where('name', 'like', $qs)->orWhere('email', 'like', $qs);
                }
            })
            ->orderBy('up.balance_usd', 'DESC')
            ->paginate(20);

        //  dd($dataList->toArray());

        // $dataList = User::with('profile')->where('is_admin', 0)->where(function($query) use ($search) {
        //     if (!empty($search)) {
        //         $qs = "%$search%";
        //         $query->where('name', 'like', $qs)->orWhere('email', 'like', $qs);
        //     }
        // })->paginate(20);
        if (!empty($search)) {
            $dataList->appends(['search' => $search]);
        }
        //dd($dataList->toArray());

        return view('admin.users.index', compact('dataList', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, UserBaseInfo $userBaseInfo)
    {
        $data = $request->all();
        $userBaseInfo->validator($data)->validate();
        $user = $userBaseInfo->commit($data);
        $userBaseInfo->updateEmailVerifiedAt($user);

        // $settings = new Setting();
        // $settings->createProfile($user->id);
        // UserProfile::create([
        //     'user_id' => $user->id,
        //     'balance_usd' => 0
        // ]);

        return redirect()->route('admin.users.edit', $user->id)->with(['tab'=>'user-info']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user, UserAssetPool $userAssetPool, UserAssetPoolItem $userAssetPoolItem)
    {
        $selectedTab = session()->get('tab');

        $userProfile = UserProfile::where('user_id', $user->id)->first();
        //dd($userProfileInfo->toArray());
        //$user->second_name = $userProfileInfo->second_name;

        $pools = $userAssetPool->getByUserId($user->id, AssetPoolGroup::G_TYPE_ASSET_POOL);
        $ratePools = $userAssetPool->getByUserId($user->id, AssetPoolGroup::G_TYPE_RATE_POOL);
        $poolIds = $pools->pluck('id');
        $poolItems = $userAssetPoolItem->getByUserPoolId($poolIds);

        // dd($pools->toArray(), $poolItems->toArray(), $ratePools->toArray());

        return view('admin.users.edit', compact('user', 'userProfile', 'pools', 'poolItems', 'ratePools', 'selectedTab'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user, UserBaseInfo $userBaseInfo)
    {
        $data = $request->all();
        $userBaseInfo->validator($data, 'update', $user->id)->validate();
        $userBaseInfo->commit($data, 'update', $user->id);

        return redirect()->route('admin.users.edit', $user->id)->with(['tab'=>'user-info']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        $idsUsers = User::pluck('id')->toArray();
//        Transaction::whereNotIn('from_user_id', $idsUsers)->delete();
//        Transaction::whereNotIn('to_user_id', $idsUsers)->delete();
        UserAssetPool::whereNotIn('user_id', $idsUsers)->delete();
        UserAssetPoolHistory::whereNotIn('user_id', $idsUsers)->delete();
        UserBalanceHistory::whereNotIn('user_id', $idsUsers)->delete();
        UserProfile::whereNotIn('user_id', $idsUsers)->delete();
        ExchangeBotLog::whereNotIn('user_id', $idsUsers)->delete();
        return redirect()->route('admin.users.index')->with(['tab'=>'user-info']);
    }

    public function addBalanceUsdt(User $user)
    {
        $userProfile = UserProfile::where('user_id', $user->id)->first();

        return view('admin.users.balance_usdt-change', compact('user', 'userProfile'));
    }

    public function storeBalanceUsdt(Request $request, User $user, UserProfile $userProfile, UserBalanceHistory $userBalanceHistory, ExchangeInfo $exchangeInfo)
    {
        $request->validate([
            'balance_usdt' => 'required|numeric|gt:0',
            'operation_type' => ['required', Rule::in(['deposit', 'withdraw']),]
        ]);

        $operationType = $request->post('operation_type');
        $depositUsdt = $request->post('balance_usdt');

        if ($operationType == 'withdraw') {
            $userProfile = UserProfile::where('user_id', $user->id)->firstOrFail();
            $this->validateUserBalanceUsdt($depositUsdt, 'balance_usdt', $userProfile);
            $depositUsdt = (-1)*floatval($depositUsdt);

            $exchangeInfo->commit([
                'withdraw_usd' => abs($depositUsdt)
            ]);
        } else {
            $exchangeInfo->commit([
                'deposit_usd' => $depositUsdt
            ]);
        }

        $userProfile = $userProfile->updateBalanceUsdt($user->id, ['deposit_change' => $depositUsdt]);
        $userBalanceHistory->commit($userProfile->toArray() + ['deposit_change' => $depositUsdt]);

        return redirect()->route('admin.users.edit', $user->id);
    }

    public function createPool(User $user)
    {
        //echo microtime(true); exit;

        $userProfile = UserProfile::where('user_id', $user->id)->first();

        return view('admin.users.pool-create', compact('user', 'userProfile'));
    }

    public function storePool(Request $request, User $user/*, UserAssetPoolItem $userAssetPoolItem*/)
    {
        $request->validate([
            'price_start_usd' => 'required|numeric|gt:0',
            'asset_pool_id' => 'required|integer|gt:0',
        ]);

        $poolPriceStartUsd = $request->post('price_start_usd');

        $userProfile = UserProfile::where('user_id', $user->id)->firstOrFail();
        $userProfile->balance_usdt = $userProfile->balance_usdt + $poolPriceStartUsd;
        $userProfile->save();

        $this->validateUserBalanceUsdt($poolPriceStartUsd, 'price_start_usd', $userProfile);

        (new UserPoolOperationsService)->createAssetPool($request->all(), $user);

        return redirect()->route('admin.users.edit', $user->id);
    }

    public function editPool(UserAssetPool $userAssetPool)
    {
        $pool = $userAssetPool::with('asset_pool')->where('id', $userAssetPool->id)->firstOrFail();
        $userProfile = UserProfile::where('user_id', $userAssetPool->user_id)->firstOrFail();

        return view('admin.users.pool-edit', compact('pool', 'userProfile'));
    }

    public function updatePool(Request $request, UserAssetPool $userAssetPool/*, UserAssetPoolItem $userAssetPoolItem*/)
    {
        $request->validate([
            'price_usd' => 'required|numeric|gt:0'
        ]);

        $poolPriceUsd = $request->post('price_start');

        $userProfile = UserProfile::where('user_id', $userAssetPool->user_id)->firstOrFail();

        $this->validateUserBalanceUsdt($poolPriceUsd - $userAssetPool->price_usd, 'price_start', $userProfile);

        (new UserPoolOperationsService)->updateAssetPool($request->all(), $userAssetPool);

        return redirect()->route('admin.users.edit', $userAssetPool->user_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyPool(UserAssetPool $userAssetPool)
    {
        (new UserPoolOperationsService)->deleteAssetPool($userAssetPool);

        return redirect()->route('admin.users.edit', $userAssetPool->user_id);
    }

    public function createRatePool(User $user)
    {
        $userProfile = UserProfile::where('user_id', $user->id)->first();

        return view('admin.users.rate_pool-create', compact('user', 'userProfile'));
    }

    public function storeRatePool(Request $request, User $user)
    {
        $request->validate([
            'rate_pool_id' => 'required|integer|gt:0',
            'price_start' => 'required|numeric|gt:0',
            'date_start' => 'required|date',
            'date_end' => 'required|date',
        ]);

        $poolPriceStartUsd = $request->post('price_start');

        $userProfile = UserProfile::where('user_id', $user->id)->firstOrFail();
        $userProfile->balance_usdt = $userProfile->balance_usdt + $poolPriceStartUsd;
        $userProfile->save();

        $this->validateUserBalanceUsdt($poolPriceStartUsd, 'price_start', $userProfile);

        (new UserPoolOperationsService)->createRatePool($request->all(), $user);

        return redirect()->route('admin.users.edit', $user->id)->with(['tab'=>'rate-pool']);
    }

    public function editRatePool(UserAssetPool $userAssetPool)
    {
        $pool = $userAssetPool::with('rate_pool')->where('id', $userAssetPool->id)->firstOrFail();
        $userProfile = UserProfile::where('user_id', $userAssetPool->user_id)->firstOrFail();

        return view('admin.users.rate_pool-edit', compact('pool', 'userProfile'));
    }

    public function updateRatePool(Request $request, UserAssetPool $userAssetPool)
    {
        $request->validate([
            'price_start' => 'required|numeric|gt:0',
            'date_start' => 'required|date',
            'date_end' => 'required|date',
        ]);

        $poolPriceStartUsd = $request->post('price_start');
        $userProfile = UserProfile::where('user_id', $userAssetPool->user_id)->firstOrFail();

        $this->validateUserBalanceUsdt($poolPriceStartUsd - $userAssetPool->price_usd, 'price_start', $userProfile);

        (new UserPoolOperationsService)->updateRatePool($request->all(), $userAssetPool/*, $userAssetPoolItem*/);

        return redirect()->route('admin.users.edit', $userAssetPool->user_id)->with(['tab'=>'rate-pool']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyRatePool(UserAssetPool $userAssetPool)
    {
        $userId = $userAssetPool->user_id;
        $userAssetPoolId = $userAssetPool->id;
        $ratePoolId = $userAssetPool->rate_pool_id;
        $assetPoolStartPriceUsd = -1*floatval($userAssetPool->price_start_usd);

        $userAssetPool->delete();

        (new UserPoolOperationsService)->userHistoryUpdates('D', $assetPoolStartPriceUsd, $userId, $userAssetPoolId, ['rate_pool_id' => $ratePoolId]);

        return redirect()->route('admin.users.edit', $userId)->with(['tab'=>'rate-pool']);
    }

    protected function validateUserBalanceUsdt(float $priceUsd, string $fieldName, UserProfile $userProfile) {
        $diff = $userProfile->balance_usdt - floatval($priceUsd);
        if ($diff < 0) {
            throw ValidationException::withMessages([$fieldName => "Not enough on user USDT balance $diff$"]);
        }
    }
}
