<?php
class Controller {
    // Load model
    public function model($model) {
        require_once APPROOT . '/app/models/' . $model . '.php';
        return new $model();
    }

    // Load view
    public function view($view, $data = []) {
        if (file_exists(APPROOT . '/app/views/' . $view . '.php')) {
            require_once APPROOT . '/app/views/' . $view . '.php';
        } else {
            die('View does not exist');
        }
    }
    
    // Redirect helper
    public function redirect($url) {
        header('Location: ' . BASE_URL . '/' . $url);
        exit;
    }
}
