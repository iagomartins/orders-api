## Instalações necessárias:

- WSL (Windows).
- Docker.

## Comandos utilizados

- Rodar o projeto: ./vendor/bin/sail up -d
- Rodar migrations e criar usuário admin: ./vendor/bin/sail artisan migrate:fresh --seed
- Listar rotas da API: ./vendor/bin/sail artisan route:list
- Rodar unit tests: ./vendor/bin/sail artisan test

## Token de acesso

- Para fazer requisições (exceto a de autenticação) utilize o header Authorization.
- No preenchimento do header adicione a palavra Bearer antes do token de acesso.

## Lista de rotas da API

  POST            api/authenticate ........................................... Api\V1\UserController@createAccessToken
  - Cria o token de acesso para a API.
    body: {
        "email":"admin@admin.com",
        "password": "password"
    }

  POST            api/v1/filterOrders .............................. Api\V1\TravelOrdersController@showOrdersByFilters
  - Traz os pedidos de viagens baseado nos filtros.
    body: {
        "destination": "Brasil",
        "start_date": "2025/03/05",
        "end_date": "2025/05/08"
    }

  GET|HEAD        api/v1/notifications ................ notifications.index › Api\V1\UserNotificationsController@index
  - Traz todas as notificações criadas.

  POST            api/v1/notifications ................ notifications.store › Api\V1\UserNotificationsController@store
  - Cria uma notificação atrelada a um id de usuário.
    body: {
        "user_id": 1,
        "message": "Test notify"
    }

  GET|HEAD        api/v1/notifications/{notification} ... notifications.show › Api\V1\UserNotificationsController@show
  - Traz a notificação pelo id.
  
  DELETE          api/v1/notifications/{notification} notifications.destroy › Api\V1\UserNotificationsController@dest…
  - Deleta a notificação pelo id.

  GET|HEAD        api/v1/orders ................................... orders.index › Api\V1\TravelOrdersController@index
  - Lista todos os pedidos de viagem.

  POST            api/v1/orders ................................... orders.store › Api\V1\TravelOrdersController@store
  - Cria um pedido de viagem.
    body: {
        "customer_name": "Usuário 2",
        "destiny": "Brasil",
        "start_date": "2025/05/05",
        "return_date": "2025/05/08",
        "status": "Cancelled",
        "user_id": 1
    }

  PUT|PATCH       api/v1/orders/{order} ......................... orders.update › Api\V1\TravelOrdersController@update
  - Edita um pedido de viagem pelo id.
    body: {
        "customer_name": "Usuário 2",
        "destiny": "Brasil",
        "start_date": "2025/05/05",
        "return_date": "2025/05/08",
        "status": "Cancelled",
        "user_id": 1
    }

  POST            api/v1/ordersByUser ................................. Api\V1\TravelOrdersController@showOrdersByUser
  - Lista todos os pedidos de viagem de um usuário específico.
    body: {
        "user_id": 1
    }

  POST            api/v1/showUserNotifications ............. Api\V1\UserNotificationsController@getNotificationsByUser
  - Lista todas as notificações de um usuário específico.
    body: {
        "user_id": 1
    }

  POST            api/v1/userLogin ....................................................... Api\V1\UserController@login
  - Faz uma solicitação de login recebendo usuário e senha.
    body: {
        "email":"admin@admin.com",
        "password": "password"
    }

  POST            api/v1/users ............................................. users.store › Api\V1\UserController@store
  - Cria um novo usuário.
    body: {
        "email":"admin@admin.com",
        "password": "password"
    }
