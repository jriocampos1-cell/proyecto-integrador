<?php
require_once __DIR__ . '/../Core/BaseDatos.php';
require_once __DIR__ . '/../Entities/Insumo.php';

class InsumoModel extends Conectar {

    public function __construct() {
        parent::__construct();
    }

   
    public function findById($id) {
        try {
            $sql = "SELECT * FROM TInsumos WHERE nInsumoID = :id";
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->bindParam(':id', $id);
            $sentencia->execute();
            $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);
            if ($resultado) {
                return new Insumo(
                    $resultado['nInsumoID'],
                    $resultado['cNombre'],
                    $resultado['cCategoria'],
                    $resultado['eUnidadMedida'],
                    $resultado['nStockActual'],
                    $resultado['nStockMinimo']
                );
            }
            return null;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function findAll() {
        try {
            $sql = "SELECT * FROM TInsumos";
            $consulta = $this->conexion->prepare($sql);
            $consulta->execute();
            $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
            $lista = [];
            foreach ($resultados as $fila) {
                $lista[] = new Insumo(
                    $fila['nInsumoID'],
                    $fila['cNombre'],
                    $fila['cCategoria'],
                    $fila['eUnidadMedida'],
                    $fila['nStockActual'],
                    $fila['nStockMinimo']
                );
            }
            return $lista;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

   

    public function findAllView() {
        try {
            $sql = "SELECT nInsumoID, cNombre, cCategoria, eUnidadMedida, nStockActual, nStockMinimo FROM TInsumos ORDER BY cCategoria, cNombre";
            $consulta = $this->conexion->prepare($sql);
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function findByIdView($id) {
        try {
            $sql = "SELECT nInsumoID, cNombre, cCategoria, eUnidadMedida, nStockActual, nStockMinimo FROM TInsumos WHERE nInsumoID = :id";
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->bindParam(':id', $id);
            $sentencia->execute();
            return $sentencia->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

        public function create(Insumo $insumo) {
        try {
            $sql = "INSERT INTO TInsumos (cNombre, cCategoria, eUnidadMedida, nStockMinimo) 
                    VALUES (:nombre, :categoria, :unidad, :stockMin)";
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->bindValue(':nombre',    $insumo->getCNombre());
            $sentencia->bindValue(':categoria', $insumo->getCCategoria());
            $sentencia->bindValue(':unidad',    $insumo->getEUnidadMedida());
            $sentencia->bindValue(':stockMin',  $insumo->getNStockMinimo());
            $sentencia->execute();
            return $this->conexion->lastInsertId();
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function update(Insumo $insumo) {
        try {
            $sql = "UPDATE TInsumos 
                    SET cNombre = :nombre, cCategoria = :categoria, 
                        eUnidadMedida = :unidad, nStockMinimo = :stockMin 
                    WHERE nInsumoID = :id";
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->bindValue(':nombre',    $insumo->getCNombre());
            $sentencia->bindValue(':categoria', $insumo->getCCategoria());
            $sentencia->bindValue(':unidad',    $insumo->getEUnidadMedida());
            $sentencia->bindValue(':stockMin',  $insumo->getNStockMinimo());
            $sentencia->bindValue(':id',        $insumo->getNInsumoID());
            $sentencia->execute();
            return ($sentencia->rowCount() > 0);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    
    public function SemaforoStock() {
        try {
            $sql = "SELECT * FROM v_SemaforoStock";
            $consulta = $this->conexion->prepare($sql);
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    
    public function InventarioFEFO() {
        try {
            $sql = "SELECT * FROM v_InventarioFEFO";
            $consulta = $this->conexion->prepare($sql);
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    /**
     * Vista: Análisis de mermas registradas.
     */
    public function AnalisisMermas() {
        try {
            $sql = "SELECT * FROM v_AnalisisMermas";
            $consulta = $this->conexion->prepare($sql);
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}
