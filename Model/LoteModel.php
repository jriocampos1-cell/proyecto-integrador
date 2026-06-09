<?php
require_once "Core/BaseDatos.php";
require_once "Entities/Lote.php";

class LoteModel extends Conectar {

    public function __construct() {
        parent::__construct();
    }

   
    public function findById($id) {
        try {
            $sql = "SELECT * FROM TLotes WHERE nLoteID = :id";
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->bindParam(':id', $id);
            $sentencia->execute();
            $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);
            if ($resultado) {
                return new Lote(
                    $resultado['nLoteID'],
                    $resultado['nInsumoID'],
                    $resultado['cCodigoLote'],
                    $resultado['nCantidadActual'],
                    $resultado['dFechaIngreso'],
                    $resultado['dVencimiento']
                );
            }
            return null;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    /**
     * Retorna todos los lotes.
     */
    public function findAll() {
        try {
            $sql = "SELECT * FROM TLotes";
            $consulta = $this->conexion->prepare($sql);
            $consulta->execute();
            $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
            $lista = [];
            foreach ($resultados as $fila) {
                $lista[] = new Lote(
                    $fila['nLoteID'],
                    $fila['nInsumoID'],
                    $fila['cCodigoLote'],
                    $fila['nCantidadActual'],
                    $fila['dFechaIngreso'],
                    $fila['dVencimiento']
                );
            }
            return $lista;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function findByInsumoId($insumoID) {
        try {
            $sql = "SELECT * FROM TLotes 
                    WHERE nInsumoID = :insumoID 
                      AND nCantidadActual > 0 
                      AND dVencimiento >= CURRENT_DATE()
                    ORDER BY dVencimiento ASC";
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->bindValue(':insumoID', $insumoID, PDO::PARAM_INT);
            $sentencia->execute();
            $resultados = $sentencia->fetchAll(PDO::FETCH_ASSOC);
            $lista = [];
            foreach ($resultados as $fila) {
                $lista[] = new Lote(
                    $fila['nLoteID'],
                    $fila['nInsumoID'],
                    $fila['cCodigoLote'],
                    $fila['nCantidadActual'],
                    $fila['dFechaIngreso'],
                    $fila['dVencimiento']
                );
            }
            return $lista;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

   
    public function registrarEntradaPro($insumoID, $codigoLote, $cantidad, $vencimiento, $usuarioID, $proveedorID) {
        try {
            $sql = "CALL sp_RegistrarEntradaInsumo(:insumoID, :codigoLote, :cantidad, :vencimiento, :usuarioID, :proveedorID)";
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->bindValue(':insumoID',    $insumoID,    PDO::PARAM_INT);
            $sentencia->bindValue(':codigoLote',  $codigoLote);
            $sentencia->bindValue(':cantidad',    $cantidad);
            $sentencia->bindValue(':vencimiento', $vencimiento);
            $sentencia->bindValue(':usuarioID',   $usuarioID,   PDO::PARAM_INT);
            $sentencia->bindValue(':proveedorID', $proveedorID, PDO::PARAM_INT);
            return $sentencia->execute();
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}