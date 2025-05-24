/seu_projeto/
│
├── /app/
│   ├── /config/           → Configurações (banco de dados, rotas, etc.)
│   ├── /controllers/      → Lógica de controle (LoginController, VeiculoController, etc.)
│   ├── /models/           → Modelos (Login.php, Veiculo.php, Solicitacao.php)
│   └── /views/            → Interfaces (login.php, painel.php, solicitar.php, devolucao.php)
│
├── /public/               → Pasta pública (index.php, CSS, JS, imagens)
│   ├── /css/
│   ├── /js/
│   └── index.php          → Front Controller (entrada do sistema)
│
├── /vendor/               → Dependências externas (opcional)
│
├── .htaccess              → Redirecionamento para /public (para ocultar URL feia)
├── composer.json          → Dependências PHP (opcional)
└── README.md              → Descrição do projeto
