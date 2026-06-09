<?php
 
class Proveedor
{
    private $nProveedorID;
    private $cNombre;
    private $cTelefono;
    private $cCorreo;
    private $eEstado;
 
    public function __construct(
        $nProveedorID = null,
        $cNombre      = null,
        $cTelefono    = null,
        $cCorreo      = null,
        $eEstado      = null
    ) {
        $this->nProveedorID = $nProveedorID;
        $this->cNombre      = $cNombre;
        $this->cTelefono    = $cTelefono;
        $this->cCorreo      = $cCorreo;
        $this->eEstado      = $eEstado;
    }

    public function getNProveedorID() {
        return $this->nProveedorID;
    }

    public function getCNombre() {
        return $this->cNombre;
    }

    public function getCTelefono() {
        return $this->cTelefono;
    }

    public function getCCorreo() {
        return $this->cCorreo;
    }

    public function getEEstado() {
        return $this->eEstado;
    }

    public function setNProveedorID($nProveedorID) {
        $this->nProveedorID = $nProveedorID;
    }

    public function setCNombre($cNombre) {
        $this->cNombre = $cNombre;
    }

    public function setCTelefono($cTelefono) {
        $this->cTelefono = $cTelefono;
    }

    public function setCCorreo($cCorreo) {
        $this->cCorreo = $cCorreo;
    }

    public function setEEstado($eEstado) {
        $this->eEstado = $eEstado;
    }

}