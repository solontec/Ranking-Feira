<?php
// Configuração do banco de dados
$host = 'localhost';
$dbname = 'ranking_feira';
$username = 'root';
$password = '';

// Conexão com o banco de dados
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}

// Função para registrar votos
function registrarVoto($pdo, $trabalhoId, $voto) {
    if ($voto < 1 || $voto > 5) {
        throw new Exception("O voto deve estar entre 1 e 5.");
    }

    $stmt = $pdo->prepare("INSERT INTO votos (trabalho_id, voto) VALUES (:trabalho_id, :voto)");
    $stmt->execute(['trabalho_id' => $trabalhoId, 'voto' => $voto]);
}

// Função para calcular o ranking
function calcularRanking($pdo) {
    $stmt = $pdo->query("
        SELECT 
            trabalhos.id, 
            trabalhos.nome, 
            COALESCE(SUM(votos.voto), 0) AS pontos
        FROM trabalhos
        LEFT JOIN votos ON trabalhos.id = votos.trabalho_id
        GROUP BY trabalhos.id
        ORDER BY pontos DESC
    ");

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Exemplo de uso
try {
    // Registrar votos (exemplo)
    registrarVoto($pdo, 1, 5);
    registrarVoto($pdo, 2, 4);
    registrarVoto($pdo, 3, 3);
    registrarVoto($pdo, 4, 5);
    registrarVoto($pdo, 5, 2);

    // Calcular ranking
    $ranking = calcularRanking($pdo);

    echo "Ranking dos Trabalhos:\n";
    foreach ($ranking as $item) {
        echo "Trabalho: {$item['nome']} - Pontos: {$item['pontos']}\n";
    }
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
?>
