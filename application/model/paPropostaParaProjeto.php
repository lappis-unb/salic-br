<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of paVoltarProjetoFinalizadoComponente
 *
 * @author 01129075125
 */
class paPropostaParaProjeto extends GenericModel {
        
    protected $_banco = 'SAC';
    protected $_name  = 'paPropostaParaProjeto';

    #public function execSP($idProposta, $CNPJCPF, $idOrgao, $idUsuario){
    /**
     * @author Alysson Vicuña de Oliveira
     * Chamada da Procedure responsável por Transformar uma Proposta em Projeto dentro do Salic
     * @param $idProposta
     * @param $CNPJCPF
     * @param $idOrgao
     * @param $idUsuario
     * @param $nrProcesso
     * @return string|Zend_Db_Statement_Interface
     */
    public function execSP($idProposta, $CNPJCPF, $idOrgao, $idUsuario, $nrProcesso){
        try{
        #$rodar = "exec " . $this->_banco .".dbo.". $this->_name . ' ' . $idProposta .',"'. $CNPJCPF.'",'. $idOrgao.','. $idUsuario;
        $rodar = "exec " . $this->_banco .".dbo.". $this->_name . ' ' . $idProposta .',"'. $CNPJCPF.'",'. $idOrgao.','. $idUsuario.',"'. $nrProcesso . '"';
        return  $this->getAdapter()->query($rodar);
        }
        catch(Zend_Exception $e){
            return $e->getMessage();
        }
    }
}
?>
