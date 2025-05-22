<?php
// Clase base para todos los controladores
class Controller {
    // Método para cargar un modelo
    protected function model($model) {
        require_once 'models/' . $model . '.php';
        return new $model();
    }
    
    // Método para cargar una vista
    protected function view($view, $data = []) {
        if (file_exists('views/' . $view . '.php')) {
            // Pasar la instancia del controlador a la vista
            $data['controller'] = $this;
            
            extract($data);
            require_once 'views/templates/header.php';
            require_once 'views/' . $view . '.php';
            require_once 'views/templates/footer.php';
        } else {
            die('La vista no existe: ' . $view);
        }
    }
    
    // Método para cargar una vista sin plantilla
    protected function viewWithoutTemplate($view, $data = []) {
        if (file_exists('views/' . $view . '.php')) {
            extract($data);
            require_once 'views/' . $view . '.php';
        } else {
            die('La vista no existe: ' . $view);
        }
    }
    
    // Método para redireccionar
    protected function redirect($url) {
        header('Location: ' . BASE_URL . $url);
        exit;
    }
    
    // Método para verificar si el usuario está autenticado
    protected function isAuthenticated() {
        return isset($_SESSION['user_id']);
    }
    
    // Método para verificar permisos
    protected function checkPermission($permission) {
        if (!isset($_SESSION['permissions']) || !in_array($permission, $_SESSION['permissions'])) {
            $this->redirect('auth/login');
        }
    }
}
?>