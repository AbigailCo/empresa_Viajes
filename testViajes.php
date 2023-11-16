<?php

include_once('Empresa.php');
include_once('Viaje.php');
include_once('Pasajero.php');
include_once('ResponsableV.php');

do {
    $opcion = MenuPrincipal();
    switch ($opcion) {
        case 1:
            empresas();
            break;
        case 2:
            viajes();
            break;
        case 3:
            responsables();
            break;
        case 4:
            pasajeros();
        default:
            break;
    };
} while ($opcion != 5);
echo ("\n\\GRACIAS\\\n");

//Menu de menus
function MenuPrincipal()
{
    $corte = false;
    do {
        echo
        "\nBIENVENIDOS\n 
        *Ingrese un numero de opcion VALIDO por favor!\n 
        1) Menu de EMPRESA
        2) Menu de VIAJES
        3) Menu de RESPONSABLE
        4) Menu de PASAJEROS
        \n(5)-> SALIR\n";
        $opcion =  trim(fgets(STDIN));
        if ($opcion >= 1 && $opcion <= 5) {
            $corte = true;
        } else {
            echo "SOLO OPCIONES CORRECTAS\n";
        }
    } while (!$corte);
    return $opcion;
}






function empresas()
{
    do {
        $opcion = menuEmpresa();
        switch ($opcion) {
            case 1:
                ingresarEmpresa();
                break;
            case 2:
                modificarEmpresa();
                break;
            case 3:
                eliminarEmpresa();
                break;
            case 4:
                mostrarEmpresa();
            default:
                break;
        };
    } while ($opcion != 5);
}
//Menu Empresa
function menuEmpresa()
{
    $corte = false;
    do {
        echo
        "\n-------------------------\n
        \n*MENU DE EMPRESAS*\n 
        *Ingrese un numero de opcion VALIDO por favor!\n 
        1 CREAR
        2 MODIFICAR 
        3 ELIMINAR
        4 VER INFORMACION
        \n(5)->SALIR\n";
        $opcion =  trim(fgets(STDIN));
        if ($opcion >= 1  && $opcion <= 5) {
            $corte = true;
        } else {
            echo "SOLO OPCIONES CORRECTAS\n";
        }
    } while (!$corte);
    return $opcion;
}
//Funciones de Empresa
function todasLasEmpresas()
{
    $objEmpresa = new Empresa();
    $coleccion = $objEmpresa->listar("");
    $string = "";
    for ($i = 0; $i < count($coleccion); $i++) {
        $empresaMostrar = $coleccion[$i];
        $string .= $empresaMostrar->__toString();
    }
    return $string;
}
function pedirEmpresa()
{
    echo "\n-------------------------\n";
    echo "Ingrese el ID de la empresa\n";
    $codigo =  trim(fgets(STDIN));
    $empresa = new Empresa();
    $existe = $empresa->Buscar($codigo);
    if (!$existe) {
        $empresa = null;
        echo "\nCODIGO NO EXISTE\n--> ¿Quiere crear la empresa? (s/n)\n";
        $desicion = trim(fgets(STDIN));
        if ($desicion == 's') {
            $empresa = ingresarEmpresa();
        }
    }else{
        $empresa->setIdempresa($codigo);
    }
    return $empresa;
}
function ingresarEmpresa()
{
    echo "\n-------------------------\n";
    $empresa = new Empresa();
    echo "->Ingrese el nombre de la empresa\n";
    $nombre =  trim(fgets(STDIN));
    echo "->Ingrese la direccion de la empresa\n";
    $direccion =  trim(fgets(STDIN));
    do {
        echo "->  ¿Quiere agregar viajes a la empresa? (s/n)  <-";
        $desicion =  trim(fgets(STDIN));
        if ($desicion == 's') {
            pedirViaje($empresa);
            $empresa->setColeccionViajes();
        }
    } while ($desicion == 's');
    $empresa->cargar($nombre, $direccion);
    if ($empresa->insertar()) {
        echo "~EXITO EN CARGAR EMPRESA~\n";
    } else {
        echo "~ERROR EN CARGAR EMPRESA~\n";
    }
}

