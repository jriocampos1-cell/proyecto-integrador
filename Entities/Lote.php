<?php
 
class Lote
{
    private $nLoteID;
    private $nInsumoID;
    private $cCodigoLote;
    private $nCantidadActual;
    private $dFechaIngreso;
    private $dVencimiento;
 
    public function __construct(
        $nLoteID         = null,
        $nInsumoID       = null,
        $cCodigoLote     = null,
        $nCantidadActual = null,
        $dFechaIngreso   = null,
        $dVencimiento    = null
    ) {
        $this->nLoteID         = $nLoteID;
        $this->nInsumoID       = $nInsumoID;
        $this->cCodigoLote     = $cCodigoLote;
        $this->nCantidadActual = $nCantidadActual;
        $this->dFechaIngreso   = $dFechaIngreso;
        $this->dVencimiento    = $dVencimiento;
    }

    public function getNLoteID() {
        return $this->nLoteID;
    }

    public function getNInsumoID() {
        return $this->nInsumoID;
    }

    public function getCCodigoLote() {
        return $this->cCodigoLote;
    }

    public function getNCantidadActual() {
        return $this->nCantidadActual;
    }

    public function getDFechaIngreso() {
        return $this->dFechaIngreso;
    }

    public function getDVencimiento() {
        return $this->dVencimiento;
    }

    public function setNLoteID($nLoteID) {
        $this->nLoteID = $nLoteID;
    }

    public function setNInsumoID($nInsumoID) {
        $this->nInsumoID = $nInsumoID;
    }

    public function setCCodigoLote($cCodigoLote) {
        $this->cCodigoLote = $cCodigoLote;
    }

    public function setNCantidadActual($nCantidadActual) {
        $this->nCantidadActual = $nCantidadActual;
    }

    public function setDFechaIngreso($dFechaIngreso) {
        $this->dFechaIngreso = $dFechaIngreso;
    }

    public function setDVencimiento($dVencimiento) {
        $this->dVencimiento = $dVencimiento;
    }
  }