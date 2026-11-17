<?php

namespace App\Controllers;

use App\Models\AuditoriaModel;

class Auditoria extends BaseController
{
    protected $auditoria;

    public function __construct()
    {
        $this->auditoria = new AuditoriaModel();
    }

    public function index()
    {
        $datos = [
            "registros" => $this->auditoria
                ->select('auditoria.*, usuarios.username')
                ->join('usuarios', 'usuarios.id = auditoria.id_usuario')
                ->orderBy('auditoria.id', 'DESC')
                ->findAll(200),
            "titulo" => "Auditoría"
        ];

        echo view('header', $datos);
        echo view('auditoria/listado', $datos);
        echo view('footer');
    }
}
