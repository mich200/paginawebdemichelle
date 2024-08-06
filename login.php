<?php  
// Datos de conexión a la base de datos  
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

// Procesar el formulario al enviar  
if ($_SERVER["REQUEST_METHOD"] == "POST") {  
    // Sanitizar y recibir datos del formulario  
    $usuario_input = htmlspecialchars(trim($_POST['usuario']));  
    $contraseña_input = htmlspecialchars(trim($_POST['contraseña']));  

    // Verificar usuario y contraseña  
    $stmt = $conn->prepare("SELECT contraseña FROM usuario WHERE correo = ?");  
    $stmt->bind_param("s", $usuario_input);  
    $stmt->execute();  
    $stmt->bind_result($contraseña);  

    if ($stmt->fetch()) {  
        // Verificar la contraseña  
        if (password_verify($contraseña_input, $contraseña)) {  
            echo "<h2>Login Exitoso!</h2>";   
            header("Location: index.html");  
            exit();  
        } else {  
            echo "<h2>Contraseña incorrecta!</h2>";  
        }  
    } else {  
        echo "<h2>Usuario no encontrado!</h2>";  
    }  

    // Cerrar declaración  
    $stmt->close();  
}  

// Cerrar conexión  
$conn->close();  