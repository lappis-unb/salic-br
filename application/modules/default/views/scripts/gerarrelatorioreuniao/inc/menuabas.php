<?php

    $somaProjetosSubmetidos = 0;
    
    foreach ( $this->consultaGeraInfo as $valueReuniao )
    {
        $numeroReuniao = $valueReuniao->NrReuniao;
        $dataInicio = $valueReuniao->DtInicio;
        $dataFechamento = $valueReuniao->DtFechamento;
        
        if( $valueReuniao->stEnvioPlenario == "S" )
        {
            $somaProjetosSubmetidos++;
        }
    }

    if(isset($numeroReuniao))
    {

        echo "<div id='Jquery'>";
        echo "<table style=\"margin-left:10px; border:0; background:none;\">";
        echo "<tr colspan=1>";
        echo "<td>";
        echo "Informa&ccedil;&otilde;es sobre a reuni&atilde;o Reuni&atilde;o";
        echo "</td>";
        echo "<td>";
        echo $numeroReuniao;
        echo "</td>";
        echo "</tr>";
        echo "<tr colspan=1>";
        echo "<td>";
        echo "Per&iacute;odo para inclus&atilde;o de projetos";
        echo "</td>";
        echo "<td>";
        echo $dataInicio . " " . $dataFechamento;
        echo "</td>";
        echo "</tr>";
        echo "<tr colspan=1>";
        echo "<td>";
        echo "Total de projetos submetidos";
        echo "</td>";
        echo "<td>";
        echo $somaProjetosSubmetidos++;
        echo "</td>";
        echo "</tr>";
        echo "</table>";
        echo "</div>";

    }



   

?>