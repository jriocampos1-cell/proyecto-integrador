<?php
require_once "Core/BaseDatos.php";
require_once "Entities/Movimiento.php";

class MovimientoModel extends Conectar{
    public function __construct(){
        parent::__construct();
    }

    public function findAll(){
        try{           
            $sql = "SELECT * FROM TMovimientos ORDER BY dFecha DESC"; 
            $consulta = $this->conexion->prepare($sql);
            $consulta->execute();   
            $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);                           
            $lista = [];
            foreach ($resultados as $fila) {
                $lista[] = new Movimiento($fila['nMovimientoID'], $fila['nInsumoID'], $fila['nLoteID'], $fila['nUsuarioID'], $fila['nProveedorID'], $fila['nSolicitudID'], $fila['nCantidad'], $fila['eTipo'], $fila['eMotivoSalida'], $fila['dFecha']);
            }
            return $lista;
        }catch(Exception $e){
            die($e->getMessage());
        }
    }

    public function create(Movimiento $movimiento){
        try{
            $sql = "INSERT INTO TMovimientos (nInsumoID, nLoteID, nUsuarioID, nProveedorID, nSolicitudID, nCantidad, eTipo, eMotivoSalida) 
                    VALUES (:insumoID, :loteID, :usuarioID, :proveedorID, :solicitudID, :cantidad, :tipo, :motivo)";
            $sentencia = $this->conexion->prepare($sql);    
            $sentencia->bindValue(':insumoID', $movimiento->getNInsumoID());       
            $sentencia->bindValue(':loteID', $movimiento->getNLoteID());  
            $sentencia->bindValue(':usuarioID', $movimiento->getNUsuarioID());  
            $sentencia->bindValue(':proveedorID', $movimiento->getNProveedorID());  
            $sentencia->bindValue(':solicitudID', $movimiento->getNSolicitudID());  
            $sentencia->bindValue(':cantidad', $movimiento->getNCantidad());  
            $sentencia->bindValue(':tipo', $movimiento->getETipo());  
            $sentencia->bindValue(':motivo', $movimiento->getEMotivoSalida());  
            $sentencia->execute();
            return $this->conexion->lastInsertId();
        }catch(Exception $e){
            die($e->getMessage());
        }
    } 
}