insert into testplayerbitcoin.venta
select * from erpplayerbitcoin.venta
where id_user not in 
(
select id from testplayerbitcoin.users
);

insert into testplayerbitcoin.venta
select * from erpplayerbitcoin.venta
where id_venta not in 
(
select id_venta from testplayerbitcoin.venta
);

insert into testplayerbitcoin.cross_venta_mercancia
select `id_venta`, `id_mercancia`, `cantidad`,
 `costo_unidad`, `impuesto_unidad`, 
 `costo_total`, `nombreImpuesto`,null id_
from erpplayerbitcoin.cross_venta_mercancia
where id_venta not in 
(
select id_venta from testplayerbitcoin.cross_venta_mercancia
);

insert into testplayerbitcoin.comision
select null id,`id_venta`, `id_afiliado`, 
`id_red`, `fecha`, `puntos`, `valor`
 from erpplayerbitcoin.comision
where id_venta not in 
(
select id_venta from testplayerbitcoin.comision
) ;


insert into testplayerbitcoin.ticket
select null id,`user_id`, `date_creation`,
 `date_final`, `amount`, 
 `estatus`, `reference`, `bonus`
 from erpplayerbitcoin.ticket
where user_id not in
(
select user_id from testplayerbitcoin.ticket
) ;

insert into testplayerbitcoin.transaccion_billetera
select null id,`fecha`, `id_user`, 
`tipo`, `descripcion`,
 `monto`, `token`, `invoice`
 from erpplayerbitcoin.transaccion_billetera
where id_user not in
(
select id_user from testplayerbitcoin.transaccion_billetera
) and id_user not in (2) ;

truncate erpplayerbitcoin.venta;
truncate erpplayerbitcoin.cross_venta_mercancia;
truncate erpplayerbitcoin.comision;
truncate erpplayerbitcoin.ticket;
truncate erpplayerbitcoin.transaccion_billetera;

insert into erpplayerbitcoin.venta
select * from testplayerbitcoin.venta;

insert into erpplayerbitcoin.cross_venta_mercancia
select * from testplayerbitcoin.cross_venta_mercancia;

insert into erpplayerbitcoin.comision
select * from testplayerbitcoin.comision;

insert into erpplayerbitcoin.ticket
select * from testplayerbitcoin.ticket;

insert into erpplayerbitcoin.transaccion_billetera
select * from testplayerbitcoin.transaccion_billetera;

insert erpplayerbitcoin.afiliar
select 
null id, 2 id_red, `id_afiliado`, `debajo_de`, `directo`, `lado`
 from erpplayerbitcoin.afiliar a
where a.id_afiliado not in
(
select b.id_afiliado 
from erpplayerbitcoin.afiliar b
where b.id_red = 2
);

