<?php

include_once("BaseDatos.php");

class Pasajero{

    private $nombre;
    private $apellido;
    private $dni;
    private $telefono;
    private $viaje;
    private $mensajeoperacion;

    public function __construct()
    {
        $this->nombre = "";
        $this->apellido ="";
        $this->dni = "";
        $this->telefono ="";
        $this->viaje = new Viaje();
    }

    public function cargar($nombre, $apellido, $dni,$telefono,$viaje)
    {
        $this->setNombre($nombre);
        $this->setApellido($apellido);
        $this->setDni($dni);
        $this->setTelefono($telefono);
        $this-> setIdViaje($viaje->getCodigo());
    }

    public function getNombre(){
        return $this->nombre;
    }

    public function getApellido(){
        return $this->apellido;
    }

    public function getDni(){
        return $this->dni;
    }

    public function getTelefono(){
        return $this->telefono;
    }
    
    public function setNombre($nombre){
        $this->nombre = $nombre;
    }

    public function setApellido($apellido){
        $this->apellido = $apellido; 
    }

    public function setDni($dni)
    {
        $this->dni = $dni;
    }

    public function setTelefono($telefono){
        $this->telefono = $telefono;
    } 
    public function getMensajeoperacion()
    {
        return $this->mensajeoperacion;
    }
    public function setMensajeoperacion($mensajeoperacion)
    {
        $this->mensajeoperacion = $mensajeoperacion;
    }
    public function getIdViaje()
    {
        return $this->viaje;
    }

    public function setIdViaje($viaje)
    {
        $this->viaje = $viaje;
    }
    public function __toString()
    {
        return "\n(Nombre: ".$this->getNombre()." ".$this->getApellido().
        "\nDNI: ".$this->getDni()."\ntel: ".$this->getTelefono().")"; 
    }

    //SQL funciones


    /**
	 * Recupera los datos de una persona por dni
	 * @param int $dni
	 * @return true en caso de encontrar los datos, false en caso contrario 
	 */		
    public function Buscar($dni){
		$base=new BaseDatos();
		$consultaPersona="Select * from pasajero where pdocumento = ".$dni;
		$resp= false;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaPersona)){
				if($row2=$base->Registro()){					

                    $this->cargar($row2['pnombre'],$row2['papellido'],$dni,$row2['ptelefono'],$row2['idviaje']);
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
		$consultaPersonas="Select * from pasajero ";
		if ($condicion!=""){
		    $consultaPersonas=$consultaPersonas.' where '.$condicion;
		}
		$consultaPersonas.=" order by papellido ";
		if($base->Iniciar()){
			if($base->Ejecutar($consultaPersonas)){				
				$arregloPersona= array();
				while($row2=$base->Registro()){
					
					$perso=new Pasajero();
					$perso->cargar($row2['pnombre'],$row2['papellido'],$row2['pdocumento'],$row2['ptelefono'], $row2['idviaje']);
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
		$consultaInsertar="INSERT INTO pasajero(pdocumento, papellido, pnombre,  ptelefono, idviaje) 
				VALUES (".$this->getDni().",'".$this->getApellido()."','".$this->getNombre()."','".$this->getTelefono()."',".$this->getIdViaje()."";
		
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
		$consultaModifica="UPDATE pasajero SET papellido='".$this->getApellido()."',pnombre='".$this->getNombre()."'
                           ,ptelefono='".$this->getTelefono()."', idviaje= ".$this->getIdViaje()."WHERE pdocumento=". $this->getDni();
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
				$consultaBorra="DELETE FROM pasajero WHERE pdocumento=".$this->getDni();
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
    
    public function estaEnElViaje($dni, $viaje){
		$base=new BaseDatos();
		$consultaPersona="Select * from pasajero where pdocumento = ".$dni." AND idviaje= ".$viaje->getCodigo();
		$resp= false;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaPersona)){
				if($row2=$base->Registro()){					

                    $this->cargar($row2['pnombre'],$row2['papellido'],$dni,$row2['ptelefono'],$row2['idviaje']);
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

    
}
