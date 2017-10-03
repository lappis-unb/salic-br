<?php if((empty($this->qtdDirigentes)) and ($this->qtdDirigentes == 0) and ($this->dados[0]['TipoPessoa'] == 1)):?>
    <center>
        <div class="msgALERT" style="width: 96%;">
            <div style="float: left;">Voc&ecirc; deve cadastrar pelo menos um dirigente!</div>
        </div>
    </center>
<?php endif;?>
<div class="row">
    <div class="input-field col s4 center">
        <?php if(($this->dados[0]['TipoPessoa']) == 1): ?>
            <b>CNPJ:</b> 
        <?php else:?>
            <b>CPF:</b>
        <?php endif;?>
        <?php echo $this->dados[0]['CNPJCPF']; ?>
    </div>
    <div class="input-field col s4 center">
        <b>NOME:</b> <?php echo $this->dados[0]['nome']; ?>
    </div>
    <div class="input-field col s4 center">
        <b>VIS&Otilde;ES:</b>
        <?php
            $i = 0;
            if($this->visoes):
                foreach($this->visoes as $v)
                {
                        if ( $i == 0 ):
                            echo $v->Descricao;
                        else:
                            echo " | " .$v->Descricao;
                        endif;
                        $i++;
                }
            else:
                echo 'O Agente n&atilde;o tem nenhuma vis&atilde;o!';
            endif;
        ?>
    </div>
</div>
<!-- <table class="tabela">
    <tr>
        <td width="160" class="centro">
            <?php if(($this->dados[0]['TipoPessoa']) == 1): ?>
            <b>CNPJ:</b> <?php #echo Mascara::addMaskCNPJ($this->dados[0]['CNPJCPF']); ?>
            <?php else:?>
            <b>CPF:</b> <?php #echo Mascara::addMaskCPF($this->dados[0]['CNPJCPF']); ?>
            <?php endif;?>
            <?php echo $this->dados[0]['CNPJCPF']; ?>
        </td>
        <td width="250"><b>NOME:</b> <?php echo $this->dados[0]['nome']; ?></td>
        <td><b>VIS&Otilde;ES:</b>
            <?php
                $i = 0;
                if($this->visoes):
                    foreach($this->visoes as $v)
                    {
                            if ( $i == 0 ):
                                echo $v->Descricao;
                            else:
                                echo " | " .$v->Descricao;
                            endif;
                            $i++;
                    }
                else:
                    echo 'O Agente n&atilde;o tem nenhuma vis&atilde;o!';
                endif;
            ?>
        </td>
    </tr>
</table> -->
