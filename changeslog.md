erp.multinivel 
=
_ERP BASE MLM - Versión: 3.9 - 
[NetworkSoft DEV](http://network-soft.com)_

27-03-2019
-
### support (tickets) new table 
   ```mysql
create table support
(
	id int auto_increment,
	id_user int default 2 null,
	title varchar(35) default 'ticket' null,
	content text null,
	attachment varchar(100) default 0 null comment 'id archivo, comma seperated',
	estatus varchar(3) default 'ACT' null comment '''DES'',''BLK'',''CLD''',
	parent int default 0 null,
	orden int default 1 null,
	constraint support_pk
		primary key (id)
)
comment 'support tickets';
```
27-03-2019
-
### settings new table 
   ```mysql
CREATE TABLE settings
(
    id int PRIMARY KEY AUTO_INCREMENT,
    title varchar(35) DEFAULT 'default',
    description varchar(150)  
      COMMENT '{json}, text',
    auto_payment_limit float DEFAULT 0,
    payment_fee float DEFAULT 0,
    transfer_fee float DEFAULT 0,
    estatus varchar(3) DEFAULT 'ACT',
    orden int DEFAULT 1
);
ALTER TABLE settings
 COMMENT = 'platform settings ex: payment';
 ```
20-03-2019
-
### max amount (cobro) in empresa_multinivel
   ```mysql
ALTER TABLE empresa_multinivel 
ADD cobro_maximo float DEFAULT 0 NULL 
COMMENT 'max amount for auto pay blockchain';
 ```
19-03-2019
-
### bitcoin fake winners
   ```mysql
CREATE TABLE fakewinners
(
    id int PRIMARY KEY AUTO_INCREMENT,
    username varchar(35) DEFAULT 'johndoe',
    fullname varchar(100) DEFAULT 'john doe',
    estatus varchar(3) DEFAULT 'ACT'
);
ALTER TABLE fakewinners 
COMMENT = 'usuarios fake para sorteo';
 ```
13-03-2019
-
### account data in blockchain
   ```mysql
ALTER TABLE blockchain 
ADD accountid varchar(100) 
  DEFAULT 0000 NULL 
  COMMENT 'guid wallet for service:3000'
  AFTER apikey,
ADD accountkey varchar(100) 
  DEFAULT 0000 NULL 
  COMMENT 'account pass for service:3000' 
  after accountid;
 ```
13-03-2019
-
### transferring data in transaccion_billetera
   ```mysql
ALTER TABLE transaccion_billetera 
ADD token varchar(50) DEFAULT 0 NULL 
COMMENT 'token to transferring between users';
ALTER TABLE transaccion_billetera 
ADD invoice int DEFAULT 2 NULL
 COMMENT 'id user sender transferring';
   ```
08-03-2019
-
### comision_pasivo
   ```mysql
  CREATE TABLE comision_pasivo
  (
      id int PRIMARY KEY AUTO_INCREMENT,
      user_id int DEFAULT 2,
      initdate timestamp 
        DEFAULT current_timestamp,
      enddate timestamp 
        DEFAULT current_timestamp,
      update_time timestamp 
        DEFAULT current_timestamp 
        COMMENT 'última actualización',
      amount float DEFAULT 0,
      estatus varchar(3) DEFAULT 'ACT',
      reference int default 0 null
        comment 'id_venta, 0 is null',
      extra varchar(150) DEFAULT '' 
        COMMENT 'json data'
  );
  ALTER TABLE comision_pasivo 
    COMMENT = 'bono pasivo';
   ```
05-03-2019
-
### extra in comision_bono
   ```mysql
  ALTER TABLE comision_bono 
  ADD extra varchar(35) 
  NULL COMMENT 'tickets ?';
   ```
### address in cross_user_banco
  ```mysql
 ALTER TABLE cross_user_banco
  ADD address varchar(120) NULL 
  COMMENT 'BTC wallet address';
  ```
### address in cobro
  ```mysql
 ALTER TABLE cobro
  ADD address varchar(120) NULL 
  COMMENT 'BTC wallet address';
  ```
### bonus:percent in ticket
```mysql
alter table ticket
add bonus float default 50 null 
comment 'percent';
```
### bitcoin diario
 ```mysql
CREATE TABLE bitcoin_stats
(
    id int PRIMARY KEY AUTO_INCREMENT,
    date_status timestamp DEFAULT 
      current_timestamp COMMENT 'UTC',
    amount float DEFAULT 0 
      COMMENT 'valor de bitcoin en ?',
    currency varchar(4) DEFAULT 'USD' 
      COMMENT 'USD... ?',
    estatus varchar(3) DEFAULT 'ACT',
    extra varchar(100) DEFAULT '' 
      COMMENT 'data json ... ?'
);
ALTER TABLE bitcoin_stats 
COMMENT = 'reporte de bitcoin';
 ```
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
  estatus varchar(3) DEFAULT 'ACT' COMMENT 'DES por jugar 
  BLK cuando pasa la fecha de sorteo',
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