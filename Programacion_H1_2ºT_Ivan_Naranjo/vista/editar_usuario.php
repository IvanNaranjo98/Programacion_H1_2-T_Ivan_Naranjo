<?php
// Incluir el controlador del usuario para interactuar con la base de datos.
require_once __DIR__ . '/../controlador/UsuarioController.php';

// Crear una instancia del controlador de usuario
$usuarioController = new UsuarioController();
$mensaje = "";

// Verificar si se proporciona un ID de usuario en la URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Obtener los datos del usuario según el ID proporcionado
    $usuario = $usuarioController->obtenerUsuarioPorId($id);
    // Si no se encuentra el usuario, mostrar un mensaje de error y detener la ejecución
    if (!$usuario) {
        die("Usuario no encontrado.");
    }
} else {
    // Si no se proporciona un ID, mostrar un mensaje de error
    die("ID de usuario no proporcionado.");
}

// Comprobar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Llamar al método de actualización del usuario con los datos del formulario
    $mensaje = $usuarioController->actualizarUsuario(
        $_POST['id'], 
        $_POST['nombre'], 
        $_POST['apellidos'], 
        $_POST['email'], 
        $_POST['edad'], 
        $_POST['plan_base'], 
        $_POST['paquete'], 
        $_POST['duracion']
    );

    // Si la actualización es exitosa, redirigir a la lista de usuarios
    if ($mensaje == "Usuario actualizado correctamente.") {
        header("Location: lista_usuarios.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../public/css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Editar Usuario</h2>
        
        <!-- Mostrar mensaje si hay algún error o mensaje de éxito -->
        <?php if ($mensaje && $mensaje !== "Usuario actualizado correctamente.") : ?>
            <div class="alert alert-info"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <!-- Formulario para editar los datos del usuario -->
        <form method="post">
            <!-- Campo oculto para enviar el ID del usuario al servidor -->
            <input type="hidden" name="id" value="<?= htmlspecialchars($usuario['id']) ?>">
            
            <!-- Campo para editar el nombre del usuario -->
            <div class="form-group">
                <label>Nombre:</label>
                <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
            </div>

            <!-- Campo para editar los apellidos del usuario -->
            <div class="form-group">
                <label>Apellidos:</label>
                <input type="text" name="apellidos" class="form-control" value="<?= htmlspecialchars($usuario['apellidos']) ?>" required>
            </div>

            <!-- Campo para editar el email del usuario -->
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($usuario['email']) ?>" required>
            </div>

            <!-- Campo para editar la edad del usuario -->
            <div class="form-group">
                <label>Edad:</label>
                <input type="number" name="edad" class="form-control" value="<?= htmlspecialchars($usuario['edad']) ?>" required>
            </div>

            <!-- Selector para elegir el plan base del usuario -->
            <div class="form-group">
                <label>Plan Base:</label>
                <select name="plan_base" class="form-control">
                    <!-- Opciones de planes base (Básico, Estándar, Premium) -->
                    <option value="Básico" <?= ($usuario['plan_base'] == 'Básico') ? 'selected' : '' ?>>Básico</option>
                    <option value="Estándar" <?= ($usuario['plan_base'] == 'Estándar') ? 'selected' : '' ?>>Estándar</option>
                    <option value="Premium" <?= ($usuario['plan_base'] == 'Premium') ? 'selected' : '' ?>>Premium</option>
                </select>
            </div>

            <!-- Selector para elegir el paquete adicional (Infantil, Cine, Deporte) -->
            <div class="form-group">
                <label>Paquete Adicional:</label>
                <select name="paquete" class="form-control">
                    <option value="Infantil" <?= ($usuario['paquete'] == 'Infantil') ? 'selected' : '' ?>>Infantil</option>
                    <option value="Cine" <?= ($usuario['paquete'] == 'Cine') ? 'selected' : '' ?>>Cine</option>
                    <option value="Deporte" <?= ($usuario['paquete'] == 'Deporte') ? 'selected' : '' ?>>Deporte</option>
                </select>
            </div>

            <!-- Selector para elegir la duración del paquete (Mensual, Anual) -->
            <div class="form-group">
                <label>Duración:</label>
                <select name="duracion" class="form-control">
                    <option value="Mensual" <?= ($usuario['duracion'] == 'Mensual') ? 'selected' : '' ?>>Mensual</option>
                    <option value="Anual" <?= ($usuario['duracion'] == 'Anual') ? 'selected' : '' ?>>Anual</option>
                </select>
            </div>

            <!-- Botón para enviar el formulario -->
            <button type="submit" class="btn btn-primary mt-3">Actualizar</button>
            <!-- Enlace para cancelar la acción y regresar a la lista de usuarios -->
            <a href="lista_usuarios.php" class="btn btn-secondary mt-3">Cancelar</a>
        </form>
    </div>
</body>
</html>