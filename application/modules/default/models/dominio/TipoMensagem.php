<?php

/**
 * Classe para o dominio da informa&ccedil;&atilde;o de tipo de Mensagens.
 * 
 */
class Dominio_TipoMensagem {

    const DILIGENCIA = 1;

    const CAPTACAO = 2;
    
    /**
     * Lista de tipos de mensagens.
     * 
     * @var array
     */
    private static $lista = array(
        self::DILIGENCIA => 'Diligência',
        self::CAPTACAO => 'Capita&ccedil;&atilde;o'
    );
    
    public static function getLista() {
        return self::$lista;
    }

    /**
     * Busca a descri&ccedil;&atilde;o de acordo com o número do tipo.
     * 
     * @param integer $numero
     * @return string
     */
    public static function getDescricao($numero){
        $descricao = NULL;
        if($numero){
            $descricao = self::$lista[$numero];
        }
        
        return $descricao;
    }
    
    /**
     * Classe para o dominio da informa&ccedil;&atilde;o de tipo de Mensagens.
     * 
     */
    public function __construct() {
        
    }

}
