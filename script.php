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
     // php script.php    // --tabla=script_php
    try {
        $sql = "INSERT INTO $tablaName (columna1, columna2) VALUES (?, ?)";
        $stmt = $pdo->prepare($sql);
        $valor1 = 'valor1';
        $valor2 = 'valor2';
        $stmt->execute([$valor1, $valor2]);
        echo "Datos insertados con éxito en la tabla '$tablaName'.\n";
    } catch (PDOException $e) {
        die("Error al insertar datos: " . $e->getMessage());
    }
}

function eliminarData($pdo, $tableName) {
    $sql = "DELETE FROM $tableName";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}
$options = [
    'dev' => isset($argv[1]) && $argv[1] == '--dev' ? true : false,
    'rollback' => isset($argv[2]) && $argv[2] == 'rollback' ? true : false,
    'table' => isset($argv[3]) ? $argv[3] : null,
];

if ($options['dev'] && $options['rollback'] && $options['table']) {
    $tableName = $options['table'];
    
    echo "Datos eliminados con éxito en la tabla $tableName\n";
    $pdo = getPDOConnection($host, $dbname, $username, $password);
    
    eliminarData($pdo, $tableName);
}else{
    // php script.php 
    $tablaName = 'script_php'; 
    $pdo = getPDOConnection($host, $dbname, $username, $password);
    insertarData($pdo, $tablaName);
    
}

?>
 
