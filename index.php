/**
 * API REST para gestión de empleados
 * 
 * Este archivo maneja las operaciones CRUD para empleados a través de endpoints REST.
 * Utiliza variables de entorno para la configuración de la base de datos.
 * 
 * Endpoints disponibles:
 * - GET /?consultar={id}  : Obtiene un empleado por ID
 * - GET /                 : Lista todos los empleados
 * - POST /?insertar=1     : Crea un nuevo empleado
 * - PUT /?actualizar={id} : Actualiza un empleado existente
 * - DELETE /?borrar={id}  : Elimina un empleado
 * 
 * Headers CORS configurados para permitir acceso desde cualquier origen
 * 
 * Formato de datos esperado para POST/PUT:
 * {
 *   "nombre": "string",
 *   "correo": "string"
 * }
 * 
 * Respuestas:
 * - Éxito: {"success": 1} 
 * - Error: {"success": 0} o {"error": "mensaje"}
 * 
 * @requires PHP >= 7.0
 * @requires mysqli
 * @requires dotenv
 */
<?php
require_once __DIR__ . '/vendor/autoload.php';

// Cargar variables de entorno
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET,POST,DELETE,PUT");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle preflight OPTIONS request
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
    if (isset($_GET["consultar"])) {
        // Consultar empleado por ID
        $resultado = $empleadoController->show($_GET["consultar"]);
        echo json_encode($resultado ?: ["success" => 0]);
    } elseif (isset($_GET["borrar"])) {
        // Borrar empleado
        $resultado = $empleadoController->delete($_GET["borrar"]);
        echo json_encode(["success" => $resultado ? 1 : 0]);
    } elseif (isset($_GET["insertar"])) {
        // Insertar empleado
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->nombre) && !empty($data->correo)) {
            $resultado = $empleadoController->store($data);
            echo json_encode(["success" => $resultado ? 1 : 0]);
        } else {
            throw new Exception("Nombre y correo son requeridos");
        }
    } elseif (isset($_GET["actualizar"])) {
        // Actualizar empleado
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->nombre) && !empty($data->correo)) {
            $id = isset($data->id) ? $data->id : $_GET["actualizar"];
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