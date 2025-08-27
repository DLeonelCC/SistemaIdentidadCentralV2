<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set('America/Lima');
    }

    public function login(Request $request)
    {
        $rules = [
            'email' => 'required|string|email|max:191',
            'password' => 'required|string'
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 400);
        }
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => false,
                'errors' => 'Unauthorized'
            ], 401);
        }
        $user = User::where('email', $request->email)->first();

        if ($user->estado == 'INACTIVO') {
            return response()->json([
                'status' => false,
                'errors' => 'Unauthorized'
            ], 401);
        }

        $empresa = Empresa::find(1);

        return response([
            'user' => $user,
            'token' => $user->createToken('authToken')->accessToken,
            'roles' => $user->getRolNamesCoreCentral(),
            'permissions' => $user->getPermissionNamesCoreCentral(),
            'empresa' => $empresa
        ]);
    }

    public function logout(Request $request)
    {
        if (request()->has('authId')) {
            $authId = request('authId');
            $accessTokens = DB::table('oauth_access_tokens')->where('user_id', $authId)->get();
            foreach ($accessTokens as $at) {
                DB::table('oauth_access_tokens')->delete($at->id);
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'User logged out successfully',
        ], 200);
    }

    public function validateToken()
    {
        if (request()->has('authTokenDecoded')) {
            $authTokenDecoded = request('authTokenDecoded');
            $accessToken = DB::table('oauth_access_tokens')->where('id', $authTokenDecoded['jti'])->first();
            if ($accessToken && $accessToken->revoked == 0 && strtotime(date('Y-m-d H:i:s')) < strtotime($accessToken->expires_at)) {
                return response()->json([
                    'status' => true,
                    'message' => 'Valid Token'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid Token'
                ]);
            };
        };

        return response()->json([
            'status' => false,
            'message' => 'Token not exist',
        ]);
    }

    public function ssoLogin(Request $request)
    {
        $rules = [
            'email' => 'required|string|email|max:191',
            'password' => 'required|string'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => false,
                'errors' => 'Unauthorized'
            ], 401);
        }

        $user = User::where('email', $request->email)->first();

        if ($user->estado === 'INACTIVO') {
            return response()->json([
                'status' => false,
                'errors' => 'Unauthorized'
            ], 401);
        }

        // Inicia sesión en Laravel (necesario para el flujo /oauth/authorize)
        Auth::login($user);

        // Parámetros necesarios para continuar el flujo OAuth
        $authorizeUrl = config('oauth.authorize_url');
        $params = http_build_query([
            'client_id' => $request->input('client_id'),
            'redirect_uri' => $request->input('redirect_uri'),
            'response_type' => 'code',
            'scope' => $request->input('scope', ''),
            'state' => $request->input('state', '/')
        ]);

        return response()->json([
            'status' => true,
            'redirect_url' => $authorizeUrl . '?' . $params
        ]);
    }

    public function ssoLogout(Request $request)
    {
        // 1. Cerrar sesión del usuario
        Auth::logout();

        // 2. Invalidar la sesión actual
        $request->session()->invalidate();

        // 3. Regenerar el token CSRF por seguridad
        $request->session()->regenerateToken();

        // 4. Leer y validar el return_url (opcional: restringir dominios seguros)
        $returnUrl = $request->query('return_url', '/');

        // 5. Redirigir de vuelta al sistema cliente (por ejemplo, SGP)
        return redirect()->to($returnUrl);
    }
}
