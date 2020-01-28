<?php
namespace App\Http\Controllers;
use Validator;
use App\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\File;

class AuthController extends BaseController 
{
    private $request;
  
    public function __construct(Request $request) {
        $this->request = $request;
    }
 
    protected function jwt(User $user) {
        $payload = [
            'iss' => "lumen-jwt", // Issuer of the token
            'sub' => $user->id, // Subject of the token
            'iat' => time(), // Time when JWT was issued. 
            'exp' => time() + 60*60 // Expiration time
        ];
     
        return JWT::encode($payload, env('JWT_SECRET'));
    } 
 
    public function authenticate(User $user) {
        $this->validate($this->request, [
            'email'     => 'required|email',
            'password'  => 'required'
        ]);
        // Find the user by email
        $user = User::where('email', $this->request->input('email'))->first();
        if (!$user) {
            return response()->json([
                'error' => 'Email does not exist.'
            ], 400);
        }
        // Verify the password and generate the token
        if (Hash::check($this->request->input('password'), $user->password)) {
            return response()->json([
                'token' => $this->jwt($user)
            ], 200);
        }
        // Bad Request response
        return response()->json([
            'error' => 'Email or password is wrong.'
        ], 400);
    }

    public function filter(Request $request){          
        $jsonString = file_get_contents(base_path('resources/data.json'));
        $data = json_decode($jsonString, true);
        $billdetails = $data['data']['response']['billdetails'];
        
        $result = [];
        foreach($billdetails as $body){
            $explode = explode(":",$body['body'][0]);
            if ((int)$explode[1] >= 100000) {
               array_push($result, $explode[1]);
            }
        }
        
        return $result;
    }
}

?>