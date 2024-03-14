<?php

namespace App\Http\Controllers;

use App\Jobs\email2;
use App\Jobs\mensajes;
use App\Jobs\sms;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;



class UserController extends Controller
{

    public function mostrarFormularioLogin()

    {

        return view('auth.login');
    }
    public function mostrarFormularioRegistro()
    {
        return view('auth.registro');
    }

    public function mostrarFormularioVerificacion()
    {
        return view('auth.verificarCodigo');
    }

    public function mostrarBienvenida()
    {
        return view('auth.bienvenido');
    }


    public function creaUser(Request $request)
    {
        srand(time());
        $primerUsuario = User::count() == 0;

        $validacion = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|regex:/^[a-zA-Z\s]+$/|max:30',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|max:30',
                'phone' => 'required|string|regex:/^[0-9]+$/|min:10|max:10|unique:users',
                'g-recaptcha-response' => 'required',
            ],
            [
                'g-recaptcha-response.required' => 'Por favor, verifica que no eres un robot.',
                'name.required' => 'El campo nombre es obligatorio.',
                'name.string' => 'El campo nombre debe ser con puras letras.',
                'name.regex' => 'Solo se permiten letras y espacios en el campo nombre.',
                'name.max' => 'El campo nombre no puede exceder los 50 caracteres.',
                'email.required' => 'El campo correo electrónico es obligatorio.',
                'email.string' => 'El campo correo electrónico debe ser una cadena de caracteres.',
                'email.email' => 'El formato del correo electrónico no es válido.',
                'email.max' => 'El campo correo electrónico no puede exceder los 50 caracteres.',
                'email.unique' => 'El correo electrónico ya está registrado.',
                'password.required' => 'El campo contraseña es obligatorio.',
                'password.string' => 'El campo contraseña debe ser una cadena de caracteres.',
                'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
                'password.max' => 'La contraseña no puede exceder los 30 caracteres.',
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
            return redirect()->back()->withErrors($validacion)->withInput();
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
            Log::info("¡Registro exitoso! Nuevo usuario: {$user->name} ({$user->email}) - Fecha: " . now());
            return redirect()->route('login.form');
        } catch (\Exception $e) {
            Log::error('Error al guardar usuario: ' . $e->getMessage());
        }
    }

    private function generarCodigoVerificacion()
    {
        return rand(1000, 9999);
    }


    public function verificarCodigo(Request $request)
    {
        $validacion = Validator::make(
            $request->all(),
            [
                'codigo' => 'required|digits:4',
            ],
            [
                'codigo.required' => 'El campo de código es obligatorio.',
                'codigo.digits' => 'El código debe tener exactamente 4 dígitos.',
            ]
        );

        if ($validacion->fails()) {
            return redirect()->back()->withErrors($validacion)->withInput();
        }

        $user = User::find($request->user);

        if (!$user) {
            abort(404);
        }




        if ($request->codigo === $user->verification_code) {

            $user->verification_code = null;
            $user->verification_code_expires_at = null;
            $user->save();

            Auth::login($user);


            $time = now();
            Log::info(" Usuario Administrador: {$user->name} ({$user->email}) ha verificado el código exitosamente y ha iniciado sesión - Hora: " . $time);

            return redirect()->route('bienvenido');
        } else {
            // Código incorrecto
            return redirect()->back()->withErrors(['codigo' => 'El código de verificación es incorrecto.']);
        }
    }

    public function inicioSesion(Request $request)
    {
        try {
            // Realiza la validación del formulario
            $validacion = Validator::make(
                $request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required',
                ]
            );

            if ($validacion->fails()) {
                return redirect('login.form')->withErrors($validacion);
            }

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                $this->incrementarIntentosFallidos($request);

                return redirect()->route('login.form')->withErrors(['user' => 'El usuario y/o la contraseña ingresados son incorrectos.']);
            }

            $this->resetearIntentosFallidos($request);

            if ($user->rol_id != 1) {
                $time = now();
                Log::info("Usuario inició sesión: " . json_encode(['user_id' => $user->id, 'name' => $user->name, 'email' => $user->email, 'role' => $user->rol_id]) . ' - Hora: ' . now());
                Auth::login($user);
                return redirect()->route('bienvenido');
            } else {
                $user->verification_code = $this->generarCodigoVerificacion();
                $user->verification_code_expires_at = now()->addMinutes(10);
                $user->save();

                if ($user->verification_code && now()->lt($user->verification_code_expires_at)) {
                    $time = now();

                    $url = URL::temporarySignedRoute('verificarCodigo', now()->addMinutes(5), ['user' => $user->id]);
                    Log::info("Usuario administrador autenticado: " . json_encode(['user_id' => $user->id, 'name' => $user->name, 'email' => $user->email]) . ' - Fase de autenticación completada - Hora: ' . $time);

                    sms::dispatch($user)->onQueue('sms')->onConnection('database')->delay(now()->addSeconds(5));

                    return redirect($url);
                } else {
                    Auth::logout();
                    return redirect()->route('login.form')->withErrors([
                        'verification' => 'Por favor, verifica tu código de verificación antes de iniciar sesión.',
                    ]);
                }
            }
        } catch (ThrottleRequestsException $exception) {
            return redirect()->route('login.form')->withErrors(['throttle' => 'Demasiados intentos fallidos. Por favor, inténtalo nuevamente más tarde.']);
        }
    }

    private function incrementarIntentosFallidos(Request $request)
    {
        $key = $this->getThrottleKey($request);

        RateLimiter::hit(
            $key,
            now()->addSeconds(30)
        );


        if (RateLimiter::tooManyAttempts($key, $maxAttempts = 2)) {
            Log::warning("Intento de inicio de sesión bloqueado: " . $request->ip() . ' - Hora: ' . now());
            abort(429);
        }
    }

    private function resetearIntentosFallidos(Request $request)
    {
        $key = $this->getThrottleKey($request);

        RateLimiter::clear($key);
    }

    private function getThrottleKey(Request $request)
    {
        return sha1($request->ip());
    }
    public function logout()
    {
        if (Auth::check()) {
            $user = Auth::user();
            Auth::logout();
            Log::info("Usuario desconectado: " . json_encode(['user_id' => $user->id, 'name' => $user->name, 'email' => $user->email]) . ' - Hora: ' . now());
            return redirect()->route('login.form');
        }
    }
}
