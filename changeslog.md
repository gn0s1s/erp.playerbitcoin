erp.multinivel 
=
_ERP BASE MLM - Versión: 3.9 - 
[NetworkSoft DEV](http://network-soft.com)_

20-02-2019
-
### coinmarketcap
 ```mysql
 create table coinmarketcap
 (
   id int auto_increment primary key,
   accountid varchar(65) default 'you@domain.com' null
    comment 'account email',
   apikey    varchar(100) default '0' null
    comment 'api key',
   testkey   varchar(100) default '0' null
    comment 'api key test',
   coin      varchar(35) default 'BTC' null
    comment ', separated',
   test      int default '0' null
    comment '1 is actived',
   estatus   varchar(3) default 'ACT' null
 )  comment 'bitcoin stats integration';
 ```
### ticket btc category
```mysql
INSERT INTO `cat_grupo_producto` 
(`descripcion`, `id_red`, `estatus`)
 VALUES ('Tickets', '1', 'ACT');
```
18-02-2019
-
### ticket btc bet
```mysql
create table ticket
(
  id int auto_increment primary key,
  user_id int default '2'                     null
  comment 'id:users',
  date_creation timestamp default CURRENT_TIMESTAMP not null
  comment 'fecha de creacion',
  date_final int comment 'fecha de sorteo',
  amount float default '5'                   null
  comment 'valor de boleto',
  estatus varchar(3) default 'ACT'            null
  comment 'DES cuando pasa la fecha de sorteo',
  reference int default '1'                     null
  comment 'venta:id_venta associate item'
) comment 'boleto de apuesta btc';
```
16-02-2019
-
### online payment confirmations
```mysql
ALTER TABLE pago_online_proceso 
ADD confirmations int DEFAULT 1 NULL 
COMMENT 'repeats ex: blockchain = 3';
```
24-10-2018
-
### update hashkey
```mysql
ALTER TABLE blockchain_wallet 
MODIFY hashkey varchar(120) DEFAULT '0';
```
23-10-2018
-
### create wallets

```mysql
CREATE TABLE blockchain_wallet
    (
        id int PRIMARY KEY AUTO_INCREMENT,
        id_usuario int DEFAULT 2 COMMENT '1 es la empresa',
        hashkey varchar(100) DEFAULT 0000,
        porcentaje int DEFAULT 100,
        estatus varchar(3) DEFAULT 'ACT'
    );
```

21-10-2018
-  
### update web
```mysql
UPDATE empresa_multinivel
  SET web = 'http://demo.networksoft.com.mx' 
  WHERE id_tributaria LIKE '98765432-1'
 ```
20-10-2018
-
### create blockchain
```mysql
CREATE TABLE blockchain
 (
     id int PRIMARY KEY AUTO_INCREMENT,
     apikey varchar(100) DEFAULT 0000,
     currency varchar(4) DEFAULT 'USD',
     test int DEFAULT 0 COMMENT '1 is Actived',
     estatus varchar(3) DEFAULT 'ACT'
 );
```