<?php

namespace App\Http\Controllers;

use App\Models\DatosPersona;
use App\Models\TipoProducto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ControlUsuarioController extends Controller
{
    public function cesta()
    {
        $tipos = TipoProducto::all();
        return view('cestaindex', ["tipos" => $tipos]);
    }
    public function formarusu()
    {
        $tipos = TipoProducto::all();
        if (Auth::user() || session('persona') != null) {
            return redirect(route('datosPersona.index'));
        } else {
            return view('pages.pedidos.FormalizarPedido', ["tipos" => $tipos]);
        }
    }

    public function finalizarpedporusucreado(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect(route('datosPersona.index'));
        } else {
            return redirect()->back()->withInput()->withErrors(['status' => 'La contraseña o el correo es incorrecto']);
        }
    }
    public function crearusunormal(Request $request)
    {

        $validated = $request->validate([
            'nombre' => 'required|string|max:30',
            'correo' => 'required|email',
            'telefono' => 'required|digits:9',
            'contrasena' => 'required|string|min:8|max:15',
            'confirmar' => 'required|same:contrasena'
        ]);
        //comprobacion de existencia de usuario
        $usuariodato = User::where('email', '=', $validated['correo'])->first();
        if ($usuariodato) {
            return redirect()->back()->withInput()->withErrors(['status' => 'Ya existe un usuario con ese Email']);
        }

        DatosPersona::create([
            'nombre'  => $validated['nombre'],
            'email' => $validated['correo'],
            'telefono'  => $validated['telefono'],
        ]);
        $persona = DatosPersona::latest('id')->first();
        $datousuario = DatosPersona::where('email', '=', $validated['correo'])->first();
        User::create([
            'id_persona' => $datousuario->id,
            'email' => $validated['correo'],
            'password' => Hash::make($validated['contrasena']),
        ]);
        $tipos = TipoProducto::all();
        $credentials = [
            'email' => $validated['correo'],
            'password' => $validated['contrasena']
        ];
        if (Auth::attempt($credentials)) {
            return redirect(route('datosPersona.index'));
        } else {
            if (session('carrito') != null) {
                return view('pages.carrito.index', ["tipos" => $tipos, "persona" => $persona]);
            } else {
                return redirect(route('productos.index'));
            }
        }
    }
}
