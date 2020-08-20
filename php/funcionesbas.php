<?php

require_once 'login.php';
$conexion = new mysqli($hn, $un, $pw, $db);
if ($conexion ->connect_error) die("No es posible conectar con la base de datos");

reg_est($conexion);

//Registrar estudiante
function reg_est($conexion){
    if(isset($_POST["codigo"]) && isset($_POST["dni"]) && isset($_POST["nombre"]) && isset($_POST["apellido"]) && isset($_POST["facultad"]) && isset($_POST["carrera"])){
        $codigo=mysql_entities_fix_string($conexion, $_POST["codigo"]);
        $dni=mysql_entities_fix_string($conexion, $_POST["dni"]);
        $nombre=mysql_entities_fix_string($conexion, $_POST["nombre"]);
        $apellido=mysql_entities_fix_string($conexion, $_POST["apellido"]);
        $facultad=mysql_entities_fix_string($conexion, $_POST["facultad"]);
        $carrera=mysql_entities_fix_string($conexion, $_POST["carrera"]);
        $telefono=mysql_entities_fix_string($conexion, $_POST["telefono"]);
        $direccion=mysql_entities_fix_string($conexion, $_POST["direccion"]);
        $query="SELECT * FROM estudiante WHERE codE='$codigo'";
        $result=$conexion->query($query);

        if(!$result) die("No se pudo cargar la consulta");
        elseif($result->num_rows) die("<br>Ya existe este estudiante");
        else{
            $result->close();
            $stm="INSERT INTO estudiante VALUES(?,?,?,?,?,?,?,?)";
            $result=$conexion->prepare($stm);
            $result->bind_param("ssssssss",$codigo,$dni,$nombre,$apellido,$facultad,$carrera,$telefono,$direccion);
            $result->execute();
            if(!$result) die("No se ha podido crear al estudiante");
            else die("<br>El estudiante $nombre $apellido se ha creado con exito");
        }
        $result->close();
    }else{
        echo <<<_END
        <h1>Registrar estudiante</h1>
        <form action="funcionesbas.php" method="post"><pre>
        Codigo     <input type="text" name="codigo" autofocus required>
        DNI        <input type="text" name="dni" required>
        Nombre     <input type="text" name="nombre" required>
        Apellido   <input type="text" name="apellido" required>
        Facultad   <select name="facultad" size="1">
                   <option value="2">Ciencias de la Empresa</options>
                   <option value="1">Ingenieria</options>
                   </select>
        Carrera    <select name="carrera" size="1">
                   <option value="1">Administracion de Empresas</options>
                   <option value="2">Contabilidad</options>
                   <option value="3">Educacion Primaria Intercultural</options>
                   <option value="4">Ingenieria Agroindustrial</options>
                   <option value="5">Ingenieria Ambiental</options>
                   <option value="6">Ingenieria de Sistemas</options>
                   </select>
        Telefono   <input type="text" name="telefono">
        Direccion  <input type="text" name="direccion">
                   <input type="submit" value="REGISTRAR">
        </form>
_END;
    }
}

// Funciones de limpieza de cadenas
function mysql_entities_fix_string($conexion, $string){
    return htmlentities(mysql_fix_string($conexion, $string));
}

function mysql_fix_string($conexion, $string){
    if (get_magic_quotes_gpc()) $string = stripslashes($string);
    return $conexion->real_escape_string($string);
}

?>