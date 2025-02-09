<?php
namespace App\Models;

class Empleado {
    private $id;
    private $nombre;
    private $correo;
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function getAll() {
        $query = "SELECT * FROM empleados";
        $result = $this->conexion->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getById($id) {
        $id = $this->conexion->real_escape_string($id);
        $query = "SELECT * FROM empleados WHERE id = $id";
        $result = $this->conexion->query($query);
        return $result->fetch_assoc();
    }

    public function create($nombre, $correo) {
        $nombre = $this->conexion->real_escape_string($nombre);
        $correo = $this->conexion->real_escape_string($correo);
        $query = "INSERT INTO empleados(nombre, correo) VALUES('$nombre', '$correo')";
        return $this->conexion->query($query);
    }

    public function update($id, $nombre, $correo) {
        $id = $this->conexion->real_escape_string($id);
        $nombre = $this->conexion->real_escape_string($nombre);
        $correo = $this->conexion->real_escape_string($correo);
        $query = "UPDATE empleados SET nombre='$nombre', correo='$correo' WHERE id='$id'";
        return $this->conexion->query($query);
    }

    public function delete($id) {
        $id = $this->conexion->real_escape_string($id);
        $query = "DELETE FROM empleados WHERE id = $id";
        return $this->conexion->query($query);
    }
}