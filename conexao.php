<?php

class Conexao {
    // Declaração de propriedades para armazenar informações de conexão
    private $host = 'localhost'; // Endereço do servidor de banco de dados
    private $dbname = 'aluguel_carros_php'; // Nome do banco de dados
    private $user = 'root'; // Nome de usuário do banco de dados
    private $pass = ''; // Senha do banco de dados

    // Método para estabelecer uma conexão com o banco de dados
    public function conectar() {
        try {
            // Inicia uma nova conexão PDO com os parâmetros fornecidos
            $conexao = new PDO(
                "mysql:host=$this->host;dbname=$this->dbname", // String de conexão com o host e o nome do banco de dados
                "$this->user", // Nome de usuário
                "$this->pass" // Senha
            );

            return $conexao; // Retorna a conexão estabelecida
        } catch (PDOException $e) {
            // Em caso de erro ao estabelecer a conexão, exibe a mensagem de erro e encerra o script
            echo '<p>'.$e->getMessage().'</p>';
            exit;
        }
    }
}

?>
