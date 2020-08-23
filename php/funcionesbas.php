<?php

require_once 'login.php';
$conexion = new mysqli($hn, $un, $pw, $db);
if ($conexion ->connect_error) die("No es posible conectar con la base de datos");

//reg_est($conexion);
asig_tar($conexion);

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

        if(!$result) echo "No se pudo cargar la consulta";
        elseif($result->num_rows) echo "<br>Ya existe este estudiante";
        else{
            $result->close();
            $query="SELECT * FROM estudiante WHERE dniE='$dni'";
            $result=$conexion->query($query);

            if(!$result) echo "No se pudo cargar la consulta";
            elseif($result->num_rows) echo "<br>El DNI $dni ya existe";
            else{
                $result->close();
                $query="SELECT * FROM estudiante WHERE nombE='$nombre' and apelE='$apellido'";
                $result=$conexion->query($query);

                if(!$result) echo "No se pudo cargar la consulta";
                elseif($result->num_rows) echo "<br>El estudiante $nombre $apellido ya existe";
                else{
                    $result->close();
                    $stm="INSERT INTO estudiante VALUES(?,?,?,?,?,?,?,?)";
                    $result=$conexion->prepare($stm);
                    $result->bind_param("ssssssss",$codigo,$dni,$nombre,$apellido,$facultad,$carrera,$telefono,$direccion);
                    $result->execute();
                    if(!$result) echo "No se ha podido crear al estudiante";
                    else echo "<br>El estudiante $nombre $apellido se ha creado con exito con codigo $codigo";
                }
            } 
        }
        $result->close();
    }else{
        echo "Faltan datos";
        /*echo <<<_END
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
_END;*/
    }
    
}

//Asignar tarjeta
function asig_tar($conexion){
    if(isset($_POST["codigot"]) && isset($_POST["sem"]) && isset($_POST["fecha"]) && isset($_POST["codigoe"]) && isset($_POST["sede"]) && isset($_POST["usuario"])){
        $codigot=mysql_entities_fix_string($conexion, $_POST["codigot"]);
        $sem=mysql_entities_fix_string($conexion, $_POST["sem"]);
        $fecha=mysql_entities_fix_string($conexion, $_POST["fecha"]);
        $codigoe=mysql_entities_fix_string($conexion, $_POST["codigoe"]);
        $sede=mysql_entities_fix_string($conexion, $_POST["sede"]);
        $usuario=mysql_entities_fix_string($conexion, $_POST["usuario"]);
        $query="SELECT * FROM estudiante WHERE codE='$codigoe'";
        $result=$conexion->query($query);
        
        if(!$result) echo "No se pudo cargar la consulta";
        elseif($result->num_rows){
            $result->close();
            $query="SELECT * FROM tarjeta WHERE codT='$codigot' and sem='$sem' and estudiante_codE='$codigoe'";
            $result=$conexion->query($query);

            if(!$result) echo "No se pudo cargar la consulta";
            elseif($result->num_rows) echo "<br>El estudiante $codigoe ya tiene una tarjeta asignada en el actual semestre $sem";
            else{
                $result->close();
                $query="SELECT * FROM tarjeta WHERE codT='$codigot' and sem='$sem'";
                $result=$conexion->query($query);

                if(!$result) echo "No se pudo cargar la consulta";
                elseif($result->num_rows) echo "<br>El estudiante $codigoe no puede tener dos tarjetas en el actual semestre $sem";
                else{
                    $result->close();
                    $query="SELECT * FROM tarjeta WHERE estudiante_codE='$codigoe' and sem='$sem'";
                    $result=$conexion->query($query);

                    if(!$result) echo "No se pudo cargar la consulta";
                    elseif($result->num_rows) echo "<br>La tarjeta $codigot no puede ser asignada a dos estudiantes en el actual semestre $sem";
                    else{
                        $result->close();
                        $stm="INSERT INTO tarjeta VALUES(?,?,?,?,?,?)";
                        $result=$conexion->prepare($stm);
                        $result->bind_param("ssssss",$codigot,$sem,$fecha,$codigoe,$sede,$usuario);
                        $result->execute();
                        if(!$result) echo "No se ha podido asignar al estudiante una tarjeta";
                        else echo "<br>Al estudiante $codigoe se le ha asignado la tarjeta $codigot correctamente en el semestre $sem";
                    }
                }
            }
        }
        else echo "No existe el estudiante $codigoe";
        $result->close();
    }else{
        //echo "Faltan datos";
        echo <<<_END
        <script src="../jquery-3.2.1.min.js"></script>
        <h1>Asignar tarjeta</h1>
        <form action="funcionesbas.php" method="post"><pre>
        Codigo Tarjeta     <input type="text" name="codigot" autofocus required>
        Semestre           <input type="text" name="sem" required>
                           <input type="hidden" id="fecha" name="fecha">
        Codigo Estudiante  <input type="text" name="codigoe" required>
        Sede               <select name="sede" size="1">
                           <option value="1">San Jeronimo</options>
                           <option value="2">Talavera</options>
                           </select>
                           <input type="hidden" name="usuario" value="12345678">
                           <input type="submit" value="ASIGNAR">
        </form>
        <script>
        $( document ).ready(function() {
            var now = new Date();
            var day = ("0" + now.getDate()).slice(-2);<!--slice: extrae caracteres de una str, aqui extrae los 2 ultimos char-->
            var month = ("0" + (now.getMonth() + 1)).slice(-2);
            var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
            $("#fecha").val(today);<!--val: se usa para inputs, text: se usa para etiquetas que no son input (user no interatua)-->
        });
        </script>
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