<?php
require_once __DIR__ . '/../Core/BaseDatos.php';
require_once __DIR__ . '/../Entities/Proveedor.php';

class ProveedorModel extends Conectar {

    public function __construct() {
        parent::__construct();
    }

    
    public function findById($id) {
        try {
            $sql = "SELECT * FROM TProveedores WHERE nProveedorID = :id";
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->bindParam(':id', $id);
            $sentencia->execute();
            $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);
            if ($resultado) {
                return new Proveedor(
                    $resultado['nProveedorID'],
                    $resultado['cNombre'],
                    $resultado['cTelefono'],
                    $resultado['cCorreo'],
                    $resultado['eEstado']
                );
            }
            return null;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    
    public function findAll() {
        try {
            $sql = "SELECT * FROM TProveedores";
            $consulta = $this->conexion->prepare($sql);
            $consulta->execute();
            $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
            $lista = [];
            foreach ($resultados as $fila) {
                $lista[] = new Proveedor(
                    $fila['nProveedorID'],
                    $fila['cNombre'],
                    $fila['cTelefono'],
                    $fila['cCorreo'],
                    $fila['eEstado']
                );
            }
            return $lista;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

   
    public function findActivos() {
        try {
            $sql = "SELECT * FROM TProveedores WHERE eEstado = 'activo'";
            $consulta = $this->conexion->prepare($sql);
            $consulta->execute();
            $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
            $lista = [];
            foreach ($resultados as $fila) {
                $lista[] = new Proveedor(
                    $fila['nProveedorID'],
                    $fila['cNombre'],
                    $fila['cTelefono'],
                    $fila['cCorreo'],
                    $fila['eEstado']
                );
            }
            return $lista;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

 

    public function findAllView() {
        try {
            $sql = "SELECT nProveedorID, cNombre, cTelefono, cCorreo, eEstado FROM TProveedores ORDER BY cNombre";
            $consulta = $this->conexion->prepare($sql);
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function findByIdView($id) {
        try {
            $sql = "SELECT nProveedorID, cNombre, cTelefono, cCorreo, eEstado FROM TProveedores WHERE nProveedorID = :id";
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->bindParam(':id', $id);
            $sentencia->execute();
            return $sentencia->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

        public function create(Proveedor $proveedor) {
        try {
            $sql = "INSERT INTO TProveedores (cNombre, cTelefono, cCorreo, eEstado) 
                    VALUES (:nombre, :telefono, :correo, :estado)";
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->bindValue(':nombre',   $proveedor->getCNombre());
            $sentencia->bindValue(':telefono', $proveedor->getCTelefono());
            $sentencia->bindValue(':correo',   $proveedor->getCCorreo());
            $sentencia->bindValue(':estado',   $proveedor->getEEstado());
            $sentencia->execute();
            return $this->conexion->lastInsertId();
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    
    public function update(Proveedor $proveedor) {
        try {
            $sql = "UPDATE TProveedores 
                    SET cNombre = :nombre, cTelefono = :telefono, cCorreo = :correo, eEstado = :estado 
                    WHERE nProveedorID = :id";
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->bindValue(':nombre',   $proveedor->getCNombre());
            $sentencia->bindValue(':telefono', $proveedor->getCTelefono());
            $sentencia->bindValue(':correo',   $proveedor->getCCorreo());
            $sentencia->bindValue(':estado',   $proveedor->getEEstado());
            $sentencia->bindValue(':id',       $proveedor->getNProveedorID());
            $sentencia->execute();
            return ($sentencia->rowCount() > 0);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    
    public function desactivar($id) {
        try {
            $sql = "UPDATE TProveedores SET eEstado = 'inactivo' WHERE nProveedorID = :id";
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->bindValue(':id', $id);
            $sentencia->execute();
            return ($sentencia->rowCount() > 0);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}