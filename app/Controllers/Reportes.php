<?php

namespace App\Controllers;

use App\Models\ComandasModel;
use App\Models\DetalleComandaModel;

class Reportes extends BaseController
{
    protected $comandas;
    protected $detalle;

    protected $reglas = [
        'desde' => 'permit_empty|valid_date[Y-m-d]',
        'hasta' => 'permit_empty|valid_date[Y-m-d]'
    ];

    protected $mensajes = [
        'desde' => [
            'valid_date' => 'La fecha desde no es válida'
        ],
        'hasta' => [
            'valid_date' => 'La fecha hasta no es válida'
        ]
    ];

    public function __construct()
    {
        $this->comandas = new ComandasModel();
        $this->detalle = new DetalleComandaModel();
    }

    public function index()
    {
        $desde = date('Y-m-01');
        $hasta = date('Y-m-d');
        $errores = [];

        if ($this->request->getGet('desde') != '' || $this->request->getGet('hasta') != '') {
            if (!$this->validate($this->reglas, $this->mensajes)) {
                $errores = $this->validator->getErrors();
            } else {
                $nuevo_desde = $this->request->getGet('desde');
                $nuevo_hasta = $this->request->getGet('hasta');

                if ($nuevo_desde != '') {
                    $desde = $nuevo_desde;
                }

                if ($nuevo_hasta != '') {
                    $hasta = $nuevo_hasta;
                }

                if ($desde > $hasta) {
                    $errores[] = 'La fecha desde no puede ser posterior a la fecha hasta';
                    $desde = date('Y-m-01');
                    $hasta = date('Y-m-d');
                }
            }
        }

        $ventas_por_dia = $this->comandas
            ->select('DATE(comandas.fecha_creacion) AS dia, COUNT(DISTINCT comandas.id) AS comandas, SUM(detalle_comanda.cantidad) AS unidades, SUM(detalle_comanda.cantidad * detalle_comanda.precio_unitario) AS total', false)
            ->join('detalle_comanda', 'detalle_comanda.id_comanda = comandas.id')
            ->where('comandas.estado !=', 'cancelada')
            ->where('comandas.fecha_creacion >=', $desde . ' 00:00:00')
            ->where('comandas.fecha_creacion <=', $hasta . ' 23:59:59')
            ->groupBy('DATE(comandas.fecha_creacion)')
            ->orderBy('dia', 'DESC')
            ->findAll();

        $ranking = $this->detalle
            ->select('detalle_comanda.nombre_producto, SUM(detalle_comanda.cantidad) AS unidades, SUM(detalle_comanda.cantidad * detalle_comanda.precio_unitario) AS total', false)
            ->join('comandas', 'comandas.id = detalle_comanda.id_comanda')
            ->where('comandas.estado !=', 'cancelada')
            ->where('comandas.fecha_creacion >=', $desde . ' 00:00:00')
            ->where('comandas.fecha_creacion <=', $hasta . ' 23:59:59')
            ->groupBy('detalle_comanda.id_producto, detalle_comanda.nombre_producto')
            ->orderBy('unidades', 'DESC')
            ->findAll();

        $total_vendido = 0;
        $total_comandas = 0;
        $total_unidades = 0;

        foreach ($ventas_por_dia as $fila) {
            $total_vendido = $total_vendido + $fila['total'];
            $total_comandas = $total_comandas + $fila['comandas'];
            $total_unidades = $total_unidades + $fila['unidades'];
        }

        $datos = [
            "desde" => $desde,
            "hasta" => $hasta,
            "errores" => $errores,
            "ventas_por_dia" => $ventas_por_dia,
            "ranking" => $ranking,
            "total_vendido" => $total_vendido,
            "total_comandas" => $total_comandas,
            "total_unidades" => $total_unidades,
            "titulo" => "Reporte de ventas"
        ];

        echo view('header', $datos);
        echo view('reportes/ventas', $datos);
        echo view('footer');
    }
}
