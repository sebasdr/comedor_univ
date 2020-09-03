<?php

require_once 'login.php';
$conexion = new mysqli($hn, $un, $pw, $db);
session_start();
if ($conexion ->connect_error) die("No es posible conectar con la base de datos");

/* if (isset($_POST["cond"])){
    $cond=mysql_entities_fix_string($conexion,$_POST["cond"]);
    if($cond === "1"){
        reg_est($conexion);
    }elseif($cond === "2"){
        asig_tar($conexion);
    }elseif($cond === "3"){
        sellar_tar($conexion);
    }
}else{
    echo json_encode("er1");
} */

//Este es el bueno
/* if (isset($_POST["codigo"]) && isset($_POST["dni"]) && isset($_POST["nombre"]) && isset($_POST["apellido"]) && isset($_POST["facultad"]) && isset($_POST["carrera"])){
    reg_est($conexion);
}elseif(isset($_POST["codigot"]) && isset($_POST["sem"]) && isset($_POST["fecha"]) && isset($_POST["codigoe"]) && isset($_POST["sede"])){
    asig_tar($conexion);
}elseif(isset($_POST["codigot"]) && isset($_POST["sem"]) && isset($_POST["comida"]) && isset($_POST["fecha"]) && isset($_POST["hora"])){
    sellar_tar($conexion);
}else{
    echo json_encode("er1");
} */

tab_reg_est($conexion);

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

        if(!$result) echo json_encode("er1");
        elseif($result->num_rows) echo json_encode("er2");
        else{
            $result->close();
            $query="SELECT * FROM estudiante WHERE dniE='$dni'";
            $result=$conexion->query($query);

            if(!$result) echo json_encode("er1");
            elseif($result->num_rows) echo json_encode("er3");
            else{
                $result->close();
                $query="SELECT * FROM estudiante WHERE nombE='$nombre' and apelE='$apellido'";
                $result=$conexion->query($query);

                if(!$result) echo json_encode("er1");
                elseif($result->num_rows) echo json_encode("er4");
                else{
                    $result->close();
                    $stm="INSERT INTO estudiante VALUES(?,?,?,?,?,?,?,?)";
                    $result=$conexion->prepare($stm);
                    $result->bind_param("ssssssss",$codigo,$dni,$nombre,$apellido,$facultad,$carrera,$telefono,$direccion);
                    $result->execute();
                    if(!$result) echo json_encode("er1");
                    else echo json_encode("succ");
                }
            } 
        }
        $result->close();
    }else{
        echo json_encode("erif");
        /*echo <<<_END
        <h1>Registrar estudiante</h1>
        <form action="funcionesbas.php" method="post"><pre>
        Codigo     <input type="text" name="codigo" autofocus required>
        DNI        <input type="text" name="dni" required>
        Nombre     <input type="text" name="nombre" required>
        Apellido   <input type="text" name="apellido" required>
        Facultad   <select id="facultad" name="facultad" size="1">
                   <option value="2">Ciencias de la Empresa</option>
                   <option value="1">Ingenieria</option>
                   </select>
        Carrera    <select id="carrera" name="carrera" size="1">
                   <!--<option value="1">Administracion de Empresas</option>
                   <option value="2">Contabilidad</option>
                   <option value="3">Educacion Primaria Intercultural</option>
                   <option value="4">Ingenieria Agroindustrial</option>
                   <option value="5">Ingenieria Ambiental</option>
                   <option value="6">Ingenieria de Sistemas</option>-->
                   </select>
        Telefono   <input type="text" name="telefono">
        Direccion  <input type="text" name="direccion">
                   <input type="submit" value="REGISTRAR">
        </form>
        <script src="../jquery-3.2.1.min.js"></script>
        <script>
        var options = {
            2 : ["Administracion de Empresas1","Contabilidad2","Educacion Primaria Intercultural3"],
            1 : ["Ingenieria Agroindustrial4","Ingenieria Ambiental5","Ingenieria de Sistemas6"]
        }
        
        $(function(){
            var fillCarrera = function(){
                var selected = $('#facultad').val();
                $('#carrera').empty();
                options[selected].forEach(function(element,index){
                    $('#carrera').append('<option value="'+element.slice(-1)+'">'+element.slice(0,-1)+'</option>');
                });
            }
            $('#facultad').change(fillCarrera);//Cada vez que se elija (cambie de eleccion) una nueva facultad, change ejecuta fillCarrera
            fillCarrera();//para darle los valores iniciales al segundo combo la primera vez
        });
        </script>
_END;*/
    }
}

