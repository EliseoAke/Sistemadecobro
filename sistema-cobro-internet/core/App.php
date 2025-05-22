<?php
// Clase principal de la aplicación
class App {
    protected $controller = DEFAULT_CONTROLLER;
    protected $action = DEFAULT_ACTION;
    protected $params = [];
    
    public function __construct() {
        $url = $this->parseUrl();
        
        // Verificar si el usuario está autenticado
        // Si no está autenticado y no está intentando acceder a auth/login, redirigir al login
        if (!isset($_SESSION['user_id']) && 
            (!isset($url[0]) || $url[0] !== 'auth' || 
            (isset($url[1]) && $url[1] !== 'login'))) {
            header('Location: ' . BASE_URL . 'auth/login');
            exit;
        }
        
        // Verificar si existe el controlador
        if (isset($url[0]) && file_exists('controllers/' . ucfirst($url[0]) . 'Controller.php')) {
            $this->controller = ucfirst($url[0]);
            unset($url[0]);
        } else {
            // Si el usuario está autenticado y no especifica un controlador, usar Home
            // Si no está autenticado, usar Auth
            $this->controller = isset($_SESSION['user_id']) ? DEFAULT_CONTROLLER : 'Auth';
        }
        
        require_once 'controllers/' . $this->controller . 'Controller.php';
        $this->controller = $this->controller . 'Controller';
        $this->controller = new $this->controller;
        
        // Verificar si existe el método
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->action = $url[1];
                unset($url[1]);
            }
        } else {
            // Si el usuario no está autenticado y el controlador es Auth, usar login como acción por defecto
            if (!isset($_SESSION['user_id']) && $this->controller instanceof AuthController) {
                $this->action = 'login';
            }
        }
        
        // Obtener parámetros
        $this->params = $url ? array_values($url) : [];
        
        // Llamar al método del controlador con los parámetros
        call_user_func_array([$this->controller, $this->action], $this->params);
    }
    
    // Método para parsear la URL
    protected function parseUrl() {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        
        return [];
    }
}
?>