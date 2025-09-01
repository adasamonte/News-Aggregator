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
  <meta charset="utf-8">  
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">  
  <title>Registration Form</title>  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style/register.css">
</head>  
<body>    
    <div class="container">
        <div class="form-container">
            <h1>Register Now</h1>
            <form method="POST" action="register.php" enctype="multipart/form-data">
                <img src="uploads/default.png" id="profilePreview" class="profile-picture" alt="Profile Picture">
                <input type="file" name="profile_picture" accept="image/*" onchange="previewImage(event)" required>
                
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

                <button type="submit" class="btn btn-primary">Register</button>
            </form>

            <div class="back-to-login">
                <p>Already have an account? <a href="login.php">Login Here</a></p>
            </div>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('profilePreview');
                img.src = e.target.result;
            }
            reader.readAsDataURL(file);
        }

        // Function to handle slide-out animation and form submission
        function handleSlideOutAndSubmit(form) {
            // Apply the slide-out animation to the registration container
            document.querySelector('.form-container').style.animation = 'slideOut 0.5s ease forwards'; // Apply slide-out animation

            // Wait for the animation to finish before submitting the form
            setTimeout(() => {
                form.submit(); // Submit the form after the animation
            }, 500); // Match this duration with the animation duration
        }

        // Add event listener to the registration form submission
        document.querySelector('form').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission
            handleSlideOutAndSubmit(this); // Call the function to handle animation and submission
        });
    </script>
</body>
</html>
