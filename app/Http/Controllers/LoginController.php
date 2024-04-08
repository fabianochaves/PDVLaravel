<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Usuario;

class LoginController extends Controller
{
    public function index(Request $request){

        $erro = "";
        
        if($request->get('error') == 1){
            $erro = "Usuário e/ou Senha Inválidos";
        }

        return view('login', ['error' => $erro]);
    }

    public function autenticar(Request $request){
        $regras = [
            "user" => 'required',
            "password" => 'required',
        ];

        $feedback = [
            'user.required' => 'Preencha o Usuário!',
            'password.required' => 'Preencha a Senha!',
        ];

        $request->validate($regras, $feedback);

        $usuario = $request->get('user');
        $password = $request->get('password');

        $passwordHashed = hash('sha256', $password);

        $user = Usuario::where('login_usuario', $usuario)
                        ->where('senha_usuario', $passwordHashed)
                        ->first();

        if ($user) {
            session_start();
            $_SESSION['user'] = $user->id_usuario;
            $_SESSION['nome'] =  $user->nome_usuario;
            $_SESSION['perfil'] =  $user->perfil_usuario;
            $_SESSION['nome_sistema'] = "Sistema de Vendas";

            return redirect()->route('home');

        } else {
            return redirect()->route('viewLogin', ['error' => 1]);
        }
    }

    public function logout()
    {
        session_start();
        session_destroy();

        return redirect()->route('viewLogin');
    }
}