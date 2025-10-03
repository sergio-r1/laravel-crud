# Laravel CRUD de Contatos

Este projeto é uma aplicação simples de CRUD para gerenciamento de contatos (Nome, E-mail, CPF), desenvolvida com:

- PHP 8+ / Laravel
- Frontend com Blade + JavaScript
- Banco de dados SQLite
- Docker 

---

## Funcionalidades

- Criar, listar, atualizar e remover contatos
- API RESTful em `/api/contacts`
- Frontend integrado
- Ambiente totalmente containerizado com Docker

---

## Requisitos

- [Docker](https://docs.docker.com/get-docker/) 

---

## Configuração com Docker

### 1. Clonar o repositório e entrar no diretório do projeto

```bash
    git clone https://github.com/sergio-r1/laravel-crud
```
```bash
    cd laravel-crud/backend
```

### 2. Criar o arquivo .env a partir do arquivo exemplo 
OBS: Para esse projeto, o .env.example já contém as informações para rodar o projeto, basta apenas fazer a cópia.
```bash
    cp .env.example .env
```
### 3. Realizar o build e a execução do container Docker para inicializar o ambiente da aplicação

Construção:
```bash
    docker build -t backend .
```

Execução:
```bash
    docker run -p 8000:8000 backend
```

### 4. Acessar o projeto no navegador

Agora que o projeto já está em execução no container, basta abrir o navegador no [localhost](http://127.0.0.1:8000/) e você verá a página inicial.
Basta clicar no botão que leva para o CRUD e adicionar um novo usuário.
