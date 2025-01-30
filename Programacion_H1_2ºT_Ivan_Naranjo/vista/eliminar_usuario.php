<?php
// Incluir el controlador de usuario
require_once __DIR__ . '/../controlador/UsuarioController.php';

// Crear una instancia del controlador de usuario
$usuarioController = new UsuarioController();

// Verificar si se ha proporcionado un parámetro 'id' en la URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Definir la consulta SQL para eliminar un usuario basado en su ID
    $query = "DELETE FROM usuarios WHERE id = :id";
    
    // Crear una nueva instancia de la clase de conexión a la base de datos
    $db = new Conexion();
    $conn = $db->conectar();
    
    // Preparar la consulta SQL para ejecutarse de manera segura
    $stmt = $conn->prepare($query);
    
    // Vincular el parámetro :id con el valor del ID recibido en la URL
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Si la eliminación es exitosa, redirigir a la lista de usuarios con un mensaje de éxito
        header("Location: lista_usuarios.php?mensaje=Usuario eliminado correctamente");
        exit(); // Termina la ejecución para evitar que el código continúe
    } else {
        // Si ocurre un error al ejecutar la consulta, mostrar un mensaje de error
        echo "Error al eliminar usuario.";
    }
} else {
    // Si no se proporciona el ID de usuario, mostrar un mensaje de error
    echo "ID de usuario no proporcionado.";
}
?>