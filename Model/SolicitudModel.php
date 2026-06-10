<?php
require_once __DIR__ . '/../Core/BaseDatos.php';
require_once __DIR__ . '/../Entities/Solicitud.php';

class SolicitudModel extends Conectar{
    public function __construct(){
        parent::__construct();
    }

    public function findById($id){
        try{
            $sql = "SELECT * FROM TSolicitud WHERE nSolicitudID = :id";                
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->bindParam(':id', $id);                      
            $sentencia->execute();          
            $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);   
            if($resultado){ 
                return new Solicitud($resultado['nSolicitudID'], $resultado['nUsuarioID'], $resultado['eEstado'], $resultado['cMotivoRechazo'], $resultado['dFecha']);
            }
            return null;
        }catch(Exception $e){
            die($e->getMessage());
        }
    }    

    public function findAll(){
        try{           
            $sql = "SELECT * FROM TSolicitud"; 
            $consulta = $this->conexion->prepare($sql);
            $consulta->execute();   
            $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);                           
            $lista = [];
            foreach ($resultados as $fila) {
                $lista[] = new Solicitud($fila['nSolicitudID'], $fila['nUsuarioID'], $fila['eEstado'], $fila['cMotivoRechazo'], $fila['dFecha']);
            }
            return $lista;
        }catch(Exception $e){
            die($e->getMessage());
        }
    }


    public function findAllView(){
        try{
            $sql = "SELECT s.nSolicitudID, u.cNombre AS cUsuario, s.eEstado, s.cMotivoRechazo, s.dFecha FROM TSolicitud s LEFT JOIN TUsuarios u ON s.nUsuarioID = u.nUsuarioID ORDER BY s.dFecha DESC";
            $consulta = $this->conexion->prepare($sql);
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            die($e->getMessage());
        }
    }

    public function findByIdView($id){
        try{
            $sql = "SELECT s.nSolicitudID, u.cNombre AS cUsuario, s.eEstado, s.cMotivoRechazo, s.dFecha FROM TSolicitud s LEFT JOIN TUsuarios u ON s.nUsuarioID = u.nUsuarioID WHERE s.nSolicitudID = :id";
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->bindParam(':id', $id);
            $sentencia->execute();
            return $sentencia->fetch(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            die($e->getMessage());
        }
    }

        public function create(Solicitud $solicitud){
        try{
            $sql = "INSERT INTO TSolicitud (nUsuarioID, eEstado) VALUES (:usuarioID, :estado)";
            $sentencia = $this->conexion->prepare($sql);    
            $sentencia->bindValue(':usuarioID', $solicitud->getNUsuarioID());       
            $sentencia->bindValue(':estado', $solicitud->getEEstado());  
            $sentencia->execute();
            return $this->conexion->lastInsertId();
        }catch(Exception $e){
            die($e->getMessage());
        }
    } 

    
    public function aprobarFEFO($solicitudID, $usuarioGerenteID){
        try{
            $sql = "CALL sp_ProcesarAprobacionSolicitudFEFO(:solicitudID, :gerenteID)";
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->bindValue(':solicitudID', $solicitudID);
            $sentencia->bindValue(':gerenteID', $usuarioGerenteID);
            return $sentencia->execute();
        }catch(Exception $e){
            die($e->getMessage());
        }
    }
}