function todosLosPasajeros()
{
    $objPasajero = new Pasajero();
    $coleccion = $objPasajero->listar("");
    $string = "";
    for ($i = 0; $i < count($coleccion); $i++) {
        $pasajeroMostrar = $coleccion[$i];
        $string .= $pasajeroMostrar->__toString();
    }
    return $string;
}
function todosLosViajes()
{
    $objViaje = new Viaje();
    $coleccion =  $objViaje->listar("");
    $string = "";
    for ($i = 0; $i < count($coleccion); $i++) {
        $mostrar = $coleccion[$i];
        $string .= $mostrar->__toString();
    }
    return $string;
}
function todosLosResponsables()
{
    $objResponsable = new ResponsableV();
    $coleccion =  $objResponsable->listar("");
    $string = "";
    for ($i = 0; $i < count($coleccion); $i++) {
        $mostrar = $coleccion[$i];
        $string .= $mostrar->__toString();
    }
    return $string;
}

function modificarEmpresa()
{
    echo todasLasEmpresas();
    $empresa=pedirEmpresa();
    echo "\n-------------------------\n";
    echo "\n~MODIFICAR EMPRESA~\n";
    echo "->Nombre de la empresa // -1 salta el paso\n";
    $nombre =  trim(fgets(STDIN));
    if ($nombre !== "-1") {
        $empresa->setEnombre($nombre);
    }
    echo "->Direccion de la empresa // -1 salta el paso\n";
    $direccion =  trim(fgets(STDIN));
    if ($direccion !== "-1") {
        $empresa->setEdireccion($direccion);
    }
    echo "->Agregar vijes a la empresa // -1 salta el paso ";
    if ($direccion !== "-1") {
        pedirViaje($empresa);
        $empresa->setColeccionViajes();
    }
    echo "->Quitar vijes a la empresa // -1 salta el paso ";
    if ($direccion !== "-1") {
        
        echo listarViajes($empresa->getIdempresa());
        echo "INGRESE EL CODIGO DEL VIAJE QUE DESEA QUITAR: ";
        $codigo =  trim(fgets(STDIN));
        $viaje= new Viaje();
        $viaje-> setCodigo($codigo);
        $coleccion= $viaje->listar("idempresa =".$empresa->getIdempresa()." AND codigo=".$codigo);
        if (count($coleccion)>0){
            $viaje->eliminar();
        }
    }
    $empresa->modificar();
}
function listarViajes($id){
        $viaje=new Viaje();
        $coleccion= $viaje->listar("idempresa =".$id);
        $string= "";
        for ($i=0; $i <count($coleccion) ; $i++) { 
            $mostrar = $coleccion[$i];
            $string.= $mostrar->__toString();
        }
    return $string;
}
function eliminarEmpresa()
{
    echo todasLasEmpresas();
    $empresa = pedirEmpresa();
    if ($empresa != null) {
        $empresa->eliminar();
    }
}

function mostrarEmpresa()
{

    $empresa = pedirEmpresa();
    if ($empresa != null) {
        echo "" . $empresa->__toString();
    }
}

