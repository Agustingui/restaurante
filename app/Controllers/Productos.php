<?php

namespace App\Controllers;

use App\Models\ProductosModel;
use App\Models\CategoriasModel;

class Productos extends BaseController
{
    protected $productos;
    protected $categorias;

    protected $reglas = [
        'id_categoria' => 'required|is_natural_no_zero',
        'nombre'       => 'required|max_length[100]',
        'descripcion'  => 'permit_empty|max_length[255]',
        'precio'       => 'required|numeric|greater_than_equal_to[0]',
        'disponible'   => 'permit_empty|in_list[0,1]'
    ];

    protected $mensajes = [
        'id_categoria' => [
            'required'           => 'Elegí una categoría',
            'is_natural_no_zero' => 'Elegí una categoría'
        ],
        'nombre' => [
            'required'   => 'Ingresá el nombre del producto',
            'max_length' => 'El nombre no puede superar los 100 caracteres'
        ],
        'descripcion' => [
            'max_length' => 'La descripción no puede superar los 255 caracteres'
        ],
        'precio' => [
            'required'              => 'Ingresá el precio',
            'numeric'               => 'El precio debe ser un número',
            'greater_than_equal_to' => 'El precio no puede ser negativo'
        ],
        'disponible' => [
            'in_list' => 'Disponible debe ser Sí o No'
        ]
    ];

    public function __construct()
    {
        $this->productos = new ProductosModel();
        $this->categorias = new CategoriasModel();
        helper('auditoria');
    }

    public function index()
    {
        $datos = [
            "productos" => $this->productos->select('productos.*, categorias.nombre AS categoria')->join('categorias', 'categorias.id = productos.id_categoria')->findAll(),
            "titulo" => "Productos"
        ];

        echo view('header', $datos);
        echo view('productos/listado', $datos);
        echo view('footer');
    }

    public function nuevo()
    {
        $datos = [
            "categorias" => $this->categorias->findAll(),
            "titulo" => "Nuevo producto"
        ];

        echo view('header', $datos);
        echo view('productos/nuevo', $datos);
        echo view('footer');
    }

    public function insertar()
    {
        $datos = $this->request->getPost(['id_categoria', 'nombre', 'descripcion', 'precio', 'disponible']);

        if (!$this->validate($this->reglas, $this->mensajes)) {
            return redirect()->back()->withInput()->with('errores', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();
        $this->productos->insert($datos);
        $id = $this->productos->getInsertID();
        registrar_auditoria('productos', $id, 'INSERT', null, $datos);
        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('errores', ['No se pudo guardar el producto']);
        }

        return redirect()->to(base_url('productos'))->with('mensaje', 'Producto guardado');
    }

    public function editar($id)
    {
        $producto = $this->productos->find($id);

        if ($producto == null) {
            return redirect()->to(base_url('productos'));
        }

        $datos = [
            "producto" => $producto,
            "categorias" => $this->categorias->findAll(),
            "titulo" => "Editar producto"
        ];

        echo view('header', $datos);
        echo view('productos/editar', $datos);
        echo view('footer');
    }

    public function actualizar()
    {
        $id = $this->request->getPost('id');

        $datos = $this->request->getPost(['id', 'id_categoria', 'nombre', 'descripcion', 'precio', 'disponible']);

        if (!$this->validate($this->reglas, $this->mensajes)) {
            return redirect()->back()->withInput()->with('errores', $this->validator->getErrors());
        }

        $anterior = $this->productos->find($id);

        $db = \Config\Database::connect();
        $db->transStart();
        $this->productos->update($id, $datos);
        registrar_auditoria('productos', $id, 'UPDATE', $anterior, $datos);
        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('errores', ['No se pudo actualizar el producto']);
        }

        return redirect()->to(base_url('productos'))->with('mensaje', 'Producto actualizado');
    }

    public function eliminar($id)
    {
        $producto = $this->productos->find($id);

        if ($producto == null) {
            return redirect()->to(base_url('productos'));
        }

        $datos = [
            "producto" => $producto,
            "titulo" => "Dar de baja producto"
        ];

        echo view('header', $datos);
        echo view('productos/eliminar', $datos);
        echo view('footer');
    }

    public function borrar($id)
    {
        $anterior = $this->productos->find($id);

        if ($anterior == null) {
            return redirect()->to(base_url('productos'));
        }

        $db = \Config\Database::connect();
        $db->transStart();
        $this->productos->delete($id);
        registrar_auditoria('productos', $id, 'BAJA', $anterior, null);
        $db->transComplete();

        return redirect()->to(base_url('productos'))->with('mensaje', 'Producto dado de baja');
    }
}
