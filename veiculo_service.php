<?php
// Inclui o arquivo que contém a definição da classe Veiculo
require_once('./veiculo_model.php');
// Inclui o arquivo que contém a definição da classe de conexão
require_once('./conexao.php');

// Definição da classe VeiculoService
class VeiculoService
{
    // Propriedades privadas da classe
    private $conexao; // Propriedade para armazenar a conexão com o banco de dados
    private $veiculo; // Propriedade para armazenar a instância de Veiculo

    // Método construtor da classe
    public function __construct($conexao, Veiculo $veiculo)
    {
        // Estabelece a conexão com o banco de dados e atribui a instância de Veiculo fornecidas às propriedades da classe
        $this->conexao = $conexao->conectar();
        $this->veiculo = $veiculo;
    }

    // Método para recuperar todos os veículos
    public function recuperarVeiculos()
    {
        // Define a query SQL para selecionar todos os veículos
        $query = 'SELECT * FROM veiculo';
        // Prepara a query para execução
        $stmt = $this->conexao->prepare($query);
        // Executa a query preparada
        $stmt->execute();
        // Retorna todas as linhas resultantes da consulta em forma de objetos anônimos
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Método para recuperar veículos disponíveis
    public function recuperarVeiculosDisponiveis()
    {
        // Define a query SQL para selecionar veículos com disponibilidade igual a 1 (disponíveis)
        $query = 'SELECT * FROM veiculo WHERE disponibilidade = 1';
        // Prepara a query para execução
        $stmt = $this->conexao->prepare($query);
        // Executa a query preparada
        $stmt->execute();
        // Retorna todas as linhas resultantes da consulta em forma de objetos anônimos
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    // Método para atualizar a disponibilidade de um veículo
    public function atualizarDisponibilidadeVeiculo($id_veiculo, $disponibilidade)
    {
        try {
            // Define a query SQL para atualizar a disponibilidade de um veículo específico
            $query = "UPDATE veiculo SET disponibilidade = :disponibilidade WHERE id = :id_veiculo";
            // Prepara a query para execução
            $stmt = $this->conexao->prepare($query);
            // Substitui os parâmetros na query pelos valores fornecidos
            $stmt->bindParam(':disponibilidade', $disponibilidade);
            $stmt->bindParam(':id_veiculo', $id_veiculo);
            // Executa a query preparada
            $stmt->execute();
            // Retorna verdadeiro para indicar que a operação foi bem-sucedida
            return true;
        } catch (PDOException $e) {
            // Retorna uma mensagem de erro em caso de exceção
            echo "Erro ao atualizar disponibilidade do veículo: " . $e->getMessage();
            // Retorna falso para indicar que a operação falhou
            return false;
        }
    }

    // Método para verificar a disponibilidade de um veículo
    public function verificarDisponibilidadeVeiculo($id_veiculo)
    {
        try {
            // Define a query SQL para verificar a disponibilidade de um veículo específico
            $query = "SELECT disponibilidade FROM veiculo WHERE id = :id_veiculo";
            // Prepara a query para execução
            $stmt = $this->conexao->prepare($query);
            // Substitui o parâmetro na query pelo valor fornecido
            $stmt->bindParam(':id_veiculo', $id_veiculo);
            // Executa a query preparada
            $stmt->execute();
            // Obtém o resultado da consulta como um array associativo
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            // Retorna o valor da disponibilidade do veículo
            return $result['disponibilidade'];
        } catch (PDOException $e) {
            // Retorna uma mensagem de erro em caso de exceção
            echo "Erro ao verificar disponibilidade do veículo: " . $e->getMessage();
            // Retorna falso para indicar que a operação falhou
            return false;
        }
    }

    // Método para lidar com a solicitação AJAX
    public function handleRequest()
    {
        // Verifica se a ação está definida na solicitação POST e se é para verificar a disponibilidade do veículo
        if (isset($_POST['action']) && $_POST['action'] == 'verificarDisponibilidade') {
            // Obtém o ID do veículo da solicitação POST
            $idVeiculo = $_POST['id_veiculo'];
            // Verifica a disponibilidade do veículo
            $disponibilidade = $this->verificarDisponibilidadeVeiculo($idVeiculo);
            // Retorna a disponibilidade do veículo como resposta AJAX
            echo $disponibilidade;
            // Encerra a execução após enviar a resposta AJAX
            exit;
        }
    }
}

// Cria uma instância de Conexao
$conexao = new Conexao();
// Cria uma instância de VeiculoService passando a conexão e uma nova instância de Veiculo
$veiculoService = new VeiculoService($conexao, new Veiculo());
// Chama o método handleRequest para lidar com a solicitação
$veiculoService->handleRequest();
?>
