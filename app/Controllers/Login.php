<?php

namespace App\Controllers;

use App\Models\UsuariosModel;
use App\Models\RolesModel;

class Login extends BaseController
{
    protected $usuarios;
    protected $roles;

    public function __construct()
    {
        $this->usuarios = new UsuariosModel();
        $this->roles = new RolesModel();
    }

    public function index()
    {
        if (session()->get('id_usuario')) {
            return redirect()->to(base_url('inicio'));
        }

        echo view('login');
    }

    public function entrar()
    {
        $username = trim($this->request->getPost('username'));
        $password = $this->request->getPost('password');

        $usuario = $this->usuarios->where('username', $username)->first();

        if ($usuario == null || !password_verify($password, $usuario['password_hash'])) {
            return redirect()->to(base_url('login'))->with('error', 'Usuario o contraseña incorrectos');
        }

        $rol = $this->roles->find($usuario['id_rol']);

        session()->regenerate();
        session()->set([
            'id_usuario' => $usuario['id'],
            'nombre'     => $usuario['nombre'] . ' ' . $usuario['apellido'],
            'rol'        => $rol['nombre']
        ]);

        return redirect()->to(base_url('inicio'));
    }

    public function inicio()
    {
        $rol = session()->get('rol');

        if ($rol == 'Administrador') {
            return redirect()->to(base_url('productos'));
        }

        if ($rol == 'Mozo') {
            return redirect()->to(base_url('mozo'));
        }

        return redirect()->to(base_url('cocina'));
    }

    public function salir()
    {
        session()->destroy();
        return redirect()->to(base_url('login'));
    }
}
