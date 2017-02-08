# Projeto DUsers Api

## Pré-requisitos
sendmail
php5-dev
php-pear
php5-curl 
php5-cli
php5-mcrypt
curl
php5.6-xml
php-redis
php5.6-mysql
    

## Sequência de instalação

#### 1. Pre configuração do Banco de dados
+ Instalar o MySql no servidor (ip_mysql)
+ Instalar o Redis no servidor (ip_redis)
+ Criar o database que será usado na applicação (db_mysql)
+ Criar o usuário que terá acesso ao banco de dados desde aplicação (user_mysql)
+ Dar as permissões para o usuário criado para o uso da database
 

#### 2. Download do codigo da Api
    git clone https://gitlab.dcide.info/dcide/dcideusers-api.git

#### 3. Configuração do banco de dados e cache da aplicação
+ **Configuração do MySql**
    - Procurar o arquivo **config/autload/doctrine.global.php-default**. 
    - Duplicar e renomear o arquivo **doctrine.global.php-default** para **doctrine.global.php**.
    - Editar o arquivo de **config/autload/doctrine.global.php** usando os valores dos itens anteriores
        * ip_mysql
        * db_mysql
        * user_mysql
        * ip_redis
+ **Configuração do Redis**
    - Configuração do redis se encontra também no arquivo: doctrine.global.php

#### 4. Instalação de libs da aplicação

+ Executar o comando abaixo na pasta da App
    - Em desenvolvimento
        
        > composer install

    - Em produção
        
        > composer install --no-scripts

#### 5. Criação da estrutura de dados

Se o banco de dados está vazio precisará criar estrutura de banco de dados. Para isso executar o comando:

    > ./vendor/doctrine/orm/bin/doctrine orm:schema-tool:create

#### 6. Configura o Virtualhost

    <VirtualHost *:80>
        ServerName dusers-api.com.br
        DocumentRoot /var/www/dcideusers-api/public
    </VirtualHost>

    + Executar o reload do Apache

## Configurações adicionais do Sistema

    Diretório de configurações **/config/autoload**

## Configuração Gerais

    Arquivo de configuração de STMP, etc:

    > config.global.php

## Apidocs

   URL: < url >/apidocs
    
    composer apidocs

## Tests

   Diretório de testes se encontra em **/test**
   
   Executando testes:
   
    phpunit

   Executando teste específico. Ex:
    
    phpunit test/App/AdminUsuarios.php
    
