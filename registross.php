<?php  
// Datos de conexión  
$servidor = "localhost";   
$usuario = "root";  
$contraseña = "";     
$base_datos = "pagina_login_registro";  

// Crear conexión  
$conn = mysqli_connect($servidor, $usuario, $contraseña, $base_datos);  

// Comprobar conexión  
if (!$conn) {  
    die("Conexión fallida: " . mysqli_connect_error());  
}  

// Obtener datos del formulario de forma segura  
$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';  
$apellido = isset($_POST['apellido']) ? trim($_POST['apellido']) : '';  
$correo = isset($_POST['correo']) ? trim($_POST['correo']) : '';  
$contraseña = isset($_POST['contraseña']) ? trim($_POST['contraseña']) : '';  

if (!empty($nombre) && !empty($apellido) && !empty($correo) && !empty($contraseña)) {  
    // Validar si el correo ya existe en la base de datos  
    $stmt = $conn->prepare("SELECT * FROM usuario WHERE correo = ?");  
    $stmt->bind_param("s", $correo);  
    $stmt->execute();  
    $result = $stmt->get_result();  

    if ($result->num_rows > 0) {  
echo "El correo ya está en uso. <a href='registro.html'>Intenta nuevamente</a>";  
    } 
    else {  
        // Hashear la contraseña antes de guardarla  
        $contraseña_hashed = password_hash($contraseña, PASSWORD_DEFAULT);  
    
        // Insertar nuevo usuario  
        $stmt = $conn->prepare("INSERT INTO usuario (nombre, apellido, correo, contraseña) VALUES (?, ?, ?, ?)");  
        $stmt->bind_param("ssss", $nombre, $apellido, $correo, $contraseña_hashed);  

        if ($stmt->execute()) {   
            header("Location: index.html");  
            exit();   
        } else {  
            echo "Error:" . $stmt->error;  
        }  
    }  

    // Cerrar declaración y conexión  
    $stmt->close();  
} else {  
    echo "Todos los campos son obligatorios. <a href='registro.html'>Intenta nuevamente</a>.";  
}  
$conn->close();  

