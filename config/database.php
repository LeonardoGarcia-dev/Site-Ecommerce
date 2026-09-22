<!-- rapaziada criei esse aq pra gente conectar o banco de dados -->
 <?php 
    // -----------------------------------------------------------
    // funcao conecta()
    // maio/2026
    function conecta($paramString = "")
      {   
          // nao mandou nada, assume a string padrao 
        if ($paramString=="")
            $string_conexao = "pgsql:host=projetoscti.com.br; port=54432; 
                 dbname=loja4n; 
                 user=loja4n; password=dVDhfeOmFB7y2POr";
        else {
            // assume a string recebida 
            $string_conexao = $paramString;
        }

        try { //tente
          $c = new PDO($string_conexao);
        } catch (PDOException $e) { // se der erro ...
          echo "Serviço indisponivel no momento, 
                tente mais tarde !<br>".$e->getMessage();
          exit;
        }          
        return $c;
    }
?>