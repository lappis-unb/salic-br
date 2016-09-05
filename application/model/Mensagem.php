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
     * Monta todos os filtros padrões das consultas e também montará os filtros dinâmicos.
     * 
     * @param Zend_Db_Table_Select $consulta
     * @param stdClass $objParam
     * @return Zend_Db_Table_Select
     */
    public function montarFiltrosListarDeDispositivo($consulta, stdClass $objParam){
        # Filtros padrões obrigatórios.
        $consulta
            ->where('m.dtExclusao IS NULL')
            ->where('md.dtExclusao IS NULL')
            ->where('d.idRegistration = ?', $objParam->idRegistration? $objParam->idRegistration: '');
        
        # Filtro(s) Dinamico(s).
        if($objParam->new) {
            $consulta->where('m.dtAcesso IS NULL');
        }
        
        return $consulta;
    }
    
    public function buscarTotalListarDeDispositivo(stdClass $objParam){
        $total = 0;
        $consulta = $this->select();
        $consulta->setIntegrityCheck(false);
        $consulta
            ->from(array('m' => 'tbMensagem'), array('total' => new Zend_Db_Expr('COUNT(DISTINCT m.idMensagem)')), 'SAC.dbo')
            ->join(array('md' => 'tbMensagemDispositivoMovel'), 'm.idMensagem = md.idMensagem', array(), 'SAC.dbo')
            ->join(array('d' => 'tbDispositivoMovel'), 'md.idDispositivoMovel = d.idDispositivoMovel', array(), 'SAC.dbo');
        # Filtros
        $this->montarFiltrosListarDeDispositivo($consulta, $objParam);

        $rs = $this->fetchRow($consulta);
        if($rs){
            $total = (int)$rs->total;
        }
        
        return $total;
    }
    
    /**
     * Lista mensagens por codigo de registro do dispositivo.
     * 
     * @param stdClass $objParam
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function listarDeDispositivo(stdClass $objParam){
        $consulta = $this->select();
        $consulta->setIntegrityCheck(false);
        $consulta
            ->from(array('m' => 'tbMensagem'), array(
                'idMensagem',
                'nrCPF',
                'idPronac',
                'titulo',
                'descricao',
                'dtEnvio',
                'dtAcesso'), 'SAC.dbo')
            ->join(array('md' => 'tbMensagemDispositivoMovel'), 'm.idMensagem = md.idMensagem', array(), 'SAC.dbo')
            ->join(array('d' => 'tbDispositivoMovel'), 'md.idDispositivoMovel = d.idDispositivoMovel', array(), 'SAC.dbo')
            ->group(array(
                'm.idMensagem',
                'm.nrCPF',
                'm.idPronac',
                'm.titulo',
                'm.descricao',
                'm.dtEnvio',
                'm.dtAcesso'))
            ->order(array(
                'dtAcesso ASC',
                'dtEnvio DESC'));

        # Filtros
        $consulta = $this->montarFiltrosListarDeDispositivo($consulta, $objParam);

        # Paginação
        if($objParam->next) {
            $consulta->limit($objParam->next, (int)$objParam->offset);
        }

        return $this->fetchAll($consulta);
    }

}
