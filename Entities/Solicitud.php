<?php
 
class Solicitud
{
    private $nSolicitudID;
    private $nUsuarioID;
    private $eEstado;
    private $cMotivoRechazo;
    private $dFecha;
 
    public function __construct(
        $nSolicitudID   = null,
        $nUsuarioID     = null,
        $eEstado        = null,
        $cMotivoRechazo = null,
        $dFecha         = null
    ) {
        $this->nSolicitudID   = $nSolicitudID;
        $this->nUsuarioID     = $nUsuarioID;
        $this->eEstado        = $eEstado;
        $this->cMotivoRechazo = $cMotivoRechazo;
        $this->dFecha         = $dFecha;
    }
    
    public function getNSolicitudID() {
        return $this->nSolicitudID;
    }

    public function getNUsuarioID() {
        return $this->nUsuarioID;
    }

    public function getEEstado() {
        return $this->eEstado;
    }

    public function getCMotivoRechazo() {
        return $this->cMotivoRechazo;
    }

    public function getDFecha() {
        return $this->dFecha;
    }

    public function setNSolicitudID($nSolicitudID) {
        $this->nSolicitudID = $nSolicitudID;
    }

    public function setNUsuarioID($nUsuarioID) {
        $this->nUsuarioID = $nUsuarioID;
    }

    public function setEEstado($eEstado) {
        $this->eEstado = $eEstado;
    }

    public function setCMotivoRechazo($cMotivoRechazo) {
        $this->cMotivoRechazo = $cMotivoRechazo;
    }

    public function setDFecha($dFecha) {
        $this->dFecha = $dFecha;
    }
}
