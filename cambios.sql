truncate cat_rango;
truncate cross_rango_tipos;
ALTER TABLE cat_rango
  MODIFY id_rango int(11) NOT NULL auto_increment;
INSERT INTO `cat_rango` (`nombre`, `descripcion`, `condicion_red_afilacion`, `estatus`)
VALUES ('compras', 'compras de items', 'EQU', 'ACT'),
       ('ventas', 'ventas de items', 'EQU', 'ACT');

INSERT INTO `cross_rango_tipos` (`id_rango`, `id_tipo_rango`, `valor`, `condicion_red`, `nivel_red`)
VALUES (1, 3, 1, 'RED', 0),
       (2, 2, 1, 'RED', 0);

truncate bono;
truncate cat_bono_condicion;
truncate cat_bono_valor_nivel;

ALTER TABLE cat_bono_condicion
  MODIFY id int(11) NOT NULL auto_increment;
ALTER TABLE cat_bono_valor_nivel
  MODIFY id int(11) NOT NULL auto_increment;

INSERT INTO `bono`
    (`nombre`, `descripcion`, `inicio`, `fin`,
     `mes_desde_afiliacion`, `mes_desde_activacion`,
     `frecuencia`, `plan`, `estatus`)
VALUES
       ('bitcoin', 'predicción del bitcoin',
        '2019-01-01', '2120-01-31', '0', '0',
        'DIA', 'NO', 'ACT');

INSERT INTO `cat_bono_valor_nivel`
    (`id_bono`, `nivel`, `valor`, `condicion_red`, `verticalidad`)
VALUES ('1', '0', '21', 'RED', 'ASC'),
       ('1', '1', '21', 'RED', 'ASC'),
       ('1', '2', '00', 'RED', 'ASC');

INSERT INTO `cat_bono_condicion`
    (`id_bono`, `id_rango`, `id_tipo_rango`, `condicion_rango`,
     `id_red`, `condicion1`, `condicion2`, `calificado`)
VALUES ('1', '1', '3', '1', '1', '2', 0, 'REC');
