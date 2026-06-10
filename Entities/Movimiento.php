<?php
 
class Movimiento
{
    private $nMovimientoID;
    private $nInsumoID;
    private $nLoteID;
    private $nUsuarioID;
    private $nProveedorID;
    private $nSolicitudID;
    private $nCantidad;
    private $eTipo;
    private $eMotivoSalida;
    private $dFecha;
 
    public function __construct(
        $nMovimientoID = null,
        $nInsumoID     = null,
        $nLoteID       = null,
        $nUsuarioID    = null,
        $nProveedorID  = null,
        $nSolicitudID  = null,
        $nCantidad     = null,
        $eTipo         = null,
        $eMotivoSalida = null,
        $dFecha        = null
    ) {
        $this->nMovimientoID = $nMovimientoID;
        $this->nInsumoID     = $nInsumoID;
        $this->nLoteID       = $nLoteID;
        $this->nUsuarioID    = $nUsuarioID;
        $this->nProveedorID  = $nProveedorID;
        $this->nSolicitudID  = $nSolicitudID;
        $this->nCantidad     = $nCantidad;
        $this->eTipo         = $eTipo;
        $this->eMotivoSalida = $eMotivoSalida;
        $this->dFecha        = $dFecha;
    }

    public function getNMovimientoID() {
        return $this->nMovimientoID;
    }

    public function getNInsumoID() {
        return $this->nInsumoID;
    }

    public function getNLoteID() {
        return $this->nLoteID;
    }

    public function getNUsuarioID() {
        return $this->nUsuarioID;
    }

    public function getNProveedorID() {
        return $this->nProveedorID;
    }

    public function getNSolicitudID() {
        return $this->nSolicitudID;
    }

    public function getNCantidad() {
        return $this->nCantidad;
    }

    public function getETipo() {
        return $this->eTipo;
    }

    public function getEMotivoSalida() {
        return $this->eMotivoSalida;
    }

    public function getDFecha() {
        return $this->dFecha;
    }

    public function setNMovimientoID($nMovimientoID) {
        $this->nMovimientoID = $nMovimientoID;
    }

    public function setNInsumoID($nInsumoID) {
        $this->nInsumoID = $nInsumoID;
    }

    public function setNLoteID($nLoteID) {
        $this->nLoteID = $nLoteID;
    }

    public function setNUsuarioID($nUsuarioID) {
        $this->nUsuarioID = $nUsuarioID;
    }

    public function setNProveedorID($nProveedorID) {
        $this->nProveedorID = $nProveedorID;
    }

    public function setNSolicitudID($nSolicitudID) {
        $this->nSolicitudID = $nSolicitudID;
    }

    public function setNCantidad($nCantidad) {
        $this->nCantidad = $nCantidad;
    }

    public function setETipo($eTipo) {
        $this->eTipo = $eTipo;
    }

    public function setEMotivoSalida($eMotivoSalida) {
        $this->eMotivoSalida = $eMotivoSalida;
    }

    public function setDFecha($dFecha) {
        $this->dFecha = $dFecha;
    }
}