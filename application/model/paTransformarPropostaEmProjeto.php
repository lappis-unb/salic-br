<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of paTransformarPropostaEmProjeto
 */
class paTransformarPropostaEmProjeto extends GenericModel {
        
    protected $_banco = 'SAC';
    protected $_name  = 'paTransformarPropostaEmProjeto';

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
    #public function execSP($idProposta, $CNPJCPF, $idOrgao, $idUsuario){
    public function execSP($idProposta, $CNPJCPF, $idOrgao, $idUsuario, $nrProcesso){
        try{
        #$rodar = "exec " . $this->_banco .".dbo.". $this->_name . ' ' . $idProposta .',"'. $CNPJCPF.'",'. $idOrgao.','. $idUsuario;
        $rodar = "exec " . $this->_banco .".dbo.". $this->_name . ' ' . $idProposta .',"'. $CNPJCPF.'",'. $idOrgao.','. $idUsuario.',"'. $nrProcesso . '"';
        #xd( $rodar);

        return  $this->getAdapter()->query($rodar);
        }
        catch(Zend_Exception $e){
            return $e->getMessage();
        }
    }
}
?>
