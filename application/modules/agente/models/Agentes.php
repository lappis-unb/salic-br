<?php

/**
 * Class Agente_Model_Agentes
 *
 * @name Agente_Model_Agentes
 * @package Modules/Agente
 * @subpackage Models
 * @version $Id$
 *
 * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
 * @since 01/09/2016
 *
 * @copyright © 2012 - Ministerio da Cultura - Todos os direitos reservados.
 * @link http://salic.cultura.gov.br
 */
class Agente_Model_Agentes extends MinC_Db_Model
{
    protected $_idagente;
    protected $_cnpjcpf;
    protected $_cnpjcpfsuperior;
    protected $_tipopessoa;
    protected $_dtcadastro;
    protected $_dtatualizacao;
    protected $_dtvalidade;
    protected $_status;
    protected $_usuario;

}
