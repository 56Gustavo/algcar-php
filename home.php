<!DOCTYPE html> <!-- Declaração do tipo de documento HTML -->
<html lang="pt-br"> <!-- Declaração do idioma da página -->

<head>
    <meta charset="UTF-8"> <!-- Definição do conjunto de caracteres -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Meta tag para responsividade -->
    <title>Aluguel de Carros</title> <!-- Título da página -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> <!-- Inclusão do Bootstrap -->
    <style>
        .navbar-nav .nav-item .nav-link.active {
            color: #FE8330; 
            font-weight: bold;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top"> <!-- Barra de navegação -->
        <div class="container"> <!-- Container para a barra de navegação -->
            <a class="navbar-brand" href="#">ALUGUEL DE CARROS</a> <!-- Título do site -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"> <!-- Botão de alternância -->
                <span class="navbar-toggler-icon"></span> <!-- Ícone do botão de alternância -->
            </button>
            <div class="collapse navbar-collapse" id="navbarNav"> <!-- Menu de navegação -->
                <ul class="navbar-nav ml-auto"> <!-- Lista de itens da barra de navegação -->
                    <li class="nav-item">
                        <a class="nav-link" href="?page=aluguel">Aluguel</a> <!-- Link para a página de aluguel -->
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?page=reservas">Reservas</a> <!-- Link para a página de reservas -->
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?page=veiculos">Todos os Veículos</a> <!-- Link para a página de todos os veículos -->
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5 pt-5"> <!-- Container principal -->
        <?php
        $acao = 'recuperar'; // Define a ação como recuperar
        include "conexao.php"; // Inclui o arquivo de conexão com o banco de dados
        $page = isset ($_GET['page']) ? $_GET['page'] : 'aluguel'; // Obtém a página atual ou define como aluguel se não houver

        switch ($page) { // Estrutura de controle switch para determinar a página a ser exibida
            case 'aluguel':
                include ('aluguel.php'); // Inclui a página de aluguel
                break;
            case 'reservas':
                include ('reservas.php'); // Inclui a página de reservas
                break;
            case 'veiculos':
                include ('veiculos.php'); // Inclui a página de todos os veículos
                break;
            default:
                include ('aluguel.php'); // Página padrão é aluguel
        }
        ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Inclusão do jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script> <!-- Inclusão do Popper.js -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> <!-- Inclusão do Bootstrap JS -->
    <script>
        $(document).ready(function () { // Função que executa quando o documento está pronto
            $('.navbar-nav .nav-link').removeClass('active'); // Remove a classe 'active' de todos os links de navegação

            var currentPage = window.location.search.split('page=')[1] || 'aluguel'; // Obtém a página atual

            $('.navbar-nav .nav-link').each(function () { // Para cada link de navegação
                var linkPage = $(this).attr('href').split('page=')[1]; // Obtém a página associada ao link
                if (linkPage === currentPage) { // Se a página associada ao link for a página atual
                    $(this).addClass('active'); // Adiciona a classe 'active' ao link
                }
            });
        });

        function recuperarDados() { // Função para recuperar dados
            fetch('veiculo_controller.php?acao=recuperar') // Requisição para recuperar dados
                .then(response => response.json()) // Converte a resposta para JSON
                .then(data => {
                    console.log(data); // Exibe os dados no console
                })
                .catch(error => console.error('Erro:', error)); // Exibe erro no console, se houver
        }
    </script>

</body>

</html>
