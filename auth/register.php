<?php
include_once("../connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    error_log("Form submitted: " . print_r($_POST, true)); // Log the form data
    $username = $_POST["username"];
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $email = $_POST["email"];
    $gender = $_POST["gender"];
    $birthday = $_POST["birthday"];
    $password = $_POST["password"];

    // Debugging: Check the values
    error_log("Username: $username, Firstname: $firstname, Lastname: $lastname, Email: $email, Gender: $gender, Birthday: $birthday, Password: $password");

    // Handle file upload
    $profilePicture = 'default.png'; // Default picture
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
        $fileName = $_FILES['profile_picture']['name'];
        $fileSize = $_FILES['profile_picture']['size'];
        $fileType = $_FILES['profile_picture']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Specify the allowed file types
        $allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg');
        if (in_array($fileExtension, $allowedfileExtensions)) {
            // Set the new file name and path
            $profilePicture = md5(time() . $fileName) . '.' . $fileExtension; // Unique file name
            $uploadFileDir = '../uploads/';
            $dest_path = $uploadFileDir . $profilePicture;

            // Move the file to the uploads directory
            if (!move_uploaded_file($fileTmpPath, $dest_path)) {
                echo "Error uploading the file.";
            }
        }
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $checkEmailQuery = "SELECT * FROM users WHERE email = ?";
    $checkStmt = $conn->prepare($checkEmailQuery);
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        echo "Email already exists. Please use a different email.";
    } else {
        // Prepare the SQL statement to insert the user
        $query = "INSERT INTO users (username, firstname, lastname, email, gender, birthday, password, profile_picture) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ssssssss", $username, $firstname, $lastname, $email, $gender, $birthday, $hashedPassword, $profilePicture);

        // Execute the statement and check for success
        if (mysqli_stmt_execute($stmt)) {
            header("Location: login.php"); // Redirect to login page after successful registration
            exit();
        } else {
            echo json_encode(["message" => "Error registering user: " . mysqli_error($conn)]);
        }

        mysqli_stmt_close($stmt);
    }
}

mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | News For You</title>
    <link rel="icon" href="../uploads/assets/logo-ico-tab.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/register.css">
    <style>
        .profile-picture-container {
            position: relative;
            width: 80px; /* Reduced width */
            height: 80px; /* Reduced height */
            border-radius: 50%; /* Circular shape */
            overflow: hidden; /* Ensure the image fits within the circle */
            margin: 0 auto 20px; /* Center the image */
            border: 2px solid #0d6efd; /* Initial border color */
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: transparent; /* Initial background */
            transition: background-color 0.3s; /* Smooth transition for hover effect */
            cursor: pointer; /* Change cursor to pointer */
        }

        .profile-picture-container:hover {
            background-color: white; /* Fill color on hover */
        }

        .camera-icon {
            font-size: 24px; /* Icon size */
            color: #0d6efd; /* Icon color */
            transition: color 0.3s; /* Smooth transition for icon color */
        }

        .profile-picture {
            width: 100%; /* Set the width to 100% for responsive resizing */
            height: 100%; /* Set the height to 100% */
            object-fit: cover; /* Maintain aspect ratio and cover the area */
            display: none; /* Hide the image initially */
            position: absolute; /* Position the image absolutely within the container */
            top: 0; /* Align to the top */
            left: 0; /* Align to the left */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <div class="back-arrow" onclick="window.history.back();">
                <i class="fas fa-arrow-left"></i>
            </div>
            <h1>Register</h1>
            <form method="POST" action="register.php" enctype="multipart/form-data">
                <div class="profile-picture-container" id="profilePreview" onclick="document.getElementById('fileInput').click();">
                    <img src="" class="profile-picture" id="uploadedImage" alt="Profile Picture"> <!-- Initially hidden -->
                    <i class="fas fa-camera camera-icon"></i> <!-- Camera icon as placeholder -->
                </div>
                <input type="file" name="profile_picture" accept="image/*" onchange="previewImage(event)" required style="display: none;" id="fileInput">
                
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                    <label for="username">Username</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="firstname" name="firstname" placeholder="First Name" required>
                    <label for="firstname">First Name</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name" required>
                    <label for="lastname">Last Name</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                    <label for="email">Email</label>
                </div>

                <div class="form-floating mb-3">
                    <select class="form-select" name="gender" required>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                    <label for="gender">Gender</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="date" class="form-control" id="birthday" name="birthday" required>
                    <label for="birthday">Birthday</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    <label for="password">Password</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                    <label for="confirm_password">Confirm Password</label>
                </div>

                <div id="error-message" class="text-danger mb-3" style="display: none;"></div>

                <button type="submit" class="btn btn-primary">Register</button>
            </form>

            <div class="back-to-login">
                <p>Already have an account? <a href="login.php">Login Here</a></p>
            </div>
        </div>
    </div>

    <footer class="footer">
        <p>
            Powered by <img src="../uploads/assets/mapua_Icowhite.png" alt="Mapua" class="footer-logo"> Mapúa University and 
            <img src="../uploads/assets/FBTO_Icowhite.png" alt="Future Business Teachers' Organization" class="footer-logo"> Future Business Teachers' Organization.
        </p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                const uploadedImage = document.getElementById('uploadedImage');
                uploadedImage.src = e.target.result; // Set the uploaded image source
                uploadedImage.style.display = 'block'; // Show the uploaded image
                document.querySelector('.camera-icon').style.display = 'none'; // Hide the camera icon
            };
            reader.readAsDataURL(file);
        }

        document.querySelector('form').addEventListener('submit', function(event) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const errorMessageDiv = document.getElementById('error-message');
            errorMessageDiv.style.display = 'none'; // Hide previous error messages
            errorMessageDiv.innerHTML = ''; // Clear previous error messages

            // Validate password length
            if (password.length < 8) {
                errorMessageDiv.innerHTML += 'Password must be at least 8 characters long.<br>';
            }

            // Validate password match
            if (password !== confirmPassword) {
                errorMessageDiv.innerHTML += 'Passwords do not match.<br>';
            }

            // If there are errors, prevent form submission
            if (errorMessageDiv.innerHTML !== '') {
                event.preventDefault(); // Prevent form submission
                errorMessageDiv.style.display = 'block'; // Show error messages
            }
        });
    </script>
</body>
</html>
