<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="./js/script.js" defer></script>
</head>
<body class = "bg-gray-950">
    <div class = "bg-[url('./img/datawareMobile.png')] md:bg-[url('./img/datawarelogo.png')] bg-cover h-screen w-screen flex items-center justify-center md:justify-end">
        <div class = "w-5/6 rounded flex items-center justify-center flex-col md:justify-end md:w-2/6" id = "formLogin">
            <!-- <img src="./img/dataware.png" alt="Dataware Picture" class = "w-5/6"> -->
            <form class = "flex flex-col pb-4 w-5/6" method = "POST">
                <!-- <label for="email" class = "text-white">E-mail</label> -->
                <input type="text" name="emailLog" id="email" class = "border border-gray-950 p-2 mb-2 pl-4 font-bold rounded" placeholder = "E-mail" required>
                <!-- <label for="password" class = "text-white">Password</label> -->
                <input type="password" name="passwordLog" id="password" class = "border border-gray-950 p-2 mb-2 pl-4 font-bold rounded" placeholder = "Password" required>
                <input type="submit" value="Submit" class = "bg-blue-500 rounded p-2 text-white hover:bg-orange-500 transition-all cursor-pointer">
            </form>
            <?php
                session_start();
                include 'connection.php';
                include './includes/user.php';

                $userObj = new User();

                $userObj->checkAuthentication();

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $email = $_POST["emailLog"];
                    $password = $_POST["passwordLog"];

                    if ($userObj->isValidCredentials($email, $password)) {
                        $userObj->initSession($email);
                    } else {
                        echo "<p class = 'text-red-300'>Invalid username or password.</p>";
                    }
                }

                ?>
    <a href = "#" class = "text-white underline" id = "registerLink">Not registered? Click here to register</a>
        </div>

        <div class = "w-5/6 rounded flex items-center justify-center flex-col md:justify-end md:w-2/6 hidden" id = "formRegister">
            <!-- <img src="./img/dataware.png" alt="Dataware Picture" class = "w-5/6"> -->
            <form action = "register.php" class = "flex flex-col pb-4 w-5/6" method = "POST" enctype="multipart/form-data">
                <div class="flex items-center justify-center mb-2">
                    <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                            </svg>
                            <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                            <p class="text-xs text-gray-500">PNG or JPG (MIN. 500x500px)</p>
                        </div>
                        <input id="dropzone-file" name="teamImage" type="file" class="hidden" accept="image/jpeg, image/png" required />
                    </label>
                </div> 
                <!-- <label for="email" class = "text-white">E-mail</label> -->
                <input type="text" name="firstName" id="firstName" class = "border border-gray-950 p-2 mb-2 pl-4 font-bold rounded" placeholder = "First Name" required>
                <input type="text" name="lastName" id="lastName" class = "border border-gray-950 p-2 mb-2 pl-4 font-bold rounded" placeholder = "Last Name" required>
                <input type="text" name="email" id="email" class = "border border-gray-950 p-2 mb-2 pl-4 font-bold rounded" placeholder = "E-mail" required>
                <input type="text" name="phoneNumber" id="phoneNumber" class = "border border-gray-950 p-2 mb-2 pl-4 font-bold rounded" placeholder = "Phone Number" required>
                <!-- <label for="password" class = "text-white">Password</label> -->
                <input type="password" name="password" id="password" class = "border border-gray-950 p-2 mb-2 pl-4 font-bold rounded" placeholder = "Password" required>
                <input type="submit" value="Register" class = "bg-blue-500 rounded p-2 text-white hover:bg-orange-500 transition-all cursor-pointer">
            </form>
            <a href = "#" class = "text-white underline" id = "loginLink">Already have an account? Click here to login</a>
        </div>
    </div>
</body>
</html>