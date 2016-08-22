<?php
/**
 * DeslocamentoDAO
 *
 * @uses Zend
 * @uses _Db_Table
 * @author 01129075125
 * @author wouerner <wouerner@gmail.com>
 */
class DeslocamentoDAO extends GenericModel {
    protected  $_schema = 'sac';
    protected $_name = 'tbdeslocamento';

    public function __construct() {
        parent::__construct();
    }
    public function init(){
        parent::init();
    }

    public static function buscarPais() {
        $sql = "SELECT * FROM AGENTES.dbo.Pais";

        $db  = Zend_Registry::get('db');
		$db->setFetchMode(Zend_DB::FETCH_OBJ);
		$resultado = $db->fetchAll($sql);

		return $resultado;
    }

    /**
     * buscarDeslocamentos
     *
     * @param mixed $idProjeto
     * @param bool $idDeslocamento
     * @static
     * @access public
     * @return void
     * @author wouerner <wouerner@gmail.com>
     */
    public static function buscarDeslocamentos($idProjeto, $idDeslocamento = null)
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $agenteSchema = parent::getSchema('agentes');
        $de = [
            'de.idDeslocamento',
            'de.idProjeto',
            'de.idPaisOrigem',
            'de.idUFOrigem',
            'de.idMunicipioOrigem',
            'de.idPaisDestino',
            'de.idUFDestino',
            'de.Qtde',
            'de.idUsuario',
            'de.idMunicipioDestino'
        ];

        $sql = $db->select()
            ->from(['de' => 'tbDeslocamento'], $de)
            ->joinLeft(['paO'=>'Pais'], 'de.idPaisOrigem = paO.idPais','paO.Descricao AS PO', $agenteSchema)
            ->joinLeft(['ufO'=>'UF'] , 'de.idUFOrigem = ufO.idUF','ufO.Descricao AS UFO', $agenteSchema)
            ->joinLeft(['muO' => 'Municipios'] , 'de.idMunicipioOrigem = muO.idMunicipioIBGE','muO.Descricao AS MUO', $agenteSchema)
            ->joinLeft(['paD' => 'Pais'], 'de.idPaisDestino = paD.idPais', 'paD.Descricao AS PD', $agenteSchema)
            ->joinLeft(['ufD' => 'UF'], 'de.idUFDestino = ufD.idUF','ufD.Descricao AS UFD', $agenteSchema)
            ->joinLeft(['muD' => 'Municipios '], 'de.idMunicipioDestino = muD.idMunicipioIBGE', 'muD.Descricao AS MUD', $agenteSchema)
            ->where("idProjeto = ?", $idProjeto)
            ;

            if($idDeslocamento != null)
            {
                $sql->where('de.idDeslocamento = ?', $idDeslocamento);
            }

        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        $resultado = $db->fetchAll($sql);

        return $resultado;
    }

    public static function buscarDeslocamentosGeral($where = array(), $order = array()){
        $meuWhere = "";
        // adicionando clausulas where
        foreach ($where as $coluna=>$valor)
        {
            if($meuWhere != ""){ $meuWhere .= " AND "; }
            $meuWhere .= $coluna.$valor;
        }

        $meuOrder = "";
        // adicionando clausulas order
        foreach ($order as $valor)
        {
            if($meuOrder != ""){ $meuOrder .= " , "; }else{ $meuOrder = " ORDER BY "; }
            $meuOrder .= $valor;
        }

        $sql = "SELECT
			    de.idDeslocamento,
			    de.idProjeto,
			    de.idPaisOrigem,
			    paO.Descricao PO,
			    de.idUFOrigem,
			    ufO.Descricao UFO,
			    de.idMunicipioOrigem,
			    muO.Descricao MUO,
			    de.idPaisDestino,
			    paD.Descricao PD,
			    de.idUFDestino,
			    ufD.Descricao UFD,
			    de.idMunicipioDestino,
			    muD.Descricao MUD,
			    de.Qtde,
			    de.idUsuario

			FROM
			    SAC.dbo.tbDeslocamento de
			   	LEFT JOIN AGENTES.dbo.Pais paO		 ON de.idPaisOrigem = paO.idPais
			    LEFT JOIN AGENTES.dbo.UF ufO		 ON de.idUFOrigem = ufO.idUF
			    LEFT JOIN AGENTES.dbo.Municipios muO ON de.idMunicipioOrigem = muO.idMunicipioIBGE
			    LEFT JOIN AGENTES.dbo.Pais paD		 ON de.idPaisDestino = paD.idPais
			    LEFT JOIN AGENTES.dbo.UF ufD		 ON de.idUFDestino = ufD.idUF
			    LEFT JOIN AGENTES.dbo.Municipios muD ON de.idMunicipioDestino = muD.idMunicipioIBGE
			WHERE
			    {$meuWhere}
                            $meuOrder";


        $db  = Zend_Registry::get('db');
		$db->setFetchMode(Zend_DB::FETCH_OBJ);
		$resultado = $db->fetchAll($sql);

		return $resultado;
    }


    public static function salvaDeslocamento($dados)
    {

        $db  = Zend_Registry::get('db');
		$db->setFetchMode(Zend_DB::FETCH_OBJ);
		$db->insert('SAC.dbo.tbDeslocamento', $dados);

    }

    public static function atualizaDeslocamento($paisOrigem,$uf,$cidade,$paisDestino,$ufD,$cidadeD,$quantidade,$idDeslocamento)
    {

    	$sql = "UPDATE SAC.dbo.tbDeslocamento SET idPaisOrigem=".$paisOrigem.", " .
    			"idUFOrigem = ".$uf.", " .
    			"idMunicipioOrigem = ".$cidade.", " .
    			"idPaisDestino = ".$paisDestino.", " .
    			"idUFDestino = ".$ufD.", " .
    			"idMunicipioDestino = ".$cidadeD.", " .
    			"Qtde = ".$quantidade." " .
    			"WHERE idDeslocamento = ".$idDeslocamento;


        $db  = Zend_Registry::get('db');
		$db->setFetchMode(Zend_DB::FETCH_OBJ);
		$db->query($sql);

    }

    public static function excluiDeslocamento($idDeslocamento){

        $sql ="DELETE FROM SAC.dbo.tbDeslocamento WHERE idDeslocamento = ".$idDeslocamento;

        $db  = Zend_Registry::get('db');
		$db->setFetchMode(Zend_DB::FETCH_OBJ);
		$db->query($sql);

    }


}
