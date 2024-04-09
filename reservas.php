<?php
// Inclui os arquivos PHP necessários
require_once('./reserva_model.php');
require_once('./conexao.php');
require_once('./reserva_service.php');
require_once('./veiculo_service.php');

// Cria uma nova instância da classe de conexão
$conexao = new Conexao();
// Cria uma nova instância da classe VeiculoService passando a conexão e uma nova instância de Veiculo
$veiculoService = new VeiculoService($conexao, new Veiculo());
// Recupera os veículos do banco de dados
$veiculos = $veiculoService->recuperarVeiculos();

// Cria uma nova instância da classe ReservaService passando a conexão e uma nova instância de Reserva
$reservaService = new ReservaService($conexao->conectar(), new Reserva());
// Recupera as reservas do banco de dados
$reservas = $reservaService->listarReservas();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"> <!-- Define o conjunto de caracteres UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Define a escala inicial do viewport -->
    <title>Reservas</title> <!-- Título da página -->
    <link rel="stylesheet" href="reservas.css"> <!-- Inclusão do CSS personalizado -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Adição do jQuery -->
</head>
<body>
    <h1 class="title">Reservas</h1> <!-- Título da página -->
    <div class="container"> <!-- Container para as reservas -->
        <?php foreach ($reservas as $reserva) : ?> <!-- Loop sobre cada reserva -->
            <div class="reserva-card"> <!-- Card de reserva -->
                <div class="reserva-info"> <!-- Informações da reserva -->
                    <p><span class="info-label">Nome:</span> <?php echo $reserva['nome_cliente']; ?></p> <!-- Nome do cliente -->
                    <p><span class="info-label">CPF:</span> <?php echo $reserva['doc_cliente']; ?></p> <!-- CPF do cliente -->
                    <p><span class="info-label">Data Início:</span> <?php echo $reserva['data_inicio']; ?></p> <!-- Data de início da reserva -->
                    <p><span class="info-label">Data Fim:</span> <?php echo $reserva['data_fim']; ?></p> <!-- Data de fim da reserva -->
                    <p><span class="info-label">Valor:</span> <!-- Valor do veículo reservado -->
                    <?php 
                        foreach ($veiculos as $veiculo) { // Loop para encontrar o veículo correspondente
                            if ($veiculo->id == $reserva['id_veiculo']) {
                                echo $veiculo->valor; // Exibe o valor do veículo
                                break; // Sair do loop assim que encontrar o valor
                            }
                        }
                    ?>
                    </p>
                    <div class="reserva-actions"> <!-- Ações da reserva -->
                        <!-- Botão de Excluir Reserva -->
                        <button class="btn-excluir-reserva" data-id="<?php echo $reserva['id']; ?>">Excluir Reserva</button>
                    </div>
                </div>

                <div class="reserva-image"> <!-- Imagem do veículo reservado -->
                    <?php 
                    foreach ($veiculos as $veiculo) { // Loop para encontrar o veículo correspondente
                        if ($veiculo->id == $reserva['id_veiculo']) {
                            echo '<div class="item"><img src="/aluguel-carros-php/assets/imagens/' . $veiculo->imagem .'.png"></div>'; // Exibe a imagem do veículo
                            break; // Sair do loop assim que encontrar a imagem
                        }
                    }
                    ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        $(document).ready(function(){ // Função que executa quando o documento está pronto
            $('.btn-excluir-reserva').click(function(){ // Ao clicar no botão de excluir reserva
                var idReserva = $(this).data('id'); // Obtém o ID da reserva
                $.ajax({ // Requisição AJAX
                    url: 'reserva_service.php', // URL do serviço PHP
                    type: 'POST', // Método de envio
                    data: { action: 'excluirReserva', id_reserva: idReserva }, // Dados a serem enviados
                    dataType: 'json', // Tipo de dados esperados na resposta
                    success: function(response){ // Função a ser executada em caso de sucesso
                        if(response.success){ // Se a exclusão for bem-sucedida
                            alert('Reserva excluída com sucesso!'); // Exibe mensagem de sucesso
                            location.reload(); // Atualiza a página após a exclusão
                        } else {
                            alert('Erro ao excluir reserva. Por favor, tente novamente.'); // Exibe mensagem de erro
                        }
                    },
                    error: function(){ // Função a ser executada em caso de erro
                        alert('Erro ao processar a solicitação. Por favor, tente novamente.'); // Exibe mensagem de erro
                    }
                });
            });
        });
    </script>
</body>
</html>
