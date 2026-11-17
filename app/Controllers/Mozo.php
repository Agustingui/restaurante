<?php

namespace App\Controllers;

use App\Models\MesasModel;
use App\Models\PedidosModel;
use App\Models\ComandasModel;
use App\Models\DetalleComandaModel;
use App\Models\ProductosModel;
use App\Models\CategoriasModel;
use App\Models\HistorialComandasModel;

class Mozo extends BaseController
{
    protected $mesas;
    protected $pedidos;
    protected $comandas;
    protected $detalle;
    protected $productos;
    protected $categorias;
    protected $historial;

    public function __construct()
    {
        $this->mesas = new MesasModel();
        $this->pedidos = new PedidosModel();
        $this->comandas = new ComandasModel();
        $this->detalle = new DetalleComandaModel();
        $this->productos = new ProductosModel();
        $this->categorias = new CategoriasModel();
        $this->historial = new HistorialComandasModel();
    }

    public function index()
    {
        $abiertos = $this->pedidos->where('estado', 'abierto')->findAll();

        $ocupadas = [];
        foreach ($abiertos as $pedido) {
            $ocupadas[] = $pedido['id_mesa'];
        }

        $preparadas = $this->comandas
            ->select('pedidos.id_mesa, COUNT(*) AS cantidad', false)
            ->join('pedidos', 'pedidos.id = comandas.id_pedido')
            ->where('comandas.estado', 'preparada')
            ->groupBy('pedidos.id_mesa')
            ->findAll();

        $listas = [];
        foreach ($preparadas as $fila) {
            $listas[$fila['id_mesa']] = $fila['cantidad'];
        }

        $datos = [
            "mesas" => $this->mesas->orderBy('numero', 'ASC')->findAll(),
            "ocupadas" => $ocupadas,
            "listas" => $listas,
            "titulo" => "Mesas"
        ];

        echo view('header', $datos);
        echo view('mozo/mesas', $datos);
        echo view('footer');
    }

    public function mesa($id)
    {
        $mesa = $this->mesas->find($id);

        if ($mesa == null) {
            return redirect()->to(base_url('mozo'));
        }

        $pedido = $this->pedidos->where('id_mesa', $id)->where('estado', 'abierto')->first();

        $comandas = [];
        if ($pedido != null) {
            $comandas = $this->comandas->where('id_pedido', $pedido['id'])->orderBy('id', 'DESC')->findAll();

            foreach ($comandas as $clave => $comanda) {
                $comandas[$clave]['detalle'] = $this->detalle->where('id_comanda', $comanda['id'])->findAll();
            }
        }

        $datos = [
            "mesa" => $mesa,
            "pedido" => $pedido,
            "comandas" => $comandas,
            "categorias" => $this->categorias->findAll(),
            "productos" => $this->productos->where('disponible', 1)->findAll(),
            "titulo" => "Mesa " . $mesa['numero']
        ];

        echo view('header', $datos);
        echo view('mozo/pedido', $datos);
        echo view('footer');
    }

    public function avisos()
    {
        $comandas = $this->comandas
            ->select('comandas.id, mesas.numero AS mesa, pedidos.id_mesa')
            ->join('pedidos', 'pedidos.id = comandas.id_pedido')
            ->join('mesas', 'mesas.id = pedidos.id_mesa')
            ->where('comandas.estado', 'preparada')
            ->orderBy('comandas.id', 'ASC')
            ->findAll();

        return $this->response->setJSON($comandas);
    }

    public function abrir()
    {
        $id_mesa = $this->request->getPost('id_mesa');

        $mesa = $this->mesas->find($id_mesa);

        if ($mesa == null) {
            return redirect()->to(base_url('mozo'));
        }

        $abierto = $this->pedidos->where('id_mesa', $id_mesa)->where('estado', 'abierto')->first();

        if ($abierto == null) {
            $this->pedidos->insert([
                'id_mesa' => $id_mesa,
                'id_usuario' => session()->get('id_usuario'),
                'estado' => 'abierto'
            ]);
        }

        return redirect()->to(base_url('mozo/mesa/' . $id_mesa));
    }

