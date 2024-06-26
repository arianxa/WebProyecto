<?php
session_start();
require("../database/datos.php");
$con = mysqli_connect($host, $user, $pass, $db_name);
if (isset($_POST['correo']) && isset($_POST['pass']) && !empty($_POST['correo']) && !empty($_POST['pass'])) {
    $query = "SELECT * FROM usuarios WHERE email = '" . $_POST['correo'] . "'";
    $result = mysqli_query($con, $query);
    //Comprueba si hay algun usuario registrado con esos datos en la base de datos
    $numUsers = mysqli_num_rows($result);
    //Se recupera contraseña desde la base de datos
    if ($numUsers == 0) {
        //Caso donde no existe el usuario en la base de datos
        echo "<script>let confirmar=window.confirm('Usuario no registrado. Debes registrarte');if(!confirmar){window.location.href='../index.php'}else{window.location.href='registro.php'}</script>";
    } else {
        while ($user = mysqli_fetch_array($result)) {
            extract($user);
            //Si el usuario cumplimenta los datos de login se realizan varias validaciones

            if ($numUsers > 0 && password_verify($_POST['pass'], $contraseña)) {
                //Se recupera el tipo de usuario mediante una consulta a la base de datos
                $query_typeUser = "SELECT id_usuario, nombre, apellidos, id_provincia, email, tipo FROM usuarios WHERE email = '" . $_POST['correo'] . "'";
                $result = mysqli_query($con, $query_typeUser);
                extract(mysqli_fetch_array($result));
                $_SESSION['tipoUsuario'] = $tipo;
                $_SESSION['usuario'] = $email;
                $_SESSION['nombre'] = $nombre;
                $_SESSION['apellidos'] = $apellidos;
                $_SESSION['id_usuario'] = $id_usuario;
                $_SESSION['provincia_usuario'] = $id_provincia;
                //Usuario registrado y validado. Se comprueba tipo de usuario
                if ($_SESSION['tipoUsuario'] == 0) {
                    echo "<script>let confirmar=confirm('Estas accediendo al panel de administrador " . $_SESSION['nombre'] . " ¿Deseas continuar?');if(!confirmar){window.location.href='../index.php'}</script>";
                    header("refresh:0; url=../admin/admin.php");
                } else {
                    //Se mostrará la página del perfil de usuario
                    echo "<script>alert('Bienvenid@ $_SESSION[nombre]')</script>";
                    header("refresh:0; url=../user.php");
                }
            } else if (password_verify($_POST['pass'], $contraseña) == false) {
                //Caso donde la contraseña no coincide
                echo "<script>alert('Contraseña incorrecta')</script>";

                //Se redirige al usuario a la página de inicio donde puede cambiar la contraseña
                $url = $_SERVER['HTTP_REFERER'];
                header("refresh:0; url=$url");
            }
        }
    }
} else if (empty($_POST['correo']) || empty($_POST['pass'])) {
    echo "<script>alert('Hay campos vacios en el formulario')</script>";
    //$_SESSION['mensaje'] = "Hay campos vacios en el formulario";
    //Se determina la página origen desde donde se llama al script
    $url = $_SERVER['HTTP_REFERER'];
    header("refresh:0; url=$url");
}
