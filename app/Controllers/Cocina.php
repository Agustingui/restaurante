<?php

namespace App\Controllers;

use App\Models\ComandasModel;
use App\Models\DetalleComandaModel;
use App\Models\HistorialComandasModel;

class Cocina extends BaseController
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
        $datos = ["titulo" => "Cocina"];

        echo view('header', $datos);
        echo view('cocina/pantalla', $datos);
        echo view('footer');
    }

    public function listar()
    {
        $comandas = $this->comandas
            ->select('comandas.id, comandas.estado, mesas.numero AS mesa, TIMESTAMPDIFF(SECOND, comandas.fecha_creacion, NOW()) AS segundos', false)
            ->join('pedidos', 'pedidos.id = comandas.id_pedido')
            ->join('mesas', 'mesas.id = pedidos.id_mesa')
            ->whereIn('comandas.estado', ['pendiente', 'en_preparacion'])
            ->orderBy('comandas.fecha_creacion', 'ASC')
            ->findAll();

        foreach ($comandas as $clave => $comanda) {
            $comandas[$clave]['detalle'] = $this->detalle->select('nombre_producto, cantidad, observaciones')->where('id_comanda', $comanda['id'])->findAll();
        }

        return $this->response->setJSON($comandas);
    }

    public function cambiarestado()
    {
        $id = $this->request->getPost('id');

        $comanda = $this->comandas->find($id);

        if ($comanda == null) {
            return $this->response->setStatusCode(400)->setJSON(['ok' => false, 'mensaje' => 'La comanda no existe']);
        }

        if ($comanda['estado'] == 'pendiente') {
            $nuevo = 'en_preparacion';
            $observacion = 'Cocina comenzó la preparación';
        } elseif ($comanda['estado'] == 'en_preparacion') {
            $nuevo = 'preparada';
            $observacion = 'Cocina terminó el pedido';
        } else {
            return $this->response->setStatusCode(400)->setJSON(['ok' => false, 'mensaje' => 'La comanda ya no se puede modificar desde cocina']);
        }

        $db = \Config\Database::connect();
        $db->transStart();
        $this->comandas->update($id, ['estado' => $nuevo]);
        $this->historial->insert([
            'id_comanda' => $id,
            'id_usuario' => session()->get('id_usuario'),
            'estado_anterior' => $comanda['estado'],
            'estado_nuevo' => $nuevo,
            'observacion' => $observacion
        ]);
        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setStatusCode(500)->setJSON(['ok' => false, 'mensaje' => 'No se pudo actualizar el estado']);
        }

        return $this->response->setJSON(['ok' => true, 'mensaje' => 'Comanda #' . $id . ' ahora está ' . str_replace('_', ' ', $nuevo)]);
    }
}
