<?php

namespace App\Model\User;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Authenticatable
{
    protected $table = 'tbl_user';
    protected $guard_name = 'web';

    const USER_STATUS_ACTIVE = 10;
    const USER_STATUS_NOT_ACTIVE = 20;

    const ACCOUNT_TYPE_CREATOR = 10;
    const ACCOUNT_TYPE_USER = 20;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username','address', 'full_name','account_type','password','status','profile_picture'
    ];


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $attributes = [
        'account_type' => self::ACCOUNT_TYPE_USER,
        'status' => self::USER_STATUS_ACTIVE
    ];

    public static $rules = [
        'username' => 'required | unique',
        'profile_picture' => 'string',
        'address' => 'string',
        'full_name' => 'required | string',
        'account_type' => 'required | integer',
        'status' => 'required | integer'
    ];

     /**
     * 
     */
     public static function getUser()
     {
        return self::where('status',self::USER_STATUS_ACTIVE)->whereNotIn('account_type', [User::ACCOUNT_TYPE_CREATOR])->get();
     }

     

     /**
     * 
     */
     public static function passwordChangeValidation($old_password, $curent_password)
     {
        if(Hash::check($old_password, $curent_password)) 
        { 
            return true;
        }

        return false;
     }

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    /**
     * @var array
     */
    public static function userByUsername($username)
    {
        $data = static::where('username', $username)->where('status', static::USER_STATUS_ACTIVE)->first();
        return $data;
    } 

   
    /**
     * 
     */
    public static function getAccountMeaning($acount)
    {
        switch ($acount) {
            case static::ACCOUNT_TYPE_CREATOR:
               return 'Creator';
            case static::ACCOUNT_TYPE_USER:
               return 'User';
            default:
                return '';
        }
    }
}
