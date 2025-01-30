<?php
// Importamos la conexión a la base de datos
require_once __DIR__ . '/../config/conexion.php';

class Usuario {
    private $conn; // Variable para manejar la conexión a la base de datos
    
    // Precios de los planes base
    private $precios_planes = [
        'Básico' => 9.99,
        'Estándar' => 13.99,
        'Premium' => 17.99
    ];
    
    // Precios de los paquetes adicionales
    private $precios_paquetes = [
        'Infantil' => 4.99,
        'Cine' => 7.99,
        'Deporte' => 6.99
    ];

    // Constructor: establece la conexión con la base de datos
    public function __construct() {
        $database = new Conexion();
        $this->conn = $database->conectar();
    }

    // Crear un nuevo usuario en la base de datos
    public function crearUsuario($nombre, $apellidos, $email, $edad, $plan_base, $paquete, $duracion) {
        // Verifica si el email ya está registrado
        if ($this->verificarEmail($email)) {
            return "El correo ya está registrado.";
        }

        // Convierte la lista de paquetes en un array
        $paquetesArray = explode(",", $paquete);

        // Restricción: los menores de 18 años solo pueden contratar el Pack Infantil
        if ($edad < 18 && !in_array("Infantil", $paquetesArray)) {
            return "Los menores de 18 años solo pueden contratar el Pack Infantil.";
        }

        // Restricción: el plan Básico solo permite un paquete adicional
        if ($plan_base == "Básico" && count($paquetesArray) > 1) {
            return "El plan Básico solo permite un paquete adicional.";
        }

        // Restricción: el Pack Deporte solo se puede contratar anualmente
        if (in_array("Deporte", $paquetesArray) && $duracion != "Anual") {
            return "El Pack Deporte solo puede contratarse de manera anual.";
        }

        // Query para insertar el usuario en la base de datos
        $query = "INSERT INTO usuarios (nombre, apellidos, email, edad, plan_base, paquete, duracion) 
                  VALUES (:nombre, :apellidos, :email, :edad, :plan_base, :paquete, :duracion)";
        
        $stmt = $this->conn->prepare($query);

        // Asociamos los valores a los parámetros de la consulta
        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":apellidos", $apellidos);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":edad", $edad);
        $stmt->bindParam(":plan_base", $plan_base);
        $stmt->bindParam(":paquete", $paquete);
        $stmt->bindParam(":duracion", $duracion);

        // Ejecutamos la consulta y retornamos un mensaje
        return $stmt->execute() ? "Usuario registrado correctamente." : "Error al registrar usuario.";
    }

    // Obtener todos los usuarios registrados
    public function obtenerUsuarios() {
        $query = "SELECT * FROM usuarios";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un usuario por su ID
    public function obtenerUsuarioPorId($id) {
        $query = "SELECT * FROM usuarios WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Actualizar los datos de un usuario existente
    public function actualizarUsuario($id, $nombre, $apellidos, $email, $edad, $plan_base, $paquete, $duracion) {
        // Verificación de restricciones
        $paquetesArray = explode(",", $paquete);

        if ($edad < 18 && !in_array("Infantil", $paquetesArray)) {
            return "Los menores de 18 años solo pueden contratar el Pack Infantil.";
        }

        if ($plan_base == "Básico" && count($paquetesArray) > 1) {
            return "El plan Básico solo permite un paquete adicional.";
        }

        if (in_array("Deporte", $paquetesArray) && $duracion != "Anual") {
            return "El Pack Deporte solo puede contratarse de manera anual.";
        }

        // Query para actualizar los datos del usuario
        $query = "UPDATE usuarios SET 
                    nombre = :nombre, 
                    apellidos = :apellidos, 
                    email = :email, 
                    edad = :edad, 
                    plan_base = :plan_base, 
                    paquete = :paquete, 
                    duracion = :duracion
                WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":apellidos", $apellidos);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":edad", $edad);
        $stmt->bindParam(":plan_base", $plan_base);
        $stmt->bindParam(":paquete", $paquete);
        $stmt->bindParam(":duracion", $duracion);
        
        return $stmt->execute() ? "Usuario actualizado correctamente." : "Error al actualizar usuario.";
    }

    // Eliminar un usuario por ID
    public function eliminarUsuario($id) {
        $query = "DELETE FROM usuarios WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        return $stmt->execute() ? "Usuario eliminado correctamente." : "Error al eliminar usuario.";
    }

    // Verificar si un email ya está registrado
    private function verificarEmail($email) {
        $query = "SELECT id FROM usuarios WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        return $stmt->rowCount() > 0; // Retorna true si hay un usuario con ese email
    }

    // Calcular el precio total de la suscripción según el plan y los paquetes adicionales
    public function calcularPrecioTotal($plan_base, $paquete, $duracion) {
        $precio_total = $this->precios_planes[$plan_base]; // Precio base del plan

        $paquetesArray = explode(",", $paquete);
        foreach ($paquetesArray as $p) {
            $p = trim($p); // Limpiar espacios en caso de que existan
            if (isset($this->precios_paquetes[$p])) {
                $precio_total += $this->precios_paquetes[$p];
            }
        }

        // Si la suscripción es anual, multiplicar por 12
        if ($duracion == "Anual") {
            $precio_total *= 12;
        }

        return number_format($precio_total, 2); // Retorna el precio con dos decimales
    }

    // Calcular el precio mensual de la suscripción
    public function calcularPrecioMensual($plan_base, $paquete) {
        $precio_mensual = $this->precios_planes[$plan_base]; // Precio del plan base

        // Sumar los precios de los paquetes adicionales
        $paquetesArray = explode(",", $paquete);
        foreach ($paquetesArray as $p) {
            $p = trim($p);
            if (isset($this->precios_paquetes[$p])) {
                $precio_mensual += $this->precios_paquetes[$p];
            }
        }

        return number_format($precio_mensual, 2);
    }
}
?>