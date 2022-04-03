<?php

namespace App\Http\Controllers;

use Google_Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use RapidWeb\GoogleOAuth2Handler\GoogleOAuth2Handler;
use RapidWeb\GooglePeopleAPI\Contact;
use RapidWeb\GooglePeopleAPI\GooglePeople;
use stdClass;
use Illuminate\Support\Facades\Storage;


class PeopleApiContoller extends Controller
{
    protected $clientId;
    protected $clientSecret;
    protected $refreshToken;
    protected $scopes;
    protected $redirectUrl;

    /**
     * @var GoogleOAuth2Handler
     */
    public function __construct(Request $request)
    {
        $value = Cookie::get('Token');

        $this->clientId = config('client.client_id');
        $this->redirectUrl = config('client.redirect_uri') ?? 'http://127.0.0.1:8000/code';
        $this->clientSecret = config('client.client_secret');
        $this->scopes =  config('client.scopes');
        $this->refreshToken = $value ?? Storage::get('example.txt'); //'1//0ggf0NWbptQu3CgYIARAAGBASNwF-L9IrlmaXJAtsXw11yNccwee_f-3ADNd5EozWZz4SFjutSraAOCkkUPPH-2J5MQrENliDWdM';
        $this->googleOAuth2Handler = new GoogleOAuth2Handler($this->clientId, $this->clientSecret, $this->scopes, $this->refreshToken);
    }

    public function index(Request $request)
    {
        if (!$this->refreshToken) {
            $this->cred();
        }
        $people = new GooglePeople($this->googleOAuth2Handler);
        $newarr = [];
        $message='';
        if (count($people->all()) > 0) {
            foreach ($people->all() as $contact) {
                $arr = $this->getContact($contact);
                array_push($newarr, $arr);
                echo PHP_EOL;
            }
            $message = 'Success';
        }
        else{
            $message = 'No contacts found';
        }
        $response = [
            'message' => $message,
            'data' => $newarr
        ];

        return response()->json($response);
    }
    public function show($id)
    {
        $people = new GooglePeople($this->googleOAuth2Handler);

        try {
            $contact = $people->get('people/' . $id);
            $arr = $this->getContact($contact);
            $response = [
                'message' => 'Success',
                'data' => $arr
            ];
        }
        catch (\Exception $e) {
            return response()->json(['message' => 'Contact not found'], 404);
        }
        return response()->json($response);
    }
    public function store(Request $request)
    {
        $people = new GooglePeople($this->googleOAuth2Handler);
        $contact = new Contact($people);
        $contact->names[0] = new stdClass;
        $contact->names[0]->givenName = $request->firstName;
        $contact->names[0]->familyName = $request->lastName;
        $contact->emailAddresses[0] = new stdClass;
        $contact->emailAddresses[0]->value = $request->email;
        $contact->phoneNumber[0] = new stdClass;
        $contact->phoneNumber[0]->phoneNumber = $request->phone;
        $contact->organizations[0] = new stdClass;
        $contact->organizations[0]->name = $request->company;
        $contact->urls[0] = new stdClass;
        $contact->urls[0]->value = $request->website;
        $contact->addresses[0] = new stdClass;
        $contact->addresses[0]->streetAddress = $request->addressLine1;
        $contact->addresses[0]->extendedAddress = $request->addressLine1;
        $contact->addresses[0]->city = $request->city;
        $contact->addresses[0]->postalCode = $request->zip;
        $contact->addresses[0]->country = $request->country;
        $contact->save();
        $arr = $this->getContact($contact);
        if ($arr){
            $response = [
                'message' => 'Contact Created Successfully',
                'data' => $arr
            ];
        }
        else{
            $response = [
                'message' => 'Error! Somthing Wrong',
                'data' => []
            ];
        }
        return response()->json($response);
    }