function tab_reg_est($conexion){
    $query="SELECT * FROM estudiante ORDER BY codE";
    $result=$conexion->query($query);
    $a = array();//CReando array para guardar json

    if($result->num_rows){
        $rows = $result->num_rows;
        for($j=0; $j<$rows; $j++){
            $row=$result->fetch_array(MYSQLI_NUM);
            $query2="SELECT c.nombCar, f.nombF FROM carrera c inner JOIN facultad f on c.facultad_codF = f.codF WHERE c.codCar='$row[5]'";
            $result2=$conexion->query($query2);
            $rows2=$result2->fetch_array(MYSQLI_NUM);
            $codigo=htmlspecialchars($row[0]);
            $dni=htmlspecialchars($row[1]);
            $nombre=htmlspecialchars($row[2]);
            $apellido=htmlspecialchars($row[3]);
            $facultad=htmlspecialchars($rows2[1]);
            $carrera=htmlspecialchars($rows2[0]);
            $telefono=htmlspecialchars($row[6]);
            $direccion=htmlspecialchars($row[7]);
            $objeto = new tabregest ($codigo,$dni,$nombre,$apellido,$facultad,$carrera,$telefono,$direccion);//CReando objeto
            $a[$j]=$objeto;//Guardando objeto en array
            $result2->close();
        }
        echo json_encode($a);//Enviando json con info correctamente ordenada
        $result->close();   
    }else echo json_encode("erif");
}

//Asignar tarjeta
function asig_tar($conexion){
    if(isset($_POST["codigot"]) && isset($_POST["sem"]) && isset($_POST["fecha"]) && isset($_POST["codigoe"]) && isset($_POST["sede"])){
        $codigot=mysql_entities_fix_string($conexion, $_POST["codigot"]);
        $sem=mysql_entities_fix_string($conexion, $_POST["sem"]);
        $fecha=mysql_entities_fix_string($conexion, $_POST["fecha"]);
        $codigoe=mysql_entities_fix_string($conexion, $_POST["codigoe"]);
        $sede=mysql_entities_fix_string($conexion, $_POST["sede"]);
        $usuario=$_SESSION["dni"];
        $query="SELECT * FROM estudiante WHERE codE='$codigoe'";
        $result=$conexion->query($query);
        
        if(!$result) echo json_encode("er1");
        elseif($result->num_rows){
            $result->close();
            $query="SELECT * FROM tarjeta WHERE codT='$codigot' and sem='$sem' and estudiante_codE='$codigoe'";
            $result=$conexion->query($query);

            if(!$result) echo json_encode("er1");
            elseif($result->num_rows) echo json_encode("er2");
            else{
                $result->close();
                $query="SELECT * FROM tarjeta WHERE codT='$codigot' and sem='$sem'";
                $result=$conexion->query($query);

                if(!$result) echo json_encode("er1");
                elseif($result->num_rows) echo json_encode("er3");
                else{
                    $result->close();
                    $query="SELECT * FROM tarjeta WHERE estudiante_codE='$codigoe' and sem='$sem'";
                    $result=$conexion->query($query);

                    if(!$result) echo json_encode("er1");
                    elseif($result->num_rows) echo json_encode("er2");
                    else{
                        $result->close();
                        $stm="INSERT INTO tarjeta VALUES(?,?,?,?,?,?)";
                        $result=$conexion->prepare($stm);
                        $result->bind_param("ssssss",$codigot,$sem,$fecha,$codigoe,$sede,$usuario);
                        $result->execute();
                        if(!$result) echo json_encode("er1");
                        else echo json_encode("succ");
                    }
                }
            }
        }
        else echo json_encode("er4");
        $result->close();
    }else{
        echo json_encode("erif");
        /*echo <<<_END
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
                           <!--<input type="hidden" name="usuario" value="12345678">-->
                           <input type="submit" value="ASIGNAR">
        </form>
        <script src="../jquery-3.2.1.min.js"></script>
        <script>
        $( document ).ready(function() {
            var now = new Date();
            var day = ("0" + now.getDate()).slice(-2);<!--slice: extrae caracteres de una str, aqui extrae los 2 ultimos char-->
            var month = ("0" + (now.getMonth() + 1)).slice(-2);
            var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
            $("#fecha").val(today);<!--val: se usa para inputs, text: se usa para etiquetas que no son input (user no interatua)-->
        });
        </script>
_END;*/
    }
}

