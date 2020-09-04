<?php

require_once 'login.php';
$conexion = new mysqli($hn, $un, $pw, $db);
session_start();
if ($conexion ->connect_error) die("No es posible conectar con la base de datos");

if(isset($_SESSION["dni"])){
    tab_asig_tar($conexion);
}else echo json_encode("erse");

function tab_asig_tar($conexion){
    $query="SELECT * FROM tarjeta ORDER BY sem";
    $result=$conexion->query($query);
    $a = array();//CReando array para guardar json

    if($result->num_rows){
        $rows = $result->num_rows;
        for($j=0; $j<$rows; $j++){
            $row=$result->fetch_array(MYSQLI_NUM);
            $query2="SELECT nombS FROM `sede` WHERE codS=$row[4]";
            $result2=$conexion->query($query2);
            $rows2=$result2->fetch_array(MYSQLI_NUM);
            $codT=htmlspecialchars($row[0]);
            $sem=htmlspecialchars($row[1]);
            $fechC=htmlspecialchars($row[2]);
            $codE=htmlspecialchars($row[3]);
            $sede=htmlspecialchars($rows2[0]);
            $usuario=htmlspecialchars($row[5]);
            $objeto = new tabasigtar ($codT,$sem,$fechC,$codE,$sede,$usuario);//CReando objeto
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
class tabasigtar{
    var $codT;
    var $sem;
    var $fechC;
    var $codE;
    var $sede;
    var $usuario;

    function __construct($codT,$sem,$fechC,$codE,$sede,$usuario){
        $this->codT = $codT;
        $this->sem = $sem;
        $this->fechC = $fechC;
        $this->codE = $codE;
        $this->sede = $sede;
        $this->usuario = $usuario;
    }
}

$conexion->close();

?>