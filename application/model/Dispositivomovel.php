<?php

/**
 * Abstração da tabela SAC.DBo.tbAplicativoSalic
 *
 * @author rafael.gloria@cultura.gov.br
 */
class Dispositivomovel extends GenericModel{

    protected $_name = 'tbDispositivoMovel';
    protected $_schema = 'dbo';
    protected $_banco = 'SAC';
    
    /**
     * Salva o dispositivo que está conectado, salva o CPF do usuário e atualiza a última data de acesso.
     * 
     * @param string $registrationId
     * @param string $cpf Optional
     * @return array $dispositivo Dados do dispositivo
     */
    public function salvar($registrationId, $cpf = NULL){
        $dispositivo = array();
        
        if(!empty($registrationId)){
            $dispositivoRow = $this->fetchRow("idRegistration = '{$registrationId}'");
            if(!$dispositivoRow){
                $dispositivoRow = $this->createRow(array(
                    'idRegistration' => $registrationId
                ));
            }
            
            if($cpf){
                $dispositivoRow->nrCPF = $cpf;
            }
            $dispositivoRow->dtAcesso = new Zend_Db_Expr('getdate()');
            $dispositivoRow->save();
            $dispositivo = $dispositivoRow->toArray();
        }
        
        return $dispositivo;
    }
        
}
