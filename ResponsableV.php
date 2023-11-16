<?php

include_once("BaseDatos.php");

class ResponsableV {
    
    private $numero;
    private $licencia;
    private $nombre;
    private $apellido;
    private $viaje;
    private $mensajeoperacion;


    public function __construct()
    {
        $this->numero= "";
        $this->licencia= "";
        $this->nombre= "";
        $this->apellido= "";

    }

    public function cargar($licencia, $nombre, $apellido)
    {
        $this->setLicencia($licencia);
        $this->setNombre($nombre);
        $this->setApellido($apellido);
    }

    public function getNumero(){
        return $this->numero;
    }

    public function getLicencia(){
        return $this->licencia;
    }

    public function getNombre(){
        return $this->nombre;
    }

    public function getApellido(){
        return $this->apellido;
    }

    public function setNumero($numero){
        $this->numero=$numero;
    }

    public function setLicencia($licencia){
        $this->licencia=$licencia; 
    }

    public function setNombre($nombre){
        $this->nombre = $nombre;
    }

    public function setApellido($apellido){
        $this->apellido = $apellido; 
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
        return "(".$this->getNumero().", ".$this->getLicencia().", ".$this->getNombre().", ".$this->getApellido().")";
    }

    //SQL

   /**
	 * Recupera los datos de una persona por dni
	 * @param int $dni
	 * @return true en caso de encontrar los datos, false en caso contrario 
	 */	

    public function Buscar($rnumeroempleado){
		$base=new BaseDatos();
		$consultaPersona="Select * from responsable where rnumeroempleado = ".$rnumeroempleado;
		$resp= false;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaPersona)){
				if($row2=$base->Registro()){					
				    
                    $this->cargar($rnumeroempleado,$row2['rnumerolicencia'],$row2['rnombre'],$row2['rapellido']);
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
		$consultaPersonas="Select * from responsable ";
		if ($condicion!=""){
		    $consultaPersonas=$consultaPersonas.' where '.$condicion;
		}
		$consultaPersonas.=" order by rapellido ";
		if($base->Iniciar()){
			if($base->Ejecutar($consultaPersonas)){				
				$arregloPersona= array();
				while($row2=$base->Registro()){
					$perso=new ResponsableV();
					$perso -> cargar($row2['rnumeroempleado'],$row2['rnumerolicencia'],$row2['rnombre'],$row2['rapellido']);
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
		$consultaInsertar="INSERT INTO responsable(rnumeroempleado, rnumerolicencia, rnombre,  rapellido) 
				VALUES (".$this->getNumero().",'".$this->getLicencia()."','".$this->getNombre()."','".$this->getApellido()."')";
		
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
		$consultaModifica="UPDATE responsable SET rapellido='".$this->getApellido()."',rnombre='".$this->getNombre()."'
                           , rnumerolicencia= '".$this->getLicencia()."' WHERE rnumeroempleado=". $this->getNumero();
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
				$consultaBorra="DELETE FROM responsable WHERE rnumeroempleado= ".$this->getNumero();
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