function viajes()
{
    do {
        $opcion = menuViaje();
        switch ($opcion) {
            case 1:
                ingresarViaje();
                break;
            case 2:
                modificarViaje();
                break;
            case 3:
                eliminarViaje();
                break;
            case 4:
                mostrarViaje();
            default:
                break;
        };
    } while ($opcion != 5);
}
function pedirViaje($empresa)
{   
    $viaje = new Viaje();
    listarViajes($empresa->getIdempresa());
    echo "\n-------------------------\n";
    echo "Ingrese el codigo del viaje\n";
    $codigo =  trim(fgets(STDIN));
    $coleccion = $viaje->listar("idviaje=".$codigo." AND idempresa=".$empresa->getIdempresa());
    if (count($coleccion)<=0) {
        $viaje = null;
        echo "\n-------------------------\n";
        echo "\nCODIGO NO EXISTE\n--> ¿Quiere crear el viaje? (s/n)\n";
        $desicion = trim(fgets(STDIN));
        if ($desicion == 's') {
            $viaje = ingresarViaje($empresa);
        }
    }else{
        $viaje->setCodigo($codigo);
    }
    return $viaje;
}
//Menu viaje
function menuViaje()
{
    $corte = false;
    do {
        echo
        "\n-------------------------\n
        \n*MENU DE VIAJES*\n 
        *Ingrese un numero de opcion VALIDO por favor!\n 
        1 CREAR
        2 MODIFICAR 
        3 ELIMINAR
        4 VER INFORMACION
        \n(5)->SALIR\n";
        $opcion =  trim(fgets(STDIN));
        if ($opcion >= 1  && $opcion <= 5) {
            $corte = true;
        } else {
            echo "SOLO OPCIONES CORRECTAS\n";
        }
    } while (!$corte);
    return $opcion;
}
// Funciones viaje

function ingresarViaje($empresa)
{
    $viaje = new Viaje();
    echo "\n-------------------------\n";
    echo "CREAR UN VIAJE";
    echo "->Destino\n";
    $destino =  trim(fgets(STDIN));
    echo "->Cantidad maxima de asientos\n";
    $cantidad =  trim(fgets(STDIN));
    echo "Valor -> $\n";
    $costo =  trim(fgets(STDIN));
    $responsable = pedirResponsable();
    $coleccion = array();
    do {
        echo "-> ¿QUIERE AGREGAR PASAJEROS AL VIAJE? <-";
        $desicion =  trim(fgets(STDIN));
        if ($desicion == 's') {
            verificaPasajero($viaje);
            $viaje->setPasajeros();
        }
    } while ($desicion == 's' && count($coleccion) < $cantidad);

    $viaje->cargar($empresa, $destino, $cantidad, $responsable, $costo);
    if ($viaje->insertar()) {
        echo "*EXITO AL INGRESAR EL VIAJE*\n";
    } else {
        echo "~ERROR AL INGRESAR EL VIAJE\n";
    }
    return $viaje;
}

function modificarViaje($empresa)
{
    $viaje=pedirViaje($empresa);
    if ($viaje != null){
        echo "\n-------------------------\n";
        echo "Ingrese el destino // -1 salta el paso\n";
        $destino =  trim(fgets(STDIN));
        if ($destino !== "-1") {
            $viaje->setDestino($destino);
        }
        echo "Ingrese la cantidad de asientos // -1 salta el paso\n";
        $cantidad =  trim(fgets(STDIN));
        if ($cantidad !== "-1") {
            $viaje->setCantidad($cantidad);
        }
        echo "Ingrese el costo // -1 salta el paso\n";
        $costo =  trim(fgets(STDIN));
        if ($costo !== "-1") {
            $viaje->setCosto($costo);
        }
        echo "-> ¿QUIERE AGREGAR RESPONSABLE AL VIAJE? <-";
        $desicion =  trim(fgets(STDIN));
        if ($desicion == "s") {
            $responsable = pedirResponsable();
            $viaje->setResponsable($responsable);
        }
        echo "-> ¿QUIERE QUITAR RESPONSABLE AL VIAJE? <-";
        $desicion =  trim(fgets(STDIN));
        if ($desicion == "s") {
            $responsable = pedirResponsable($viaje);
            $responsable->eliminar();
        }

        do {
            echo "-> ¿QUIERE AGREGAR PASAJEROS AL VIAJE? <-";
            $desicion =  trim(fgets(STDIN));
            if ($desicion == 's') {
                $nuevoPasajero = verificaPasajero($viaje);
                $viaje->setPasajeros();
            }
        } while ($desicion == 's');
        do {
            echo "-> ¿QUIERE QUITAR PASAJEROS AL VIAJE? <-";
            $desicion =  trim(fgets(STDIN));
            if ($desicion == 's') {
                $nuevoPasajero = verificaPasajero($viaje);
                $nuevoPasajero ->eliminar();
            }
        } while ($desicion == 's');
        $viaje->modificar();
    }
}

