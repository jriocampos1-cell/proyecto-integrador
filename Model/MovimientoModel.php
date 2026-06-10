<?php
require_once __DIR__ . '/../Core/BaseDatos.php';
require_once __DIR__ . '/../Entities/Movimiento.php';

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


    public function findAllView() {
        try {
            $sql = "SELECT m.nMovimientoID, i.cNombre AS cInsumo, m.eTipo, m.eMotivoSalida, m.nCantidad, u.cNombre AS cUsuario, p.cNombre AS cProveedor, s.eEstado AS eSolicitud, m.dFecha FROM TMovimientos m LEFT JOIN TInsumos i ON m.nInsumoID = i.nInsumoID LEFT JOIN TUsuarios u ON m.nUsuarioID = u.nUsuarioID LEFT JOIN TProveedores p ON m.nProveedorID = p.nProveedorID LEFT JOIN TSolicitud s ON m.nSolicitudID = s.nSolicitudID ORDER BY m.dFecha DESC";
            $consulta = $this->conexion->prepare($sql);
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function findByIdView($id) {
        try {
            $sql = "SELECT m.nMovimientoID, i.cNombre AS cInsumo, m.eTipo, m.eMotivoSalida, m.nCantidad, u.cNombre AS cUsuario, p.cNombre AS cProveedor, s.eEstado AS eSolicitud, m.dFecha FROM TMovimientos m LEFT JOIN TInsumos i ON m.nInsumoID = i.nInsumoID LEFT JOIN TUsuarios u ON m.nUsuarioID = u.nUsuarioID LEFT JOIN TProveedores p ON m.nProveedorID = p.nProveedorID LEFT JOIN TSolicitud s ON m.nSolicitudID = s.nSolicitudID WHERE m.nMovimientoID = :id";
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->bindParam(':id', $id);
            $sentencia->execute();
            return $sentencia->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
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