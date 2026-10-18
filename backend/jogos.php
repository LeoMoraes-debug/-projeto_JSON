<?php
 //testar se o conteudo do relatorio e valido
 if(!isset($_REQUEST['jogo'])){
    echo"<h1> Recurso Indisponivel";
    exit();
 }

 //receber os valores dos relatorios do front end
 $jogo = $_REQUEST['jogo'];
 
 //varivel como nome do site com api do json
 //eu tinha colcado a chave direto aqui, mas o claude disse que depois de colar o codigo em um chat nao adianta e passou essa cripto aí  
 require_once "config.php";
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

 // Verificar se algum jogo foi encontrado
   // if (!isset($dados['results'][0])){
    //echo "<h1>Jogo não encontrado ou erro na API.</h1>";
    //exit();
 //}

 //acaba que esse bloco é inutil por um puta merito da api, a busca deles retorna uma lista com os nomes mais parecidos e o retorno do json e sempre como primeiro da lista
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