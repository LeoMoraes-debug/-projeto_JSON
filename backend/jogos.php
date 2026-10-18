<?php
 //testar se o conteudo do relatorio e valido
 if(!isset($_REQUEST['jogo'])){
    echo"<h1> Recurso Indisponivel";
    exit();
 }

 //receber os valores dos relatorios do front end
 $jogo = $_REQUEST['jogo'];
 
 //varivel como nome do site com api do json

 $chave = "f76ecf22cd614f9ba9c585a7cc1c5e89";
 $url = "https://api.rawg.io/api/games?key=" . $chave . "&search=" . urlencode($jogo);

 //criar uam funcao para acessar uma url
 $curl = curl_init();
 curl_setopt_array($curl,[
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_URL => $url,
 ]);

 //jogar os valores do relatorio na api e receber o json no back ja em array
 $retorno = curl_exec($curl);

   //aqui eu ainda ano sei o que e mas o chat esta mandando eu por 
   if ($retorno === false) {
       echo "<h1>Erro ao consultar a API</h1>";
       echo "<p>Erro cURL: " . htmlspecialchars(curl_error($curl)) . "</p>";
       echo "<p>Código do erro: " . curl_errno($curl) . "</p>";
       curl_close($curl);
       exit();
   }

 $dados = json_decode($retorno, true);

 curl_close($curl);

  //aqui eu ainda ano sei o que e mas o chat esta mandando eu por 
 if ($retorno === false) {
    echo "<h1>Erro ao consultar a API</h1>";
    echo "<p>Erro cURL: " . htmlspecialchars(curl_error($curl)) . "</p>";
    echo "<p>Codigo do erro: " . curl_errno($curl) . "</p>";
    curl_close($curl);
    exit();
 }

 // Verificar se algum jogo foi encontrado
 if (!isset($dados['results'][0])){
    echo "<h1>Jogo não encontrado ou erro na API.</h1>";
    exit();
 }
 //selecionar o primeiro jogo esncontrado
 $dadosJogo = $dados['results'][0];
 $generos = array_column($dadosJogo['genres'],'name');

 //receber os valores dos json 
 $id = $dadosJogo['id'];
 $nome = $dadosJogo['name'];
 $lancamento = $dadosJogo['released'];
 $nota = $dadosJogo['rating'];
 $genero = implode(", ", $generos);
 

 //colocar as informacoes do array no front no lugar de #dados
 $pagina = file_get_contents("../frontend/jogos.html");

 $texto = "id: " . htmlspecialchars($id)
          . " - " . htmlspecialchars($nome)
          . " - " . htmlspecialchars($lancamento ?? 'N/D')
          . " - " . htmlspecialchars($genero)
          . "<br>" . htmlspecialchars($nota ?? 'N/D');

 //imprir as informacoes na tela
 echo str_replace("#dados" , $texto , $pagina)
?>