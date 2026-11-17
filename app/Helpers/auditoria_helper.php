<?php

use App\Models\AuditoriaModel;

function registrar_auditoria($entidad, $id_registro, $accion, $anterior, $datos)
{
    $auditoria = new AuditoriaModel();
    $ignorar = ['id', 'password_hash', 'fecha_creacion', 'fecha_actualizacion', 'deleted_at'];
    $anteriores = null;
    $nuevos = null;

    if ($accion == 'INSERT') {
        foreach ($datos as $campo => $valor) {
            if (!in_array($campo, $ignorar)) {
                $nuevos[$campo] = $valor;
            }
        }
        if (isset($datos['password_hash'])) {
            $nuevos['password'] = 'establecida';
        }
    }

    if ($accion == 'UPDATE') {
        foreach ($datos as $campo => $valor) {
            if (!in_array($campo, $ignorar) && $anterior[$campo] != $valor) {
                $anteriores[$campo] = $anterior[$campo];
                $nuevos[$campo] = $valor;
            }
        }
        if (isset($datos['password_hash'])) {
            $nuevos['password'] = 'modificada';
        }
    }

    if ($accion == 'BAJA') {
        foreach ($anterior as $campo => $valor) {
            if (!in_array($campo, $ignorar)) {
                $anteriores[$campo] = $valor;
            }
        }
    }

    $auditoria->insert([
        'id_usuario'       => session()->get('id_usuario'),
        'entidad'          => $entidad,
        'id_registro'      => $id_registro,
        'accion'           => $accion,
        'datos_anteriores' => $anteriores == null ? null : json_encode($anteriores, JSON_UNESCAPED_UNICODE),
        'datos_nuevos'     => $nuevos == null ? null : json_encode($nuevos, JSON_UNESCAPED_UNICODE)
    ]);
}
