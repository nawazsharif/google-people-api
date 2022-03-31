<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RapidWeb\GoogleOAuth2Handler\GoogleOAuth2Handler;
use RapidWeb\GooglePeopleAPI\Contact;
use RapidWeb\GooglePeopleAPI\GooglePeople;
use stdClass;

class PeopleApiContoller extends Controller
{
    protected $clientId;
    protected $clientSecret;
    protected $refreshToken;
    protected $scopes;
    protected $googleOAuth2Handler;
    /**
     * @var GoogleOAuth2Handler
     */
    public function __construct()
    {
        $this->clientId = '204420105568-sb5rhrarl6qs8g62f3n752niffld8pl0.apps.googleusercontent.com';
        $this->clientSecret = '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';
        $this->refreshToken = '1//0g8KCvlZIxmKPCgYIARAAGBASNwF-L9IrbsbvKqiRk2J2Komwpb5JMTXM00bDwfqi-YMwH0-2sYDT9EXny_aSHF6KgD3uHJ02d_c';
        $this->scopes = [
            'https://www.googleapis.com/auth/userinfo.profile',
            'https://www.googleapis.com/auth/contacts',
            'https://www.googleapis.com/auth/contacts.readonly'
        ];
        $this->googleOAuth2Handler = new GoogleOAuth2Handler($this->clientId, $this->clientSecret, $this->scopes, $this->refreshToken);

    }
    public function index()
    {
        $people = new GooglePeople($this->googleOAuth2Handler);
//        foreach($people->all() as $contact) {
//            echo $contact->resourceName.' - ';
//            if ($contact->names) {
//                echo $contact->names[0]->displayName;
//            }
//            echo PHP_EOL;
//        }

        return response()->json($people->all());
    }
    public function show($resourse)
    {
        dd($resourse);
        $people = new GooglePeople($this->googleOAuth2Handler);
        return response()->json($people->get($request->resourceName));
    }

    public function store(Request $request)
    {
        $people = new GooglePeople($this->googleOAuth2Handler);
        // Create new contact
        $contact = new Contact($people);
        $contact->names[0] = new stdClass;
        $contact->names[0]->givenName = 'Testy';
        $contact->names[0]->familyName = 'McTest Test';
        $contact->save();
        dd($contact);
//        return response()->json('Contact created');
    }
    public function update($id, Request $request)
    {
        $people = new GooglePeople($this->googleOAuth2Handler);
        $contact = $people->get($id);
        $contact->names[0]->familyName = 'McTest';
        $contact->save();
        return response()->json($people->all());
    }

    public function destroy($id)
    {
        $people = new GooglePeople($this->googleOAuth2Handler);
        $contact = $people->get($id);
        $contact->delete();
        return response()->json($people->all());
    }

}
