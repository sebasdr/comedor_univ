<?php

require_once "login.php";
$conexion = new mysqli($hn, $un, $pw, $db);

if($conexion->connect_error) die("Error de conexion");

if(isset($_POST['dni']) && isset($_POST['pass'])){
    $dni = mysql_entities_fix_string($conexion, $_POST['dni']);
    $pass = mysql_entities_fix_string($conexion,$_POST['pass']);
    $query = "SELECT * FROM usuario WHERE dniU='$dni'";
    $result = $conexion->query($query);

    if (!$result) echo json_encode("er1");
    elseif ($result->num_rows){
        $row = $result->fetch_array(MYSQLI_NUM);
        $result->close();

        if (password_verify($pass, $row[1])){
            session_start();
            $_SESSION["dni"]=$row[0];
            echo json_encode("succ");
        }else echo json_encode("er2");
    }else echo json_encode("er3");
}else{
    echo json_encode("erif");
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