<?php
 
header("Content-Type: application/json; charset=utf-8"); 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
 
//Métodos que são permitidos
 
// Configuração do banco
 
$host "localhost";
$user = "root"; I
$pass = "";
$db - "api_video";
 
//Cria a Conexão com o Mysql/MariaDB
 
$conn = new mysqli($host, $user, $pass, $db);
 
// Verifica erro
 
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Falha na conexão:" . $conn->connect_error]);
    exit;
}
 
//Essa linha detecta o método utilizado (Post, Get, Delete ou Put)
 
$method $SERVER['REQUEST_METHOD'];
//Switch para direcionar o código para o seu respectivo método
 
switch ($method) {
 
    case 'GET':
    
        //if que testa se existe a variável pesquisa, simbolizando uma busca
        
        // Se não exister a query busca todos registros
        
        if (isset($_GET['pesquisa'])) {
        
            //Coloca as porcentagens simbolizando que a palavra buscada pode estar em qualquer lugar do texto
            
            $pesquisa "%".$_GET['pesquisa']. "%";
            
            //Prepara o comando SQL
            
            $stmt Sconn->prepare("SELECT FROM usuarios WHERE LOGIN LIKE? OR NOME LIKE ?");
            
            I //Substitui os parametros evitando SQL Injection
            
            $stmt->bind_param("ss", Spesquisa, Spesquisa);
            
            //Executa a query
            
            $stmt->execute();

            $result =$stmt->get_result();
        } else {
            $result= $conn ->querry ("SELECT * FROM usuarios ordee by ID desc");
        }
        $retorno = [];

        while ($linha = $result-> fetch_assoc()){
            $retorno[] = $linha;
        }
        
        echo json_encode($retorno);
        break;

    case 'POST':

        $data = json_decode(file_get_contents("php://input")), true);

        $stmt= $conn ->prepare ("INSERT INTO usuarios (LOGIN, NOME, EMAIL, SENHA, ATIVO) VALUES (?, ?, ?, ?, ?)");

        $stmt -> bind_param("ssssi", $data['LOGIN'], $data['NOME'], $data['EAMIL'], $data['DATA'], $data['ATIVO']);

        $stmt->execute();

        //Retorna para o usuário status ok e o ID inserido
 
echo json_encode(["status" => "ok", "insert_id" => $stmt->insert_id]);
 
break;
 
//0 método PUT é responsável por editar um registro no banco de dados
 
case 'PUT':
 
    //Essa linha lê todo o conteudo do body da requisição e transforma em json
    
    $data = json_decode(file_get_contents("php://input"), true);
    
    //Aqui é preparada a SQL
    
    $stmt = $conn->prepare("UPDATE usuarios SET LOGIN=?, NOME=?, EMAIL=?, SENHA=?, ATIVO? WHERE ID=?");
    
    //Os parametros são trocados pelos dados, uma proteção contra SQL Injection
    
    $stmt->bind_param("ssssii", $data['LOGIN'], $data['NOME'], $data['EMAIL'], $data['SENHA'], $data['ATIVO'], $data['ID']);
    
    //Comando executado
    
    $stmt->execute();
    
    //retorna status ok
    
    echo json_encode(["status" => "ok"]);
    break;
    
    //Método delete responsável por excluir um registro do banco de dados
 
case 'DELETE':
 
//Recebi o Id que venho junto a URL
 
$id = $_GET['id'];
 
//prepara o comando SOL

//Método delete responsável por excluir um registro do banco de dados
 
case 'DELETE':
 
    //Recebi o Id que venho junto a URL
     
    $id = $_GET['id'];
     
    //prepara o comando SQL
     
    $stmt = $conn->prepare("DELETE FROM usuarios WHERE ID=?");
     
    //Substitui os parametros
     
    $stmt->bind_param("i", $id);
     
    //executa o comando SQL
     
    $stmt->execute();
     
    //Retorna o status Ok para o front
     
    echo json_encode(["status" => "ok"]);
     
    break;
     
}
     
//Fecha a conexão com o banco de dados
     
$conn->close();
