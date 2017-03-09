<?php

class Projeto_Model_Situacao extends MinC_Db_Table_Abstract
{
    protected $_name = 'Situacao';
    protected $_schema = 'sac';
    protected $_primary = 'Codigo';

    const PROPOSTA_TRANSFORMADA_EM_PROJETO = 'B01';
    const ENCAMINHADO_PARA_ANALISE_TECNICA = 'B11';
    const AGUARDANDO_ELABORACAO_DE_PORTARIA_DE_PRORROGACAO = 'D22';

}
