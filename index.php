<?php
 $nomeArq = "frontend/jogos.html";
 if(!file_exists($nomeArq)){
    echo "<h1> arquivo nao existe";
    exit();
 }
 $pagina = file_get_contents($nomeArq);
 $pagina = str_replace("#dados", "", $pagina);

 echo $pagina;
 
?>