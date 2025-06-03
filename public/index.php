<?php
// Ativa exibição de erros
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Obtém a URL requisitada
$request = $_SERVER['REQUEST_URI'];

// Remove o caminho do projeto
// Define a base da URL corretamente, independente de maiúsculas ou do ambiente
$base = '/Dev-admn';
$request = str_replace($base, '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

//var_dump($request); exit;



// Roteamento simples
switch ($request) {
    
    case '/public/login' :
    case '/login':
        require __DIR__ . '/../app/views/login.php';
        break;

    case '/public/painel':
    case '/painel':
        require __DIR__ . '/../app/views/painel.php';
        break;

    case '/public/solicitar':
    case '/solicitar':
        require __DIR__ . '/../app/views/solicitar.php';
        break;

    case '/public/devolucao':
    case '/devolucao':
        require __DIR__ . '/../app/views/devolucao.php';
        break;

    case '/public/cadastro':
    case '/cadastro':
        require __DIR__ . '/../app/views/cadastro.php';
        break;

        case '/public/admin' :
    case '/admin':
        require __DIR__ . '/../app/views/admin.php';
        break;


       case '/js/painel.js':
    header('Content-Type: application/javascript');
    readfile(__DIR__ . '/../public/js/painel.js');
    break;


        case '/ajax/lista_veiculos.php':
    require __DIR__ . '/../app/ajax/lista_veiculos.php';
    break;

case '/ajax/atualiza_veiculo.php':
    require __DIR__ . '/../app/ajax/atualiza_veiculo.php';
    break;

    default:
        http_response_code(404);
        echo "Página não encontrada";
        break;
}

?>
