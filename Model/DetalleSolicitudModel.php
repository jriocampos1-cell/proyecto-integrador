<?php
require_once __DIR__ . '/../Core/BaseDatos.php';
require_once __DIR__ . '/../Entities/DetalleSolicitud.php';

class DetalleSolicitudModel extends Conectar {
    public function __construct() {
        parent::__construct();
    }

    public function findBySolicitudId($solicitudID) {
        try {
            $sql = "SELECT * FROM TDetalleSolicitud WHERE nSolicitudID = :solicitudID";
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->bindParam(':solicitudID', $solicitudID);
            $sentencia->execute();
            $resultados = $sentencia->fetchAll(PDO::FETCH_ASSOC);
            $lista = [];
            foreach ($resultados as $fila) {
                $lista[] = new DetalleSolicitud($fila['nDetalleSolicitudID'], $fila['nSolicitudID'], $fila['nInsumoID'], $fila['nCantidad']);
            }
            return $lista;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function findBySolicitudIdView($solicitudID) {
        try {
            $sql = "SELECT d.nDetalleSolicitudID, i.cNombre AS cInsumo, d.nCantidad FROM TDetalleSolicitud d JOIN TInsumos i ON d.nInsumoID = i.nInsumoID WHERE d.nSolicitudID = :solicitudID";
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->bindParam(':solicitudID', $solicitudID);
            $sentencia->execute();
            return $sentencia->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function create(DetalleSolicitud $detalle) {
        try {
            $sql = "INSERT INTO TDetalleSolicitud (nSolicitudID, nInsumoID, nCantidad) VALUES (:solicitudID, :insumoID, :cantidad)";
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->bindValue(':solicitudID', $detalle->getNSolicitudID());
            $sentencia->bindValue(':insumoID', $detalle->getNInsumoID());
            $sentencia->bindValue(':cantidad', $detalle->getNCantidad());
            $sentencia->execute();
            return $this->conexion->lastInsertId();
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    // Método simplificado para crear desde el controlador
    public function createSimple($solicitudID, $insumoID, $cantidad) {
        try {
            $sql = "INSERT INTO TDetalleSolicitud (nSolicitudID, nInsumoID, nCantidad) VALUES (:s, :i, :c)";
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->bindValue(':s', $solicitudID);
            $sentencia->bindValue(':i', $insumoID);
            $sentencia->bindValue(':c', $cantidad);
            $sentencia->execute();
            return $this->conexion->lastInsertId();
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}
