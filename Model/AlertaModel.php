<?php
require_once "Core/BaseDatos.php";
require_once "Entities/Alerta.php";

class AlertaModel extends Conectar {

    public function __construct() {
        parent::__construct();
    }

   
    public function findActivas() {
        try {
            $sql = "SELECT * FROM TAlerta WHERE eEstado = 'activa' ORDER BY dFecha DESC";
            $consulta = $this->conexion->prepare($sql);
            $consulta->execute();
            $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
            $lista = [];
            foreach ($resultados as $fila) {
                $lista[] = new Alerta(
                    $fila['nAlertaID'],
                    $fila['nInsumoID'],
                    $fila['nLoteID'],
                    $fila['eTipo'],
                    $fila['cMensaje'],
                    $fila['dFecha'],
                    $fila['eEstado']
                );
            }
            return $lista;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

   
    public function findAll() {
        try {
            $sql = "SELECT * FROM TAlerta ORDER BY dFecha DESC";
            $consulta = $this->conexion->prepare($sql);
            $consulta->execute();
            $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
            $lista = [];
            foreach ($resultados as $fila) {
                $lista[] = new Alerta(
                    $fila['nAlertaID'],
                    $fila['nInsumoID'],
                    $fila['nLoteID'],
                    $fila['eTipo'],
                    $fila['cMensaje'],
                    $fila['dFecha'],
                    $fila['eEstado']
                );
            }
            return $lista;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    
    public function atenderAlerta($id) {
        try {
            $sql = "UPDATE TAlerta SET eEstado = 'atendida' WHERE nAlertaID = :id";
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->bindParam(':id', $id);
            $sentencia->execute();
            return ($sentencia->rowCount() > 0);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

   
    public function create(Alerta $alerta) {
        try {
            $sql = "INSERT INTO TAlerta (nInsumoID, nLoteID, eTipo, cMensaje, eEstado) 
                    VALUES (:insumoID, :loteID, :tipo, :mensaje, :estado)";
            $sentencia = $this->conexion->prepare($sql);
            
            $sentencia->bindValue(':insumoID', $alerta->getNInsumoID());
            $sentencia->bindValue(':loteID',   $alerta->getNLoteID());
            $sentencia->bindValue(':tipo',     $alerta->getETipo());
            $sentencia->bindValue(':mensaje',  $alerta->getCMensaje());
            $sentencia->bindValue(':estado',   $alerta->getEEstado());
            $sentencia->execute();
            return $this->conexion->lastInsertId();
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}