<?php
require_once __DIR__ . '/vendor/autoload.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET,POST,DELETE,PUT");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

use App\Controllers\EmpleadoController;

// Conecta a la base de datos usando variables de entorno
$servidor = getenv("DB_HOST");
$usuario = getenv("DB_USER");
$contrasenia = getenv("DB_PASSWORD");
$nombreBaseDatos = getenv("DB_NAME");

// Crear conexión
$conexionBD = new mysqli($servidor, $usuario, $contrasenia, $nombreBaseDatos);

// Verificar conexión
if ($conexionBD->connect_error) {
    die(json_encode(["error" => "Error de conexión: " . $conexionBD->connect_error]));
}

// Inicializar el controlador
$empleadoController = new EmpleadoController($conexionBD);

// Consultar empleado por ID
if (isset($_GET["consultar"])) {
    $resultado = $empleadoController->show($_GET["consultar"]);
    echo json_encode($resultado ? $resultado : ["success" => 0]);
    exit();
}

// Borrar empleado
if (isset($_GET["borrar"])) {
    $resultado = $empleadoController->delete($_GET["borrar"]);
    echo json_encode(["success" => $resultado ? 1 : 0]);
    exit();
}

// Insertar empleado
if (isset($_GET["insertar"])) {
    $data = json_decode(file_get_contents("php://input"));
    if (!empty($data->nombre) && !empty($data->correo)) {
        $resultado = $empleadoController->store($data);
        echo json_encode(["success" => $resultado ? 1 : 0]);
    }
    exit();
}

// Actualizar empleado
if (isset($_GET["actualizar"])) {
    $data = json_decode(file_get_contents("php://input"));
    $id = isset($data->id) ? $data->id : $_GET["actualizar"];
    $resultado = $empleadoController->update($id, $data);
    echo json_encode(["success" => $resultado ? 1 : 0]);
    exit();
}

// Obtener todos los empleados
$resultados = $empleadoController->index();
echo json_encode($resultados ?: [["success" => 0]]);

// Cerrar la conexión
$conexionBD->close();