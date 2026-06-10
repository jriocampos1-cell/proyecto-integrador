<?php
 
class DetalleSolicitud
{
    private $nDetallesSolicitudID;
    private $nSolicitudID;
    private $nInsumoID;
    private $nCantidad;
 
    public function __construct(
        $nDetallesSolicitudID = null,
        $nSolicitudID         = null,
        $nInsumoID            = null,
        $nCantidad            = null
    ) {
        $this->nDetallesSolicitudID = $nDetallesSolicitudID;
        $this->nSolicitudID         = $nSolicitudID;
        $this->nInsumoID            = $nInsumoID;
        $this->nCantidad            = $nCantidad;
    }

    public function getNDetallesSolicitudID() {
        return $this->nDetallesSolicitudID;
    }

    public function getNSolicitudID() {
        return $this->nSolicitudID;
    }

    public function getNInsumoID() {
        return $this->nInsumoID;
    }

    public function getNCantidad() {
        return $this->nCantidad;
    }

    public function setNDetallesSolicitudID($nDetallesSolicitudID) {
        $this->nDetallesSolicitudID = $nDetallesSolicitudID;
    }

    public function setNSolicitudID($nSolicitudID) {
        $this->nSolicitudID = $nSolicitudID;
    }

    public function setNInsumoID($nInsumoID) {
        $this->nInsumoID = $nInsumoID;
    }

    public function setNCantidad($nCantidad) {
        $this->nCantidad = $nCantidad;
    }
}