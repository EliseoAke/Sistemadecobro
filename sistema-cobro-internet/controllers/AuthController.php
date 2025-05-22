<?php
class AuthController extends Controller {
    private $usuarioModel;
    
    public function __construct() {
        $this->usuarioModel = $this->model('Usuario');
    }
    
    public function login() {
        // Si ya está autenticado, redirigir al home
        if (isset($_SESSION['user_id'])) {
            $this->redirect('home');
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            
            $errores = [];
            
            if (empty($email)) {
                $errores[] = 'El email es obligatorio';
            }
            
            if (empty($password)) {
                $errores[] = 'La contraseña es obligatoria';
            }
            
            if (empty($errores)) {
                $usuario = $this->usuarioModel->verificarCredenciales($email, $password);
                
                if ($usuario) {
                    // Iniciar sesión
                    $_SESSION['user_id'] = $usuario['id'];
                    $_SESSION['user_nombre'] = $usuario['nombre'];
                    $_SESSION['user_email'] = $usuario['email'];
                    $_SESSION['user_rol'] = $usuario['rol'];
                    
                    // Redirigir al dashboard
                    $this->redirect('home');
                } else {
                    $errores[] = 'Credenciales incorrectas';
                }
            }
            
            $this->viewWithoutTemplate('auth/login', [
                'errores' => $errores,
                'email' => $email
            ]);
        } else {
            $this->viewWithoutTemplate('auth/login');
        }
    }
    
    public function logout() {
        // Destruir la sesión
        session_destroy();
        $this->redirect('auth/login');
    }
    
    public function perfil() {
        // Verificar si el usuario está autenticado
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth/login');
        }
        
        $usuario = $this->usuarioModel->getById($_SESSION['user_id']);
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = trim($_POST['nombre']);
            $email = trim($_POST['email']);
            $password_actual = $_POST['password_actual'];
            $password_nueva = $_POST['password_nueva'];
            $password_confirmar = $_POST['password_confirmar'];
            
            $errores = [];
            
            if (empty($nombre)) {
                $errores[] = 'El nombre es obligatorio';
            }
            
            if (empty($email)) {
                $errores[] = 'El email es obligatorio';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errores[] = 'El email no es válido';
            }
            
            // Si se quiere cambiar la contraseña
            if (!empty($password_actual) || !empty($password_nueva) || !empty($password_confirmar)) {
                if (empty($password_actual)) {
                    $errores[] = 'La contraseña actual es obligatoria';
                } elseif (!password_verify($password_actual, $usuario['password'])) {
                    $errores[] = 'La contraseña actual es incorrecta';
                }
                
                if (empty($password_nueva)) {
                    $errores[] = 'La nueva contraseña es obligatoria';
                } elseif (strlen($password_nueva) < 6) {
                    $errores[] = 'La nueva contraseña debe tener al menos 6 caracteres';
                }
                
                if ($password_nueva != $password_confirmar) {
                    $errores[] = 'Las contraseñas no coinciden';
                }
            }
            
            if (empty($errores)) {
                $data = [
                    'nombre' => $nombre,
                    'email' => $email
                ];
                
                $result = $this->usuarioModel->update($_SESSION['user_id'], $data);
                
                // Actualizar la contraseña si se proporcionó
                if (!empty($password_nueva)) {
                    $this->usuarioModel->cambiarPassword($_SESSION['user_id'], $password_nueva);
                }
                
                if ($result) {
                    $_SESSION['user_nombre'] = $nombre;
                    $_SESSION['user_email'] = $email;
                    $_SESSION['mensaje'] = 'Perfil actualizado correctamente';
                    $this->redirect('auth/perfil');
                } else {
                    $errores[] = 'Error al actualizar el perfil';
                }
            }
            
            $this->view('auth/perfil', [
                'errores' => $errores,
                'usuario' => $usuario
            ]);
        } else {
            $this->view('auth/perfil', ['usuario' => $usuario]);
        }
    }
}
?>