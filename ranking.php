
<?php

// Conexao com banco de dados 
$link = mysqli_connect("localhost", "root", "", "Ranking");

// se não conectar, já para 
if (!$link) {
    exit('Erro na conexão com o banco.');
}

// Pega todos os nomes diferentes dos projetos que receberam votos
$sql_nomes = "SELECT DISTINCT nome FROM votos";
$result_nomes = mysqli_query($link, $sql_nomes);

// cria um array vazio para guardar o ranking
$ranking = [];

// se realmente encontrar os nomes
if (mysqli_num_rows($result_nomes) > 0) {

    // vai percorrendo cada nome de projeto
    while ($row = mysqli_fetch_assoc($result_nomes)) {
        $nomeProjeto = $row["nome"];

        // proteção contra SQL Injection
        $nomeSeguro = mysqli_real_escape_string($link, $nomeProjeto);

        // pega o total de pontos que o projeto recebeu 
        $sql_total = "SELECT SUM(voto) AS total FROM votos WHERE nome = '$nomeSeguro'";
        $total = mysqli_fetch_assoc(mysqli_query($link, $sql_total))["total"];

        // pega quantos votos esse projeto recebeu no total
        $sql_qtd_votos = "SELECT COUNT(*) AS qtd FROM votos WHERE nome = '$nomeSeguro'";
        $qtd_votos = mysqli_fetch_assoc(mysqli_query($link, $sql_qtd_votos))["qtd"];

        // conta quantas  5 esse projeto recebeu
        $sql_qtd_5 = "SELECT COUNT(*) AS qtd5 FROM votos WHERE nome = '$nomeSeguro' AND voto = 5";
        $qtd5 = mysqli_fetch_assoc(mysqli_query($link, $sql_qtd_5))["qtd5"];

        // conta quantas 4 ou 5 ele recebeu
        $sql_qtd_45 = "SELECT COUNT(*) AS qtd45 FROM votos WHERE nome = '$nomeSeguro' AND voto IN (4, 5)";
        $qtd45 = mysqli_fetch_assoc(mysqli_query($link, $sql_qtd_45))["qtd45"];

        // coloca isso no arrayu de ranking 
        $ranking[] = [
            "nome" => $nomeProjeto,
            "total" => (int)$total,
            "qtd_votos" => (int)$qtd_votos,
            "qtd5" => (int)$qtd5,
            "qtd45" => (int)$qtd45
        ];
    }

    // parte que ordena o ranking 
    usort($ranking, function ($a, $b) {

        // primeiro compara o total de pontos, lembrando que o maior vence
        if ($b["total"] != $a["total"]) {
            return $b["total"] - $a["total"];
        }

        // se empatar no total, vence quem teve menos votos
        if ($a["qtd_votos"] != $b["qtd_votos"]) {
            return $a["qtd_votos"] - $b["qtd_votos"];
        }

        // se ainda empatar, vai pelo que recebeu mais notas 5
        if ($b["qtd5"] != $a["qtd5"]) {
            return $b["qtd5"] - $a["qtd5"];
        }

        // e se ainda empatar, quem teve mais 4 e 5 juntos vence
        return $b["qtd45"] - $a["qtd45"];
    });

    // mostra o ranking tudo certo aqui embaixo  
    $posicao = 1;
    foreach ($ranking as $item) {
        echo "🏆 " . $posicao . "º lugar - Projeto: " . $item["nome"] . " | Pontos: " . $item["total"] . "<br>";
        $posicao++;
    }

} else {
    echo "Nenhum projeto com votos ainda.";
}

?>
