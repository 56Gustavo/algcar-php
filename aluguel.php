<?php
# Inclui os arquivos necessários para as classes e serviços
require_once('./reserva_model.php'); # Importa o modelo de reserva
require_once('./veiculo_model.php'); # Importa o modelo de veículo
require_once('./veiculo_service.php'); # Importa o serviço de veículo
require_once('./reserva_service.php'); # Importa o serviço de reserva
require_once('./conexao.php'); # Importa o arquivo de conexão com o banco de dados

# Cria uma nova instância de conexão com o banco de dados
$conexao = new Conexao(); // Criação de uma instância de conexão com o banco de dados

# Cria instâncias dos serviços de veículo e reserva
$veiculoService = new VeiculoService($conexao, new Veiculo()); // Criação de uma instância do serviço de veículo
$reservaService = new ReservaService($conexao, new Reserva()); // Criação de uma instância do serviço de reserva

# Recupera todos os veículos disponíveis
$veiculos = $veiculoService->recuperarVeiculos(); // Recupera todos os veículos disponíveis

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8"> <!-- Define o conjunto de caracteres como UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Define a largura da viewport e a escala inicial -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Inclui a biblioteca jQuery -->
    <title>Aluguel de Carros</title> <!-- Define o título da página -->
    <!-- Inclui os estilos CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    <link rel="stylesheet" href="aluguel.css"> <!-- Inclui um arquivo CSS local -->
    <!-- Estilos CSS específicos -->
    <style>
        /* Estilos para a navegação do carousel */
        .owl-carousel .owl-nav button.owl-prev,
        .owl-carousel .owl-nav button.owl-next {
            z-index: 1; /* Define a ordem de empilhamento */
            width: 50px; /* Define a largura */
            height: 50px; /* Define a altura */
            background-color: #fff; /* Define a cor de fundo */
            border-radius: 50%; /* Define o raio da borda */
            top: 50%; /* Define a posição superior */
        }

        .owl-carousel .owl-nav button.owl-prev:hover,
        .owl-carousel .owl-nav button.owl-next:hover {
            background-color: #ffdcbf; /* Define a cor de fundo ao passar o mouse */
        }

        .owl-nav button span {
            font-size: 40px; /* Define o tamanho da fonte */
            height: 100%; /* Define a altura */
            width: 100%; /* Define a largura */
            color: #ff6219; /* Define a cor do texto */
        }

        .owl-dots {
            display: none; /* Oculta os pontos de navegação do carousel */
        }
    </style>
</head>

