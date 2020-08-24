<?php

require_once "login.php";
$conexion = new mysqli($hn, $un, $pw, $db);

if($conexion->connect_error) die("Error de conexion");

if(isset($_POST['dni']) && isset($_POST['pass'])){
    $dni = mysql_entities_fix_string($conexion, $_POST['dni']);
    $pass = mysql_entities_fix_string($conexion,$_POST['pass']);
    $query = "SELECT * FROM usuario WHERE dniU='$dni'";
    $result = $conexion->query($query);

    if (!$result) echo "No se pudo realizar la consulta";
    elseif ($result->num_rows){
        $row = $result->fetch_array(MYSQLI_NUM);
        $result->close();

        if (password_verify($pass, $row[1])){
            session_start();
            $_SESSION["dni"]=$row[0];
            echo "Hola $row[0], Bienvenido <a href='funcionesbas.php'>Click para continuar</a>";
        }else echo "Password incorrecto";
    }else echo "No existe el usuario";
}else{
    echo <<<_END
    <h1>Ingresa</h1>
    <form action="signin.php" method="post"><pre>
    DNI         <input type="text" name="dni" required>
    Password    <input type="password" name="pass" required>
                <input type="submit" value="INGRESAR">
    </form>
_END;
}

$conexion->close();

function mysql_entities_fix_string($conexion, $string){
    return htmlentities(mysql_fix_string($conexion, $string));
}

function mysql_fix_string($coneccion, $string){
    if (get_magic_quotes_gpc())
        $string = stripcslashes($string);
    return $coneccion->real_escape_string($string);
}

?>