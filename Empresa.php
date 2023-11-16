<?php

class Empresa
{

    private $idempresa;
    private $enombre;
    private $edireccion;
    private $coleccionViajes;
    private $mensajeoperacion;

    public function __construct()
    {
        $this->idempresa = '';
        $this->enombre = '';
        $this->edireccion = '';
        $this->coleccionViajes =array ();
    }

    public function cargar($enombre, $edireccion)
    {
        $this->setEnombre($enombre);
        $this->setEdireccion($edireccion);
       $this->setColeccionViajes();
    }

    public function getIdempresa()
    {
        return $this->idempresa;
    }

    public function setIdempresa($idempresa)
    {
        $this->idempresa = $idempresa;
    }

    public function getEnombre()
    {
        return $this->enombre;
    }

    public function setEnombre($enombre)
    {
        $this->enombre = $enombre;
    }

    public function getEdireccion()
    {
        return $this->edireccion;
    }

    public function setEdireccion($edireccion)
    {
        $this->edireccion = $edireccion;
    }

    public function getColeccionViajes()
    {
        return $this->coleccionViajes;
    }

    public function setColeccionViajes()
    { 
        $viaje = new Viaje();
        $this->coleccionViajes= $viaje->listar('idempresa =' .$this->getIdempresa());
    }
    public function getMensajeoperacion()
    {
        return $this->mensajeoperacion;
    }
    public function setMensajeoperacion($mensajeoperacion)
    {
        $this->mensajeoperacion = $mensajeoperacion;
    }
    public function __toString()
    {
        return "EMPRESA-> (idempresa: " . $this->getIdempresa() . " \nNOMBRE: " . $this->getEnombre() . " \nDireccion:" . $this->getEdireccion() . "\nViajes-> ( " . $this->daColeccionViajes();
    }

    private function daColeccionViajes()
    {
        $cadena = "VIAJES ->(";
        $coleccionViajes = $this->getColeccionViajes();
        $cantidadViajes = count($coleccionViajes);
        if ($cantidadViajes == 0) {
            $cadena = "Ups, no hay viajes cargados en esta empresa)";
        } else {
            for ($i = 0; $i < $cantidadViajes; $i++) {
                $cadena.= ($coleccionViajes[$i]->__toString()) . ")\n ";
            }
        
        }
        return $cadena;
    }


    //SQL


