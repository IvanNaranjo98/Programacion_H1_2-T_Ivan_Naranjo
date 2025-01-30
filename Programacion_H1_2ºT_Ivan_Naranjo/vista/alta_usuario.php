<?php
// Incluye el controlador de usuarios para gestionar el registro
require_once __DIR__ . '/../controlador/UsuarioController.php';

// Crea una instancia del controlador de usuario
$usuarioController = new UsuarioController();
$mensaje = ""; // Variable para almacenar mensajes de estado

// Verifica si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Convierte los paquetes seleccionados en una cadena separada por comas o asigna "Ninguno" si no hay selección
    $paquetesSeleccionados = isset($_POST['paquete']) ? implode(",", $_POST['paquete']) : "Ninguno";

    // Llama al método para registrar un usuario con los datos recibidos del formulario
    $mensaje = $usuarioController->registrarUsuario(
        $_POST['nombre'],
        $_POST['apellidos'],
        $_POST['email'],
        $_POST['edad'],
        $_POST['plan_base'],
        $paquetesSeleccionados,
        $_POST['duracion']
    );

    // Si el usuario se ha registrado correctamente, redirige a la lista de usuarios
    if ($mensaje == "Usuario registrado correctamente.") {
        header("Location: lista_usuarios.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Usuario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../public/css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Registrar Usuario</h2>
        <!-- Muestra el mensaje de error o éxito si existe -->
        <?php if ($mensaje && $mensaje !== "Usuario registrado correctamente.") : ?>
            <div class="alert alert-info"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>
        
        <!-- Formulario de registro de usuario -->
        <form method="post">
            <div class="form-group">
                <label>Nombre:</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Apellidos:</label>
                <input type="text" name="apellidos" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Edad:</label>
                <input type="number" name="edad" id="edad" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Plan Base:</label>
                <select name="plan_base" id="plan_base" class="form-control">
                    <option value="Básico">Básico</option>
                    <option value="Estándar">Estándar</option>
                    <option value="Premium">Premium</option>
                </select>
            </div>

            <!-- Selección de paquetes adicionales -->
            <div class="form-group">
                <label>Paquetes Adicionales:</label>
                <div>
                    <input type="checkbox" name="paquete[]" value="Infantil" id="paquete_infantil">
                    <label for="paquete_infantil">Infantil</label>
                </div>
                <div>
                    <input type="checkbox" name="paquete[]" value="Cine" id="paquete_cine">
                    <label for="paquete_cine">Cine</label>
                </div>
                <div>
                    <input type="checkbox" name="paquete[]" value="Deporte" id="paquete_deporte">
                    <label for="paquete_deporte">Deporte (Solo Anual)</label>
                </div>
            </div>

            <div class="form-group">
                <label>Duración:</label>
                <select name="duracion" id="duracion" class="form-control">
                    <option value="Mensual">Mensual</option>
                    <option value="Anual">Anual</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Registrar</button>
            <a href="lista_usuarios.php" class="btn btn-secondary mt-3">Volver</a>
        </form>
    </div>

    <script>
        // Restricción de selección de paquetes según el plan base
        document.getElementById("plan_base").addEventListener("change", function() {
            let planSeleccionado = this.value;
            let checkboxes = document.querySelectorAll('input[name="paquete[]"]');

            if (planSeleccionado === "Básico") {
                checkboxes.forEach(checkbox => checkbox.addEventListener("change", function() {
                    if (document.querySelectorAll('input[name="paquete[]"]:checked').length > 1) {
                        this.checked = false;
                        alert("El plan Básico solo permite un paquete adicional.");
                    }
                }));
            }
        });

        // Restricción del paquete "Deporte" solo para la suscripción anual
        document.getElementById("duracion").addEventListener("change", function() {
            let duracionSeleccionada = this.value;
            let deporteCheckbox = document.getElementById("paquete_deporte");

            if (deporteCheckbox.checked && duracionSeleccionada !== "Anual") {
                deporteCheckbox.checked = false;
                alert("El paquete Deporte solo puede contratarse con una suscripción Anual.");
            }
        });

        // Restricción de paquetes según la edad del usuario
        document.getElementById("edad").addEventListener("input", function() {
            let edad = parseInt(this.value);
            let infantilCheckbox = document.getElementById("paquete_infantil");
            let otrosPaquetes = document.querySelectorAll('input[name="paquete[]"]:not([value="Infantil"])');

            if (edad < 18) {
                otrosPaquetes.forEach(checkbox => {
                    checkbox.checked = false;
                    checkbox.disabled = true;
                });
                infantilCheckbox.checked = true;
            } else {
                otrosPaquetes.forEach(checkbox => checkbox.disabled = false);
            }
        });
    </script>
</body>
</html>