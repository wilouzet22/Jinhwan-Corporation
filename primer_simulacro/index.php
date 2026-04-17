<?php
    // Define una constante global llamada BASE_URL para usarla en todo el proyecto
    // Esto facilita la creacion de enlaces o referencias a recursos publicos (css, img, js)
    define('BASE_URL', 'http://localhost/mvc1101/');
    
    // Se definen los valores por defecto iniciales para el controlador y la accion
    // En caso de que el usuario entre por defecto a la raiz, sus variables seran 'paginas' e 'inicio'
    $getcontrolador = "paginas";
    $getaccion = "inicio";

    // Verifica si la variable especial 'url' existe en la peticion GET 
    // (Esta variable es generada por el archivo .htaccess a partir de la ruta "bonita" que el usuario visitó)
    if(isset($_GET['url'])){
        // trim: Remueve las barras diagonales (/) extras ubicadas al inicio y al final de la URL
        $url = trim ($_GET['url'], '/');
        
        // filter_var: Filtra y limpia elementos inseguros en la URL, previniendo ataques comunes de inyeccion
        $url = filter_var($url, FILTER_SANITIZE_URL);
        
        // explode: Divide el texto de la URL en partes, cortando donde haya un '/' y asignandoselos a una array (arreglo)
        // Ejemplo: Si el usuario busca "mvc1101/usuarios/editar/5", el array $url tendra: [0]=>'usuarios', [1]=>'editar', [2]=>'5'
        $url = explode('/', $url);
        
        // Si existe el primer index del array (Posicion 0), este determinará qué CONTROlADOR llamaremos
        if(isset($url[0])){
            $getcontrolador = $url[0];
        }
        
        // Si existe el segundo index (Posicion 1), este indica que ACCIÓN/MÉTODO de dicho controlador ejecutaremos
        if(isset($url[1])){
            $getaccion = $url[1];
        }
        
        // Si existe el tercer index (Posicion 2), sera usado normalmente como un ID (para buscar un registro por su ID de BD)
        // NOTA PARA EL USUARIO: Se corrigió un error en tu código original que decía "isset(isset(...))" lo que generaba un error de programa fatal. 
        if(isset($url[2])){
            $_GET['id'] = $url[2];
        }
        
    }
    
    // Al final, se carga el archivo principal/plantilla que contiene la base maestra de la web (el layout principal)
    // Se le añade el correspondiente ";" al final de la instrucción (faltante en el archivo original)
    include_once ("vistas/plantilla.php");
?>