    /**
	 * Recupera los datos de una persona por dni
	 * @param int $dni
	 * @return true en caso de encontrar los datos, false en caso contrario 
	 */
    public function Buscar($idEmpresa){
		$base=new BaseDatos();
		$consultaPersona="Select * from empresa where idempresa = ".$idEmpresa;
		$resp= false;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaPersona)){
				if($row2=$base->Registro()){					

                    $this->cargar($idEmpresa, $row2['enombre'],$row2['edireccion'],$row2['viajes']);
					$resp= true;
				}				
			
		 	}	else {
		 			$this->setmensajeoperacion($base->getError());
		 		
			}
		 }	else {
		 		$this->setmensajeoperacion($base->getError());
		 	
		 }		
		 return $resp;
	}	

    public function listar($condicion=""){
	    $arregloPersona = null;
		$base=new BaseDatos();
		$consultaPersonas="Select * from empresa ";
		if ($condicion!=""){
		    $consultaPersonas=$consultaPersonas.' where '.$condicion;
		}
		$consultaPersonas.=" order by enombre ";
		if($base->Iniciar()){
			if($base->Ejecutar($consultaPersonas)){				
				$arregloPersona= array();
				while($row2=$base->Registro()){
					$perso=new Empresa();
					$perso->cargar($row2['idempresa'],$row2['enombre'],$row2['edireccion'],$row2['viajes']);
					array_push($arregloPersona,$perso);
				}
				
			
		 	}	else {
		 			$this->setmensajeoperacion($base->getError());
		 		
			}
		 }	else {
		 		$this->setmensajeoperacion($base->getError());
		 	
		 }	
		 return $arregloPersona;
	}	

    public function insertar(){
		$base=new BaseDatos();
		$resp= false;
		$consultaInsertar="INSERT INTO empresa(enombre, edireccion, viajes) 
				VALUES (".$this->getEnombre().",'".$this->getEdireccion()."','".$this->getColeccionViajes()."')";
		
		if($base->Iniciar()){

			if($base->Ejecutar($consultaInsertar)){

			    $resp=  true;

			}	else {
					$this->setmensajeoperacion($base->getError());
					
			}

		} else {
				$this->setmensajeoperacion($base->getError());
			
		}
		return $resp;
	}
    	
    public function modificar(){
	    $resp =false; 
	    $base=new BaseDatos();
		$consultaModifica="UPDATE empresa SET enombre='".$this->getEnombre()."',edireccion='".$this->getEdireccion()."'
                           ,viajes='".$this->getColeccionViajes()."' WHERE idempresa=". $this->getIdempresa();
		if($base->Iniciar()){
			if($base->Ejecutar($consultaModifica)){
			    $resp=  true;
			}else{
				$this->setmensajeoperacion($base->getError());
				
			}
		}else{
				$this->setmensajeoperacion($base->getError());
			
		}
		return $resp;
	}

    public function eliminar(){
		$base=new BaseDatos();
		$resp=false;
		if($base->Iniciar()){
				$consultaBorra="DELETE FROM empresa WHERE idempresa=".$this->getIdempresa();
				if($base->Ejecutar($consultaBorra)){
				    $resp=  true;
				}else{
						$this->setmensajeoperacion($base->getError());	
				}
		}else{
				$this->setmensajeoperacion($base->getError());	
		}
		return $resp; 
	}
}
    
    /* public function insertar()
    {
        $base = new BaseDatos();
        $resp = false;
        $consultaInsertar = "INSERT INTO empresa(enombre, edireccion) 
                VALUES ('" . $this->getEnombre() . "','" . $this->getEdireccion() . "')";

        if ($base->Iniciar()) {

            if ($base->Ejecutar($consultaInsertar)) {

                $resp =  true;
            }
        }
        return $resp;
    }

    public function modificar()
    {
        $resp = false;
        $base = new BaseDatos();
        $consultaModifica = "UPDATE empresa SET enombre='" . $this->getEnombre() . "',edireccion='" . $this->getEdireccion() . "' WHERE idempresa= " . $this->getIdempresa();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaModifica)) {
                $resp =  true;
            }
        }
        return $resp;
    }

    public function eliminar()
    {
        $base = new BaseDatos();
        $resp = false;
        if ($base->Iniciar()) {
            $consultaBorra = "DELETE FROM empresa WHERE idempresa= " . $this->getIdempresa();
            if ($base->Ejecutar($consultaBorra)) {
                $resp =  true;
            }
        }
        return $resp;
    }


    public function Buscar($codigo)
    {
        $base = new BaseDatos();
        $consultaempresa = "Select * from empresa where idempresa= " . intval($codigo);
        $resp = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaempresa)) {
                if ($row2 = $base->Registro()) {
                    $this->cargar($codigo, $row2['enombre'], $row2['edireccion'], $row2['coleccionviajes']);
                    $resp = true;
                }
            }
        }
        return $resp;
    }

    public static function listar($condicion = "")
    {
        $arregloempresa = null;
        $base = new BaseDatos();
        $consultaempresas = "Select * from empresa ";
        if ($condicion != "") {
            $consultaempresas = $consultaempresas . ' where ' . $condicion;
        }
        $consultaempresas .= " order by idempresa ";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaempresas)) {
                $arregloempresa = array();
                while ($row2 = $base->Registro()) {

                    $idEmpresa = $row2['idempresa'];
                    $enombre = $row2['enombre'];
                    $edireccion = $row2['edireccion'];

                    $empresa = new Empresa();
                    $empresa->cargar($idEmpresa, $enombre, $edireccion);
                    array_push($arregloempresa, $empresa);
                }
            }
        }
        return $arregloempresa;
    }

    public function agregarViaje($viaje)
    {
        $band = $this->esViaje($viaje);
        if (!$band) {
            $base = new BaseDatos();
            $consultaModifica = "UPDATE viaje SET idempresa= " . $this->getIdempresa() . " WHERE idviaje= " . $viaje->getCodigo();
            if ($base->Iniciar()) {
                if ($base->Ejecutar($consultaModifica)) {
                    $this->setViajes(Viaje::listar('idempresa =' . $this->getIdempresa()));
                    return true;
                }
            }
        }
        return false;
    }

    public function quitarViaje($viaje)
    {
        $band = $this->esViaje($viaje);
        if ($band) {
            $base = new BaseDatos();
            $consultaModifica = "UPDATE viaje SET idempresa= NULL WHERE idviaje= " . $viaje->getCodigo();
            if ($base->Iniciar()) {
                if ($base->Ejecutar($consultaModifica)) {
                    $this->setViajes(Viaje::listar('idempresa =' . $this->getIdempresa()));
                    return true;
                }
            }
        }
        return false;
    }

    public function esViaje($viaje)
    {
        $viajes = $this->getViajes();
        $indice = count($viajes);
        $band = false;
        $i = 0;
        while ($i < $indice) {
            if ($viajes[$i]->getCodigo() === $viaje->getCodigo()) {
                $band = true;
                break;
            }
            $i++;
        }
        return $band;
    }

    
}*/