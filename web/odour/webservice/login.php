<?php
header('Access-Control-Allow-Origin: *');

include_once('db_config.php');


if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Get post data`

    $username = isset($_POST['username']) ? mysqli_real_escape_string($conn,$_POST['username']) : "";
    $password = isset($_POST['password']) ? mysqli_real_escape_string($conn,$_POST['password']) : "";

    // Save data into database
    $query = "SELECT * FROM `user_oc` WHERE username='$username';";
    $result = mysqli_query($conn,$query);

    while ($posts_row = mysqli_fetch_assoc($result))
    {
        $md5_password = md5($password);
        if ($md5_password == $posts_row['password']) {
            $data = array("result" => 1, "username" => $username, "id" => $posts_row['id']);
        } else {
            $data = array("result" => 0, "message" => "Error! Username and password do not match.");
            http_response_code(401);
        }
    }

    if(mysqli_num_rows($result) != 1){
        $data = array("result" => 0, "message" => "Wrong username");
        http_response_code(401);
    }
} else {
    $data = array("result" => 0, "message" => "Method not allowed");
    http_response_code(405);
}


mysqli_close($conn);

/* JSON Response */
echo json_encode($data);
?>

