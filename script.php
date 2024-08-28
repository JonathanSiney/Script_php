<?php
$host = "localhost";
$username = "root";
$dbname = "script";
$password = "";

function getPDOConnection($host, $dbname, $username, $password) {
    $dsn = "mysql:host=$host;dbname=$dbname";
    try {
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("No se pudo conectar a la base de datos: " . $e->getMessage());
    }
}

function insertarData($pdo, $tablaName) {
   //php script.php nombre_tabla
    try {
        $sql = "INSERT INTO $tablaName DEFAULT VALUES";
        $stmt = $pdo->prepare($sql);
        $stmt -> execute();
        echo "Dato insertado con éxito en la tabla '$tablaName'.\n";
    } catch (PDOException $e) {
        die("Error al insertar datos: " . $e->getMessage());
    }
}
function eliminarData($pdo, $tableName) {
    //php script.php --dev rollback nombre_tabla
    $sql = "DELETE FROM $tableName";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}
$options = [
    'dev' => isset($argv[1]) && $argv[1] == '--dev',
    'rollback' => isset($argv[2]) && $argv[2] == 'rollback',
    'table' => isset($argv[3]) ? $argv[3] : null,
];
if ($options['dev'] && $options['rollback'] && $options['table']) {
    $tableName = $options['table'];
    
    echo "Datos eliminados con éxito en la tabla $tableName\n";
    $pdo = getPDOConnection($host, $dbname, $username, $password);
    eliminarData($pdo, $tableName);
}else{
    $tablaName = 'script_php'; 
    $pdo = getPDOConnection($host, $dbname, $username, $password);
    insertarData($pdo, $tablaName);
}

?>
 
