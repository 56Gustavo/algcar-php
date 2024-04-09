<?php

// Definição da classe Veiculo
class Veiculo {
    // Propriedades privadas da classe
	private $id; // Armazena o ID do veículo
	private $marca; // Armazena a marca do veículo
	private $modelo; // Armazena o modelo do veículo
	private $placa; // Armazena a placa do veículo
	private $valor; // Armazena o valor do veículo
    private $disponibilidade; // Armazena a disponibilidade do veículo
    private $imagem; // Armazena o caminho da imagem do veículo
	
    // Método para obter o valor de uma propriedade privada
	public function __get($atributo) {
        // Retorna o valor da propriedade se ela existir na instância atual da classe
		return $this->$atributo;
	}

    // Método para definir o valor de uma propriedade privada
	public function __set($atributo, $valor) {
        // Define o valor da propriedade se ela existir na instância atual da classe
		$this->$atributo = $valor;
		return $this; // Retorna a própria instância para permitir o encadeamento de métodos
	}
}

?>
