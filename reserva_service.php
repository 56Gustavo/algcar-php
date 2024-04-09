<?php
// Inclui o arquivo que contém a definição da classe Reserva
require_once('./reserva_model.php');
// Inclui o arquivo que contém a definição da classe de conexão
require_once('./conexao.php');
// Inclui o arquivo que contém a definição da classe VeiculoService
require_once('./veiculo_service.php');

// Definição da classe ReservaService
class ReservaService
{
    // Propriedades privadas da classe
    private $conexao; // Propriedade para armazenar a conexão com o banco de dados
    private $reserva; // Propriedade para armazenar a instância da reserva

    // Método construtor da classe
    public function __construct($conexao, $reserva)
    {
        // Atribui a conexão e a instância de Reserva fornecidas às propriedades da classe
        $this->conexao = $conexao;
        $this->reserva = $reserva;
    }

    // Método para salvar uma reserva
    public function salvarReserva($dataInicio, $dataFim, $idVeiculo, $nomeCliente, $docCliente)
    {
        try {
            // Define a query SQL para inserir uma nova reserva
            $query = "INSERT INTO reserva (data_inicio, data_fim, id_veiculo, nome_cliente, doc_cliente) VALUES (:dataInicio, :dataFim, :idVeiculo, :nomeCliente, :docCliente)";
            // Prepara a query para execução
            $stmt = $this->conexao->prepare($query);
            // Substitui os parâmetros na query pelos valores fornecidos
            $stmt->bindParam(':dataInicio', $dataInicio);
            $stmt->bindParam(':dataFim', $dataFim);
            $stmt->bindParam(':idVeiculo', $idVeiculo);
            $stmt->bindParam(':nomeCliente', $nomeCliente);
            $stmt->bindParam(':docCliente', $docCliente);
            // Executa a query preparada
            $stmt->execute();

            // Instancia o VeiculoService para atualizar a disponibilidade do veículo
            $veiculoService = new VeiculoService(new Conexao(), new Veiculo());
            $veiculoService->atualizarDisponibilidadeVeiculo($idVeiculo, 0);

            // Retorna um JSON indicando o sucesso da operação
            echo json_encode(["success" => true]); 
        } catch (PDOException $e) {
            // Retorna um JSON com mensagem de erro em caso de exceção
            echo json_encode(["success" => false, "message" => "Erro ao salvar reserva: " . $e->getMessage()]); 
        }
    }

    // Método para excluir uma reserva
    public function excluirReserva($idReserva)
    {
        try {
            // Define a query SQL para selecionar o veículo associado à reserva
            $query = "SELECT id_veiculo FROM reserva WHERE id = :idReserva";
            // Prepara a query para execução
            $stmt = $this->conexao->prepare($query);
            // Substitui o parâmetro na query pelo valor fornecido
            $stmt->bindParam(':idReserva', $idReserva);
            // Executa a query preparada
            $stmt->execute();
            // Obtém o resultado da consulta
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verifica se há um resultado e se o id_veiculo está definido
            if ($result && isset($result['id_veiculo'])) {
                $idVeiculo = $result['id_veiculo'];

                // Define a query SQL para excluir a reserva
                $query = "DELETE FROM reserva WHERE id = :idReserva";
                // Prepara a query para execução
                $stmt = $this->conexao->prepare($query);
                // Substitui o parâmetro na query pelo valor fornecido
                $stmt->bindParam(':idReserva', $idReserva);
                // Executa a query preparada
                $stmt->execute();

                // Instancia o VeiculoService para atualizar a disponibilidade do veículo
                $veiculoService = new VeiculoService(new Conexao(), new Veiculo());
                $veiculoService->atualizarDisponibilidadeVeiculo($idVeiculo, 1);

                // Retorna um JSON indicando o sucesso da operação
                echo json_encode(["success" => true]);
            } else {
                // Retorna um JSON com mensagem de erro se a reserva não for encontrada
                echo json_encode(["success" => false, "message" => "Reserva não encontrada ou dados inválidos."]); 
            }
        } catch (PDOException $e) {
            // Retorna um JSON com mensagem de erro em caso de exceção
            echo json_encode(["success" => false, "message" => "Erro ao excluir reserva: " . $e->getMessage()]); 
        }
    }

    // Método para listar todas as reservas
    public function listarReservas()
    {
        try {
            // Define a query SQL para selecionar todas as reservas
            $query = "SELECT * FROM reserva";
            // Prepara a query para execução
            $stmt = $this->conexao->prepare($query);
            // Executa a query preparada
            $stmt->execute();
            // Retorna todas as linhas resultantes da consulta em forma de array associativo
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Retorna uma mensagem de erro em caso de exceção
            echo "Erro ao listar reservas: " . $e->getMessage();
            return false;
        }
    }

    // Método para lidar com a requisição recebida
    public function handleRequest()
    {
        // Verifica se a variável 'action' está definida na requisição POST
        if (isset($_POST['action'])) {
            // Atribui o valor da variável 'action' a uma nova variável
            $action = $_POST['action'];
            // Executa um switch case baseado no valor de 'action'
            switch ($action) {
                // Caso 'salvarReserva'
                case 'salvarReserva':
                    // Obtém os dados da reserva da requisição POST e chama o método salvarReserva
                    $dataInicio = $_POST['data_inicio'];
                    $dataFim = $_POST['data_fim'];
                    $idVeiculo = $_POST['id_veiculo'];
                    $nomeCliente = $_POST['nome_cliente'];
                    $docCliente = $_POST['doc_cliente'];
                    $this->salvarReserva($dataInicio, $dataFim, $idVeiculo, $nomeCliente, $docCliente);
                    break;
                // Caso 'excluirReserva'
                case 'excluirReserva':
                    // Obtém o ID da reserva da requisição POST e chama o método excluirReserva
                    $idReserva = $_POST['id_reserva'];
                    $this->excluirReserva($idReserva);
                    break;
                // Caso padrão
                default:
                    // Retorna um JSON indicando uma ação desconhecida
                    echo json_encode(["success" => false, "message" => "Ação desconhecida"]);
                    break;
            }
            // Finaliza o script após lidar com a requisição
            exit;
        }
    }
    
}

// Cria uma instância de Conexao
$conexao = new Conexao();
// Cria uma instância de ReservaService passando a conexão e uma nova instância de Reserva
$reservaService = new ReservaService($conexao->conectar(), new Reserva());
// Chama o método handleRequest para lidar com a requisição
$reservaService->handleRequest();
?>
