<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Connect to database
include_once("connect.php");

// Get the request method
$request_method = $_SERVER["REQUEST_METHOD"];

// Retrieve the action parameter (supports both GET and POST)
$action = isset($_GET["action"]) ? $_GET["action"] : (isset($_POST["action"]) ? $_POST["action"] : null);

// Debugging: Log the received action
if ($action === null) {
    echo json_encode(["message" => "Missing action parameter"]);
    exit;
}

switch ($request_method) {
    case "POST":
        // Get raw POST data
        $input = json_decode(file_get_contents("php://input"), true);
        
        if ($action == "register") {
            registerUser($input);
        } elseif ($action == "login") {
            loginUser($input);
        } else {
            echo json_encode(["message" => "Invalid action"]);
        }
        break;

    case "GET":
        if ($action == "getUsers") {
            getUsers();
        } else {
            echo json_encode(["message" => "Invalid action"]);
        }
        break;

    default:
        echo json_encode(["message" => "Invalid request method"]);
        break;
}

function registerUser($data) {
    global $conn;
    
    if (!isset($data['username'], $data['firstname'], $data['lastname'], $data['email'], $data['password'], $data['gender'])) {
        echo json_encode(["message" => "Missing required fields"]);
        return;
    }
    
    $username = $data['username'];
    $firstname = $data['firstname'];
    $lastname = $data['lastname'];
    $email = $data['email'];
    $password = password_hash($data['password'], PASSWORD_BCRYPT);
    $gender = $data['gender'];
    
    $query = "INSERT INTO users (username, firstname, lastname, email, password, gender) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssss", $username, $firstname, $lastname, $email, $password, $gender);
    
    if ($stmt->execute()) {
        echo json_encode(["message" => "User registered successfully"]);
    } else {
        echo json_encode(["message" => "Error registering user"]);
    }
}

function loginUser($data) {
    global $conn;
    
    if (!isset($data['email'], $data['password'])) {
        echo json_encode(["message" => "Missing email or password"]);
        return;
    }
    
    $email = $data['email'];
    $password = $data['password'];
    
    $query = "SELECT id, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            echo json_encode(["message" => "Login successful", "user_id" => $row['id']]);
        } else {
            echo json_encode(["message" => "Invalid credentials"]);
        }
    } else {
        echo json_encode(["message" => "User not found"]);
    }
}

function getUsers() {
    global $conn;
    $query = "SELECT id, username, firstname, lastname, email, gender FROM users";
    $result = $conn->query($query);
    
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    
    echo json_encode($users);
}
