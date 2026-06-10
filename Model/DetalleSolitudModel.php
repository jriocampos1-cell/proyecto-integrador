<?php
require_once "Core/BaseDatos.php";
require_once "Entities/DetalleSolicitud.php";

class DetalleSolicitudModel extends Conectar{
    public function __construct(){
        parent::__construct();
    }

    public function findBySolicitudId($solicitudID){
        try{
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
        }catch(Exception $e){
            die($e->getMessage());
        }
    }    

    public function create(DetalleSolicitud $detalle){
        try{
            $sql = "INSERT INTO TDetalleSolicitud (nSolicitudID, nInsumoID, nCantidad) VALUES (:solicitudID, :insumoID, :cantidad)";
            $sentencia = $this->conexion->prepare($sql);    
            $sentencia->bindValue(':solicitudID', $detalle->getNSolicitudID());       
            $sentencia->bindValue(':insumoID', $detalle->getNInsumoID());  
            $sentencia->bindValue(':cantidad', $detalle->getNCantidad());  
            $sentencia->execute();
            return $this->conexion->lastInsertId();
        }catch(Exception $e){
            die($e->getMessage());
        }
    } 
}