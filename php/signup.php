<?php

require_once "login.php";
$conexion = new mysqli($hn, $un, $pw, $db);

if($conexion->connect_error) die("Error de conexion");

if(isset($_POST["dni"]) && isset($_POST["pass"]) && isset($_POST["nombre"]) && isset($_POST["apellido"]) && isset($_POST["telefono"]) && isset($_POST["direccion"])){
    $dni = mysql_entities_fix_string($conexion, $_POST['dni']);
    $pass = mysql_entities_fix_string($conexion, (password_hash($_POST['pass'],PASSWORD_DEFAULT)));
    $nombre = mysql_entities_fix_string($conexion, $_POST['nombre']);
    $apellido = mysql_entities_fix_string($conexion, $_POST['apellido']);
    $telefono = mysql_entities_fix_string($conexion, $_POST['telefono']);
    $direccion = mysql_entities_fix_string($conexion, $_POST['direccion']);
    $query="SELECT * FROM usuario WHERE dniU='$dni'";
    $result=$conexion->query($query);

    if(!$result) echo json_encode("er1");
    elseif($result->num_rows) echo json_encode("er2");
    else{
        $result->close();
        $query="SELECT * FROM usuario WHERE nombU='$nombre' and apelU='$apellido'";
        $result=$conexion->query($query);

        if(!$result) echo json_encode("er1");
        elseif($result->num_rows) echo json_encode("er3");
        else{
            $result->close();
            $stm="INSERT INTO usuario VALUES(?,?,?,?,?,?)";
            $result=$conexion->prepare($stm);
            if (!$result) echo json_encode("er1");
            else{
                $result->bind_param("ssssss",$dni,$pass,$nombre,$apellido,$telefono,$direccion);
                $result->execute();
                if(!$result) echo json_encode("er1");
                else {
                    echo json_encode("El usuario $nombre $apellido se ha creado con exito con DNI $dni");
                }
            }
        }
    }
    $result->close();
}else{
    echo json_encode("erif");
}

function mysql_entities_fix_string($conexion, $string){
    return htmlentities(mysql_fix_string($conexion, $string));
}

function mysql_fix_string($coneccion, $string){
    if (get_magic_quotes_gpc())
        $string = stripcslashes($string);
    return $coneccion->real_escape_string($string);
}

$conexion->close();

?>