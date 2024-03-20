# Documentação do Sistema de Transferências

Este é um sistema de transferências monetárias entre usuários e lojistas, com a capacidade de realizar operações seguras e confiáveis, seguindo as especificações fornecidas.

Funcionalidades
Cadastro de usuários comuns e lojistas.
Envio de dinheiro entre usuários e para lojistas.
Consulta a um serviço autorizador externo antes de finalizar a transferência.
Operações de transferência são tratadas como transações, revertendo em caso de inconsistência.
Notificação de recebimento de pagamento via serviço de terceiro, com tratamento de possíveis indisponibilidades.
Endpoints

## 1. Autenticacao

Endpoint: POST  /auth/user

Payload:

form-data
{
    "email" : "teste@123.com",
    "password" : "sad4684",
}
será retornado um token para as proximas chamadas

## 2. get user

Endpoint: GET /users/myUser
Bearer token conseguido no passo anterior

## 2. Envio de Dinheiro

Descrição: Realiza uma transferência de dinheiro entre usuários ou para lojistas.

Endpoint: POST /transactions


json
{
    "value" : 100.00,
    "payee" : "sad4684",
    "provider" : "users/shopkeepers"
}

payee = id de quem recebera o deposito