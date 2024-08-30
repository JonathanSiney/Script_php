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

function insertarData($pdo, $tableName, $data) {
    //php script.php --data="[columna1=valor1,columna2=valor2]"
    $columns = implode(", ", array_keys($data));
    $placeholders = implode(", ", array_fill(0, count($data), '?'));
    $sql = "INSERT INTO $tableName ($columns) VALUES ($placeholders)";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array_values($data));
        echo "Datos insertados correctamente en la tabla $tableName.\n";
    } catch (PDOException $e) {
        die("Error al insertar datos: " . $e->getMessage());
    }
}

function eliminarData($pdo, $tableName) {
    //php script.php --dev rollback nombre_tabla
    try {
        $sql = "DELETE FROM $tableName";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        echo "Datos eliminados con éxito en la tabla $tableName\n";
    } catch (PDOException $e) {
        die("Error al eliminar datos: " . $e->getMessage());
    }
}

if (isset($argv[1]) && $argv[1] == '--dev' && isset($argv[2]) && $argv[2] == 'rollback' && isset($argv[3])) {
    $tableName = $argv[3];
    $pdo = getPDOConnection($host, $dbname, $username, $password);
    eliminarData($pdo, $tableName);
}

if (isset($argv)) {
    $options = getopt("", ["data:"]);
    
    if (isset($options['data'])) {
        $dataString = $options['data'];
        parse_str(str_replace(['[', ']', ','], ['&', '', '&'], $dataString), $dataArray);
        
        if (!empty($dataArray)) {
            $pdo = getPDOConnection($host, $dbname, $username, $password);
            $tableName = 'script_php';
            insertarData($pdo, $tableName, $dataArray);
        } else {
            die("El argumento --data no es un array asociativo válido.\n");
        }
    }
}
?>
