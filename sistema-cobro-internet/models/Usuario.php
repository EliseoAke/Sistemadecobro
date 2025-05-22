<?php
class Usuario extends Model {
    protected $table = 'usuarios';
    
    // Método para verificar credenciales de inicio de sesión
    public function verificarCredenciales($email, $password) {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($usuario && password_verify($password, $usuario['password'])) {
            return $usuario;
        }
        
        return false;
    }
    
    // Método para crear un nuevo usuario con contraseña encriptada
    public function crearUsuario($nombre, $email, $password, $rol) {
        $data = [
            'nombre' => $nombre,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'rol' => $rol,
            'fecha_creacion' => date('Y-m-d H:i:s')
        ];
        
        return $this->create($data);
    }
    
    // Método para cambiar la contraseña
    public function cambiarPassword($id, $password) {
        $data = [
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'fecha_actualizacion' => date('Y-m-d H:i:s')
        ];
        
        return $this->update($id, $data);
    }
}
?>