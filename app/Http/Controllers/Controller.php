<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Model\ActionLog\ActionLog;

use Auth;

use DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getUserLogin()
    {
        $user = Auth::user();
        return $user;
    }

    /**
     * @return void
     */
    public function getResponse($status,$status_code,$data=null,$message)
    {
        if($status != false)
        {
            return response()->json(['status'=> $status, 'status_code'=> $status_code, 'data'=>$data, 'message'=>$message]); 
        }
        else
        {
            return response()->json(['status'=> $status, 'status_code'=> $status_code, 'data'=>$data, 'message'=>$message]);  
        }
    }

}