<body>
    <div style="min-height: 500px;">
        <!-- Contêúdo principal -->
        <div class="container" id="car-selection-form">
            <!-- Formulário de seleção de carro -->
            <div class="form-container">
                <!-- Container do formulário -->
                <h4 style="text-align: center;">Preencha as informações</h4> <!-- Título do formulário -->
                <hr> <!-- Linha horizontal -->
                <label>Carro</label> <!-- Rótulo do campo de seleção de carro -->
                <select id="car-type-select" class="form-control mb-3" style="cursor: pointer;" required> <!-- Campo de seleção de carro -->
                    <option value="">Selecione</option> <!-- Opção padrão -->
                    <?php foreach ($veiculos as $veiculo) : ?> <!-- Loop para cada veículo -->
                        <option value="<?php echo $veiculo->id; ?>"><?php echo $veiculo->marca . ' ' . $veiculo->modelo; ?></option> <!-- Opção com marca e modelo do veículo -->
                    <?php endforeach; ?> <!-- Fim do loop -->
                </select> <!-- Fim do campo de seleção de carro -->
                <div class="form-group">
                    <!-- Grupo de formulário -->
                    <label for="start-date">Data de Início</label> <!-- Rótulo do campo de data de início -->
                    <input type="date" class="form-control" id="start-date" style="cursor: pointer;" required> <!-- Campo de data de início -->
                </div> <!-- Fim do grupo de formulário -->
                <div class="form-group">
                    <!-- Grupo de formulário -->
                    <label for="end-date">Data de Fim</label> <!-- Rótulo do campo de data de fim -->
                    <input type="date" class="form-control" id="end-date" style="cursor: pointer;" required> <!-- Campo de data de fim -->
                </div> <!-- Fim do grupo de formulário -->
                <div id="error-message" style="display: none; margin-bottom: 10px; color: red;">Por favor, preencha todos os campos
                    obrigatórios.</div> <!-- Mensagem de erro -->
                <button id="continue-reservation-btn" class="btn btn-primary btn-block">Continuar Reserva</button> <!-- Botão para continuar a reserva -->
            </div> <!-- Fim do container do formulário -->
            <div class="carousel-container">
                <!-- Container do carousel -->
                <div class="owl-carousel owl-theme"></div> <!-- Carousel para exibição de imagens -->
            </div> <!-- Fim do container do carousel -->
        </div> <!-- Fim do conteúdo principal -->

        <div class="container" id="customer-info-form" style="display: none; width: 1496px;">
            <!-- Formulário de informações do cliente -->
            <div class="form-container" style="width: 60%">
                <!-- Container do formulário -->
                <form id="customer-form" class="customer-form">
                    <!-- Formulário do cliente -->
                    <h4 style="text-align: center;">Finalize a reserva</h4> <!-- Título do formulário -->
                    <hr> <!-- Linha horizontal -->
                    <label for="customer-car" style="color:black;">Carro selecionado</label> <!-- Rótulo do campo de carro selecionado -->
                    <select class="form-control continue-reservation-inputs" id="customer-car" disabled> <!-- Campo de carro selecionado -->
                        <?php foreach ($veiculos as $veiculo) : ?> <!-- Loop para cada veículo -->
                            <?php if ($veiculo->id == $selectedCarId) : ?> <!-- Condição se o veículo está selecionado -->
                                <option value="<?php echo $veiculo->id; ?>" selected><?php echo $veiculo->marca . ' ' . $veiculo->modelo; ?></option> <!-- Opção selecionada -->
                            <?php else : ?> <!-- Se não estiver selecionado -->
                                <option value="<?php echo $veiculo->id; ?>"><?php echo $veiculo->marca . ' ' . $veiculo->modelo; ?></option> <!-- Outra opção -->
                            <?php endif; ?> <!-- Fim da condição -->
                        <?php endforeach; ?> <!-- Fim do loop -->
                    </select> <!-- Fim do campo de carro selecionado -->
                    <!-- Outros campos de informações do cliente -->
                    <label for="customer-startDate" style="color:black;">Início da reserva</label>
                    <input type="text" class="form-control continue-reservation-inputs" id="customer-startDate" value="<?php echo $selectedStartDate; ?>" disabled>
                    <label for="customer-endDate" style="color:black;">Fim da reserva</label>
                    <input type="text" class="form-control continue-reservation-inputs" id="customer-endDate" value="<?php echo $selectedEndDate; ?>" disabled>
                    <label for="customer-name" style="color:black;">Nome do Cliente</label>
                    <input type="text" class="form-control continue-reservation-inputs" id="customer-name" required>
                    <label for="customer-cpf" style="color:black;">CPF</label>
                    <input type="text" class="form-control continue-reservation-inputs" id="customer-cpf" required><br>
                    <div id="customer-error-message" style="display: none; margin-bottom: 15px; margin-top: -20px; color: red;">Por favor, preencha todos os campos obrigatórios.</div> <!-- Mensagem de erro -->
                    <!-- Botões de voltar e finalizar -->
                    <button id="back-to-car-selection-btn" class="btn btn-secondary costumer-back-button">Voltar</button>
                    <button id="confirm-reservation-btn" class="btn  costumer-next-button">Finalizar</button>
                </form> <!-- Fim do formulário do cliente -->
            </div> <!-- Fim do container do formulário -->
        </div> <!-- Fim do formulário de informações do cliente -->

        <!-- Modais para exibição de mensagens -->
        <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
            <!-- Modal de sucesso -->
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="successModalLabel">Sucesso!</h5> <!-- Título do modal -->
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        A reserva foi salva com sucesso! <!-- Corpo do modal -->
                    </div>
                </div>
            </div>
        </div> <!-- Fim do modal de sucesso -->

        <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
            <!-- Modal de erro -->
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="errorModalLabel">Erro!</h5> <!-- Título do modal -->
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Houve um erro ao salvar a reserva. Por favor, tente novamente. <!-- Corpo do modal -->
                    </div>
                </div>
            </div>
        </div> <!-- Fim do modal de erro -->
    </div> <!-- Fim do conteúdo principal -->

    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script> <!-- Inclui a biblioteca Owl Carousel -->
    <script>
        // Variáveis para armazenar dados selecionados
        var selectedCarId;
        var selectedStartDate;
        var selectedEndDate;

        // Função executada quando o documento estiver pronto
        $(document).ready(function() {
            // Recupera os veículos disponíveis
            var cars = <?php echo json_encode($veiculos); ?>; // Recupera os veículos disponíveis e converte para JSON

            // Inicializa as variáveis de opções de seleção e itens do carousel
            var selectOptions = ''; // Inicialização de variável para armazenar as opções de seleção
            var carouselItems = ''; // Inicialização de variável para armazenar os itens do carousel

            // Para cada veículo, adiciona as opções de seleção e os itens do carousel
            $.each(cars, function(index, car) { // Loop para cada veículo
                selectOptions += '<option value="' + car.id + '">' + car.marca + ' ' + car.modelo + '</option>'; // Adiciona uma opção de seleção
                carouselItems += '<div class="item"><img src="/aluguel-carros-php/assets/imagens/' + car.imagem + '.png" alt="' + car.marca + ' ' + car.modelo + '"></div>'; // Adiciona um item de carousel
            });

            // Adiciona as opções de seleção ao campo de seleção de carro e os itens ao carousel
            $('#car-type-select').append(selectOptions); // Adiciona as opções de seleção ao campo de seleção de carro
            $('.owl-carousel').html(carouselItems); // Adiciona os itens ao carousel

            // Configurações do carousel
            $(".owl-carousel").owlCarousel({
                items: 1,
                loop: true,
                nav: true,
                dots: true,
                autoplay: true,
                autoplaySpeed: 1000,
                smartSpeed: 1500,
                autoplayHoverPause: true
            });

            // Evento de mudança no campo de seleção de carro
            $('#car-type-select').on('change', function() { // Evento de mudança no campo de seleção de carro
                selectedCarId = $(this).val(); // Obtém o ID do carro selecionado
                $('.owl-carousel').trigger('to.owl.carousel', [selectedCarId - 1, 500]); // Atualiza o carousel para mostrar o item selecionado
            });

            // Evento de clique no botão de continuar reserva
            $('#continue-reservation-btn').on('click', function(event) { // Evento de clique no botão de continuar reserva
                event.preventDefault(); // Previne o comportamento padrão do formulário
                var selectedCar = document.getElementById('car-type-select').value; // Obtém o carro selecionado
                var startDate = document.getElementById('start-date').value; // Obtém a data de início
                var endDate = document.getElementById('end-date').value; // Obtém a data de fim

                // Verifica se todos os campos estão preenchidos
                if (selectedCar === "" || startDate === "" || endDate === "") { // Verifica se todos os campos estão preenchidos
                    $('#error-message').show(); // Exibe a mensagem de erro
                    return false; // Retorna falso para evitar o envio do formulário
                }

                // Armazena os dados selecionados
                selectedCarId = selectedCar; // Armazena o ID do carro selecionado
                selectedStartDate = startDate; // Armazena a data de início
                selectedEndDate = endDate; // Armazena a data de fim

                // Continua a reserva
                onContinueCarReservation(); // Chama a função para continuar a reserva
            });

            // Evento de clique no botão de voltar para seleção de carro
            $('#back-to-car-selection-btn').on('click', function(event) { // Evento de clique no botão de voltar para seleção de carro
                event.preventDefault(); // Previne o comportamento padrão do botão
                onBackToCarSelection(); // Chama a função para voltar à seleção de carro
            });
        });

        // Função para continuar a reserva de carro
        function onContinueCarReservation() { // Função para continuar a reserva de carro
            $('#car-selection-form').hide(); // Esconde o formulário de seleção de carro
            $('#customer-info-form').show(); // Exibe o formulário de informações do cliente
            $('#customer-car').val(selectedCarId); // Define o valor do campo de carro no formulário do cliente
            $('#customer-startDate').val(selectedStartDate); // Define o valor do campo de data de início no formulário do cliente
            $('#customer-endDate').val(selectedEndDate); // Define o valor do campo de data de fim no formulário do cliente
        }

        // Função para voltar para a seleção de carro
        function onBackToCarSelection() { // Função para voltar para a seleção de carro
            $('#customer-info-form').hide(); // Esconde o formulário de informações do cliente
            $('#car-selection-form').show(); // Exibe o formulário de seleção de carro
        }

        // Evento de clique no botão de confirmação de reserva
        $('#confirm-reservation-btn').on('click', function(event) { // Evento de clique no botão de confirmação de reserva
            event.preventDefault(); // Previne o comportamento padrão do botão

            // Obtém os dados do cliente
            var nomeCliente = $('#customer-name').val(); // Obtém o nome do cliente
            var docCliente = $('#customer-cpf').val(); // Obtém o CPF do cliente

            // Verifica se todos os campos do cliente estão preenchidos
            if (!nomeCliente || !docCliente) { // Verifica se todos os campos do cliente estão preenchidos
                $('#customer-error-message').show(); // Exibe a mensagem de erro
                return false; // Retorna falso para evitar o envio do formulário
            } else {
                $('#customer-error-message').hide(); // Esconde a mensagem de erro
            }

            // Obtém as datas e o ID do veículo selecionado
            var dataInicio = $('#start-date').val(); // Obtém a data de início
            var dataFim = $('#end-date').val(); // Obtém a data de fim
            var idVeiculo = selectedCarId; // Obtém o ID do veículo selecionado

            // Verifica a disponibilidade do veículo
            $.ajax({
                url: 'veiculo_service.php', // URL do serviço de veículo
                method: 'POST', // Método de requisição
                data: { // Dados a serem enviados
                    action: 'verificarDisponibilidade', // Ação a ser executada no serviço
                    id_veiculo: idVeiculo // ID do veículo
                },
                success: function(disponibilidade) { // Função de sucesso da requisição
                    if (disponibilidade == 1) { // Se o veículo estiver disponível
                        // Salva a reserva
                        $.ajax({
                            url: 'reserva_service.php', // URL do serviço de reserva
                            method: 'POST', // Método de requisição
                            data: { // Dados a serem enviados
                                action: 'salvarReserva', // Ação a ser executada no serviço
                                data_inicio: dataInicio, // Data de início
                                data_fim: dataFim, // Data de fim
                                id_veiculo: idVeiculo, // ID do veículo
                                nome_cliente: nomeCliente, // Nome do cliente
                                doc_cliente: docCliente // CPF do cliente
                            },
                            success: function(response) { // Função de sucesso da requisição
                                // Após a reserva ser salva com sucesso, atualiza a disponibilidade do veículo
                                $.ajax({
                                    url: 'veiculo_service.php', // URL do serviço de veículo
                                    method: 'POST', // Método de requisição
                                    data: { // Dados a serem enviados
                                        action: 'atualizarDisponibilidade', // Ação a ser executada no serviço
                                        id_veiculo: idVeiculo // ID do veículo
                                    },
                                    success: function(response) { // Função de sucesso da requisição
                                        $('#successModal').modal('show'); // Exibe o modal de sucesso
                                    },
                                    error: function(error) { // Função de erro da requisição
                                        $('#errorModal').modal('show'); // Exibe o modal de erro
                                    }
                                });
                            },
                            error: function(error) { // Função de erro da requisição
                                $('#errorModal').modal('show'); // Exibe o modal de erro
                            }
                        });
                    } else { // Se o veículo não estiver disponível
                        alert('Veículo não disponível para o período selecionado.'); // Exibe uma mensagem de alerta
                    }
                },
                error: function(error) { // Função de erro da requisição
                    $('#errorModal').modal('show'); // Exibe o modal de erro
                }
            });
        });
    </script>
</body>

</html>
