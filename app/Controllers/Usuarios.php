<?php

namespace App\Controllers;

use App\Models\UsuariosModel;
use App\Models\RolesModel;

class Usuarios extends BaseController
{
    protected $usuarios;
    protected $roles;

    protected $reglas = [
        'id_rol'   => 'required|is_natural_no_zero',
        'nombre'   => 'required|max_length[60]',
        'apellido' => 'required|max_length[60]',
        'username' => 'required|min_length[3]|max_length[40]|is_unique[usuarios.username]'
    ];

    protected $mensajes = [
        'id_rol' => [
            'required'           => 'Elegí un rol',
            'is_natural_no_zero' => 'Elegí un rol'
        ],
        'nombre' => [
            'required'   => 'Ingresá el nombre',
            'max_length' => 'El nombre no puede superar los 60 caracteres'
        ],
        'apellido' => [
            'required'   => 'Ingresá el apellido',
            'max_length' => 'El apellido no puede superar los 60 caracteres'
        ],
        'username' => [
            'required'   => 'Ingresá el nombre de usuario',
            'min_length' => 'El usuario debe tener al menos 3 caracteres',
            'max_length' => 'El usuario no puede superar los 40 caracteres',
            'is_unique'  => 'Ese nombre de usuario ya existe (incluso entre los dados de baja)'
        ],
        'password' => [
            'required'   => 'Ingresá una contraseña',
            'min_length' => 'La contraseña debe tener al menos 6 caracteres'
        ]
    ];

    public function __construct()
    {
        $this->usuarios = new UsuariosModel();
        $this->roles = new RolesModel();
        helper('auditoria');
    }

    public function index()
    {
        $datos = [
            "usuarios" => $this->usuarios->select('usuarios.*, roles.nombre AS rol')->join('roles', 'roles.id = usuarios.id_rol')->findAll(),
            "titulo" => "Usuarios"
        ];

        echo view('header', $datos);
        echo view('usuarios/listado', $datos);
        echo view('footer');
    }

    public function nuevo()
    {
        $datos = [
            "roles" => $this->roles->findAll(),
            "titulo" => "Nuevo usuario"
        ];

        echo view('header', $datos);
        echo view('usuarios/nuevo', $datos);
        echo view('footer');
    }

    public function insertar()
    {
        $password = $this->request->getPost('password');

        $datos = $this->request->getPost(['id_rol', 'nombre', 'apellido', 'username']);

        $reglas = $this->reglas;
        $reglas['password'] = 'required|min_length[6]';

        if (!$this->validate($reglas, $this->mensajes)) {
            return redirect()->back()->withInput()->with('errores', $this->validator->getErrors());
        }

        $datos['password_hash'] = password_hash($password, PASSWORD_DEFAULT);

        $db = \Config\Database::connect();
        $db->transStart();
        $this->usuarios->insert($datos);
        $id = $this->usuarios->getInsertID();
        registrar_auditoria('usuarios', $id, 'INSERT', null, $datos);
        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('errores', ['No se pudo guardar el usuario']);
        }

        return redirect()->to(base_url('usuarios'))->with('mensaje', 'Usuario guardado');
    }

    public function editar($id)
    {
        $usuario = $this->usuarios->find($id);

        if ($usuario == null) {
            return redirect()->to(base_url('usuarios'));
        }

        $datos = [
            "usuario" => $usuario,
            "roles" => $this->roles->findAll(),
            "titulo" => "Editar usuario"
        ];

        echo view('header', $datos);
        echo view('usuarios/editar', $datos);
        echo view('footer');
    }

    public function actualizar()
    {
        $id = $this->request->getPost('id');
        $password = $this->request->getPost('password');

        $datos = $this->request->getPost(['id', 'id_rol', 'nombre', 'apellido', 'username']);

        $reglas = $this->reglas;
        $reglas['username'] = 'required|min_length[3]|max_length[40]|is_unique[usuarios.username,id,' . $id . ']';
        $reglas['password'] = 'permit_empty|min_length[6]';

        if (!$this->validate($reglas, $this->mensajes)) {
            return redirect()->back()->withInput()->with('errores', $this->validator->getErrors());
        }

        if ($password != '') {
            $datos['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $anterior = $this->usuarios->find($id);

        $db = \Config\Database::connect();
        $db->transStart();
        $this->usuarios->update($id, $datos);
        registrar_auditoria('usuarios', $id, 'UPDATE', $anterior, $datos);
        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('errores', ['No se pudo actualizar el usuario']);
        }

        return redirect()->to(base_url('usuarios'))->with('mensaje', 'Usuario actualizado');
    }

    public function eliminar($id)
    {
        $usuario = $this->usuarios->find($id);

        if ($usuario == null) {
            return redirect()->to(base_url('usuarios'));
        }

        $datos = [
            "usuario" => $usuario,
            "titulo" => "Dar de baja usuario"
        ];

        echo view('header', $datos);
        echo view('usuarios/eliminar', $datos);
        echo view('footer');
    }

    public function borrar($id)
    {
        $anterior = $this->usuarios->find($id);

        if ($anterior == null) {
            return redirect()->to(base_url('usuarios'));
        }

        if ($id == session()->get('id_usuario')) {
            return redirect()->to(base_url('usuarios'))->with('errores', ['No podés darte de baja a vos mismo']);
        }

        $db = \Config\Database::connect();
        $db->transStart();
        $this->usuarios->delete($id);
        registrar_auditoria('usuarios', $id, 'BAJA', $anterior, null);
        $db->transComplete();

        return redirect()->to(base_url('usuarios'))->with('mensaje', 'Usuario dado de baja');
    }
}
