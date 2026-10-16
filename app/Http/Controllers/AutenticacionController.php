<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AutenticacionController extends Controller
{
    public function mostrarLogin()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $rol = strtolower(trim($user->role ?? ''));

            if (in_array($rol, ['operador', 'admin', 'administrador'])) {
                return redirect()->route('administrador.panel');
            }

            return redirect()->route('inicio');
        }

        return view('autenticacion.login');
    }

    public function autenticar(Request $request)
    {
        $credenciales = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingresa un correo válido.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        if (Auth::attempt($credenciales, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            $rol = strtolower(trim($user->role ?? ''));

            // Redirección forzada según el rol hacia la ruta del panel de control
            if (in_array($rol, ['operador', 'admin', 'administrador'])) {
                return redirect()->route('administrador.panel');
            }

            return redirect()->route('inicio');
        }

        return back()->withErrors([
            'email' => 'Las credenciales ingresadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    public function cerrarSesion(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}