<?php
namespace App\Controllers;

use App\Models\Empleado;

class EmpleadoController {
    private $empleadoModel;

    public function __construct($conexion) {
        $this->empleadoModel = new Empleado($conexion);
    }

    public function index() {
        return $this->empleadoModel->getAll();
    }

    public function show($id) {
        return $this->empleadoModel->getById($id);
    }

    public function store($data) {
        return $this->empleadoModel->create($data->nombre, $data->correo);
    }

    public function update($id, $data) {
        return $this->empleadoModel->update($id, $data->nombre, $data->correo);
    }

    public function delete($id) {
        return $this->empleadoModel->delete($id);
    }
}