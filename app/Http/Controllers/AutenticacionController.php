<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AutenticacionController extends Controller
{
    public function mostrarLogin()
    {
        if (Auth::check()) {
            return $this->redireccionarPorRol(Auth::user());
        }
        return view('autenticacion.login');
    }

    public function autenticar(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ], [
            'login.required'    => 'Ingresa tu DNI o Correo Electrónico.',
            'password.required' => 'Ingresa tu contraseña.',
        ]);

        $loginInput = trim($request->input('login'));
        $password = $request->input('password');

        // Determinar si lo ingresado es un DNI (sólo dígitos) o un correo electrónico
        $esDni = ctype_digit($loginInput);
        $campoBusqueda = $esDni ? 'dni' : 'email';

        // 1. Intento primario con la columna detectada ('dni' o 'email')
        if (Auth::attempt([$campoBusqueda => $loginInput, 'password' => $password])) {
            $request->session()->regenerate();
            return $this->redireccionarPorRol(Auth::user());
        }

        // 2. Fallback: Si ingresó un DNI pero la columna 'dni' estaba vacía en DB, intenta con su correo autogenerado
        if ($esDni) {
            $emailAutogenerado = $loginInput . '@anka.com';
            if (Auth::attempt(['email' => $emailAutogenerado, 'password' => $password])) {
                $request->session()->regenerate();
                return $this->redireccionarPorRol(Auth::user());
            }
        }

        return redirect()->back()
            ->withInput($request->only('login'))
            ->withErrors(['login' => 'Las credenciales ingresadas son incorrectas.']);
    }

    public function cerrarSesion(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    private function redireccionarPorRol($user)
    {
        $role = strtolower(trim($user->role ?? ''));

        if (in_array($role, ['admin', 'administrador', 'operador'])) {
            return redirect()->route('administrador.panel');
        }

        return redirect()->route('inicio');
    }
}