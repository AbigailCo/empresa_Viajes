<?php

include_once("BaseDatos.php");
include_once('Pasajero.php');
include_once('ResponsableV.php');

class Viaje
{

    private $codigo;
    private $destino;
    private $cantidad;
    private $responsable;
    private $costo;
    private $pasajeros;
    private $empresa;
    private $mensajeoperacion;

    public function __construct()
    {
        $this->codigo = "";
        $this->destino = "";
        $this->cantidad = "";
        $this->responsable = new ResponsableV();
        $this->costo = "";
        $this->empresa = new Empresa;
        $this->pasajeros = array();
        
    }

    public function cargar($idviaje, $empresa, $destino, $cantidad, $responsable, $costo)
    {
        $this->setDestino($destino);
        $this->setCantidad($cantidad);
        $this->setResponsable($responsable);
        $this->setEmpresa($empresa);
        $this->setCosto($costo);
        $this->setCodigo($idviaje);
       /*  $this->setPasajeros(); */
    }

    public function getCodigo()
    {
        return $this->codigo;
    }

    public function getDestino()
    {
        return $this->destino;
    }

    public function getCantidad()
    {
        return $this->cantidad;
    }

    public function getResponsable()
    {
        return $this->responsable;
    }

    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    public function setDestino($destino)
    {
        $this->destino = $destino;
    }

    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;
    }

    public function setResponsable($responsable)
    {
        $this->responsable = $responsable;
    }

    public function getCosto()
    {
        return $this->costo;
    }

    public function setCosto($costo)
    {
        $this->costo = $costo;
    }


    public function getPasajeros()
    {
        
        return $this->pasajeros;
    }

    public function setPasajeros()
    {
        $pasajero= new Pasajero();
        $this->pasajeros= ($pasajero->listar("idviaje =" .$this->getCodigo()));
    }
    public function getEmpresa()
    {
        return $this->empresa;
    }
    public function setEmpresa($empresa)
    {
        $this->empresa = $empresa;
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
        $string= "\nVIAJE -> ID DE LA EMPRESA: " . $this->getEmpresa() . "\nCódigo: " . $this->getCodigo() . "\nDestino: " . $this->getDestino() . "\nMax de pasajeros: " . $this->getCantidad() . "\nResponsable: " . $this->devuelveResponsable() . "\nCosto: " . $this->getCosto() . "\n(Pasajeros: " . $this->devuelvePasajeros() . ")";
        return $string;
    }

   
    private function devuelveResponsable()
    {
        $nuevoRes=new ResponsableV();
        $lista = $nuevoRes->listar("rnumeroempleado =".$this->getResponsable());
        $string = "\n-----------\n(";
        if (count($lista)<= 0) {
            $string .= "No hay responsable)\n-----------\n";
        } else {
            for ($i=0; $i < count($lista); $i++) { 
                $responsable = $lista[$i];
                $string .= $responsable->__toString() . ")\n-----------\n";
            }
        }
        return $string;
    }
    private function devuelvePasajeros()
    {
        $pasajero = new Pasajero ();
        $string = "\n-----------\n(";
        $coleccionPasajeros = $pasajero->listar("idviaje = ".$this->getCodigo());
        if (count($coleccionPasajeros) == 0) {
            $string .= "TODAVIA NO SE CARGARON PASAJEROS)\n-----------\n";
        } else {
            for ($i = 0; $i < count($coleccionPasajeros); $i++) {
                $pasajeroMostrar= $coleccionPasajeros[$i];
                $string .= $pasajeroMostrar->__toString(). ")\n-----------\n ";
            }
        }
        return $string;
    }
    /**
	 * Recupera los datos de una persona por dni
	 * @param int $dni
	 * @return true en caso de encontrar los datos, false en caso contrario 
	 */	

     public function Buscar($idviaje){
		$base=new BaseDatos();
		$consultaPersona="Select * from viaje where idviaje = ".$idviaje;
		$resp= false;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaPersona)){
				if($row2=$base->Registro()){					
				    
                    $this->cargar($row2['idempresa'],$idviaje,$row2['vdestino'],$row2['vcantmaxpasajeros'],$row2['rnumeroempleado'],$row2['vimporte']);
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
		$consultaPersonas="SELECT * from viaje ";
		if ($condicion!=""){
		    $consultaPersonas .= " where " . $condicion;
		}
		$consultaPersonas .= " order by idempresa";
		if($base->Iniciar()){
			if($base->Ejecutar($consultaPersonas)){				
				$arregloPersona= array();
				while($row2=$base->Registro()){
					$perso=new Viaje();
					$perso -> cargar($row2['idempresa'],$row2['idviaje'],$row2['vdestino'],$row2['vcantmaxpasajeros'],$row2['rnumeroempleado'],$row2['vimporte']);
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
		$consultaInsertar="INSERT INTO viaje(vdestino,vcantmaxpasajeros,idempresa,rnumeroempleado,vimporte) 
				VALUES ('".$this->getDestino()."',".$this->getCantidad().",".$this->getEmpresa().",".$this->getResponsable().",".$this->getCosto().")";
		
		if($base->Iniciar()){

			if($id= $base->devuelveIDInsercion($consultaInsertar)){
			    
                $this->setCodigo($id);
                $this->setPasajeros();
                $resp = true;
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
		$consultaModifica="UPDATE viaje SET vdestino='".$this->getDestino()."',vcantmaxpasajeros=".$this->getCantidad()."
                           , rnumeroempleado= ".$this->getResponsable().",vimporte=".$this->getCosto()."  WHERE idviaje=". $this->getCosto();
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
				$consultaBorra="DELETE FROM viaje WHERE idviaje= ".$this->getCodigo();
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