function eliminarViaje($empresa)
{
    echo "\n-------------------------\n";
    $viaje = pedirViaje($empresa);
    if ($viaje != null) {
        $viaje->eliminar();
    }
}

function mostrarViaje($empresa)
{
    echo "\n-------------------------\n";
    $viaje = pedirViaje($empresa);
    if ($viaje != null) {
        echo "" . $viaje->__toString();
    }
}

function responsables()
{
    do {
        $opcion = menuResponsable();
        switch ($opcion) {
            case 1:
                ingresarResponsable();
                break;
            case 2:
                modificarResponsable();
                break;
            case 3:
                eliminarResponsable();
                break;
            case 4:
                mostrarResponsable();
            default:
                break;
        };
    } while ($opcion != 5);
}
//Menu responsable
function menuResponsable()
{
    $corte = false;
    do {
        echo
        "\n-------------------------\n
        \n*MENU DE RESPONSABLE*\n 
        *Ingrese un numero de opcion VALIDO por favor!\n 
        1 CREAR
        2 MODIFICAR 
        3 ELIMINAR
        4 VER INFORMACION
        \n(5)->SALIR\n";
        $opcion =  trim(fgets(STDIN));
        if ($opcion >= 1  && $opcion <= 5) {
            $corte = true;
        } else {
            echo "SOLO OPCIONES CORRECTAS\n";
        }
    } while (!$corte);
    return $opcion;
}
// Funciones Responsable

function ingresarResponsable()
{
    echo "\n-------------------------\n";
    $responsable = new ResponsableV();
    echo "->n° licencia: \n";
    $licencia =  trim(fgets(STDIN));
    echo "-> Nombre: \n";
    $nombre =  trim(fgets(STDIN));
    echo "-> Apellido\n";
    $apellido =  trim(fgets(STDIN));
    $responsable->cargar($licencia, $nombre, $apellido);
    if ($responsable->insertar()) {
        echo "*EXITO AL INGRESAR EL RESPONSABLE*\n";
        echo "\n-------------------------\n";
    } else {
        echo "*ERROR AL INGRESAR EL RESPONSABLE*\n";
        echo "\n-------------------------\n";
    }
}

function modificarResponsable()
{
    $responsable = pedirResponsable();

    if ($responsable != null) {
        echo "\n-------------------------\n";
        echo "Ingrese la licencia // -1 salta el paso\n\n";
        $licencia =  trim(fgets(STDIN));
        echo "Ingrese el nombre // -1 salta el paso\n\n";
        $nombre =  trim(fgets(STDIN));
        echo "Ingrese el apellido // -1 salta el paso\n\n";
        $apellido =  trim(fgets(STDIN));
        if ($licencia !== "-1") {
            $responsable->setLicencia($licencia);
        }
        if ($nombre !== "-1") {
            $responsable->setNombre($nombre);
        }
        if ($apellido !== "-1") {
            $responsable->setApellido($apellido);
        }

        $responsable->modificar();
    }
}

function eliminarResponsable()
{

    $responsable = pedirResponsable();
    if ($responsable != null) {
        $responsable->eliminar();
    }
}

function mostrarResponsable()
{
    echo "\n-------------------------\n";
    $responsable = pedirResponsable();
    if ($responsable != null) {
        echo "" . $responsable->__toString();
    }
}


