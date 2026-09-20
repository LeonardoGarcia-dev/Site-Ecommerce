<?php
session_start();
if (empty($_SESSION['sessaoAdmin'])) {
    header('Location: /index.php');
    exit;
}

function conecta ($paramStringConexao="")
{
    // string padrao
    if ($paramStringConexao == "") 
    {
      $paramStringConexao = "pgsql:host=localhost;port=5432;dbname=crudprod;user=postgres;password=postgres";

    }

    try { //tente
      $c = new PDO($paramStringConexao);
    } catch (PDOException $e) { // se der erro ...
      echo ">> Nao conectado ! <br>".
            "<b>Erro:</b> ".$e->getMessage();
      exit;
    }

    return $c;
}

    function salvaUpload($paramConn, $paramFiles, $paramCampo)
    {   
     // ISSET verifica se a variavel existe !!  
     //var_dump($paramFiles); 
     if ( isset( $paramFiles[$paramCampo] ) ) {
            // obtem o id do curso inserido
            $novoId   = $paramConn->lastInsertId();
            // obtem a extensão do arquivo
            $ext = pathinfo($paramFiles[$paramCampo]['name'],
                   PATHINFO_EXTENSION);
            // cria o novo nome do arquivo
            // exemplo: /imagens/10.png
            $arquivoNovo = "imagens/$novoId.$ext";
            try {
               if (move_uploaded_file($paramFiles[$paramCampo]['tmp_name'], 
                   $arquivoNovo)) {
                   echo "<br>Arquivo $arquivoNovo criado com sucesso.\n";
               } 
            } catch (PDOException $e) { // se der erro ...
               echo "Erro, verifique o arquivo se a pasta imagens existe";
            }     
        }
    }

?>