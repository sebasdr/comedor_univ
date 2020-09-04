<?php

require_once 'login.php';
$conexion = new mysqli($hn, $un, $pw, $db);
if ($conexion ->connect_error) die("No es posible conectar con la base de datos");

tab_reg_est($conexion);

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