//Registrar la tarjeta por dia, osea sellar la tarjeta
function sellar_tar($conexion){
    if(isset($_POST["codigot"]) && isset($_POST["sem"]) && isset($_POST["comida"]) && isset($_POST["fecha"]) && isset($_POST["hora"])){
        $codigot=mysql_entities_fix_string($conexion, $_POST["codigot"]);
        $sem=mysql_entities_fix_string($conexion, $_POST["sem"]);
        $comida=mysql_entities_fix_string($conexion, $_POST["comida"]);
        $fecha=mysql_entities_fix_string($conexion, $_POST["fecha"]);
        $hora=mysql_entities_fix_string($conexion, $_POST["hora"]);
        $usuario=$_SESSION["dni"];
        $query="SELECT * FROM tarjeta WHERE codT='$codigot' and sem='$sem'";
        $result=$conexion->query($query);

        if(!$result) echo json_encode("er1");
        elseif($result->num_rows){
            $result->close();
            $query="SELECT * FROM registro WHERE tarjeta_codT='$codigot' and tarjeta_sem='$sem' and comidas_codCo='$comida'";
            $result=$conexion->query($query);

            if(!$result) echo json_encode("er1");
            elseif($result->num_rows) echo json_encode("er2");
            else{
                $result->close();
                $stm="INSERT INTO registro VALUES(?,?,?,?,?,?)";
                $result=$conexion->prepare($stm);
                $result->bind_param("ssssss",$codigot,$sem,$comida,$fecha,$hora,$usuario);
                $result->execute();
                if(!$result) echo json_encode("er1");
                else echo json_encode("succ");
            } 
        }else echo json_encode("er3");
        $result->close();
    }else{
        echo json_encode("erif");
        /*echo <<<_END
        <h1>Sellar tarjeta</h1>
        <form action="funcionesbas.php" method="post"><pre>
        Codigo Tarjeta  <input type="text" name="codigot" autofocus required>
        Semestre        <input type="text" name="sem" required>
        Comida          <select name="comida" size="1">
                        <option value="1">Desayuno</options>
                        <option value="2">Almuerzo</options>
                        </select>
                        <input type="hidden" id="fecha" name="fecha">
                        <input type="hidden" id="hora" name="hora">
                        <!--<input type="hidden" name="usuario" value="12345678">-->
                        <input type="submit" value="SELLAR">
        </form>
        <script src="../jquery-3.2.1.min.js"></script>
        <script>
        $( document ).ready(function() {
            var now = new Date();
            var day = ("0" + now.getDate()).slice(-2);<!--slice: extrae caracteres de una str, aqui extrae los 2 ultimos char-->
            var month = ("0" + (now.getMonth() + 1)).slice(-2);
            var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
            var hour = ("0" + now.getHours()).slice(-2);
            var min = ("0" + now.getMinutes()).slice(-2);
            var seg = ("0" + now.getSeconds()).slice(-2);
            var tohour = (hour)+":"+(min)+":"+(seg);
            $("#fecha").val(today);<!--val: se usa para inputs, text: se usa para etiquetas que no son input (user no interatua)-->
            $("#hora").val(tohour);
        });
        </script>
_END;*/
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

//Clases para crear objetos para json
class tabregest{
    var $codigo;
    var $dni;
    var $nombre;
    var $apellido;
    var $facultad;
    var $carrera;
    var $telefono;
    var $direccion;

    function __construct($codigo,$dni,$nombre,$apellido,$facultad,$carrera,$telefono,$direccion){
        $this->codigo = $codigo;
        $this->dni = $dni;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->facultad = $facultad;
        $this->carrera = $carrera;
        $this->telefono = $telefono;
        $this->direccion = $direccion;
    }
}

$conexion->close();

?>