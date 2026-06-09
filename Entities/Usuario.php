<?php

class Usuario
{
    private $nUsuarioID;
    private $cNombre;
    private $cNombreUsuario;
    private $cContraseñaUsuario;
    private $eRol;
    private $eEstado;
    private $cCorreo;

    public function __construct($nUsuarioID=null, $cNombre = null, $cNombreUsuario = null, $cContraseña= null, $eRol= null, $eEstado= null, $cCorreo= null
    ) {
        $this->nUsuarioID = $nUsuarioID;
        $this->cNombre = $cNombre;
        $this->cNombreUsuario = $cNombreUsuario;
        $this->cContraseñaUsuario = $cContraseña;
        $this->eRol = $eRol;
        $this->eEstado = $eEstado;
        $this->cCorreo  = $cCorreo;
    }

    public function getNUsuarioID() {
        return $this->nUsuarioID;
    }

    public function getCNombre() {
        return $this->cNombre;
    }

    public function getCNombreUsuario() {
        return $this->cNombreUsuario;
    }

    public function getCContraseñaUsuario() {
        return $this->cContraseñaUsuario;
    }

    public function getERol() {
        return $this->eRol;
    }

    public function getEEstado() {
        return $this->eEstado;
    }

    public function getCCorreo() {
        return $this->cCorreo;
    }

    public function setNUsuarioID($nUsuarioID) {
        $this->nUsuarioID = $nUsuarioID;
    }

    public function setCNombre($cNombre) {
        $this->cNombre = $cNombre;
    }

    public function setCNombreUsuario($cNombreUsuario) {
        $this->cNombreUsuario = $cNombreUsuario;
    }

    public function setCContraseñaUsuario($cContraseñaUsuario) {
        $this->cContraseñaUsuario = $cContraseñaUsuario;
    }

    public function setERol($eRol) {
        $this->eRol = $eRol;
    }

    public function setEEstado($eEstado) {
        $this->eEstado = $eEstado;
    }

    public function setCCorreo($cCorreo) {
        $this->cCorreo = $cCorreo;
    }
}