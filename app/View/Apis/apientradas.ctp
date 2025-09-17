<?php
//header
error_reporting(0);
header("X-Powered-By: ASP.NET");#esconde a versao do php / mostra asp
header_remove(); 

//reader para jason
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");


// include de base e classes
include_once 'connect.ctp';
include_once 'cEntradas.php';

#verificaco de tipo e senha
$tipoRequisicao = $_SERVER['REQUEST_METHOD'];
$login          = $_SERVER['PHP_AUTH_USER'];
$senha          = $_SERVER['PHP_AUTH_PW'];

    
if($login === "ApiPib2020Entradas" && $senha ==="+;iO7F0cZyHJ"){

    if($tipoRequisicao=="POST"){
            
            //instancia de classes BD
            $database = new Database();
            $db = $database->getConnection();

            //instancia de classes Membro
            $entradas = new Entradas($db);

                //recebe os dados enviados via curl ou postman
                $json = file_get_contents('php://input');
                $obj = json_decode($json);

                try {
                    #atualiza pessoa com os dados enviados
                    $entradas->inserePessoa($obj);
                    // se tudo der certo executa a resposta em tela
                    http_response_code(200);
                    echo json_encode(
                        array("mensagem" => "1")#inserido
                    );
                }
                catch (Exception $e) {
                    http_response_code(404);
                        echo json_encode(
                            array("mensagem" => "0")#não inseriu
                        );
                }
                #echo 'Exceção capturada: ',  $e->getMessage(), "\n";
        }
        else{
            #ocorreu algum erro de passagem get ou post
            http_response_code(404);
            echo json_encode(
                array("mensagem" => "Metodo executado não corresponde a url.")
            );

        }
}

else{
    #ocorreu algum erro de senha e login
    http_response_code(404);
    echo json_encode(
        array("mensagem" => "Login e Senha incorretos")
    );

}



?>