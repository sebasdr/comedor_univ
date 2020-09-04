<?php

require_once 'login.php';
$conexion = new mysqli($hn, $un, $pw, $db);
session_start();
if ($conexion ->connect_error) die("No es posible conectar con la base de datos");

if(isset($_SESSION["dni"])){
    tab_sell_tar($conexion);
}else echo json_encode("erse");

function tab_sell_tar($conexion){
    $query="SELECT * FROM registro ORDER BY tarjeta_sem";
    $result=$conexion->query($query);
    $a = array();//CReando array para guardar json

    if($result->num_rows){
        $rows = $result->num_rows;
        for($j=0; $j<$rows; $j++){
            $row=$result->fetch_array(MYSQLI_NUM);
            $query2="SELECT nombCo FROM comidas WHERE codCo=$row[2]";
            $result2=$conexion->query($query2);
            $rows2=$result2->fetch_array(MYSQLI_NUM);
            $codT=htmlspecialchars($row[0]);
            $sem=htmlspecialchars($row[1]);
            $codC=htmlspecialchars($rows2[0]);
            $fechC=htmlspecialchars($row[3]);
            $hora=htmlspecialchars($row[4]);
            $usuario=htmlspecialchars($row[5]);
            $objeto = new tabselltar ($codT,$sem,$codC,$fechC,$hora,$usuario);//CReando objeto
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
class tabselltar{
    var $codT;
    var $sem;
    var $codC;
    var $fechC;
    var $hora;
    var $usuario;

    function __construct($codT,$sem,$codC,$fechC,$hora,$usuario){
        $this->codT = $codT;
        $this->sem = $sem;
        $this->codC = $codC;
        $this->fechC = $fechC;
        $this->hora = $hora;
        $this->usuario = $usuario;
    }
}

$conexion->close();

?>