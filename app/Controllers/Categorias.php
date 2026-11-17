<?php

namespace App\Controllers;

use App\Models\CategoriasModel;
use App\Models\ProductosModel;

class Categorias extends BaseController
{
    protected $categorias;
    protected $productos;

    protected $reglas = [
        'nombre'      => 'required|max_length[50]|is_unique[categorias.nombre,id,{id}]',
        'descripcion' => 'permit_empty|max_length[150]'
    ];

    protected $mensajes = [
        'nombre' => [
            'required'   => 'Ingresá el nombre de la categoría',
            'max_length' => 'El nombre no puede superar los 50 caracteres',
            'is_unique'  => 'Ya existe una categoría con ese nombre (incluso entre las dadas de baja)'
        ],
        'descripcion' => [
            'max_length' => 'La descripción no puede superar los 150 caracteres'
        ]
    ];

    public function __construct()
    {
        $this->categorias = new CategoriasModel();
        $this->productos = new ProductosModel();
        helper('auditoria');
    }

    public function index()
    {
        $datos = [
            "categorias" => $this->categorias->findAll(),
            "titulo" => "Categorías"
        ];

        echo view('header', $datos);
        echo view('categorias/listado', $datos);
        echo view('footer');
    }

    public function nuevo()
    {
        $datos = ["titulo" => "Nueva categoría"];

        echo view('header', $datos);
        echo view('categorias/nuevo', $datos);
        echo view('footer');
    }

    public function insertar()
    {
        $datos = $this->request->getPost(['nombre', 'descripcion']);

        if (!$this->validate($this->reglas, $this->mensajes)) {
            return redirect()->back()->withInput()->with('errores', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();
        $this->categorias->insert($datos);
        $id = $this->categorias->getInsertID();
        registrar_auditoria('categorias', $id, 'INSERT', null, $datos);
        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('errores', ['No se pudo guardar la categoría']);
        }

        return redirect()->to(base_url('categorias'))->with('mensaje', 'Categoría guardada');
    }

    public function editar($id)
    {
        $categoria = $this->categorias->find($id);

        if ($categoria == null) {
            return redirect()->to(base_url('categorias'));
        }

        $datos = [
            "categoria" => $categoria,
            "titulo" => "Editar categoría"
        ];

        echo view('header', $datos);
        echo view('categorias/editar', $datos);
        echo view('footer');
    }

    public function actualizar()
    {
        $id = $this->request->getPost('id');

        $datos = $this->request->getPost(['id', 'nombre', 'descripcion']);

        if (!$this->validate($this->reglas, $this->mensajes)) {
            return redirect()->back()->withInput()->with('errores', $this->validator->getErrors());
        }

        $anterior = $this->categorias->find($id);

        $db = \Config\Database::connect();
        $db->transStart();
        $this->categorias->update($id, $datos);
        registrar_auditoria('categorias', $id, 'UPDATE', $anterior, $datos);
        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('errores', ['No se pudo actualizar la categoría']);
        }

        return redirect()->to(base_url('categorias'))->with('mensaje', 'Categoría actualizada');
    }

    public function eliminar($id)
    {
        $categoria = $this->categorias->find($id);

        if ($categoria == null) {
            return redirect()->to(base_url('categorias'));
        }

        $datos = [
            "categoria" => $categoria,
            "titulo" => "Dar de baja categoría"
        ];

        echo view('header', $datos);
        echo view('categorias/eliminar', $datos);
        echo view('footer');
    }

    public function borrar($id)
    {
        $anterior = $this->categorias->find($id);

        if ($anterior == null) {
            return redirect()->to(base_url('categorias'));
        }

        if ($this->productos->where('id_categoria', $id)->countAllResults() > 0) {
            return redirect()->to(base_url('categorias'))->with('errores', ['No se puede dar de baja una categoría que tiene productos. Primero dá de baja sus productos.']);
        }

        $db = \Config\Database::connect();
        $db->transStart();
        $this->categorias->delete($id);
        registrar_auditoria('categorias', $id, 'BAJA', $anterior, null);
        $db->transComplete();

        return redirect()->to(base_url('categorias'))->with('mensaje', 'Categoría dada de baja');
    }
}