function pasajeros($viaje)
{
    do {
        $opcion = menuPasajero();
        switch ($opcion) {
            case 1:
                ingresarPasajero($viaje, $dni);
                break;
            case 2:
                modificarPasajero($viaje);
                break;
            case 3:
                eliminarPasajero($viaje);
                break;
            case 4:
                mostrarPasajero($viaje);
            default:
                break;
        };
    } while ($opcion != 5);
}
//Menu Pasajero
function menuPasajero()
{
    $corte = false;
    do {
        echo "\n-------------------------\n";
        echo
        "\n*MENU DE PASAJEROS*\n 
        *Ingrese un numero de opcion VALIDO por favor!\n 
        1 CREAR
        2 MODIFICAR 
        3 ELIMINAR
        4 VER INFORMACION
        \n(5)->SALIR\n";
        $opcion =  trim(fgets(STDIN));
        if ($opcion >= 1  && $opcion <= 5) {
            $corte = true;
        } else {
            echo "SOLO OPCIONES CORRECTAS\n";
        }
    } while (!$corte);
    return $opcion;
}

//devuelve objtos pasajeros NULL si el pasajero ya esta cargado ene ese viaje
//y ObjPasajero si se creo el pasajero 
function verificaPasajero($viaje)
{
        echo "\n-------------------------\n";
        echo "Ingrese el DNI del pasajero\n";
        $dni =  trim(fgets(STDIN));
        $pasajero = new Pasajero();
        $estaEnElViaje = $pasajero->estaEnElViaje($dni, $viaje);
        if (!$estaEnElViaje) {
            $pasajero = null;
            echo "\nVerificamos que no exite el pasajero\n";
            echo "--> ¿DESEA CONTINUAR?  (s/n)";
            $desicion =  trim(fgets(STDIN));
            if ($desicion == 's') {
                $pasajero = ingresarPasajero($viaje, $dni);
            }
        } else {
            echo "\nEl pasajero se encuentra en vieje";
            $pasajero->setDni($dni);
        }
    return $pasajero;
}
//crea y carga el pasajero a la base de datos
function ingresarPasajero($viaje, $dni)
{
    $pasajero = new Pasajero();
    echo "\n->Nombre\n";
    $nombre =  trim(fgets(STDIN));
    echo "->Apellido\n";
    $apellido =  trim(fgets(STDIN));
    echo "->Telefono\n";
    $telefono =  trim(fgets(STDIN));
    $pasajero->cargar($nombre, $apellido, $dni, $telefono, $viaje);
    if ($pasajero->insertar()) {
        echo "*EXITO AL INGRESAR EL PASAJERO\n";
    } else {
        echo "~ERROR AL INGRESAR EL PASAJERO\n";
    }
    return $pasajero;
}
function pedirDni($objPasajero)
{
    echo "Ingrese el DNI del pasajero\n";
    $dni =  trim(fgets(STDIN));
    $existe = $objPasajero->Buscar($dni);
    if ($existe) {
        $objPasajero->setDni($dni);
    } else {
        $objPasajero = null;
    }
    return $objPasajero;
}
function modificarPasajero()
{
    $objPasajero = new Pasajero();
    $objPasajero = pedirDni($objPasajero);
    if ($objPasajero != null) {
        echo "\n-------------------------\n";
        echo "Ingrese el nombre // -1 salta el paso\n";
        $nombre =  trim(fgets(STDIN));
        echo "Ingrese el apellido // -1 salta el paso\n";
        $apellido =  trim(fgets(STDIN));
        echo "Ingrese el telefono // -1 salta el paso\n";
        $telefono =  trim(fgets(STDIN));
        if ($nombre !== "-1") {
            $objPasajero->setNombre($nombre);
        }
        if ($apellido !== "-1") {
            $objPasajero->setApellido($apellido);
        }
        if ($telefono !== "-1") {
            $objPasajero->setTelefono($telefono);
        }
        $objPasajero->modificar();
    }
}

function eliminarPasajero()
{
    $objPasajero = new Pasajero();
    $objPasajero = pedirDni($objPasajero);
    echo "\n-------------------------\n";
    if ($objPasajero != null) {
        $objPasajero->eliminar();
    }
}

