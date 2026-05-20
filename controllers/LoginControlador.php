<?php

class LoginControlador {

    public function login(){
        // Quitamos session_start() de aquí porque el index.php ya lo inicia de forma global al principio.

        $usuario = isset($_POST['usuario']) ? $_POST['usuario'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        // Usuario demo
        $userCorrecto = "admin";
        $passCorrecta = "123456";

        if($usuario == $userCorrecto && $password == $passCorrecta){

            $_SESSION['usuario_id'] = 1;
            $_SESSION['usuario_nombre'] = $usuario;
            $_SESSION['rol'] = "ADMINISTRADOR"; // Para que pinte dinámico en tu barra de estado

            // SOLUCIÓN: Quitamos el "../" porque el navegador ya está ejecutando desde la raíz del proyecto
            header("Location: index.php?action=dashboard");
            exit();

        } else {
            // Quitamos el "../" aquí también
            echo "
            <script>
                alert('Usuario o contraseña incorrectos');
                window.location='index.php';
            </script>
            ";
        }
    }

    public function logout(){
        // Destruimos la sesión limpiamente
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();

        // Redirección limpia a la raíz
        header("Location: index.php");
        exit();
    }
}