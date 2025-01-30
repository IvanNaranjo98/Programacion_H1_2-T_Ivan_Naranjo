<?php
// Importamos la clase Usuario desde el modelo
require_once __DIR__ . '/../modelo/class_usuario.php';

// Definimos la clase UsuarioController para gestionar usuarios
class UsuarioController {
    private $usuarioModel; // Propiedad para manejar el modelo de usuario

    // Constructor: crea una nueva instancia del modelo Usuario
    public function __construct() {
        $this->usuarioModel = new Usuario();
    }

    // Método para registrar un usuario con los datos proporcionados
    public function registrarUsuario($nombre, $apellidos, $email, $edad, $plan_base, $paquete, $duracion) {
        return $this->usuarioModel->crearUsuario($nombre, $apellidos, $email, $edad, $plan_base, $paquete, $duracion);
    }

    // Método para obtener la lista de usuarios
    public function listarUsuarios() {
        return $this->usuarioModel->obtenerUsuarios();
    }

    // Método para obtener un usuario por su ID
    public function obtenerUsuarioPorId($id) {
        return $this->usuarioModel->obtenerUsuarioPorId($id);
    }

    // Método para actualizar la información de un usuario
    public function actualizarUsuario($id, $nombre, $apellidos, $email, $edad, $plan_base, $paquete, $duracion) {
        return $this->usuarioModel->actualizarUsuario($id, $nombre, $apellidos, $email, $edad, $plan_base, $paquete, $duracion);
    }

    // Método para eliminar un usuario por su ID
    public function eliminarUsuario($id) {
        return $this->usuarioModel->eliminarUsuario($id);
    }

    // Método para calcular el precio total de una suscripción
    public function calcularPrecioTotal($plan_base, $paquete, $duracion) {
        return $this->usuarioModel->calcularPrecioTotal($plan_base, $paquete, $duracion);
    }

    // Método para calcular el precio mensual de una suscripción
    public function calcularPrecioMensual($plan_base, $paquete) {
        return $this->usuarioModel->calcularPrecioMensual($plan_base, $paquete);
    }    
}
?>