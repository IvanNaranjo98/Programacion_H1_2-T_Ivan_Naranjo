<?php
// Defino una clase llamada "Conexion" para gestionar la conexión a la base de datos
class Conexion {
    private $host = "localhost";
    private $db_name = "streamweb";
    private $username = "root";
    private $password = "curso";
    public $conn;

    // Método para conectar a la base de datos
    public function conectar() {
        $this->conn = null;
        try {
            //Crear una nueva conexión
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            
            // Configuración para que muestre errores en caso de problemas
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            // Si hay un error, se muestra un mensaje con la descripción del problema
            echo "Error de conexión: " . $exception->getMessage();
        }

        // Retornamos la conexión (si fue exitosa)
        return $this->conn;
    }
}
?>