    public function enviar()
    {
        $id_pedido = $this->request->getPost('id_pedido');
        $cantidades = $this->request->getPost('cantidad');
        $observaciones = $this->request->getPost('observaciones');

        $pedido = $this->pedidos->find($id_pedido);

        if ($pedido == null || $pedido['estado'] != 'abierto') {
            return redirect()->to(base_url('mozo'))->with('errores', ['El pedido no está abierto']);
        }

        $items = [];

        if ($cantidades != null) {
            foreach ($cantidades as $id_producto => $cantidad) {
                $cantidad = (int) $cantidad;

                if ($cantidad < 1) {
                    continue;
                }

                if ($cantidad > 99) {
                    $cantidad = 99;
                }

                $producto = $this->productos->find($id_producto);

                if ($producto == null || $producto['disponible'] != 1) {
                    continue;
                }

                $observacion = trim($observaciones[$id_producto]);

                $items[] = [
                    'id_producto' => $producto['id'],
                    'nombre_producto' => $producto['nombre'],
                    'precio_unitario' => $producto['precio'],
                    'cantidad' => $cantidad,
                    'observaciones' => $observacion == '' ? null : substr($observacion, 0, 255)
                ];
            }
        }

        if (count($items) == 0) {
            return redirect()->back()->with('errores', ['Cargá al menos un producto con cantidad mayor a 0']);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $this->comandas->insert([
            'id_pedido' => $id_pedido,
            'id_usuario' => session()->get('id_usuario'),
            'estado' => 'pendiente'
        ]);
        $id_comanda = $this->comandas->getInsertID();

        foreach ($items as $item) {
            $item['id_comanda'] = $id_comanda;
            $this->detalle->insert($item);
        }

        $this->historial->insert([
            'id_comanda' => $id_comanda,
            'id_usuario' => session()->get('id_usuario'),
            'estado_anterior' => null,
            'estado_nuevo' => 'pendiente',
            'observacion' => 'Comanda enviada a cocina'
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('errores', ['No se pudo enviar la comanda']);
        }

        return redirect()->to(base_url('mozo/mesa/' . $pedido['id_mesa']))->with('mensaje', 'Comanda enviada a cocina');
    }

    public function entregar($id)
    {
        $comanda = $this->comandas->find($id);

        if ($comanda == null) {
            return redirect()->to(base_url('mozo'));
        }

        $pedido = $this->pedidos->find($comanda['id_pedido']);

        if ($comanda['estado'] != 'preparada') {
            return redirect()->to(base_url('mozo/mesa/' . $pedido['id_mesa']))->with('errores', ['La comanda todavía no está preparada']);
        }

        $db = \Config\Database::connect();
        $db->transStart();
        $this->comandas->update($id, ['estado' => 'entregada']);
        $this->historial->insert([
            'id_comanda' => $id,
            'id_usuario' => session()->get('id_usuario'),
            'estado_anterior' => 'preparada',
            'estado_nuevo' => 'entregada',
            'observacion' => 'Entrega registrada por el mozo'
        ]);
        $db->transComplete();

        return redirect()->to(base_url('mozo/mesa/' . $pedido['id_mesa']))->with('mensaje', 'Entrega registrada');
    }

    public function cancelarcomanda($id)
    {
        $comanda = $this->comandas->find($id);

        if ($comanda == null) {
            return redirect()->to(base_url('mozo'));
        }

        $pedido = $this->pedidos->find($comanda['id_pedido']);

        if ($comanda['estado'] != 'pendiente') {
            return redirect()->to(base_url('mozo/mesa/' . $pedido['id_mesa']))->with('errores', ['Solo se puede cancelar una comanda pendiente']);
        }

        $db = \Config\Database::connect();
        $db->transStart();
        $this->comandas->update($id, ['estado' => 'cancelada']);
        $this->historial->insert([
            'id_comanda' => $id,
            'id_usuario' => session()->get('id_usuario'),
            'estado_anterior' => 'pendiente',
            'estado_nuevo' => 'cancelada',
            'observacion' => 'Comanda cancelada por el mozo'
        ]);
        $db->transComplete();

        return redirect()->to(base_url('mozo/mesa/' . $pedido['id_mesa']))->with('mensaje', 'Comanda cancelada');
    }

    public function cerrar()
    {
        $id_pedido = $this->request->getPost('id_pedido');

        $pedido = $this->pedidos->find($id_pedido);

        if ($pedido == null || $pedido['estado'] != 'abierto') {
            return redirect()->to(base_url('mozo'));
        }

        $sin_entregar = $this->comandas->where('id_pedido', $id_pedido)->whereIn('estado', ['pendiente', 'en_preparacion', 'preparada'])->countAllResults();

        if ($sin_entregar > 0) {
            return redirect()->back()->with('errores', ['No se puede cerrar: hay comandas sin entregar']);
        }

        $cantidad = $this->comandas->where('id_pedido', $id_pedido)->countAllResults();

        $this->pedidos->update($id_pedido, [
            'estado' => $cantidad == 0 ? 'cancelado' : 'cerrado',
            'fecha_cierre' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to(base_url('mozo'))->with('mensaje', 'Pedido cerrado');
    }
}
