<?php

namespace App\Controllers;

use App\Models\MesasModel;
use App\Models\PedidosModel;

class Mesas extends BaseController
{
    protected $mesas;
    protected $pedidos;

    protected $reglas = [
        'numero'    => 'required|is_natural_no_zero|is_unique[mesas.numero,id,{id}]',
        'capacidad' => 'required|is_natural_no_zero|less_than[100]'
    ];

    protected $mensajes = [
        'numero' => [
            'required'           => 'Ingresá el número de mesa',
            'is_natural_no_zero' => 'El número de mesa debe ser mayor a 0',
            'is_unique'          => 'Ya existe una mesa con ese número (incluso entre las dadas de baja)'
        ],
        'capacidad' => [
            'required'           => 'Ingresá la capacidad',
            'is_natural_no_zero' => 'La capacidad debe ser mayor a 0',
            'less_than'          => 'La capacidad debe ser menor a 100'
        ]
    ];

    public function __construct()
    {
        $this->mesas = new MesasModel();
        $this->pedidos = new PedidosModel();
        helper('auditoria');
    }

    public function index()
    {
        $datos = [
            "mesas" => $this->mesas->orderBy('numero', 'ASC')->findAll(),
            "titulo" => "Mesas"
        ];

        echo view('header', $datos);
        echo view('mesas/listado', $datos);
        echo view('footer');
    }

    public function nuevo()
    {
        $datos = ["titulo" => "Nueva mesa"];

        echo view('header', $datos);
        echo view('mesas/nuevo', $datos);
        echo view('footer');
    }

    public function insertar()
    {
        $datos = $this->request->getPost(['numero', 'capacidad']);

        if (!$this->validate($this->reglas, $this->mensajes)) {
            return redirect()->back()->withInput()->with('errores', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();
        $this->mesas->insert($datos);
        $id = $this->mesas->getInsertID();
        registrar_auditoria('mesas', $id, 'INSERT', null, $datos);
        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('errores', ['No se pudo guardar la mesa']);
        }

        return redirect()->to(base_url('mesas'))->with('mensaje', 'Mesa guardada');
    }

    public function editar($id)
    {
        $mesa = $this->mesas->find($id);

        if ($mesa == null) {
            return redirect()->to(base_url('mesas'));
        }

        $datos = [
            "mesa" => $mesa,
            "titulo" => "Editar mesa"
        ];

        echo view('header', $datos);
        echo view('mesas/editar', $datos);
        echo view('footer');
    }

    public function actualizar()
    {
        $id = $this->request->getPost('id');

        $datos = $this->request->getPost(['id', 'numero', 'capacidad']);

        if (!$this->validate($this->reglas, $this->mensajes)) {
            return redirect()->back()->withInput()->with('errores', $this->validator->getErrors());
        }

        $anterior = $this->mesas->find($id);

        $db = \Config\Database::connect();
        $db->transStart();
        $this->mesas->update($id, $datos);
        registrar_auditoria('mesas', $id, 'UPDATE', $anterior, $datos);
        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('errores', ['No se pudo actualizar la mesa']);
        }

        return redirect()->to(base_url('mesas'))->with('mensaje', 'Mesa actualizada');
    }

    public function eliminar($id)
    {
        $mesa = $this->mesas->find($id);

        if ($mesa == null) {
            return redirect()->to(base_url('mesas'));
        }

        $datos = [
            "mesa" => $mesa,
            "titulo" => "Dar de baja mesa"
        ];

        echo view('header', $datos);
        echo view('mesas/eliminar', $datos);
        echo view('footer');
    }

    public function borrar($id)
    {
        $anterior = $this->mesas->find($id);

        if ($anterior == null) {
            return redirect()->to(base_url('mesas'));
        }

        if ($this->pedidos->where('id_mesa', $id)->where('estado', 'abierto')->countAllResults() > 0) {
            return redirect()->to(base_url('mesas'))->with('errores', ['No se puede dar de baja una mesa con un pedido abierto']);
        }

        $db = \Config\Database::connect();
        $db->transStart();
        $this->mesas->delete($id);
        registrar_auditoria('mesas', $id, 'BAJA', $anterior, null);
        $db->transComplete();

        return redirect()->to(base_url('mesas'))->with('mensaje', 'Mesa dada de baja');
    }
}
