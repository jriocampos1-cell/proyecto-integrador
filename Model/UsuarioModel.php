<?php
require_once __DIR__ . '/../Core/BaseDatos.php';
require_once __DIR__ . '/../Entities/Usuario.php';

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


    public function findAllView(){
        try{
            $sql = "SELECT nUsuarioID, cNombre, cNombreUsuario, eRol, eEstado, cCorreo FROM TUsuarios ORDER BY nUsuarioID";
            $consulta = $this->conexion->prepare($sql);
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            die($e->getMessage());
        }
    }

    public function findByIdView($id){
        try{
            $sql = "SELECT nUsuarioID, cNombre, cNombreUsuario, eRol, eEstado, cCorreo FROM TUsuarios WHERE nUsuarioID = :id";
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->bindParam(':id', $id);
            $sentencia->execute();
            return $sentencia->fetch(PDO::FETCH_ASSOC);
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
    // Autenticación flexible: busca por nombre, username, o coincidencia parcial
    public function authenticate($cNombre, $cCedula){
        try{
            // Buscar por nombre exacto, username exacto, o nombre que contenga el texto
            $sql = "SELECT * FROM TUsuarios WHERE (cNombre = :nombre OR cNombreUsuario = :nombre OR cNombre LIKE :likeNombre) AND cContraseñaUsuario = :pass AND eEstado = 'activo' LIMIT 1";
            $like = '%' . $cNombre . '%';
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':nombre', $cNombre);
            $stmt->bindParam(':likeNombre', $like);
            $stmt->bindParam(':pass', $cCedula);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if($row){
                return new Usuario($row['nUsuarioID'], $row['cNombre'], $row['cNombreUsuario'], $row['cContraseñaUsuario'], $row['eRol'], $row['eEstado'], $row['cCorreo']);
            }
            return null;
        }catch(Exception $e){
            die($e->getMessage());
        }
    }

    // Autenticación por nombre de usuario (compatibilidad)
    public function authenticateByUsername($cNombreUsuario, $cCedula){
        try{
            $sql = "SELECT * FROM TUsuarios WHERE cNombreUsuario = :user AND cContraseñaUsuario = :pass AND eEstado = 'activo'";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':user', $cNombreUsuario);
            $stmt->bindParam(':pass', $cCedula);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if($row){
                return new Usuario($row['nUsuarioID'], $row['cNombre'], $row['cNombreUsuario'], $row['cContraseñaUsuario'], $row['eRol'], $row['eEstado'], $row['cCorreo']);
            }
            return null;
        }catch(Exception $e){
            die($e->getMessage());
        }
    }

}