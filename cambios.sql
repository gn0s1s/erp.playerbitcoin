
ALTER TABLE cat_rango
  MODIFY id_rango int(11) NOT NULL auto_increment;
INSERT INTO cat_rango (nombre, descripcion, condicion_red_afilacion, estatus)
VALUES ('compras', 'compras de items', 'EQU', 'ACT'),
       ('ventas', 'ventas de items', 'EQU', 'ACT');

INSERT INTO cross_rango_tipos (id_rango, id_tipo_rango, valor, condicion_red, nivel_red)
VALUES (1, 3, 1, 'RED', 0),
       (2, 2, 1, 'RED', 0);

/*
ALTER TABLE cat_bono_condicion
  MODIFY id int(11) NOT NULL auto_increment;
ALTER TABLE cat_bono_valor_nivel
  MODIFY id int(11) NOT NULL auto_increment;
*/
INSERT INTO bono
    (nombre, descripcion, inicio, fin,
     mes_desde_afiliacion, mes_desde_activacion,
     frecuencia, plan, estatus)
VALUES
       ('bitcoin', 'predicción del bitcoin',
        '2019-01-01', '2120-01-31', '0', '0',
        'DIA', 'NO', 'ACT');

INSERT INTO cat_bono_valor_nivel
    (id_bono, nivel, valor, condicion_red, verticalidad)
VALUES ('1', '0', '21', 'RED', 'ASC'),
       ('1', '1', '21', 'RED', 'ASC'),
       ('1', '2', '00', 'RED', 'ASC');

INSERT INTO cat_bono_condicion
    (id_bono, id_rango, id_tipo_rango, condicion_rango,
     id_red, condicion1, condicion2, calificado)
VALUES ('1', '1', '3', '1', '1', '2', 9, 'REC');

-- PSR

INSERT INTO bono
  (nombre, descripcion, inicio, fin,
  mes_desde_afiliacion, mes_desde_activacion,
   frecuencia, plan, estatus)
 VALUES
 ('Tickets PSR', 'Tickets auto by PSR purchasing',
  '2019-01-01', '2029-02-28', '0', '0', 'UNI', 'NO', 'ACT');
 

 INSERT INTO cat_bono_valor_nivel
 (id_bono, nivel, valor, condicion_red, verticalidad)
 VALUES ('2', '0', '30', 'RED', 'ASC') ,('2', '1', '1', 'RED', 'ASC') ,
    ('2', '2', '2', 'RED', 'ASC') , ('2', '3', '3', 'RED', 'ASC') ,
    ('2', '4', '9', 'RED', 'ASC') , ('2', '5', '15', 'RED', 'ASC') ,
     ('2', '6', '30', 'RED', 'ASC') ;

 INSERT INTO cat_bono_condicion
   (id_bono, id_rango, id_tipo_rango, condicion_rango,
   id_red, condicion1, condicion2, calificado)
 VALUES ('2', '1', '3', '1', '1', '2', '2', 'REC') ,('2', '1', '3', '1', '1', '2', '3', 'REC'),
      ('2', '1', '3', '1', '1', '2', '4', 'REC'), ('2', '1', '3', '1', '1', '2', '5', 'REC') ,
      ('2', '1', '3', '1', '1', '2', '6', 'REC') , ('2', '1', '3', '1', '1', '2', '7', 'REC') ;

-- rankings

INSERT INTO `bono`
    (`nombre`, `descripcion`, `inicio`, `fin`,
     `mes_desde_afiliacion`, `mes_desde_activacion`,
     `frecuencia`, `plan`, `estatus`)
VALUES
       ('Rankings', 'see Rankings levels', '2019-01-01', '2030-01-31',
        '0', '0', 'MES', 'NO', 'ACT');

INSERT INTO `cat_bono_valor_nivel`
    (`id_bono`, `nivel`, `valor`, `condicion_red`, `verticalidad`)
VALUES ('3', '0', '6', 'RED', 'ASC'),('3', '1', '10', 'RED', 'ASC');

INSERT INTO `cat_bono_condicion`
    (`id_bono`, `id_rango`, `id_tipo_rango`, `condicion_rango`,
     `id_red`, `condicion1`, `condicion2`, `calificado`)
VALUES ('3', '2', '2', '1', '1', '2', '2', 'REC'),
       ('3', '2', '2', '1', '1', '2', '3', 'REC'),
       ('3', '2', '2', '1', '1', '2', '4', 'REC'),
       ('3', '2', '2', '1', '1', '2', '5', 'REC'),
       ('3', '2', '2', '1', '1', '2', '6', 'REC'),
       ('3', '2', '2', '1', '1', '2', '7', 'REC');
