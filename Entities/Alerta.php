<?php
 
class Alerta
{
    private $nAlertaID;
    private $nInsumoID;
    private $nLoteID;
    private $eTipo;
    private $cMensaje;
    private $dFecha;
    private $eEstado;
 
    public function __construct(
        $nAlertaID = null,
        $nInsumoID = null,
        $nLoteID   = null,
        $eTipo     = null,
        $cMensaje  = null,
        $dFecha    = null,
        $eEstado   = null
    ) {
        $this->nAlertaID = $nAlertaID;
        $this->nInsumoID = $nInsumoID;
        $this->nLoteID   = $nLoteID;
        $this->eTipo     = $eTipo;
        $this->cMensaje  = $cMensaje;
        $this->dFecha    = $dFecha;
        $this->eEstado   = $eEstado;
    }

   

    public function getNAlertaID() {
        return $this->nAlertaID;
    }

    public function getNInsumoID() {
        return $this->nInsumoID;
    }

    public function getNLoteID() {
        return $this->nLoteID;
    }

    public function getETipo() {
        return $this->eTipo;
    }

    public function getCMensaje() {
        return $this->cMensaje;
    }

    public function getDFecha() {
        return $this->dFecha;
    }

    public function getEEstado() {
        return $this->eEstado;
    }


    public function setNAlertaID($nAlertaID) {
        $this->nAlertaID = $nAlertaID;
    }

    public function setNInsumoID($nInsumoID) {
        $this->nInsumoID = $nInsumoID;
    }

    public function setNLoteID($nLoteID) {
        $this->nLoteID = $nLoteID;
    }

    public function setETipo($eTipo) {
        $this->eTipo = $eTipo;
    }

    public function setCMensaje($cMensaje) {
        $this->cMensaje = $cMensaje;
    }

    public function setDFecha($dFecha) {
        $this->dFecha = $dFecha;
    }

    public function setEEstado($eEstado) {
        $this->eEstado = $eEstado;
    }

}