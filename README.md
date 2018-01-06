Teste para desenvolvedor do Estadão
==============================

Explicação do Teste
--------

### Alguns requisitos necessário para executar o teste.

- Extensão sqlite3
- Extensão pdo_sqlite

### Back-End/PHP

Backend desenvolvido sem nenhum framework, código totalmente limpo.

Para manipulação de dados foi utilizado o SQLite

**Classes Criadas:**

- **Connection.class.php** = Class abstrata para a manipulação de dados.
- **Carro.class.php** = Class para manipulação da tabela carros, é necessarário herdar a class Connection.
- **Controller.class.php** = Execução de Funções chamada pela Classe Root
- **Root.class.php** = Criação de rotas para cada função.
