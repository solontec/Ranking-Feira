<?php

$link = mysqli_connect("localhost", "root", "", "Ranking");
if (!$link) {
    exit('Erro na conexão com o banco.');
}

// Primeiro, busca todos os nomes únicos dos projetos
$sql_nomes = "SELECT DISTINCT nome FROM votos";
$result_nomes = mysqli_query($link, $sql_nomes);

// Verifica se a consulta retornou resultados
$ranking = [];

if (mysqli_num_rows($result_nomes) > 0) {
    // Para cada projeto, calcula a soma dos votos
    while ($row = mysqli_fetch_assoc($result_nomes)) {
        $nomeProjeto = $row["nome"];

        // Consulta para somar os votos de um projeto específico
        $sql_soma = "SELECT SUM(voto) AS total FROM votos WHERE nome = '" . mysqli_real_escape_string($link, $nomeProjeto) . "'";
        $result_soma = mysqli_query($link, $sql_soma);
        $soma = mysqli_fetch_assoc($result_soma)["total"];

        // Armazena no array de ranking
        $ranking[] = [
            "nome" => $nomeProjeto,
            "total" => $soma
        ];
    }

    // Ordena o ranking por total de votos (decrescente)
    usort($ranking, function($a, $b) {
        return $b["total"] - $a["total"];
    });

    // Exibe o ranking
    $posicao = 1;
    foreach ($ranking as $item) {
        echo "🏆 " . $posicao . "º lugar - Projeto: " . $item["nome"] . " | Total de pontos: " . $item["total"] . "<br>";
        $posicao++;
    }

} else {
    echo "Nenhum nome encontrado.";
}

?>