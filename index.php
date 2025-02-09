<?php
require_once __DIR__ . '/vendor/autoload.php';

// Cargar variables de entorno
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access, Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");

// Manejar petición preflight OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("HTTP/1.1 200 OK");
    exit();
}

use App\Controllers\EmpleadoController;

try {
    // Conectar a la base de datos usando variables de entorno
    $servidor = $_ENV["DB_HOST"];
    $usuario = $_ENV["DB_USER"];
    $contrasenia = $_ENV["DB_PASSWORD"];
    $nombreBaseDatos = $_ENV["DB_NAME"];

    // Crear conexión
    $conexionBD = new mysqli($servidor, $usuario, $contrasenia, $nombreBaseDatos);

    // Verificar conexión
    if ($conexionBD->connect_error) {
        throw new Exception("Error de conexión: " . $conexionBD->connect_error);
    }

    // Inicializar el controlador
    $empleadoController = new EmpleadoController($conexionBD);

    // Router básico
    if ($_SERVER['REQUEST_METHOD'] === "GET" && isset($_GET["consultar"])) {
        // Consultar empleado por ID
        $resultado = $empleadoController->show($_GET["consultar"]);
        echo json_encode($resultado ?: ["success" => 0]);
    } elseif ($_SERVER['REQUEST_METHOD'] === "GET" && isset($_GET["borrar"])) {
        // Borrar empleado (idealmente, usar el método DELETE)
        $resultado = $empleadoController->delete($_GET["borrar"]);
        echo json_encode(["success" => $resultado ? 1 : 0]);
    } elseif ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_GET["insertar"])) {
        // Insertar empleado
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->nombre) && !empty($data->correo)) {
            $resultado = $empleadoController->store($data);
            echo json_encode(["success" => $resultado ? 1 : 0]);
        } else {
            throw new Exception("Nombre y correo son requeridos");
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === "PUT" && isset($_GET["actualizar"])) {
        // Actualizar empleado
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->nombre) && !empty($data->correo)) {
            $id = $_GET["actualizar"];
            $resultado = $empleadoController->update($id, $data);
            echo json_encode(["success" => $resultado ? 1 : 0]);
        } else {
            throw new Exception("Nombre y correo son requeridos");
        }
    } else {
        // Obtener todos los empleados
        $resultados = $empleadoController->index();
        echo json_encode($resultados ?: []);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
} finally {
    // Cerrar la conexión
    if (isset($conexionBD)) {
        $conexionBD->close();
    }
}