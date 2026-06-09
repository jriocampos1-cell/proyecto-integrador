<?php
require_once "Core/BaseDatos.php";
require_once "Entities/Usuario.php";

class UsuarioModel extends Conectar{
    public function __construct(){
        parent::__construct();
    }

    public function findById($id){
        try{
            $sql = "SELECT * FROM TUsuarios WHERE nUsuarioID = :id";                
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->bindParam(':id', $id);                      
            $sentencia->execute();          
            $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);   
            if($resultado){ 
                return new Usuario($resultado['nUsuarioID'], $resultado['cNombre'], $resultado['cNombreUsuario'], $resultado['cContraseñaUsuario'], $resultado['eRol'], $resultado['eEstado'], $resultado['cCorreo']);
            }
            return null;
        }catch(Exception $e){
            die($e->getMessage());
        }
    }    

    public function findAll(){
        try{           
            $sql = "SELECT * FROM TUsuarios"; 
            $consulta = $this->conexion->prepare($sql);
            $consulta->execute();   
            $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);                           
            $lista = [];
            foreach ($resultados as $fila) {
                $lista[] = new Usuario($fila['nUsuarioID'], $fila['cNombre'], $fila['cNombreUsuario'], $fila['cContraseñaUsuario'], $fila['eRol'], $fila['eEstado'], $fila['cCorreo']);
            }
            return $lista;
        }catch(Exception $e){
            die($e->getMessage());
        }
    }

    public function create(Usuario $usuario){
        try{
            // Usamos el procedimiento almacenado seguro que definiste en tu BD
            $sql = "CALL sp_InsertarUsuario(:nombre, :nombreUsuario, :contrasena, :rol, :correo)";
            $sentencia = $this->conexion->prepare($sql);    
            $sentencia->bindValue(':nombre', $usuario->getCNombre());       
            $sentencia->bindValue(':nombreUsuario', $usuario->getCNombreUsuario());  
            $sentencia->bindValue(':contrasena', $usuario->getCContraseñaUsuario());  
            $sentencia->bindValue(':rol', $usuario->getERol());  
            $sentencia->bindValue(':correo', $usuario->getCCorreo());  
            $sentencia->execute();
            return $this->conexion->lastInsertId();
        }catch(Exception $e){
            die($e->getMessage());
        }
    } 

    public function update(Usuario $usuario){
        try{
            $sql = "UPDATE TUsuarios SET cNombre = :nombre, cNombreUsuario = :nombreUsuario, cContraseñaUsuario = :contrasena, eRol = :rol, eEstado = :estado, cCorreo = :correo WHERE nUsuarioID = :id";
            $sentencia = $this->conexion->prepare($sql);    
            $sentencia->bindValue(':nombre', $usuario->getCNombre()); 
            $sentencia->bindValue(':nombreUsuario', $usuario->getCNombreUsuario());  
            $sentencia->bindValue(':contrasena', $usuario->getCContraseñaUsuario());  
            $sentencia->bindValue(':rol', $usuario->getERol());  
            $sentencia->bindValue(':estado', $usuario->getEEstado());  
            $sentencia->bindValue(':correo', $usuario->getCCorreo());  
            $sentencia->bindValue(':id', $usuario->getNUsuarioID()); 
            $sentencia->execute();
            return ($sentencia->rowCount() > 0);
        }catch(Exception $e){
            die($e->getMessage());
        }
    }  
    
    public function desactivar($id){
    try {
        $sql = "UPDATE TUsuarios SET eEstado = 'inactivo' WHERE nUsuarioID = :id";
        $sentencia = $this->conexion->prepare($sql);
        $sentencia->bindValue(':id', $id);
        $sentencia->execute();
        return ($sentencia->rowCount() > 0);
    } catch(Exception $e) {
        die($e->getMessage());
    }
}
}