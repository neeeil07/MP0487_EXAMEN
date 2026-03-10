<?php
session_start();

class UserController
{
    private $conn;
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "mp0487_knockoutzone";

    public function __construct()
    {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function login(): void
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception("Método no permitido");
            }

            $username = $_POST["username"] ?? '';
            $password = $_POST["password"] ?? '';

            if (empty($username) || empty($password)) {
                throw new Exception("Username1 and password are required");
            }

            $stmt = $this->conn->prepare("SELECT id, name FROM users WHERE name=? AND password=?");
            $stmt->bind_param("ss", $username, $password);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $row = $result->fetch_assoc();
                $_SESSION["logged"] = true;
                $_SESSION["user"] = $username;
                $_SESSION["user_name"] = $username;
                $_SESSION["user_id"] = $row['id'];
                header("Location: ../view/profile.php");
                exit();
            } else {
                throw new Exception("Username2 or password incorrect");
            }
        } catch (Exception $e) {
            $_SESSION["error"] = $e->getMessage();
            header("Location: ../view/vlogin.php");
            exit();
        }
    }

    public function logout(): void
    {
        session_destroy();
        header("Location: ../view/index.html");
        exit();
    }

    public function register(): void
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception("Método no permitido");
            }

            $username = $_POST["user"] ?? '';
            $password = $_POST["password"] ?? '';
            $email = $_POST["email"] ?? '';

            if (empty($username) || empty($password) || empty($email)) {
                throw new Exception("All fields are required");
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Invalid email format");
            }

            $stmt = $this->conn->prepare("SELECT * FROM users WHERE name=? OR email=?");
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                throw new Exception("User or email already exists");
            }

            $nombre_final = null;

            if (isset($_FILES["pfp"]) && $_FILES["pfp"]["error"] === UPLOAD_ERR_OK) {
                $nombre_img = $_FILES["pfp"]["name"];
                $tipo = $_FILES["pfp"]["type"];
                $tamano = $_FILES["pfp"]["size"];

                if (!empty($nombre_img) && $tamano <= 2000000) {
                    if ($tipo == "image/jpeg" || $tipo == "image/jpg" || $tipo == "image/png") {
                        $directorio = $_SERVER['DOCUMENT_ROOT'] . "/knockoutzone/resources/profiles/";
                        if (!file_exists($directorio)) {
                            mkdir($directorio, 0777, true);
                        }

                        $nombre_final = time() . "_" . basename($nombre_img);
                        $ruta_guardada = $directorio . $nombre_final;
                        move_uploaded_file($_FILES["pfp"]["tmp_name"], $ruta_guardada);
                    }
                }
            }

            $stmt = $this->conn->prepare("INSERT INTO users (name, password, email, path_pfp) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $password, $email, $nombre_final);

            if ($stmt->execute()) {
                $_SESSION["success"] = "User registered successfully";
                header("Location: ../view/vlogin.php");
                exit();
            } else {
                throw new Exception("Error registering user");
            }
        } catch (Exception $e) {
            $_SESSION["error"] = $e->getMessage();
            header("Location: ../view/vregister.php");
            exit();
        }
    }


    public function registerAdmin(): void
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception("Método no permitido");
            }

            $username = $_POST["user"] ?? '';
            $password = $_POST["password"] ?? '';
            $email = $_POST["email"] ?? '';

            if (empty($username) || empty($password) || empty($email)) {
                throw new Exception("All fields are required");
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Invalid email format");
            }

            $stmt = $this->conn->prepare("SELECT * FROM users WHERE name=? OR email=?");
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                throw new Exception("User or email already exists");
            }

            $nombre_final = null;

            if (isset($_FILES["pfp"]) && $_FILES["pfp"]["error"] === UPLOAD_ERR_OK) {
                $nombre_img = $_FILES["pfp"]["name"];
                $tipo = $_FILES["pfp"]["type"];
                $tamano = $_FILES["pfp"]["size"];

                if (!empty($nombre_img) && $tamano <= 2000000) {
                    if ($tipo == "image/jpeg" || $tipo == "image/jpg" || $tipo == "image/png") {
                        $directorio = $_SERVER['DOCUMENT_ROOT'] . "/knockoutzone/resources/profiles/";
                        if (!file_exists($directorio)) {
                            mkdir($directorio, 0777, true);
                        }

                        $nombre_final = time() . "_" . basename($nombre_img);
                        $ruta_guardada = $directorio . $nombre_final;
                        move_uploaded_file($_FILES["pfp"]["tmp_name"], $ruta_guardada);
                    }
                }
            }

            $stmt = $this->conn->prepare("INSERT INTO users (name, password, email, path_pfp) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $password, $email, $nombre_final);

            if ($stmt->execute()) {
                $_SESSION["success"] = "Admin registered successfully";
                header("Location: ../view/vlogin.php");
                exit();
            } else {
                throw new Exception("Error registering admin");
            }
        } catch (Exception $e) {
            $_SESSION["error"] = $e->getMessage();
            header("Location: ../view/vregister.php");
            exit();
        }
    }

    public function subirImagenPerfil(): void
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception("Método no permitido");
            }

            if (!isset($_SESSION["user"])) {
                throw new Exception("Usuario no autenticado");
            }

            $user = $_POST['name'] ?? '';
            
            if (empty($user)) {
                throw new Exception("Usuario no especificado");
            }

            if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] === UPLOAD_ERR_NO_FILE) {
                throw new Exception("No se ha seleccionado ninguna imagen");
            }

            $nombre_img = $_FILES['imagen']['name'];
            $tipo = $_FILES['imagen']['type'];
            $tamano = $_FILES['imagen']['size'];

            if (empty($nombre_img) || $tamano > 2000000) {
                throw new Exception("Empty image or too big.");
            }

            if (!in_array($tipo, ["image/jpeg", "image/jpg", "image/png"])) {
                throw new Exception("Format not allowed. Only JPG or PNG.");
            }

            $directorio = $_SERVER['DOCUMENT_ROOT'] . "/knockoutzone/resources/profiles/";
            if (!file_exists($directorio)) {
                mkdir($directorio, 0777, true);
            }

            $nuevo_nombre = time() . "_" . basename($nombre_img);
            $ruta_guardada = $directorio . $nuevo_nombre;

            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_guardada)) {
                $stmt = $this->conn->prepare("UPDATE users SET path_pfp = ? WHERE name = ?");
                $stmt->bind_param("ss", $nuevo_nombre, $user);
                $stmt->execute();

                $_SESSION["success"] = "Image uploaded successfully.";
            } else {
                throw new Exception("Error while saving image.");
            }

            header("Location: ../view/profile.php");
            exit();
        } catch (Exception $e) {
            $_SESSION["error"] = $e->getMessage();
            header("Location: ../view/profile.php");
            exit();
        }
    }

    public function updateUser(): void
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception("Método no permitido");
            }

            if (!isset($_SESSION["user"])) {
                throw new Exception("Usuario no autenticado");
            }

            $current_user = $_SESSION["user"];
            $new_name = $_POST["name"] ?? '';
            $new_email = $_POST["email"] ?? '';

            if (empty($new_name) || empty($new_email)) {
                throw new Exception("All fields are required");
            }

            if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Invalid email format");
            }

            // Check if new email or name already exists (excluding current user)
            $stmt = $this->conn->prepare("SELECT id FROM users WHERE (name=? OR email=?) AND name != ?");
            $stmt->bind_param("sss", $new_name, $new_email, $current_user);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                throw new Exception("Username or email already exists");
            }

            $stmt = $this->conn->prepare("UPDATE users SET name=?, email=? WHERE name=?");
            $stmt->bind_param("sss", $new_name, $new_email, $current_user);
            
            if ($stmt->execute()) {
                $_SESSION["user"] = $new_name;
                $_SESSION["user_name"] = $new_name;
                $_SESSION["success"] = "User information updated successfully";
            } else {
                throw new Exception("Error updating user information");
            }

            header("Location: ../view/profile.php");
            exit();
        } catch (Exception $e) {
            $_SESSION["error"] = $e->getMessage();
            header("Location: ../view/profile.php");
            exit();
        }
    }

    public function updatePassword(): void
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception("Método no permitido");
            }

            if (!isset($_SESSION["user"])) {
                throw new Exception("Usuario no autenticado");
            }

            $current_user = $_SESSION["user"];
            $current_password = $_POST["current_password"] ?? '';
            $new_password = $_POST["new_password"] ?? '';

            if (empty($current_password) || empty($new_password)) {
                throw new Exception("All fields are required");
            }

            // Verify current password
            $stmt = $this->conn->prepare("SELECT id FROM users WHERE name=? AND password=?");
            $stmt->bind_param("ss", $current_user, $current_password);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 0) {
                throw new Exception("Current password is incorrect");
            }

            // Update password
            $stmt = $this->conn->prepare("UPDATE users SET password=? WHERE name=?");
            $stmt->bind_param("ss", $new_password, $current_user);

            if ($stmt->execute()) {
                $_SESSION["success"] = "Password updated successfully";
            } else {
                throw new Exception("Error updating password");
            }

            header("Location: ../view/profile.php");
            exit();
        } catch (Exception $e) {
            $_SESSION["error"] = $e->getMessage();
            header("Location: ../view/profile.php");
            exit();
        }
    }

    public function deleteUser(): void
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception("Método no permitido");
            }

            if (!isset($_SESSION["user"])) {
                throw new Exception("Usuario no autenticado");
            }

            $current_user = $_SESSION["user"];

            $stmt = $this->conn->prepare("DELETE FROM users WHERE name=?");
            $stmt->bind_param("s", $current_user);

            if ($stmt->execute()) {
                session_destroy();
                $_SESSION["success"] = "Account deleted successfully";
                header("Location: ../view/index.html");
                exit();
            } else {
                throw new Exception("Error deleting account");
            }
        } catch (Exception $e) {
            $_SESSION["error"] = $e->getMessage();
            header("Location: ../view/profile.php");
            exit();
        }
    }

    public function __destruct()
    {
        if (isset($this->conn)) {
            $this->conn->close();
        }
    }
}

// Get action from GET parameter
$action = $_GET['action'] ?? '';

// Create controller instance and call appropriate method
$controller = new UserController();

switch ($action) {
    case 'login':
        $controller->login();
        break;
    case 'logout':
        $controller->logout();
        break;
    case 'register':
        $controller->register();
        break;
    case 'registerAdmin':
        $controller->registerAdmin();
        break;
    case 'uploadImage':
        $controller->subirImagenPerfil();
        break;
    case 'updateUser':
        $controller->updateUser();
        break;
    case 'updatePassword':
        $controller->updatePassword();
        break;
    case 'deleteUser':
        $controller->deleteUser();
        break;
    default:
        $_SESSION['error'] = "Acción no válida";
        header("Location: ../view/profile.php");
        exit();
}
?>
