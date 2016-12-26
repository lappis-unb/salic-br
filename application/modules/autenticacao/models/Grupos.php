<?php

class Autenticacao_Model_Grupos extends MinC_Db_Table_Abstract
{
    protected $_name = 'usuarios';
    protected $_schema = 'tabelas';
    protected $_primary = 'gru_codigo';

    const TECNICO_ADMISSIBILIDADE = 92;
    const COORDENADOR_ADMISSIBILIDADE = 131;
}