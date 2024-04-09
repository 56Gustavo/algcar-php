<?php

// Definição da classe Reserva
class Reserva {
    // Propriedades privadas da classe
    private $id; // Armazena o ID da reserva
    private $data_inicio; // Armazena a data de início da reserva
    private $data_fim; // Armazena a data de fim da reserva
    private $id_veiculo; // Armazena o ID do veículo reservado
    private $id_cliente; // Armazena o ID do cliente que fez a reserva
    private $nome_cliente; // Armazena o nome do cliente que fez a reserva
    private $doc_cliente; // Armazena o documento do cliente que fez a reserva
    
    // Método mágico para obter o valor de uma propriedade privada
    public function __get($atributo) {
        // Retorna o valor da propriedade se ela existir na instância atual da classe
        return $this->$atributo;
    }

    // Método mágico para definir o valor de uma propriedade privada
    public function __set($atributo, $valor) {
        // Define o valor da propriedade se ela existir na instância atual da classe
        $this->$atributo = $valor;
        return $this; // Retorna a própria instância para permitir o encadeamento de métodos
    }
}

?>
