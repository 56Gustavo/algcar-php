<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8"> <!-- Define o conjunto de caracteres UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Define a escala inicial do viewport -->
    <link rel="stylesheet" href="veiculos.css"> <!-- Link para o arquivo CSS veiculos.css -->
    <title>Veículos</title> <!-- Título da página -->
</head>

<body>

    <?php

    // Inclui os arquivos PHP necessários
    require_once('./conexao.php');
    require_once('./veiculo_model.php');
    require_once('./veiculo_service.php');

    // Cria uma instância da classe de conexão
    $conexao = new Conexao();
    // Cria uma instância da classe VeiculoService passando a conexão e uma nova instância de Veiculo
    $veiculoService = new VeiculoService($conexao, new Veiculo());
    // Recupera os veículos do banco de dados
    $veiculos = $veiculoService->recuperarVeiculos();

    // Verifica se há veículos recuperados
    if ($veiculos) {
        // Título para a seção de veículos
        echo '<h2 class="title">Todos os Veículos</h2>';
        // Container para os cards de veículos
        echo '<div class="container">';
        // Loop sobre cada veículo recuperado
        foreach ($veiculos as $veiculo) {
            // Card de cada veículo
            echo '<div class="card">';
            // Imagem do veículo
            echo '<div class="item"><img src="/aluguel-carros-php/assets/imagens/' . $veiculo->imagem . '.png" alt="' . $veiculo->marca . ' ' . $veiculo->modelo . '"></div>';
            // Informações do veículo
            echo '<div class="card-info">';
            // Título do card com marca e modelo do veículo
            echo '<div class="card-title">' . $veiculo->marca . ' ' . $veiculo->modelo . '</div>';
            // Placa do veículo
            echo '<p><strong>Placa:</strong> ' . $veiculo->placa . '</p>';
            // Valor do veículo
            echo '<p><strong>Valor:</strong> ' . $veiculo->valor . '</p>';
            // Verifica a disponibilidade do veículo
            if ($veiculo->disponibilidade == 1) {
                // Se disponível, mostra a disponibilidade em verde
                echo '<p><strong>Disponibilidade:</strong> <span style="font-weight: bold; color: #4CAF50;">Disponível</span></p>';
            } else {
                // Se indisponível, mostra a disponibilidade em vermelho
                echo '<p><strong>Disponibilidade:</strong> <span style="font-weight: bold; color: #F44336;">Indisponível</span></p>';
            }
            echo '</div>'; // Fecha a div 'card-info'
            echo '</div>'; // Fecha a div 'card'
        }
        echo '</div>'; // Fecha a div 'container'
    } else {
        // Mensagem caso nenhum veículo seja encontrado
        echo '<p>Nenhum veículo encontrado.</p>';
    }
    ?>

</body>

</html>
