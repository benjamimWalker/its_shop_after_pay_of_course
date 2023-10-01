<?php

return [
    'labels' => [
        'search' => 'Pesquisar',
        'base_url' => 'URL Base',
    ],

    'auth' => [
        'none' => 'Esta API não requer autenticação.',
        'instruction' => [
            'query' => <<<'TEXT'
                Para autenticar as requisições, inclua um parâmetro de consulta **`:parameterName`** na requisição.
                TEXT,
            'body' => <<<'TEXT'
                Para autenticar as requisições, inclua um parâmetro **`:parameterName`** no corpo da requisição.
                TEXT,
            'query_or_body' => <<<'TEXT'
                Para autenticar as requisições, inclua um parâmetro **`:parameterName`** na string de consulta (query string) ou no corpo da requisição.
                TEXT,
            'bearer' => <<<'TEXT'
                Para autenticar as requisições, inclua um cabeçalho **`Authorization`** com o valor **`"Bearer :placeholder"`**.
                TEXT,
            'basic' => <<<'TEXT'
                Para autenticar as requisições, inclua um cabeçalho **`Authorization`** no formato **`"Basic {credenciais}"`**.
                O valor de `{credenciais}` deve ser o seu nome de usuário/ID e sua senha, separados por dois pontos (:),
                e depois codificados em base64.
                TEXT,
            'header' => <<<'TEXT'
                Para autenticar as requisições, inclua um cabeçalho **`:parameterName`** com o valor **`":placeholder"`**.
                TEXT,
        ],
        'details' => <<<'TEXT'
            Todos os endpoints que requerem autenticação estão marcados como `requer autenticação` na documentação abaixo.
            TEXT,
    ],

    'headings' => [
        'introduction' => 'Introdução',
        'auth' => 'Autenticação de requisições',
    ],

    'endpoint' => [
        'request' => 'Requisição',
        'headers' => 'Cabeçalhos',
        'url_parameters' => 'Parâmetros de URL',
        'body_parameters' => 'Parâmetros do Corpo',
        'query_parameters' => 'Parâmetros da Consulta',
        'response' => 'Resposta',
        'response_fields' => 'Campos da Resposta',
        'example_request' => 'Exemplo de requisição',
        'example_response' => 'Exemplo de resposta',
        'responses' => [
            'binary' => 'Dados binários',
            'empty' => 'Resposta vazia',
        ],
    ],

    'try_it_out' => [
        'open' => 'Experimente ⚡',
        'cancel' => 'Cancelar 🛑',
        'send' => 'Enviar Requisição 💥',
        'loading' => '⏱ Enviando...',
        'received_response' => 'Resposta Recebida',
        'request_failed' => 'Requisição falhou com o erro',
        'error_help' => <<<'TEXT'
            Dica: Verifique se você está conectado corretamente à rede.
            Se você é o mantenedor desta API, verifique se a sua API está em execução e se o CORS está habilitado.
            Você pode verificar as informações de depuração no console das Ferramentas de Desenvolvedor.
            TEXT,
    ],

    'links' => [
        'postman' => 'Visualizar a coleção do Postman',
        'openapi' => 'Visualizar a especificação do OpenAPI',
    ],
];
