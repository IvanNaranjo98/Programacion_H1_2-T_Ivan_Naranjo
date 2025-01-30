<?php
// Incluir el controlador de usuario para manejar las operaciones con la base de datos
require_once __DIR__ . '/../controlador/UsuarioController.php';

// Crear una instancia del controlador de usuario
$usuarioController = new UsuarioController();

// Obtener la lista de usuarios desde el controlador
$usuarios = $usuarioController->listarUsuarios();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Usuarios</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../public/css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Lista de Usuarios</h2>

        <!-- Botón para registrar un nuevo usuario que redirige al formulario de alta -->
        <a href="alta_usuario.php" class="btn btn-success mb-3">Registrar Usuario</a>

        <!-- Tabla para mostrar los usuarios -->
        <table class="table table-bordered table-striped">
            <!-- Encabezados de las columnas de la tabla -->
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Plan Base</th>
                <th>Paquete(s) Adicional(es)</th>
                <th>Duración</th>
                <th>Precio Mensual Desglosado</th>
                <th>Acciones</th>
            </tr>

            <!-- Recorrer el array de usuarios y mostrar cada uno en una fila de la tabla -->
            <?php foreach ($usuarios as $usuario) : ?>
                <tr>
                    <!-- Mostrar nombre completo (nombre y apellidos) del usuario -->
                    <td><?= htmlspecialchars($usuario['nombre'] . " " . $usuario['apellidos']) ?></td>

                    <!-- Mostrar el email del usuario -->
                    <td><?= htmlspecialchars($usuario['email']) ?></td>

                    <!-- Mostrar el plan base del usuario -->
                    <td><?= htmlspecialchars($usuario['plan_base']) ?></td>

                    <!-- Mostrar los paquetes adicionales del usuario -->
                    <td>
                        <?= !empty($usuario['paquete']) ? htmlspecialchars(str_replace(",", ", ", $usuario['paquete'])) : "Ninguno" ?>
                    </td>

                    <!-- Mostrar la duración del paquete del usuario -->
                    <td><?= htmlspecialchars($usuario['duracion']) ?></td>

                    <!-- Mostrar el precio mensual calculado para este usuario -->
                    <td>
                        <?= $usuarioController->calcularPrecioMensual($usuario['plan_base'], $usuario['paquete']) ?> €
                    </td>

                    <!-- Botones de acción para editar y eliminar el usuario -->
                    <td>
                        <!-- Botón para editar al usuario, redirige a la página de edición con el ID del usuario -->
                        <a href="editar_usuario.php?id=<?= $usuario['id'] ?>" class="btn btn-warning btn-sm">Editar</a>

                        <!-- Botón para eliminar al usuario, con una confirmación de seguridad antes de eliminar -->
                        <a href="eliminar_usuario.php?id=<?= $usuario['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este usuario?');">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>