<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Persona;
use App\Models\Personal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set('America/Lima');
    }

    public function getMyInformation(Request $request)
    {
        $empresa = Empresa::withoutGlobalScopes()->where('tenant_id', Auth::user()->tenant_active_id)->first();
        $user = User::where('id', Auth::user()->id)->first();
        $persona = Persona::where('num_doc', Auth::user()->persona_num_doc)->first();
        $personal = Personal::where('persona_num_doc', Auth::user()->persona_num_doc)->first();

        $user->persona = $persona;
        $user->personal = $personal;

        return response()->json([
            'empresa' => $empresa,
            'user' => $user,
        ]);
    }

    public function updateProfileInformation(Request $request)
    {
        $user = User::findOrFail($request->input('user_id'));

        if (!$user) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        if ($request->input('onlyPhoto') == '1') {
            if ($request->hasFile('photo')) {
                $request->validate([
                    'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
                ]);
                $user->deletePhoto();
                $user->updatePhoto($request->file('photo'));
            }
        } else {
            $user->update([
                'name' => $request->name,
                'email' => $request->email
            ]);
        }

        return response($user);
    }

    public function updatePassword(Request $request)
    {
        $user = User::findOrFail($request->input('user_id'));

        if (!$user) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }

        $request->validate([
            'current_password' => ['required', 'string', 'current_password:api'],
            'password' => ['required', 'string', 'confirmed'],
            'password_confirmation' => ['required']
        ], [
            'current_password.current_password' => __('La contraseña proporcionada no coincide con su contraseña actual.'),
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return response($user);
    }

    public function deleteProfilePhoto(Request $request)
    {
        $user = User::findOrFail($request->input('user_id'));

        if (!$user) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }

        $user->deletePhoto();
        return response($user);
    }
}
