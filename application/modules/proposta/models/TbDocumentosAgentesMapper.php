<?php

/**
 * Class Proposta_Model_TbDocumentoAgentesMapper
 *
 * @name Proposta_Model_TbDocumentoAgentesMapper
 * @package Modules/Agente
 * @subpackage Models
 *
 * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
 * @since 29/09/2016
 *
 * @link http://salic.cultura.gov.br
 */
class Proposta_Model_TbDocumentosAgentesMapper extends MinC_Db_Mapper
{
    public function __construct()
    {
        parent::setDbTable('Proposta_Model_DbTable_TbDocumentosAgentes');
    }

    /**
     * @name saveCustom
     * @param $arrPost
     * @param Zend_File_Transfer $file
     * @return bool
     *
     * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
     * @since  02/10/2016
     */
    public function saveCustom($arrPost, Zend_File_Transfer $file)
    {
        $files = $file->getFileInfo();
        $booResult = true;
        if ($file->isUploaded()) {
            $arquivoNome = $files['arquivo']['name']; # nome
            $arquivoTemp = $files['arquivo']['tmp_name']; # nome temporario
            $arquivoTipo = $files['arquivo']['type']; # tipo
            $arquivoTamanho = $files['arquivo']['size']; # tamanho
            if (!empty($arquivoNome) && !empty($arquivoTemp)) {
                $arquivoExtensao = Upload::getExtensao($arquivoNome); # extensao
                $arquivoBinario = Upload::setBinario($arquivoTemp); # binario
                $arquivoHash = Upload::setHash($arquivoTemp); # hash
            }

            # Tamanho do arquivo: 10MB
            if ($arquivoTamanho > 10485760) {
                $this->setMessage('O arquivo não pode ser maior do que 10MB!');
                $booResult = false;
            } else {

                # Verifica se tipo de documento ja esta cadastrado
                $tbPreProjeto = new Proposta_Model_DbTable_PreProjeto();
                $dadosProjeto = $tbPreProjeto->findBy(array('idpreprojeto' => $arrPost['idpreprojeto']));

                $where = array();
                $where['codigodocumento'] = $arrPost['documento'];
                if($arrPost['tipodocumento'] == 1){
                    $where['idagente'] = $dadosProjeto['idagente'];
                } else {
                    $where['idprojeto'] = $arrPost['idpreprojeto'];
                }

                $strPath = '/data/proposta/model/tbdocumentoagentes/';
                $strPathFull = APPLICATION_PATH . '/..' . $strPath;

                $dadosArquivo = array(
                    'codigodocumento' => $arrPost['documento'],
                    'idprojeto' => $arrPost['idpreprojeto'],
                    'data' => date('Y-m-d'),
                    'noarquivo' => $arquivoNome,
                    'taarquivo' => $arquivoTamanho,
                    'dsdocumento' => $arrPost['observacao'],
                    'idagente' => $dadosProjeto['idagente'],
                );

                if ($arrPost['tipodocumento'] == 1) {
                    $table = $this;
                    $model = new Proposta_Model_TbDocumentosAgentes();
                } else {
                    $table = new Proposta_Model_TbDocumentosPreProjetoMapper();
                    $model = new Proposta_Model_TbDocumentosPreProjeto();
                }

                if($table->findBy($where)){
                    $this->setMessage('Tipo de documento j&aacute; cadastrado!');
                    $booResult = false;
                }

                if ($this->getDbTable()->getAdapter() instanceof Zend_Db_Adapter_Pdo_Mssql) {
                    $dadosArquivo['imdocumento'] = new Zend_Db_Expr("CONVERT(varbinary(MAX), {$arquivoBinario})");
                    try {
                        $model->setOptions($dadosArquivo);
                        $table->save($model);
                    } catch (Exception $e) {
                        $this->setMessage($e->getMessage());
                        $booResult = false;
                    }
                } else {
                    $strId = md5(uniqid(rand(), true));
                    $fileName = $strId . '.' . array_pop(explode('.', $file->getFileName()));
                    $dadosArquivo['imdocumento'] = $strPath . $fileName;
                    try {
                        $model->setOptions($dadosArquivo);
                        $table->save($model);
                        $file->receive();
                        copy($file->getFileName(), $strPathFull . $fileName);
                    } catch (Exception $e) {
                        $this->setMessage($e->getMessage());
                        $booResult = false;
                    }
                }

                # REMOVER AS PENDENCIAS DE DOCUMENTO
                $tblDocumentosPendentesProjeto = new Proposta_Model_DbTable_DocumentosProjeto();
                $tblDocumentosPendentesProponente = new Proposta_Model_DbTable_DocumentosProponente();
                $tblDocumentosPendentesProjeto->delete("idprojeto = {$arrPost['idpreprojeto']} AND codigodocumento = {$arrPost['documento']}");
                $tblDocumentosPendentesProponente->delete("idprojeto = {$arrPost['idpreprojeto']} AND codigodocumento = {$arrPost['documento']}");
            }
        } else {
            $this->setMessage('Falha ao anexar arquivo! O tamanho m&aacute;ximo permitido &egrave; de 10MB.');
            $booResult = false;
        }

        return $booResult;
    }

    public function save(Proposta_Model_TbDocumentosAgentes $model)
    {
        return parent::save($model);
    }
}