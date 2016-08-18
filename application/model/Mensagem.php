<?php

/**
 * Abstração da tabela SAC.dbo.tbMensagem
 *
 * @author rafael.gloria@cultura.gov.br
 */
class Mensagem extends GenericModel{

    protected $_name = 'tbMensagem';
    protected $_schema = 'dbo';
    protected $_banco = 'SAC';

    /**
     * Salva a informação de para quais dispositivos foram enviadas as mensagens no banco de dados.
     * 
     * @param Zend_Db_Table_Row_Abstract $messageRow
     */
    public function saveListDevice(Zend_Db_Table_Row_Abstract $messageRow, $listDeviceId = array()) {
        if($listDeviceId){
            $modelMensagemDispositivo = new MensagemDispositivoMovel();
            foreach ($listDeviceId as $deviceId) {
                $messageDeviceRow = $modelMensagemDispositivo->createRow();
                $messageDeviceRow->idMensagem = $messageRow->idMensagem;
                $messageDeviceRow->idDispositivoMovel = $deviceId;
                $messageDeviceRow->save();
            }
        }
    }
    
    /**
     * Lista todos os dispositivos por idPronac.
     * 
     * @param integer $idPronac
     * @return array
     */
//    public function listarPorIdPronac($idPronac){
//        $consulta = $this->select();
//        $consulta->setIntegrityCheck(false);
//        $consulta
//            ->from(array('projetos' => 'vwAgentesSeusProjetos'), array(), 'SAC.dbo')
//            ->join(array('usuario' => 'SGCacesso'), 'projetos.IdUsuario = usuario.IdUsuario', array(
//                'cpf' => 'Cpf'), 'ControleDeAcesso.dbo')
//            ->join(array('dispositivo' => 'tbDispositivoMovel'), 'usuario.Cpf = dispositivo.nrCPF', array(
//                'idRegistration'), 'SAC.dbo')
//            ->group(array(
//                'cpf',
//                'idRegistration'))
//        ;
//        
//        return $this->fetchAll($consulta);
//    }
    
}
