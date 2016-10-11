<?php 
/**
 * @author Mikhail Cavalcanti <mikhailcavalcanti@gmail.com> 
 */
class FornecedorInvoice extends GenericModel
{
    private $idFornecedorExterior = null;
    private $dsNome = null;
    private $dsEndereco = null;
    private $dsPais = null;

    protected $_banco = 'bdcorporativo';
    protected $_schema = 'scSAC';
    protected $_name = 'tbFonecedorExterior';

    function __construct($idFornecedorExterior = null, $dsNome = null, $dsEndereco = null, $dsPais = null)
    {
        parent::__construct();
        $this->idFornecedorExterior = $idFornecedorExterior;
        $this->dsNome = $dsNome;
        $this->dsEndereco = $dsEndereco;
        $this->dsPais = $dsPais;
    }

    public function getIdFornecedorExterior()
    {
        return $this->idFornecedorExterior;
    }

    public function getNome()
    {
        return $this->dsNome;
    }

    public function getEndereco()
    {
        return $this->dsEndereco;
    }

    public function getPais()
    {
        return $this->dsPais;
    }

    public function setIdFornecedorExterior($idFornecedorExterior)
    {
        $this->idFornecedorExterior = $idFornecedorExterior;
    }

    public function setNome($dsNome)
    {
        $this->dsNome = $dsNome;
    }

    public function setEndereco($dsEndereco)
    {
        $this->dsEndereco = $dsEndereco;
    }

    public function setPais($dsPais)
    {
        $this->dsPais = $dsPais;
    }



}
