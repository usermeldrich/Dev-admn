<?php
// Ativa exibição de erros
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Obtém a URL requisitada
$request = $_SERVER['REQUEST_URI'];

// Remove o caminho do projeto
$request = str_replace('/Dev-admn/public', '', $request);

// DEBUG TEMPORÁRIO
var_dump($request); exit;

// Roteamento simples
switch ($request) {
    case '/' :
    case '' :
        require __DIR__ . '/../app/views/login.php';
        break;
    case '/painel' :
        require __DIR__ . '/../app/views/painel.php';
        break;
    case '/solicitar' :
        require __DIR__ . '/../app/views/solicitar.php';
        break;
    case '/devolucao' :
        require __DIR__ . '/../app/views/devolucao.php';
        break;
    case '/cadastro':
    require __DIR__ . '/../app/views/cadastro.php';
    break;

    default:
        http_response_code(404);
        echo "Página não encontrada";
        break;
}
?>
