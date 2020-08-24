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

    if(!$result) echo "No se pudo cargar la consulta";
    elseif($result->num_rows) echo "<br>Ya existe este usuario";
    else{
        $result->close();
        $query="SELECT * FROM usuario WHERE nombU='$nombre' and apelU='$apellido'";
        $result=$conexion->query($query);

        if(!$result) echo "No se pudo cargar la consulta";
        elseif($result->num_rows) echo "<br>El usuario $nombre $apellido ya existe";
        else{
            $result->close();
            $stm="INSERT INTO usuario VALUES(?,?,?,?,?,?)";
            $result=$conexion->prepare($stm);
            if (!$result) echo "Error de conexion";
            else{
                $result->bind_param("ssssss",$dni,$pass,$nombre,$apellido,$telefono,$direccion);
                $result->execute();
                if(!$result) echo "Error al registrarse";
                else {
                    echo "<br>El usuario $nombre $apellido se ha creado con exito con dni $dni";
                    echo "<br>Ahora intente <a href='signin.php'>Ingresar</a>";
                }
            }
        }
    }
    $result->close();
}else{
    echo <<<_END
    <h1>Registrate</h1>
    <form action="signup.php" method="post"><pre>
    DNI         <input type="text" name="dni" placeholder="Este sera su codigo de usuario" required>
    Password    <input type="password" name="pass" required>
    Nombre      <input type="text" name="nombre" required>
    Apellido    <input type="text" name="apellido" required>
    Telefono    <input type="text" name="telefono">
    Direccion   <input type="text" name="direccion">
                <input type="submit" value="REGISTRAR">
    </form>
_END;
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