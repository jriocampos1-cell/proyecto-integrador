<?php
 
class Insumo
{
    private $nInsumoID;
    private $cNombre;
    private $cCategoria;
    private $eUnidadMedida;
    private $nStockActual;
    private $nStockMinimo;
 
    public function __construct($nInsumoID = null, $cNombre = null,$cCategoria = null, $eUnidadMedida = null, $nStockActual = null, $nStockMinimo = null
    ) 
    {
        $this->nInsumoID  = $nInsumoID;
        $this->cNombre = $cNombre;
        $this->cCategoria = $cCategoria;
        $this->eUnidadMedida = $eUnidadMedida;
        $this->nStockActual  = $nStockActual;
        $this->nStockMinimo  = $nStockMinimo;
    }


    public function getNInsumoID() {
        return $this->nInsumoID;
    }

    public function getCNombre() {
        return $this->cNombre;
    }

    public function getCCategoria() {
        return $this->cCategoria;
    }

    public function getEUnidadMedida() {
        return $this->eUnidadMedida;
    }

    public function getNStockActual() {
        return $this->nStockActual;
    }

    public function getNStockMinimo() {
        return $this->nStockMinimo;
    }

    public function setNInsumoID($nInsumoID) {
        $this->nInsumoID = $nInsumoID;
    }

    public function setCNombre($cNombre) {
        $this->cNombre = $cNombre;
    }

    public function setCCategoria($cCategoria) {
        $this->cCategoria = $cCategoria;
    }

    public function setEUnidadMedida($eUnidadMedida) {
        $this->eUnidadMedida = $eUnidadMedida;
    }

    public function setNStockActual($nStockActual) {
        $this->nStockActual = $nStockActual;
    }

    public function setNStockMinimo($nStockMinimo) {
        $this->nStockMinimo = $nStockMinimo;
    }

}