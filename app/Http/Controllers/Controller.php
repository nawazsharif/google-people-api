<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use RapidWeb\GoogleOAuth2Handler\GoogleOAuth2Handler;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

//    public function getAuthGoogle(){
//        $clientId     = '204420105568-sb5rhrarl6qs8g62f3n752niffld8pl0.apps.googleusercontent.com';
//        $clientSecret = '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';
//        $refreshToken = '';
//        $scopes       = ['https://www.googleapis.com/auth/userinfo.profile', 'https://www.googleapis.com/auth/contacts', 'https://www.googleapis.com/auth/contacts.readonly'];
//
//        $googleOAuth2Handler = new GoogleOAuth2Handler($clientId, $clientSecret, $scopes, $refreshToken);
//
//        $people = new GooglePeople($googleOAuth2Handler);
//        dd($people->all());
//
//    }


}
