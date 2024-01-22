<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function authenticate(Request $request, $access)
    {
        $decodedAccess = base64_decode($access);
        list($clientId, $publicKey) = explode(':', $decodedAccess);
        $clientApp = ClientApp::where('client_id', $clientId)->first();

        if (!$clientApp || $clientApp->public_key !== $publicKey) {
            return response()->json(['message' => 'application cliente pas valide desole.'], 401);
        }
        $request->session()->put('clientApp', $clientApp);
        return redirect()->route('loginPage')->with('appName', $clientApp->name);
    }


    public function showLoginPage()
    {

    $appName = session('clientApp')->name ?? '';
    return view('login', compact('appName'));

    }

    public function login(Request $request)
  {
    $email = $request->input('email');
    $password = $request->input('password');
    $user = User::where('email', $email)->first();

    if (!$user || !Hash::check($password, $user->password)) {
        return redirect()->back()->with('error', 'identifiants invalid');
    }
    $accessToken = AccessToken::create([
        'client_id' => $client->id, 
        'user_id' => $user->id,
        'expires_at' => now()->addDay() 
    ]);

    $client = ClientApp::where('id', $clientId)->first();

    $tokenInJson = json_encode([
        'id' => $accessToken->id,
        'client_id' => $accessToken->client_id,
        'user_id' => $accessToken->user_id ,
        'expires_at' => $accessToken->expires_at,
    ]);

    $signature = md5($tokenInJson . $client->secret_key);
    $token = base64_encode("$signature:$tokenInJson");
    
    $returnUrl = $request->input('return_url');
    $redirectUrl = $returnUrl . '?access_token=' . $token;
     return Redirect::to($redirectUrl);

  }

  public function getUser(Request $request)
  {
    $token = $request->input('token');
    $decodedToken = base64_decode($token);
    $tokenParts = explode(':', $decodedToken);

    if (count($tokenParts) !== 2) {
        return response()->json(['error' => 'Token invalid.'], 400);
    }

    $signature = $tokenParts[0];
    $tokenJson = $tokenParts[1];

    $client = ClientApp::where('id', $clientId)->first();
    $computedSignature = md5($tokenJson . $client->secret_key);

    if ($signature !== $computedSignature) {
        return response()->json(['error' => 'Signature invalid.'], 400);
    }

    $tokenData = json_decode($tokenJson, true);
    $userId = $tokenData['user']['id'];

    $user = User::find($userId);

    if (!$user) {
        return response()->json(['error' => 'Utilisateur non trouve.'], 404);
    }

    return response()->json($user);
  }

  public function showInscriptionPage()
    {
    return view('register');

    }

  public function createUser(Request $request)
 {
    $validatedData =Validator::make($request->all(),[
        'first_name' => 'required',
        'last_name' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:8',
    ]);

    if (!$validatedData->fails()) {
        return response()->json([
            'status' => 422,
            'error' => 'Utilisateur non trouve.'
        ], 404);
    }else{
        $user = new User();
        $user->first_name = $validatedData['first_name'];
        $user->last_name = $validatedData['last_name'];
        $user->email = $validatedData['email'];
        $user->password = bcrypt($validatedData['password']);
        $user->save();

        $log = new Log;
        $log->message = 'Un nouvel utilisateur a été créé : ' . $user->first_name;
        $log->type = 'UserCreated';
        $log->loggable()->associate($user);
        $log->save(); 

        return redirect('/login')->with('success', 'Votre compte a ete cree avec succes! connecter vous et continue.');
    }
    
}


}

