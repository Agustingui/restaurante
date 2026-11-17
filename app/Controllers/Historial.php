<?php

namespace App\Controllers;

use App\Models\ComandasModel;
use App\Models\DetalleComandaModel;
use App\Models\HistorialComandasModel;

class Historial extends BaseController
{
    protected $comandas;
    protected $detalle;
    protected $historial;

    public function __construct()
    {
        $this->comandas = new ComandasModel();
        $this->detalle = new DetalleComandaModel();
        $this->historial = new HistorialComandasModel();
    }

    public function index()
    {
        $datos = [
            "comandas" => $this->comandas
                ->select('comandas.*, mesas.numero AS mesa, usuarios.username AS mozo')
                ->join('pedidos', 'pedidos.id = comandas.id_pedido')
                ->join('mesas', 'mesas.id = pedidos.id_mesa')
                ->join('usuarios', 'usuarios.id = comandas.id_usuario')
                ->orderBy('comandas.id', 'DESC')
                ->findAll(200),
            "titulo" => "Comandas anteriores"
        ];

        echo view('header', $datos);
        echo view('historial/listado', $datos);
        echo view('footer');
    }

    public function ver($id)
    {
        $comanda = $this->comandas
            ->select('comandas.*, pedidos.id AS id_pedido, mesas.numero AS mesa')
            ->join('pedidos', 'pedidos.id = comandas.id_pedido')
            ->join('mesas', 'mesas.id = pedidos.id_mesa')
            ->where('comandas.id', $id)
            ->first();

        if ($comanda == null) {
            return redirect()->to(base_url('historial'));
        }

        $datos = [
            "comanda" => $comanda,
            "detalle" => $this->detalle->where('id_comanda', $id)->findAll(),
            "cambios" => $this->historial
                ->select('historial_comandas.*, usuarios.username')
                ->join('usuarios', 'usuarios.id = historial_comandas.id_usuario')
                ->where('historial_comandas.id_comanda', $id)
                ->orderBy('historial_comandas.id', 'ASC')
                ->findAll(),
            "titulo" => "Comanda " . $id
        ];

        echo view('header', $datos);
        echo view('historial/ver', $datos);
        echo view('footer');
    }
}
