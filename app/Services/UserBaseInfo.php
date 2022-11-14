<?php

namespace App\Services;

//use App\Models\AssetPool;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserBalanceHistory;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Image;

class UserBaseInfo
{    
    protected $validationRools = [
        'name' => 'required|string|min:3|max:100',
        'second_name' => 'required|string|min:3|max:100',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:3|max:12',
        'phone' => 'required|string|min:3|max:12',
        'is_active' => 'numeric',
    ];

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validator(array $data, $mode='create', $userId=null)
    {
        $rules = $this->validationRools;
        
        if ($mode == 'update') {
            $rules['email'] = $rules['email'] . ",{$userId},id";
            unset($rules['password']);
        }

        return Validator::make($data, $rules);
    }

    public function commit($data, $mode='create', $userId=null) {
        //dd($data);
        $user = null;
        $userProfile = null;

        if ($mode == 'create') {
            $user = new User();
            $userProfile = new UserProfile();
        } elseif ($mode == 'update') {
            $user = User::find($userId);
            $userProfile = UserProfile::where('user_id', $userId)->first();
        }

        $user->name = $data['name'];
        $user->email = $data['email'];         
        if (isset($data['is_active'])) {
            $user->is_active = empty($data['is_active']) ? 0 : 1;
        }        
        $user->save();

        $password = $data['password'];
        if (!empty($password)) {
            $user->setPassword($password);
        }
        
        $userProfile->user_id = $user->id;
        $userProfile->second_name = $data['second_name'];
        $userProfile->phone = $data['phone'];
        $userProfile->telegram = $data['telegram'] ?? '';
        if ($mode == 'create') {
            $userProfile->balance_usd = 0;
        }
        $userProfile->save();

        if ($mode == 'create') {
            // UserBalanceHistory::create([
            //     'user_id' => $user->id
            // ]);
            $userBalanceHistory = new UserBalanceHistory();
            $userBalanceHistory->commit($userProfile->toArray());
        }   
        
        return $user;
    }

    public function updateEmailVerifiedAt(User $user, $ts=null) {  
        $ts = $ts ?? Carbon::now();      
        //$user = User::find($userId);            
        $user->email_verified_at = $ts;                 
        $user->save();
    }

    public function updateAva($avaFile, int $userId)
    {        
        $userProfile = UserProfile::where('user_id', $userId)->firstOrFail();

        $basePath = 'assets/imgs/cabinet/ava';

        $fileName = Str::random(8).'.'.$avaFile->extension();  

        $ff = dechex(rand(0, 255));
        $qq = dechex(rand(0, 255));
        $avaPath = "$basePath/$ff/$qq";
        if (!file_exists(public_path("$basePath/$ff/"))) {        
            if(!mkdir(public_path("$basePath/$ff/"), 0777)) {
                echo "can't create dir $basePath/$ff/";
                exit;
            }
        }
        if (!file_exists(public_path($avaPath.'/'))) {        
            if(!mkdir(public_path($avaPath.'/'), 0777)) {
                echo "can't create dir $avaPath";
                exit;
            }
        }

        $avaFile->move(public_path($avaPath), $fileName);        

        $this->deleteAva($userId);

        $resultFile = $avaPath.'/'.$fileName;

        $imgFile = Image::make(public_path($resultFile));

        $imgFile->resize(100, 100, function ($constraint) {
		    $constraint->aspectRatio();
		})->save(public_path($resultFile));
        
        $userProfile->ava = $resultFile;
        $userProfile->save();

        return $userProfile;
    }

    public function deleteAva(int $userId)
    {        
        $userProfile = UserProfile::where('user_id', $userId)->firstOrFail();        

        $avaFile = $userProfile->ava;
        if (!empty($avaFile)) {
            $file = public_path($avaFile);
            if (file_exists($file)) {        
                if (!unlink($file)) {
                    echo "can't delete file $file";
                    exit;
                }
            }
            $parts = explode('/', $file);
            array_pop($parts);
            $folder1 = implode('/', $parts);
            if (is_dir($folder1)) {
                $isDirEmpty = !(new \FilesystemIterator($folder1))->valid();
                if ($isDirEmpty) {
                    rmdir($folder1);
                }                
            }                   
            array_pop($parts);
            $folder0 = implode('/', $parts);
            if (is_dir($folder0)) {
                $isDirEmpty = !(new \FilesystemIterator($folder0))->valid();
                if ($isDirEmpty) {
                    rmdir($folder0);
                }
            }
        }
        
        $userProfile->ava = '';
        $userProfile->save();

        return $userProfile;
    }
}