function mostrarPasajero()
{
    $objPasajero = new Pasajero();
    $objPasajero = pedirDni($objPasajero);
    echo "\n-------------------------\n";
    if ($objPasajero != null) {
        echo "" .  $objPasajero->__toString();
    }
}



//auxiliares





function pedirResponsable()
{
        
        echo "\n-------------------------\n";
        echo "Ingrese el numero del responsable: \n";
        $codigo =  trim(fgets(STDIN));
        $responsable = new ResponsableV();
        $band = $responsable->Buscar($codigo);
        
        if (!$band) {
            $responsable = null;
            echo "\nCODIGO NO EXISTE\n--> ¿Quiere agregar al RESPONSABLE? (s/n)\n";
            $desicion = trim(fgets(STDIN));
            if ($desicion == 's') {
                $responsable = ingresarResponsable();
            }
        }else{
            $responsable->getNumero($codigo);
        }

    return $responsable;
}
//Devuelve un pasajero que existe


/* //Devuelve un DNI inexistente
function comprobarDNIPasajero()
{
    $band = false;
    do {
        echo "\n-------------------------\n";
        echo "Ingrese el DNI del pasajero\n";
        $dni =  trim(fgets(STDIN));
        $pasajero = new Pasajero();
        $band = $pasajero->Buscar($dni);
        
    } while (!$band);
    return $dni;
}
 */
/* function agregarPasajeroaViaje($viaje)
{
    $dniPasajero = verificaPasajero($viaje);
    if ($dniPasajero != null && $viaje != null) {
        if (!$viaje->esPasajero($dniPasajero)) {
            $importe = $viaje->venderPasaje($dniPasajero);
            if ($importe == null) {
                echo "El viaje esta lleno --> NO SE PUEDEN VENDER MAS PASAJES\n";
            } else {
                echo "PASAJER VENDIDO monto a pagar: $" . $importe . "\n";
            }
        } else {
            echo "EL PASAJERO YA SE ENCUENTRA EN ESTE VIAJE\n";
        }
    }
    return $pasajero;
}
 */
/* function quitarPasajeroaViaje($viaje)
{
    $pasajero = pedirPasajero();
    if ($pasajero != null && $viaje != null) {
        $importe = $viaje->devolverPasaje($pasajero);
        if ($importe == null) {
            echo "EL PASAJERO NO SE ENCUENTRA EN EL VIAJE\n";
        } else {
            echo "EL MONTO A PAGAR POR EL PASAJERO-> $" . $importe . "\n";
        }
    }
}
 */
function agregarViaje($empresa)
{
    $viaje = pedirViaje($empresa);
    if ($viaje != null && $empresa != null) {
        if ($empresa->agregarViaje($viaje)) {
            echo "SE AGREGO UN VIAJE A LA EMPRESA\n";
        } else {
            echo "ESTE VIAJE YA SE ENCUENTRA EN LA EMPRESA\n";
        }
    }
    return $viaje;
}

/* function quitarViaje($empresa)
{
    $viaje = pedirViaje();
    if ($viaje != null && $empresa != null) {
        if ($empresa->quitarViaje($viaje)) {
            echo "SE QUITO EL VIAJE DE LA EMPRESA\n";
        } else {
            echo "EL VIAJE QUE QUIERE QUITAR NO SE ENCUETRA EN LA EMPRESA\n";
        }
    }
} */
/* function agregarResponsable($viaje)
{
    $responsable = pedirResponsable();
    if ($viaje != null && $responsable != null) {
        if ($viaje->agregarResponsable($responsable)) {
            echo "SE AGREGO UN RESPONSABLE AL VIAJE\n";
        } else {
            echo "ESTE RESPONSABLE YA SE ENCUENTRA EN EL VIAJE\n";
        }
    }
}

function quitarResponsable($viaje)
{
    if ($viaje != null) {
        if ($viaje->quitarResponsable()) {
            echo "EL RESPONSABLE SE HA QUITADO DEL VIAJE\n";
        } else {
            echo "NO HAY RESPONSABLE PARA QUITAR\n";
        }
    }
} */
