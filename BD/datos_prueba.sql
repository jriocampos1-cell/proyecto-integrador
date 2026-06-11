-- =============================================================================
-- DATOS DE PRUEBA - Ejecutar DESPUÉS de parvin 2.0.sql
-- Abre phpMyAdmin → selecciona Gestionpanaderia → Importar → este archivo
-- =============================================================================

USE Gestionpanaderia;

-- Limpiar TODO para empezar limpio
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE TDetalleSolicitud;
TRUNCATE TABLE TSolicitud;
TRUNCATE TABLE TAlerta;
TRUNCATE TABLE TMovimientos;
TRUNCATE TABLE TLotes;
TRUNCATE TABLE TProveedorInsumo;
TRUNCATE TABLE TInsumos;
TRUNCATE TABLE TProveedores;
TRUNCATE TABLE TUsuarios;
SET FOREIGN_KEY_CHECKS = 1;

-- =============================================================================
-- USUARIOS (contraseña = cédula, texto plano sin hashear)
-- =============================================================================

INSERT INTO TUsuarios (cNombre, cNombreUsuario, cContraseñaUsuario, eRol, eEstado, cCorreo) VALUES
('Laura', 'gerente', '123456', 'gerente', 'activo', 'gerencia@parvipan.com'),
('Carlos', 'bodega', '234567', 'bodega', 'activo', 'bodega@parvipan.com'),
('Pedro', 'cocinero', '345678', 'cocinero', 'activo', 'cocina@parvipan.com'),
('María', 'pastelero', '456789', 'pastelero', 'activo', 'pasteleria@parvipan.com');

-- =============================================================================
-- INSUMOS
-- =============================================================================

INSERT INTO TInsumos (cNombre, cCategoria, eUnidadMedida, nStockActual, nStockMinimo) VALUES
('Harina de Trigo', 'Harinas', 'kg', 25.00, 50.00),
('Levadura Seca', 'Levaduras', 'g', 800.00, 1000.00),
('Azúcar', 'Endulzantes', 'kg', 40.00, 30.00),
('Mantequilla', 'Lácteos', 'kg', 15.00, 5.00),
('Huevos', 'Huevos', 'unidades', 200.00, 50.00),
('Vainilla', 'Saborizantes', 'ml', 500.00, 100.00),
('Chocolate', 'Chocolates', 'kg', 10.00, 3.00),
('Fresas', 'Frutas', 'kg', 8.00, 5.00);

-- =============================================================================
-- PROVEEDORES
-- =============================================================================

INSERT INTO TProveedores (cNombre, cTelefono, cCorreo, eEstado) VALUES
('Distribuciones El Molino', '3157654321', 'ventas@elmolino.com', 'activo'),
('Avícola San Fernando', '3101234567', 'ventas@sanfernando.com', 'activo');

-- =============================================================================
-- LOGIN: Nombre + Cédula
-- Laura / 123456 = Gerente
-- Carlos / 234567 = Bodega
-- Pedro / 345678 = Cocinero
-- María / 456789 = Pastelero
-- =============================================================================