<?php
class SyntaxError extends Exception {
    public function __construct($mensagem) {
        parent::__construct($mensagem);
    }
}

class FileNotFoundError extends Exception {
    public function __construct($mensagem) {
        parent::__construct($mensagem);
    }
}

class FatalEror extends Exception {
    public function __construct($mensagem) {
        parent::__construct($mensagem);
    }
}

function errorHandler() {
    $error = error_get_last(); // último erro ocorrido
}

register_shutdown_function('errorHandler');
