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
    try {
        $data = [
            ['columna1' => 'valor1', 'columna2' => 'valor2']
        ];
        foreach ($data as $row) {
            $data = implode(",", array_keys($row));
            $placeholders = implode(",", array_fill(0, count($row), '?'));
            $sql = "INSERT INTO $tablaName ($data) VALUES ($placeholders)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array_values($row));
        }
        echo "Datos insertados correctamente.\n";
    } catch (PDOException $e) {
        die("Error al insertar datos: " . $e->getMessage());
    }
}

function eliminarData($pdo, $tableName) {
    try {
        $sql = "DELETE FROM $tableName";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        echo "Datos eliminados con éxito en la tabla $tableName\n";
    } catch (PDOException $e) {
        die("Error al eliminar datos: " . $e->getMessage());
    }
}

$options = [
    'dev' => isset($argv[1]) && $argv[1] == '--dev',
    'rollback' => isset($argv[2]) && $argv[2] == 'rollback',
    'table' => isset($argv[3]) ? $argv[3] : null,
];

if ($options['dev'] && $options['rollback'] && $options['table']) {
    $tableName = $options['table'];
    $pdo = getPDOConnection($host, $dbname, $username, $password);
    
    eliminarData($pdo, $tableName);
} else {
   
    $tablaName = 'script_php'; 
    $pdo = getPDOConnection($host, $dbname, $username, $password);
    insertarData($pdo, $tablaName);
}

?>
