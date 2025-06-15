<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Video;
use App\Models\Pregunta;

class AuthController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('success', 'Usuario registrado correctamente');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return redirect()->back()->withErrors(['login' => 'Usuario o contraseña incorrectos'])->withInput();
        }

        Auth::login($user);

        Session::put('username', $user->username);
        Session::put('imagen_perfil', $user->imagen_perfil ?? '/img/default.png');
        Session::put('descripcion', $user->descripcion ?? '¡Hola! Soy nuevo en Fun Gains');

        return redirect()->route('home');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function showHome()
    {
        $usuarios = User::orderByDesc('nivel')->take(6)->get();
        $topVideos = Video::withCount('likes')->orderByDesc('likes_count')->take(3)->get();
        return view('auth.home', compact('usuarios', 'topVideos'));
    }

    public function showUser()
    {
        $user = Auth::user();
        $user->actualizarNivel();
        $totalAciertos = $user->totalAciertos();
        $nivel = $user->nivel;
        $totalVideos = $user->videos()->count();

        return view('auth.user', compact('user', 'totalAciertos', 'nivel', 'totalVideos'));
    }

    public function handleHomePost(Request $request)
    {
        return redirect()->route('home')->with('success', 'Datos procesados correctamente');
    }

    public function handleUserPost(Request $request)
    {
        $user = Auth::user();

        if ($request->accion === 'editar_descripcion') {
            $user->descripcion = $request->descripcion;
            $user->save();

            Session::put('descripcion', $user->descripcion);
            return redirect()->route('user')->with('success', 'Descripción actualizada correctamente.');
        }

        if ($request->accion === 'editar_imagen') {
            if ($request->hasFile('imagen_perfil')) {
                $request->validate([
                    'imagen_perfil' => 'image|mimes:jpg,jpeg,png,gif|max:2048'
                ]);

                try {
                    $filename = time() . '_' . $request->file('imagen_perfil')->getClientOriginalName();
                    $destination = base_path('../public_html/storage/perfiles');

                    if (!file_exists($destination)) {
                        mkdir($destination, 0775, true);
                    }

                    $request->file('imagen_perfil')->move($destination, $filename);
                    $imageUrl = 'storage/perfiles/' . $filename;
                    $user->imagen_perfil = $imageUrl;
                    $user->save();
                    Session::put('imagen_perfil', $imageUrl);

                    return redirect()->route('user')->with('success', 'Imagen de perfil actualizada correctamente.');
                } catch (\Exception $e) {
                    \Log::error('Error al subir imagen de perfil: ' . $e->getMessage());
                    return redirect()->route('user')->with('error', 'Hubo un problema al subir la imagen.');
                }
            }

            return redirect()->route('user')->with('error', 'No se ha subido ninguna imagen.');
        }

        return redirect()->route('user')->with('error', 'Acción no válida.');
    }
}