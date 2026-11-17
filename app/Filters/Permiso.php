<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class Permiso implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if (!$session->get('id_usuario')) {
            return redirect()->to(base_url('login'));
        }

        if ($arguments != null && !in_array($session->get('rol'), $arguments)) {
            return redirect()->to(base_url('inicio'))->with('errores', ['No tenés permiso para entrar a esa sección']);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
