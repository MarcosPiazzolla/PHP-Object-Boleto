<?php
//18/05/11
class Boleto{
    /**
      *
      * @version 0.1 18/05/2011 Initial
      *
      */
    public function __construct(&$obj){
        $this->parent = $obj;
        
        //Pré-configurando o boleto
        $this->setDataEmissao();
    }
    
    
    
    # # # # # # # # # # # # # # # # # # # # # #
    # # 
    # #     SETTERS E TRATAMENTO DOS VALORES DE ENTRADA
    # #
    # # # # # # # # # # # # # # # # # # # # # #


    //Setters Valores
    public $Valor;
    public $ValorUnitario;
    public $FatorVencimento;
    public $Vencimento;
    public $NossoNumero;
    public $NumDocumento;
    public $DataEmissao;
    //Opcionais
    public $Quantidade;
    public $Desconto;
    public $OutrosAbatimentos;
    public $Multa;
    public $OutrosAcrescimos;
    //
    public $CodigoBarras;
    public $LinhaDigitavel;
    
    /**
      * Configura os dados dos clientes a partir de um array
      *
      * @version 0.1 20/05/2011 Initial
      */
    public function set($array){
        foreach($array as $var => $value){
            $method = 'set' . $var;
            $this->$method($value);
        }
        return $this;
    }

    /**
      * Define a data da emissão do boleto. Qualquer um dos parâmetros
      * deixados em branco seraá preenchido com o valor referente ao dia
      * atual
      * 
      * @version 0.1 19/05/2011 Initial
      *
      */
    public function setDataEmissao($dia = null, $mes = null, $ano = null){
        $dia = empty($dia) ? date('d') : $dia;
        $mes = empty($mes) ? date('m') : $mes;
        $ano = empty($ano) ? date('Y') : $ano;
        
        $this->DataEmissao = $dia . '/' . $mes . '/' . $ano;
        return $this;
    }
    
    /**
      *
      * @version 0.1 19/05/2011 Initial
      *
      */
    public function setNumDocumento($numero){
        $this->NumDocumento = $numero;
        return $this;
    }
    
    /**
      * Define a propriedade Valor e ValorUnitario, retirando a vírgula.
      * setValor() verifica o valor da propriedade Quantidade e ajusta
      * o valor da propriedade Valor
      * 
      * @version 0.1 18/05/2011 Initial
      *
      */
    public function setValor($valor){
        $this->ValorUnitario = (int) ($valor * 100);
        if(empty($this->Quantidade)){
            $this->Quantidade = 1;
        }
        $this->Valor = $this->ValorUnitario * $this->Quantidade;
        
        return $this;
    }
    
    /**
      * Define o nosso número do boleto
      * 
      * @version 0.1 18/05/2011 Initial
      *
      */
    public function setNossoNumero($valor){
        if(!preg_match('/[^0-9]/', $valor)){
            $this->NossoNumero = $valor;
            return $this;
        }
        else{
            throw new Exception('Boleto::setNossoNumero só permite números');
        }
        
    }
    
    /**
      * Define uma data específica de vencimento
      * 
      * @version 0.1 18/05/2011 Initial
      *
      */
    public function setVencimento($dia, $mes, $ano){
        $this->FatorVencimento = OB::fatorVencimento($dia, $mes, $ano);
        $this->Vencimento = $dia . '/' . $mes . '/' . $ano;
        return $this;
    }
    
    /**
      * Adiciona dias à data de hoje para definir o vencimento
      * 
      * @version 0.1 18/05/2011 Initial
      *
      */
    public function setDiasVencimento($num){
        $time = strtotime('+' . $num . ' days');
        $this->setVencimento(date('d', $time), date('m', $time), date('Y', $time));
        return $this;
    }
    
    /**
      * 
      * 
      * @version 0.1 20/05/2011 Initial
      */
    public function setMulta($valor){
        $this->Multa = $valor;
        return $this;
    }
    
    /**
      * 
      * 
      * @version 0.1 20/05/2011 Initial
      */
    public function setDesconto($valor){
        $this->Desconto = $valor;
        return $this;
    }
    
    /**
      * 
      * 
      * @version 0.1 20/05/2011 Initial
      */
    public function setOutrosAcrescimos($valor){
        $this->OutrosAcrescimos = $valor;
        return $this;
    }
    
    /**
      * 
      * 
      * @version 0.1 20/05/2011 Initial
      */
    public function setOutrosAbatimentos($valor){
        $this->OutrosAbatimentos = $valor;
        return $this;
    }
    
    /**
      * Define o índice quantidade desse boleto
      * 
      * @version 0.1 20/05/2011 Initial
      *          0.2 21/05/2011 Ajusta o valor nominal do boleto de acordo
      *             com a quantidade
      *          0.3 21/05/2011 Obriga $num a ser maior ou igual a 1
      */
    public function setQuantidade($num = 1){
        if($num < 1) {
            $num = (int) 1;
        }
        
        $this->Quantidade = $num;
        
        if(!empty($this->ValorUnitario)){
            $this->Valor = $this->ValorUnitario * $this->Quantidade;
        }
        
        return $this;
    }
    
    
}