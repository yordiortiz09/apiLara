<?php

namespace App\Http\Controllers;

use App\Jobs\email2;
use App\Jobs\mensajes;
use App\Jobs\sms;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;



class UserController extends Controller
{

    public function mostrarFormularioLogin()
    {
        return view('email.login');
    }
    public function mostrarFormularioRegistro()
    {
        return view('email.registro');
    }

    public function creaUser(Request $request)
    {
        srand(time());
        $primerUsuario = User::count() == 0;

        $validacion = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|regex:/^[a-zA-Z\s]+$/|max:50',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'phone' => 'required|string|regex:/^[0-9]+$/|min:10|max:10|unique:users',
            ],
            [
                'name.required' => 'El campo nombre es obligatorio.',
                'name.string' => 'El campo nombre debe ser con puras letras.',
                'name.regex' => 'Solo se permiten letras y espacios en el campo nombre.',
                'name.max' => 'El campo nombre no puede exceder los 50 caracteres.',
                'email.required' => 'El campo correo electrónico es obligatorio.',
                'email.string' => 'El campo correo electrónico debe ser una cadena de caracteres.',
                'email.email' => 'El formato del correo electrónico no es válido.',
                'email.max' => 'El campo correo electrónico no puede exceder los 255 caracteres.',
                'email.unique' => 'El correo electrónico ya está registrado.',
                'password.required' => 'El campo contraseña es obligatorio.',
                'password.string' => 'El campo contraseña debe ser una cadena de caracteres.',
                'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
                'password.confirmed' => 'La confirmación de la contraseña no coincide.',
                'phone.required' => 'El campo teléfono es obligatorio.',
                'phone.string' => 'El campo teléfono debe ser una cadena de caracteres.',
                'phone.regex' => 'Solo se permiten números  en el campo teléfono.',
                'phone.min' => 'El campo teléfono debe tener 10 dígitos.',
                'phone.max' => 'El campo teléfono debe tener 10 dígitos.',
                'phone.unique' => 'El teléfono ya está registrado.',

            ]
        );


        if ($validacion->fails()) {
            return redirect()->route('formularioRegistro')->withErrors($validacion)->withInput();
        }

        $numero_aleatorio = $this->generarCodigoVerificacion();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'rol_id' => $primerUsuario ? 1 : 2,
            'status' => 0,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'verification_code' => $numero_aleatorio,
            'verification_code_expires_at' => now()->addMinutes(10)
        ]);

        try {
            $user->save();
            Log::info('New Admin User Register: ' . $user->name . ' (' . $user->email . ') , Time:(' . now() . ')');
            return redirect()->route('login.form');
        } catch (\Exception $e) {
            Log::error('Error al guardar usuario: ' . $e->getMessage());
            print_r($e->getMessage());
        }
    }


    public function mostrarFormularioVerificacion()
    {
        return view('email.verificarCodigo');
    }



    private function generarCodigoVerificacion()
    {
        return rand(1000, 9999);
    }

    public function numeroVerificacionMovil(Request $request)
    {
        if (!$request->hasValidSignature()) {
            abort(401, "EL CODIGO ES INCORRECTO");
        }

        $numeroiddelaurl = $request->url;

        $user = User::where('id', $numeroiddelaurl)->first();

        sms::dispatch($user)->onQueue('sms')->onConnection('database')->delay(now()->addSeconds(5));

        $domain = substr($user->email, strpos($user->email, '@') + 1);

        header("Status: 301 Moved Permanently");
        header("Location:https://" . $domain);
        exit;
    }


    public function registrarSMS(Request $request)
    {
        $validacion = Validator::make($request->all(), [
            'codigo' => 'required|digits:4'
        ]);

        if ($validacion->fails()) {
            return response()->json([
                "error" => $validacion->errors()
            ], 400);
        }

        $user = User::where('verification_code', $request->codigo)->first();

        if (!$user) {
            return response()->json([
                'mensaje' => 'Usuario no encontrado',
            ], 401);
        }

        $user->status = 1;
        $user->save();

        return view('email.login');
    }


    public function inicioSesion(Request $request)
    {

        $validacion = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'password' => 'required',
            ]
        );

        if ($validacion->fails()) {
            return response()->json([
                'status' => false,
                'mensaje' => 'Error de validación',
                'error' => $validacion->errors()
            ], 401);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        if ($user->rol_id == 1) {
            $codigoVerificacion = $this->generarCodigoVerificacion();
            $user->update(['verification_code' => $codigoVerificacion]);

            $url = URL::temporarySignedRoute(
                'validarnumero',
                now()->addMinutes(20),
                ['url' => $user->id]
            );

            mensajes::dispatch($user, $url)
                ->onQueue('mensajes')
                ->onConnection('database')
                ->delay(now()->addSeconds(10));

            return view('email.verificarCodigo', ['user' => $user]);
        }

        if (!$user->verification_code) {
            return response()->json([
                'status' => false,
                'msg' => 'Se requiere verificación adicional.',
                'user' => $user,
            ], 401);
        }

        $codigoIngresado = $request->input('verification_code');

        if ($codigoIngresado !== $user->verification_code) {
            return response()->json([
                'status' => false,
                'msg' => 'Código de verificación incorrecto.',
            ], 401);
        }

        $user->update(['verification_code' => null]);

        auth()->login($user);

        $token = $user->createToken("Token")->plainTextToken;

        return response()->json([
            'status' => true,
            'msg' => "Inicio sesión correctamente",
            'user' => $user,
            'token' => $token
        ], 201);
    }
}
