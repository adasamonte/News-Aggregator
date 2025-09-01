<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Connect to database
include_once("connect.php");

// Get the request method
$request_method = $_SERVER["REQUEST_METHOD"];

switch ($request_method) {
    case "POST":
        if ($_GET["action"] == "register") {
            registerUser();
        } elseif ($_GET["action"] == "login") {
            loginUser();
        }
        break;

    case "GET":
        if ($_GET["action"] == "getUsers") {
            getUsers();
        }
        break;

    default:
        echo json_encode(["message" => "Invalid Request"]);
}

function registerUser()
{
    global $conn;
    $data = json_decode(file_get_contents("php://input"), true);

    $firstname = $data["firstname"];
    $lastname = $data["lastname"];
    $email = $data["email"];
    $gender = $data["gender"];
    $birthday = $data["birthday"];
    $password = password_hash($data["password"], PASSWORD_DEFAULT);

    $query = "INSERT INTO users (firstname, lastname, email, gender, birthday, password) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssssss", $firstname, $lastname, $email, $gender, $birthday, $password);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(["message" => "Registration successful"]);
    } else {
        echo json_encode(["message" => "Error registering user: " . mysqli_error($conn)]);
    }

    mysqli_stmt_close($stmt);
}

function loginUser()
{
    global $conn;
    $data = json_decode(file_get_contents("php://input"), true);
    $email = $data["email"];
    $password = $data["password"];

    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user["password"])) {
        echo json_encode(["message" => "Login successful", "user" => $user]);
    } else {
        echo json_encode(["message" => "Invalid email or password"]);
    }

    mysqli_stmt_close($stmt);
}

function getUsers()
{
    global $conn;
    $query = "SELECT id, firstname, lastname, email FROM users";
    $result = mysqli_query($conn, $query);
    $users = mysqli_fetch_all($result, MYSQLI_ASSOC);

    echo json_encode($users);
}
?>
