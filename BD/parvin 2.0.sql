-- =============================================================================
-- SISTEMA DE GESTIÓN DE MATERIAS PRIMAS - PARVI PAN (VERSIÓN ENTERPRISE MVC)
-- SCRIPT COMPLETO DE BASE DE DATOS OPTIMIZADA CON CARACTERÍSTICAS AVANZADAS
-- =============================================================================

CREATE DATABASE IF NOT EXISTS Gestionpanaderia;
USE Gestionpanaderia;

-- Configuración de caracteres y encendido del motor de eventos automáticos
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
SET GLOBAL event_scheduler = ON;

-- =============================================================================
-- 1. CREACIÓN DE TABLAS (CON RESTRICCIONES 'CHECK' DE SEGURIDAD FÍSICA)
-- =============================================================================

CREATE TABLE `TUsuarios` (
  `nUsuarioID` INT PRIMARY KEY AUTO_INCREMENT,
  `cNombre` VARCHAR(100) NOT NULL,
  `cNombreUsuario` VARCHAR(50) UNIQUE NOT NULL,
  `cContraseñaUsuario` VARCHAR(255) NOT NULL,
  `eRol` ENUM ('gerente', 'cocinero', 'pastelero', 'bodega') NOT NULL,
  `eEstado` ENUM ('activo', 'inactivo') DEFAULT 'activo',
  `cCorreo` VARCHAR(100) NOT NULL,
  -- Validar que el correo tenga formato básico
  CONSTRAINT `CHK_CorreoValido` CHECK (`cCorreo` LIKE '%_@__%.__%')
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `TInsumos` (
  `nInsumoID` INT PRIMARY KEY AUTO_INCREMENT,
  `cNombre` VARCHAR(100) NOT NULL,
  `cCategoria` VARCHAR(50) NOT NULL,
  `eUnidadMedida` ENUM ('kg', 'g', 'litros', 'ml', 'unidades') NOT NULL,
  `nStockActual` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `nStockMinimo` DECIMAL(10,2) NOT NULL,
  -- Blindaje anti-números negativos
  CONSTRAINT `CHK_StockNoNegativo` CHECK (`nStockActual` >= 0),
  CONSTRAINT `CHK_StockMinimoNoNegativo` CHECK (`nStockMinimo` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `TProveedores` (
  `nProveedorID` INT PRIMARY KEY AUTO_INCREMENT,
  `cNombre` VARCHAR(100) NOT NULL,
  `cTelefono` VARCHAR(20) NOT NULL,
  `cCorreo` VARCHAR(100) NOT NULL,
  `eEstado` ENUM ('activo', 'inactivo') DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `TProveedorInsumo` (
  `nProveedorInsumoID` INT PRIMARY KEY AUTO_INCREMENT,
  `nProveedorID` INT NOT NULL,
  `nInsumoID` INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `TLotes` (
  `nLoteID` INT PRIMARY KEY AUTO_INCREMENT,
  `nInsumoID` INT NOT NULL,
  `cCodigoLote` VARCHAR(50) NOT NULL,
  `nCantidadActual` DECIMAL(10,2) NOT NULL,
  `dFechaIngreso` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `dVencimiento` DATE NOT NULL,
  -- Blindaje para que la cantidad del lote nunca sea menor a 0
  CONSTRAINT `CHK_CantidadLoteNoNegativa` CHECK (`nCantidadActual` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `TSolicitud` (
  `nSolicitudID` INT PRIMARY KEY AUTO_INCREMENT,
  `nUsuarioID` INT NOT NULL,
  `eEstado` ENUM ('pendiente', 'aprobada', 'rechazada') DEFAULT 'pendiente',
  `cMotivoRechazo` VARCHAR(255) DEFAULT NULL,
  `dFecha` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `TDetalleSolicitud` (
  `nDetalleSolicitudID` INT PRIMARY KEY AUTO_INCREMENT,
  `nSolicitudID` INT NOT NULL,
  `nInsumoID` INT NOT NULL,
  `nCantidad` DECIMAL(10,2) NOT NULL,
  CONSTRAINT `CHK_DetalleSolicitudCantidad` CHECK (`nCantidad` > 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `TMovimientos` (
  `nMovimientoID` INT PRIMARY KEY AUTO_INCREMENT,
  `nInsumoID` INT NOT NULL,
  `nLoteID` INT DEFAULT NULL,
  `nUsuarioID` INT NOT NULL,
  `nProveedorID` INT DEFAULT NULL,
  `nSolicitudID` INT DEFAULT NULL,
  `nCantidad` DECIMAL(10,2) NOT NULL,
  `eTipo` ENUM ('entrada', 'salida') NOT NULL,
  `eMotivoSalida` ENUM ('produccion', 'merma', 'vencimiento') DEFAULT NULL,
  `dFecha` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `TAlerta` (
  `nAlertaID` INT PRIMARY KEY AUTO_INCREMENT,
  `nInsumoID` INT NOT NULL,
  `nLoteID` INT DEFAULT NULL,
  `eTipo` ENUM ('stockMinimo', 'proximoVencimiento') NOT NULL,
  `cMensaje` VARCHAR(255) NOT NULL,
  `dFecha` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `eEstado` ENUM ('activa', 'atendida') DEFAULT 'activa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLA PRO: Auditoría Sombra Continua
CREATE TABLE `TAuditoriaStock` (
  `nAuditoriaID` INT PRIMARY KEY AUTO_INCREMENT,
  `nInsumoID` INT NOT NULL,
  `nStockAnterior` DECIMAL(10,2) NOT NULL,
  `nStockNuevo` DECIMAL(10,2) NOT NULL,
  `cUsuarioMySQL` VARCHAR(100) NOT NULL COMMENT 'Usuario del sistema que ejecutó la consulta',
  `dFechaCambio` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================================================
-- 2. RELACIONES E ÍNDICES DE RENDIMIENTO
-- =============================================================================

ALTER TABLE `TProveedorInsumo` ADD CONSTRAINT `FK_ProvInsumo_Proveedor` FOREIGN KEY (`nProveedorID`) REFERENCES `TProveedores` (`nProveedorID`) ON UPDATE CASCADE;
ALTER TABLE `TProveedorInsumo` ADD CONSTRAINT `FK_ProvInsumo_Insumo` FOREIGN KEY (`nInsumoID`) REFERENCES `TInsumos` (`nInsumoID`) ON UPDATE CASCADE;
ALTER TABLE `TLotes` ADD CONSTRAINT `FK_Lotes_Insumo` FOREIGN KEY (`nInsumoID`) REFERENCES `TInsumos` (`nInsumoID`) ON UPDATE CASCADE;
ALTER TABLE `TSolicitud` ADD CONSTRAINT `FK_Solicitud_Usuario` FOREIGN KEY (`nUsuarioID`) REFERENCES `TUsuarios` (`nUsuarioID`) ON UPDATE CASCADE;
ALTER TABLE `TDetalleSolicitud` ADD CONSTRAINT `FK_Detalle_Solicitud` FOREIGN KEY (`nSolicitudID`) REFERENCES `TSolicitud` (`nSolicitudID`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `TDetalleSolicitud` ADD CONSTRAINT `FK_Detalle_Insumo` FOREIGN KEY (`nInsumoID`) REFERENCES `TInsumos` (`nInsumoID`) ON UPDATE CASCADE;
ALTER TABLE `TMovimientos` ADD CONSTRAINT `FK_Movimientos_Insumo` FOREIGN KEY (`nInsumoID`) REFERENCES `TInsumos` (`nInsumoID`) ON UPDATE CASCADE;
ALTER TABLE `TMovimientos` ADD CONSTRAINT `FK_Movimientos_Lote` FOREIGN KEY (`nLoteID`) REFERENCES `TLotes` (`nLoteID`) ON UPDATE CASCADE;
ALTER TABLE `TMovimientos` ADD CONSTRAINT `FK_Movimientos_Usuario` FOREIGN KEY (`nUsuarioID`) REFERENCES `TUsuarios` (`nUsuarioID`) ON UPDATE CASCADE;
ALTER TABLE `TMovimientos` ADD CONSTRAINT `FK_Movimientos_Proveedor` FOREIGN KEY (`nProveedorID`) REFERENCES `TProveedores` (`nProveedorID`) ON UPDATE CASCADE;
ALTER TABLE `TMovimientos` ADD CONSTRAINT `FK_Movimientos_Solicitud` FOREIGN KEY (`nSolicitudID`) REFERENCES `TSolicitud` (`nSolicitudID`) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE `TAlerta` ADD CONSTRAINT `FK_Alerta_Insumo` FOREIGN KEY (`nInsumoID`) REFERENCES `TInsumos` (`nInsumoID`) ON UPDATE CASCADE;
ALTER TABLE `TAlerta` ADD CONSTRAINT `FK_Alerta_Lote` FOREIGN KEY (`nLoteID`) REFERENCES `TLotes` (`nLoteID`) ON DELETE SET NULL ON UPDATE CASCADE;

CREATE INDEX IDX_Lotes_Vencimiento ON `TLotes` (`dVencimiento`);
CREATE INDEX IDX_Movimientos_TipoMotivo ON `TMovimientos` (`eTipo`, `eMotivoSalida`);
CREATE INDEX IDX_Insumos_Categoria ON `TInsumos` (`cCategoria`);

SET FOREIGN_KEY_CHECKS = 1;

-- =============================================================================
-- 3. FUNCIONES ALMACENADAS PRO Y VISTAS PARA EL DASHBOARD
-- =============================================================================

DELIMITER //

-- Función Pro: Calcular días restantes con exactitud
CREATE FUNCTION fn_DiasRestantesVencimiento(p_dVencimiento DATE) 
RETURNS INT
DETERMINISTIC
BEGIN
    RETURN DATEDIFF(p_dVencimiento, CURRENT_DATE);
END//

DELIMITER ;

-- Vista A: Semáforo de Stock en Tiempo Real
CREATE OR REPLACE VIEW v_SemaforoStock AS
SELECT 
    `nInsumoID`, `cNombre` AS `cInsumo`, `cCategoria`, `nStockActual`, `nStockMinimo`, `eUnidadMedida`,
    CASE 
        WHEN `nStockActual` = 0 THEN 'Rojo (Sin Existencias)'
        WHEN `nStockActual` <= `nStockMinimo` THEN 'Rojo (Crítico)'
        WHEN `nStockActual` <= (`nStockMinimo` * 1.30) THEN 'Amarillo (Próximo a agotar)'
        ELSE 'Verde (Óptimo)'
    END AS `eEstadoSemaforo`
FROM `TInsumos`;

-- Vista B: Listado de Lotes FEFO utilizando la función PRO
CREATE OR REPLACE VIEW v_InventarioFEFO AS
SELECT 
    l.`nLoteID`, i.`cNombre` AS `cInsumo`, l.`cCodigoLote`, l.`nCantidadActual`, i.`eUnidadMedida`, l.`dVencimiento`,
    fn_DiasRestantesVencimiento(l.`dVencimiento`) AS `nDiasParaVencer`
FROM `TLotes` l
JOIN `TInsumos` i ON l.`nInsumoID` = i.`nInsumoID`
WHERE l.`nCantidadActual` > 0
ORDER BY l.`dVencimiento` ASC;

-- Vista C: Comparativa Analítica de Mermas
CREATE OR REPLACE VIEW v_AnalisisMermas AS
SELECT 
    i.`nInsumoID`, i.`cNombre` AS `cInsumo`,
    IFNULL(SUM(CASE WHEN m.`eTipo` = 'entrada' THEN m.`nCantidad` ELSE 0 END), 0) AS `nTotalComprado`,
    IFNULL(SUM(CASE WHEN m.`eTipo` = 'salida' AND m.`eMotivoSalida` = 'produccion' THEN m.`nCantidad` ELSE 0 END), 0) AS `nTotalUtilizado`,
    IFNULL(SUM(CASE WHEN m.`eTipo` = 'salida' AND m.`eMotivoSalida` = 'merma' THEN m.`nCantidad` ELSE 0 END), 0) AS `nTotalMermas`,
    IFNULL(SUM(CASE WHEN m.`eTipo` = 'salida' AND m.`eMotivoSalida` = 'vencimiento' THEN m.`nCantidad` ELSE 0 END), 0) AS `nTotalVencido`
FROM `TInsumos` i
LEFT JOIN `TMovimientos` m ON i.`nInsumoID` = m.`nInsumoID`
GROUP BY i.`nInsumoID`, i.`cNombre`;

-- =============================================================================
-- 4. EVENTOS Y DISPARADORES AUTOMÁTICOS (TRIGGERS)
-- =============================================================================

DELIMITER //

-- Evento PRO: Revisión automática nocturna de vencimientos
CREATE EVENT evt_GenerarAlertasVencimientoDiario
ON SCHEDULE EVERY 1 DAY
STARTS (TIMESTAMP(CURRENT_DATE) + INTERVAL 1 DAY)
DO
BEGIN
    INSERT INTO `TAlerta` (`nInsumoID`, `nLoteID`, `eTipo`, `cMensaje`, `eEstado`)
    SELECT l.`nInsumoID`, l.`nLoteID`, 'proximoVencimiento', 
           CONCAT('ATENCIÓN: El lote ', l.`cCodigoLote`, ' vencerá el ', l.`dVencimiento`), 'activa'
    FROM `TLotes` l
    WHERE l.`nCantidadActual` > 0 
      AND l.`dVencimiento` BETWEEN CURRENT_DATE AND DATE_ADD(CURRENT_DATE, INTERVAL 7 DAY)
      AND NOT EXISTS (
          SELECT 1 FROM `TAlerta` a 
          WHERE a.`nLoteID` = l.`nLoteID` AND a.`eTipo` = 'proximoVencimiento' AND a.`eEstado` = 'activa'
      );
END//

-- Trigger PRO: Auditoría Silenciosa de Inventario
CREATE TRIGGER trg_AuditarCambiosInsumo
AFTER UPDATE ON `TInsumos`
FOR EACH ROW
BEGIN
    IF NEW.`nStockActual` <> OLD.`nStockActual` THEN
        INSERT INTO `TAuditoriaStock` (`nInsumoID`, `nStockAnterior`, `nStockNuevo`, `cUsuarioMySQL`)
        VALUES (NEW.`nInsumoID`, OLD.`nStockActual`, NEW.`nStockActual`, CURRENT_USER());
    END IF;
END//

-- Trigger: Kardex Automatizado 
CREATE TRIGGER trg_SincronizarStockPorMovimiento
AFTER INSERT ON `TMovimientos`
FOR EACH ROW
BEGIN
    IF NEW.`eTipo` = 'entrada' THEN
        UPDATE `TInsumos` SET `nStockActual` = `nStockActual` + NEW.`nCantidad` WHERE `nInsumoID` = NEW.`nInsumoID`;
        IF NEW.`nLoteID` IS NOT NULL THEN
            UPDATE `TLotes` SET `nCantidadActual` = `nCantidadActual` + NEW.`nCantidad` WHERE `nLoteID` = NEW.`nLoteID`;
        END IF;
    ELSEIF NEW.`eTipo` = 'salida' THEN
        UPDATE `TInsumos` SET `nStockActual` = `nStockActual` - NEW.`nCantidad` WHERE `nInsumoID` = NEW.`nInsumoID`;
        IF NEW.`nLoteID` IS NOT NULL THEN
            UPDATE `TLotes` SET `nCantidadActual` = `nCantidadActual` - NEW.`nCantidad` WHERE `nLoteID` = NEW.`nLoteID`;
        END IF;
    END IF;
END//

-- Trigger: Monitor de Stock Mínimo
CREATE TRIGGER trg_ComprobarUmbralStockMinimo
AFTER UPDATE ON `TInsumos`
FOR EACH ROW
BEGIN
    IF NEW.`nStockActual` <= NEW.`nStockMinimo` AND NOT EXISTS (
        SELECT 1 FROM `TAlerta` WHERE `nInsumoID` = NEW.`nInsumoID` AND `eTipo` = 'stockMinimo' AND `eEstado` = 'activa'
    ) THEN
        INSERT INTO `TAlerta` (`nInsumoID`, `eTipo`, `cMensaje`, `eEstado`)
        VALUES (NEW.`nInsumoID`, 'stockMinimo', CONCAT('Alerta: Insumo "', NEW.`cNombre`, '" bajo el nivel crítico.'), 'activa');
    END IF;
END//

DELIMITER ;

-- =============================================================================
-- 5. PROCEDIMIENTOS ALMACENADOS PRO (CON 'ROLLBACK' DE ERRORES)
-- =============================================================================

DELIMITER //

-- Insertar Usuario Seguro
CREATE PROCEDURE sp_InsertarUsuario(
    IN p_cNombre VARCHAR(100), IN p_cNombreUsuario VARCHAR(50), IN p_cContraseñaUsuario VARCHAR(255),
    IN p_eRol ENUM('gerente', 'cocinero', 'pastelero', 'bodega'), IN p_cCorreo VARCHAR(100)
)
BEGIN
    IF EXISTS (SELECT 1 FROM `TUsuarios` WHERE `cNombreUsuario` = p_cNombreUsuario) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Error: Nombre de usuario ya existe.';
    ELSE
        INSERT INTO `TUsuarios` (`cNombre`, `cNombreUsuario`, `cContraseñaUsuario`, `eRol`, `eEstado`, `cCorreo`)
        VALUES (p_cNombre, p_cNombreUsuario, p_cContraseñaUsuario, p_eRol, 'activo', p_cCorreo);
    END IF;
END//

-- Ingreso Seguro a Bodega (Protección ACID transaccional)
CREATE PROCEDURE sp_RegistrarEntradaInsumo(
    IN p_nInsumoID INT, IN p_cCodigoLote VARCHAR(50), IN p_nCantidad DECIMAL(10,2),
    IN p_dVencimiento DATE, IN p_nUsuarioID INT, IN p_nProveedorID INT
)
BEGIN
    DECLARE v_nNuevoLoteID INT;
    
    -- Handler PRO: Si algo falla (ej: constraint físico), deshacer todo (ROLLBACK) y avisar a PHP
    DECLARE EXIT HANDLER FOR SQLEXCEPTION 
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;
        INSERT INTO `TLotes` (`nInsumoID`, `cCodigoLote`, `nCantidadActual`, `dVencimiento`)
        VALUES (p_nInsumoID, p_cCodigoLote, p_nCantidad, p_dVencimiento);
        SET v_nNuevoLoteID = LAST_INSERT_ID();
        
        INSERT INTO `TMovimientos` (`nInsumoID`, `nLoteID`, `nUsuarioID`, `nProveedorID`, `nCantidad`, `eTipo`, `dFecha`)
        VALUES (p_nInsumoID, v_nNuevoLoteID, p_nUsuarioID, p_nProveedorID, p_nCantidad, 'entrada', CURRENT_TIMESTAMP());
    COMMIT;
END//

-- Aprobación FEFO Segura (Protección ACID transaccional)
CREATE PROCEDURE sp_ProcesarAprobacionSolicitudFEFO(
    IN p_nSolicitudID INT, IN p_nUsuarioGerenteID INT
)
BEGIN
    DECLARE v_bDetalleFinalizado INT DEFAULT 0;
    DECLARE v_nInsumoID INT;
    DECLARE v_nCantidadSolicitada DECIMAL(10,2);
    
    -- Handler PRO: Blindaje de errores
    DECLARE EXIT HANDLER FOR SQLEXCEPTION 
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;
    
    DECLARE cur_Detalle CURSOR FOR SELECT `nInsumoID`, `nCantidad` FROM `TDetalleSolicitud` WHERE `nSolicitudID` = p_nSolicitudID;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_bDetalleFinalizado = 1;
    
    START TRANSACTION;
        UPDATE `TSolicitud` SET `eEstado` = 'aprobada' WHERE `nSolicitudID` = p_nSolicitudID;
        
        OPEN cur_Detalle;
        lbl_BucleInsumos: LOOP
            FETCH cur_Detalle INTO v_nInsumoID, v_nCantidadSolicitada;
            IF v_bDetalleFinalizado = 1 THEN LEAVE lbl_BucleInsumos; END IF;
            
            BEGIN
                DECLARE v_bLotesFinalizados INT DEFAULT 0;
                DECLARE v_nLoteID INT;
                DECLARE v_nCantidadLote DECIMAL(10,2);
                DECLARE v_nConsumir DECIMAL(10,2);
                
                DECLARE cur_LotesFEFO CURSOR FOR 
                    SELECT `nLoteID`, `nCantidadActual` FROM `TLotes` 
                    WHERE `nInsumoID` = v_nInsumoID AND `nCantidadActual` > 0 AND `dVencimiento` >= CURRENT_DATE()
                    ORDER BY `dVencimiento` ASC;
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_bLotesFinalizados = 1;
                
                OPEN cur_LotesFEFO;
                lbl_Lotes: LOOP
                    IF v_nCantidadSolicitada <= 0 THEN LEAVE lbl_Lotes; END IF;
                    FETCH cur_LotesFEFO INTO v_nLoteID, v_nCantidadLote;
                    IF v_bLotesFinalizados = 1 THEN LEAVE lbl_Lotes; END IF;
                    
                    IF v_nCantidadLote >= v_nCantidadSolicitada THEN
                        SET v_nConsumir = v_nCantidadSolicitada;
                        SET v_nCantidadSolicitada = 0;
                    ELSE
                        SET v_nConsumir = v_nCantidadLote;
                        SET v_nCantidadSolicitada = v_nCantidadSolicitada - v_nCantidadLote;
                    END IF;
                    
                    INSERT INTO `TMovimientos` (`nInsumoID`, `nLoteID`, `nUsuarioID`, `nSolicitudID`, `nCantidad`, `eTipo`, `eMotivoSalida`)
                    VALUES (v_nInsumoID, v_nLoteID, p_nUsuarioGerenteID, p_nSolicitudID, v_nConsumir, 'salida', 'produccion');
                END LOOP lbl_Lotes;
                CLOSE cur_LotesFEFO;
                
                IF v_nCantidadSolicitada > 0 THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Error: Stock insuficiente para FEFO en lotes vigentes.';
                END IF;
            END;
        END LOOP lbl_BucleInsumos;
        CLOSE cur_Detalle;
    COMMIT;
END//

DELIMITER ;

-- =============================================================================
-- 6. DATOS SEMILLA PARA PRUEBAS
-- =============================================================================

CALL sp_InsertarUsuario('Gerente General', 'gerente_parvi', '$2y$10$7rXUBl...', 'gerente', 'gerencia@parvipan.com');

INSERT INTO `TInsumos` (`cNombre`, `cCategoria`, `eUnidadMedida`, `nStockActual`, `nStockMinimo`) VALUES 
('Harina de Trigo Haz de Oro', 'Harinas', 'kg', 0.00, 50.00),
('Levadura Seca Tradi-Pan', 'Levaduras', 'g', 0.00, 1000.00),
('Azúcar Manuelita Alta Pureza', 'Endulzantes', 'kg', 0.00, 30.00);

INSERT INTO `TProveedores` (`cNombre`, `cTelefono`, `cCorreo`, `eEstado`) VALUES 
('Distribuciones El Molino', '3157654321', 'ventas@elmolino.com', 'activo');