    public function update($id, Request $request)
    {
        $people = new GooglePeople($this->googleOAuth2Handler);
        $contact = $people->get('people/' . $id);
        if ($contact->names){
            $contact->names[0]->givenName = $request->firstName ?? (isset($contact->names[0]->givenName) ? $contact->names[0]->givenName : '');
            $contact->names[0]->familyName = $request->lastName ?? (isset($contact->names[0]->familyName) ? $contact->names[0]->familyName : '');
        }
        else{
            $contact->names[0] = new stdClass;
            $contact->names[0]->givenName = $request->firstName;
            $contact->names[0]->familyName = $request->lastName;
        }

        if ($contact->emailAddresses){
            $contact->emailAddresses[0]->value = $request->email ?? (isset($contact->emailAddresses[0]->value) ? $contact->emailAddresses[0]->value : '');
        }
        else{
            $contact->emailAddresses[0] = new stdClass;
            $contact->emailAddresses[0]->value = $request->email;
        }
        if ($contact->phoneNumbers){
            $contact->phoneNumbers[0]->value = $request->phone ?? (isset($contact->phoneNumbers[0]->value) ? $contact->phoneNumbers[0]->value : '');
        }
        else{
            $contact->phoneNumbers[0] = new stdClass;
            $contact->phoneNumbers[0]->value = $request->phone;
        }
        if ($contact->organizations){
            $contact->organizations[0]->name = $request->company ?? (isset($contact->organizations[0]->name) ? $contact->organizations[0]->name : '');
        }
        else{
            $contact->organizations[0] = new stdClass;
            $contact->organizations[0]->name = $request->company;
        }
        if ($contact->urls){

            $contact->urls[0]->value = $request->website ?? (isset($contact->urls[0]->value) ? $contact->urls[0]->value : '');
        }
        else{
            $contact->urls[0] = new stdClass;
            $contact->urls[0]->value = $request->website;
        }
        if($contact->addresses){
            $contact->addresses[0]->streetAddress = $request->addressLine1 ?? (isset($contact->addresses[0]->streetAddress) ? $contact->addresses[0]->streetAddress : '');
            $contact->addresses[0]->extendedAddress = $request->addressLine2 ?? (isset($contact->addresses[0]->extendedAddress) ? $contact->addresses[0]->extendedAddress : '');
            $contact->addresses[0]->city = $request->city ?? (isset($contact->addresses[0]->city) ? $contact->addresses[0]->city : '');
            $contact->addresses[0]->postalCode = $request->zip ?? (isset($contact->addresses[0]->postalCode) ? $contact->addresses[0]->postalCode : '');
            $contact->addresses[0]->country = $request->country ?? (isset($contact->addresses[0]->country) ? $contact->addresses[0]->country : '');
        }
        else{
            $contact->addresses[0] = new stdClass;
            $contact->addresses[0]->streetAddress = $request->addressLine1;
            $contact->addresses[0]->extendedAddress = $request->addressLine1;
            $contact->addresses[0]->city = $request->city;
            $contact->addresses[0]->postalCode = $request->zip;
            $contact->addresses[0]->country = $request->country;
        }
        $contact->save();
        $arr = $this->getContact($contact);
        if ($arr){
            $response = [
                'message' => 'Contact Updated Successfully',
                'data' => $arr
            ];
        }
        else{
            $response = [
                'message' => 'Error! Somthing Wrong',
                'data' => []
            ];
        }
        return response()->json($response);
    }

    public function destroy($id)
    {
        $people = new GooglePeople($this->googleOAuth2Handler);

        try {
            $contact = $people->get('people/' . $id);
            $contact->delete();
            $response = [
                'message' => 'Contact Deleted Successfully'
            ];
        } catch (\Exception $e) {
            $response = [
                'message' => 'Error! Somthing Wrong'
            ];
        }
        return response()->json($response);
    }
    public function getContact($contact)
    {
        $arr=[];
        if ($contact->names) {
            $resourseName = str_replace('people/','',$contact->resourceName);
            if ($contact->emailAddresses) {
                $email = isset($contact->emailAddresses[0]->value) ? $contact->emailAddresses[0]->value : '';
            } else {
                $email = '';
            }
            if ($contact->organizations) {
                $organizations = $contact->organizations[0]->name;
            } else {
                $organizations = '';
            }
            if ($contact->addresses) {
                $address = isset($contact->addresses[0]->streetAddress) ? $contact->addresses[0]->streetAddress : '';
                $address2 = isset($contact->addresses[0]->extendedAddress) ? $contact->addresses[0]->extendedAddress : '';
                $city = isset($contact->addresses[0]->city) ? $contact->addresses[0]->city : '';
                $zip = isset($contact->addresses[0]->postalCode) ? $contact->addresses[0]->postalCode : '';
                $country = isset($contact->addresses[0]->country) ? $contact->addresses[0]->country : '';

            } else {
                $address = '';
                $address2 = '';
                $city = '';
                $zip = '';
                $country = '';
            }

            if ($contact->urls) {
                $url = isset($contact->urls[0]->value) ? $contact->urls[0]->value : '';
            } else {
                $url = '';
            }
            if ($contact->phoneNumbers) {
                $phone = isset($contact->phoneNumbers[0]->value) ? $contact->phoneNumbers[0]->value : '';
            } else {
                $phone = '';
            }

            $arr = [
                'resourcename' => $resourseName,
                'firstName' => isset($contact->names[0]->givenName) ? $contact->names[0]->givenName : '',
                'lastName' => isset($contact->names[0]->familyName) ? $contact->names[0]->familyName : '',
                'email' => $email,
                'phone' => $phone,
                'company' => $organizations,
                'addresses' => $address,
                'addressLine2' => $address2,
                'city' => $city,
                'zip' => $zip,
                'country' => $country,
                'website' => $url,
            ];
        }
        return $arr;
    }

    public function cred(){
        $url = "https://accounts.google.com/o/oauth2/auth?response_type=code&access_type=offline&client_id=".$this->clientId."&redirect_uri=".$this->redirectUrl."&state&scope=https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.profile%20https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fcontacts%20https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fcontacts.readonly&approval_prompt=force";
        return redirect($url);
    }

    public function code(Request $request)
    {
        $google = new GoogleOAuth2Handler($this->clientId, $this->clientSecret, $this->scopes);
        $refreshToken = $google->getRefreshToken($request->code);
        Cookie::queue('Token', $refreshToken, 120);
        Storage::disk('local')->put('example.txt', $refreshToken);
        return redirect('/');
    }


}
