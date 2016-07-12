<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ItemCustoxComprovantePagamento
 *
 * @author 01610881125
 */
class ComprovantePagamentoxPlanilhaAprovacao extends GenericModel
{
    protected $_banco   = 'bdcorporativo';
    protected $_name    = 'tbComprovantePagamentoxPlanilhaAprovacao';
    protected $_schema  = 'scSAC';

    public function inserirItemCustoxComprovantePagamento($data){
        $insert = $this->insert($data);
        return $insert;
    }

    public function alterarItemCustoxComprovantePagamento($data, $where){
        $update = $this->update($data, $where);
        return $update;
    }

    public function deletarItemCustoxComprovantePagamento($where){
        $delete = $this->delete($where);
        return $delete;
    }

    public function valorTotalPorItem($idPlanilhaAprovacao){
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(
                        array('cpa'=>$this->_schema.'.'.$this->_name),
                        array(
                                'Total'=>new Zend_Db_Expr('sum(cpa.vlComprovado)'),
                              )
                      );

        $select->where('cpa.idPlanilhaAprovacao = ?',$idPlanilhaAprovacao);

        return $this->fetchAll($select);
    }

    public function valorComprovadoItem($retornaSelect = false)
    {
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(
            array('cpxpa' => $this->_name),
            array(
                'vlComprovado' => new Zend_Db_Expr('sum(cpxpa.vlComprovado)'),
                'idPlanilhaAprovacao'
            ),
            $this->_banco.'.'.$this->_schema
        );
        $select->where('stItemAvaliado = ?', 1);
        $select->group('idPlanilhaAprovacao');

        if($retornaSelect)
            return $select;
        else
            return $this->fetchAll($select);
    }

    /**
     * Validacao do valor a ser comprovado, verifica o valor aprovado - total ja aprovado
     * identificando o valor máximo permitido para comprovação 
     */
    public function validarValorComprovado($idPronac, $idPlanilhaAprovacao, $idPlanilhaItem, $vlComprovado)
    {
    	$planilhaAprovacaoModel = new PlanilhaAprovacao();
    	$planilhaItem = $planilhaAprovacaoModel->buscar(array('idPlanilhaAprovacao = ?' => $idPlanilhaAprovacao))->current();
    	$valorAprovado = $planilhaItem->qtItem * $planilhaItem->nrOcorrencia * $planilhaItem->vlUnitario;
    	
    	$comprovantesPagamento = $planilhaAprovacaoModel->buscarcomprovantepagamento($idPronac, $idPlanilhaItem);
    	$totalComprovado = 0;
    	foreach ($comprovantesPagamento as $comprovante) {
    		if (2 == $comprovante->stItemAvaliado) {
    			$totalComprovado += $comprovante->vlComprovadoPlanilhaAprovacao;
    		}
    	}
    	if ($valorAprovado < ($totalComprovado + $vlComprovado)) {
    		throw new Exception('Comprovação de pagamento do item acima do valor aprovado.');
    	}
    }

    /**
     * Author: Fernao Lopes Ginez de Lara
     * Descrição: Função criada a pedido da Área Finalistica em 13/04/2016
     * @param $idPronac
     */
    public function atualizarComprovanteRecusado($idPronac) {
      $db = Zend_Registry::get('db');
      $db->setFetchMode(Zend_DB::FETCH_ASSOC);

      try {
          $update = "UPDATE bdcorporativo.scSAC.tbComprovantePagamentoxPlanilhaAprovacao
                   SET stItemAvaliado = 4
                   FROM bdcorporativo.scSAC.tbComprovantePagamentoxPlanilhaAprovacao AS a
                   INNER JOIN bdcorporativo.scSAC.tbComprovantePagamento AS b ON b.idComprovantePagamento = a.idComprovantePagamento
                   INNER JOIN SAC.dbo.tbPlanilhaAprovacao AS c ON c.idPlanilhaAprovacao = a.idPlanilhaAprovacao
                   WHERE stItemAvaliado = 3
                   AND IdPRONAC = ? ";
          
          $db->query($update, array($idPronac));
          
          $update2 = "UPDATE sac.dbo.tbDiligencia
                    SET DtResposta = GETDATE(),
                    RESPOSTA  = 'O PROPONENTE JÁ REALIZOU O AJUSTE DOS COMPROVANTES QUE HAVIAM SIDO RECUSADOS PELO MINISTÉRIO DA CULTURA.'
                    WHERE idTipoDiligencia = 174 and idPronac = ? AND stEstado = 0";
          
          $db->query($update2, array($idPronac));
          
      } catch (Exception $e) {
          die("ERRO: " . $e->getMessage());
      }      
    }    
}
