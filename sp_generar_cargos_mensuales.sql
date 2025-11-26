-- Stored Procedure: sp_generar_cargos_mensuales

DELIMITER $$

CREATE PROCEDURE sp_generar_cargos_mensuales(IN fecha_ejecucion DATE)
BEGIN
    DECLARE v_dia_limite INT;
    DECLARE v_tipo_penalizacion VARCHAR(20);
    DECLARE v_valor_penalizacion DECIMAL(10,2);
    DECLARE v_concepto_penalizacion INT DEFAULT 4; -- Asumiendo que el concepto de penalización tiene ID 4

    -- Obtener configuración financiera
    SELECT dia_limite_pago, tipo_penalizacion, valor_penalizacion
    INTO v_dia_limite, v_tipo_penalizacion, v_valor_penalizacion
    FROM configuracion_financiera
    LIMIT 1;

    -- 1. GENERAR CARGOS MENSUALES PARA ALUMNOS INSCRITOS
    INSERT INTO cargos (id_alumno, id_grupo, id_concepto, id_periodo, mes, anio, monto, saldo_pendiente, fecha_limite, estatus)
    SELECT 
        a.id_alumno,
        a.id_grupo,
        2 AS id_concepto, -- Colegiatura
        g.id_periodo,
        MONTH(fecha_ejecucion) AS mes,
        YEAR(fecha_ejecucion) AS anio,
        p.monto_colegiatura * (1 - (a.porcentaje_beca / 100)) AS monto,
        p.monto_colegiatura * (1 - (a.porcentaje_beca / 100)) AS saldo_pendiente,
        DATE_FORMAT(fecha_ejecucion, CONCAT('%Y-%m-', LPAD(v_dia_limite, 2, '0'))) AS fecha_limite,
        'PENDIENTE' AS estatus
    FROM alumnos a
    INNER JOIN grupos g ON a.id_grupo = g.id_grupo
    INNER JOIN programas p ON a.id_programa = p.id_programa
    INNER JOIN periodos per ON g.id_periodo = per.id_periodo
    WHERE a.estatus = 'INSCRITO'
      AND fecha_ejecucion BETWEEN per.fecha_inicio AND per.fecha_fin
      AND NOT EXISTS (
          SELECT 1 FROM cargos c 
          WHERE c.id_alumno = a.id_alumno 
            AND c.mes = MONTH(fecha_ejecucion) 
            AND c.anio = YEAR(fecha_ejecucion)
            AND c.id_concepto = 2
      );

    -- 2. GENERAR PENALIZACIONES PARA CARGOS VENCIDOS
    INSERT INTO cargos (id_alumno, id_grupo, id_concepto, id_periodo, mes, anio, monto, saldo_pendiente, fecha_limite, estatus)
    SELECT 
        c.id_alumno,
        c.id_grupo,
        v_concepto_penalizacion AS id_concepto,
        c.id_periodo,
        MONTH(fecha_ejecucion) AS mes,
        YEAR(fecha_ejecucion) AS anio,
        CASE 
            WHEN v_tipo_penalizacion = 'MONTO' THEN v_valor_penalizacion
            WHEN v_tipo_penalizacion = 'PORCENTAJE' THEN c.saldo_pendiente * (v_valor_penalizacion / 100)
            ELSE 0
        END AS monto,
        CASE 
            WHEN v_tipo_penalizacion = 'MONTO' THEN v_valor_penalizacion
            WHEN v_tipo_penalizacion = 'PORCENTAJE' THEN c.saldo_pendiente * (v_valor_penalizacion / 100)
            ELSE 0
        END AS saldo_pendiente,
        DATE_ADD(fecha_ejecucion, INTERVAL 30 DAY) AS fecha_limite,
        'PENALIZACION' AS estatus
    FROM cargos c
    INNER JOIN alumnos a ON c.id_alumno = a.id_alumno
    WHERE c.estatus IN ('PENDIENTE', 'PARCIAL')
      AND c.fecha_limite < fecha_ejecucion
      AND a.estatus = 'INSCRITO'
      AND NOT EXISTS (
          SELECT 1 FROM cargos c2
          WHERE c2.id_alumno = c.id_alumno
            AND c2.id_concepto = v_concepto_penalizacion
            AND c2.mes = MONTH(fecha_ejecucion)
            AND c2.anio = YEAR(fecha_ejecucion)
      );

    -- 3. ACTUALIZAR ESTATUS DE CARGOS VENCIDOS
    UPDATE cargos
    SET estatus = 'VENCIDO'
    WHERE estatus IN ('PENDIENTE', 'PARCIAL')
      AND fecha_limite < fecha_ejecucion;

END$$

DELIMITER ;
