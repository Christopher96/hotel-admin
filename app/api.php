<?php
// Created by: Christopher Gauffin
// Description: Core API functionality, contains cleaning method, session check, profile edit, post retrieval, post creation and post deletion.
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once("php/connect.php");

$full_target = "img/post_images/post_image_";
$thumb_target = $full_target."thumb_";

$response = array(
  "success" => false
);

$clean = array();
$_FILES = cleanArray( $_FILES );
$_REQUEST = cleanArray( $_REQUEST );
$_POST = cleanArray( $_POST );
$_GET = cleanArray( $_GET );

function cleanArray($array){
  global $clean;
  $clean = array();

  array_walk_recursive($array, function($value, $key){
    global $conn, $clean;

    $value = trim($value);
    $value = stripslashes($value);
    $value = htmlspecialchars($value);
    $value = htmlentities($value);
    $value = strip_tags($value);
    $value = str_replace(array("\r\n", "\n", "\r"), ' ', $value);
    $value = mysqli_real_escape_string($conn, $value);

    $clean[$key] = $value;
  });

  return $clean;
}

if(!empty($_REQUEST['action'])){

  $methodIsValid = false;


  switch ($_REQUEST['action']) {
    case 'createUser':
    case 'deleteUser':
    case 'createRoom':
    case 'updateRoom':
    case 'deleteRoom':
    case 'changeName':
    case 'getProfilePosts':
    case 'getUserInfo':
      if(!empty($_REQUEST['user_id']) && !empty($_REQUEST['session_id'])){

        $user_id = $_REQUEST['user_id'];
        $session_id = $_REQUEST['session_id'];

        $result = mysqli_query($conn, "SELECT last_session FROM users WHERE id='{$user_id}'");

        if(mysqli_fetch_assoc($result)['last_session'] === $session_id){
          $methodIsValid = true;
        }
      }
    break;
    default:
      $methodIsValid = true;
    break;
  }

  if($methodIsValid){
    
    if(!empty($_POST['action'])){

      switch ($_POST['action']) {

        // USER METHODS
        case 'createUser':
          if( checkParams($_POST, array("name", "username", "password", "role")) ){
              createUser($_POST['name'], $_POST['username'], $_POST['password'], $_POST['role']);
          } else {
              $response['message'] = "Fyll i alla fält för att skapa användare.";
          }
        break;
        case 'deleteUser':
          if( checkParams($_POST, array("target_id")) ){  
            deleteUser($_POST['target_id']);
          }
        break;
        case 'createRoom':
        if( checkParams($_POST, array("number", "level", "department", "status", "description")) ){
            createRoom($_POST['number'], $_POST['level'], $_POST['department'], $_POST['status'], $_POST['description'], $_FILES);
        } else {
            $response['message'] = "Fyll i alla fält för att skapa rum.";
        }
        break;
        case 'updateRoom':
        if( checkParams($_POST, array("room_id", "number", "level", "department", "status", "description"))){
            updateRoom($_POST['room_id'], $_POST['number'], $_POST['level'], $_POST['department'], $_POST['status'], $_POST['description'], $_FILES);
        } else {
            $response['message'] = "Fyll i alla fält för att uppdatera rum.";
        }
        break;
        case 'changeRoomStatus':
        if( checkParams($_POST, array("room_id", "status"))){
            changeRoomStatus($_POST['room_id'], $_POST['status']);
        } else {
            $response['message'] = "Fyll i alla fält för att uppdatera rum.";
        }
        break;
        case 'deleteRoom':
          if( checkParams($_POST, array("room_id")) ){
            deleteRoom($_POST['room_id']);
          }
        break;
        case 'changeName':
          if( checkParams($_POST, array("name")) ){
            updateUserInfo("name", $_POST['name'], false, $user_id);
          }
        break;

      }

    } else if(!empty($_GET['action'])){

      switch ($_GET['action']) {
        case 'getRooms':
          getRooms();
        break;
        case 'getUsers':
          getUsers();
        break;
        case 'getSingleRoom':
          if( checkParams($_GET, array("room_id")) ){
            getSingleRoom($_GET['room_id']);
          }
        break;
      }
    }
  }
}

function checkParams($data, $params){
  return true;
  foreach ($params as $param) {
    if(isset($data[$param])){
      if($data[$param] === "") {
        return false;
      }
    } else {
      return false;
    }
  }

  return true;
}


function checkFileErrors() {
  global $_FILES, $response;

  $file_error = $_FILES['error'];
  if ($file_error == UPLOAD_ERR_OK) { 
    return true;
  } else {
    $response['message'] = errorMessage($file_error);
    return false;
  }
}


function createUser($name, $username, $password, $role) {
  global $conn, $response;

  $query = "SELECT * FROM users WHERE username='".$username."'";
  $result = mysqli_query($conn, $query);

  if( mysqli_num_rows($result) == 0 ){

    // this function uses autogenerated salt
    $pw_hash = password_hash($password, PASSWORD_DEFAULT);
    $session_id = session_id();

    $query = "INSERT INTO users (name, username, role, pw_hash, last_session) VALUES ('$name', '$username', '$role', '$pw_hash', '{$session_id}')";
    $result = mysqli_query($conn, $query);
    $id = mysqli_insert_id($conn);

    $_SESSION['user_id'] = $id;
    $response['success'] = true;
    $response['message'] = "Användaren ".$username." har skapats!";
  } else {
    $response['message'] = "Användaren ".$username." existerar redan, välj ett annat användarnamn.";
  }
}


function deleteUser($target_id){
  global $conn, $response;

  $query = "DELETE FROM users WHERE id={$target_id}";
  
  if( mysqli_query($conn, $query) ){
      $response['success'] = true;
      $response['message'] = "Användaren har tagits bort!";
  } else {
      $response['message'] = "Användaren existerar inte.";
  }
}

function getUserInfo($user_id){
  global $conn, $response;

  $query = "SELECT name FROM users WHERE id={$user_id}";

  if( $result = mysqli_query($conn, $query) ){
    $response['body'] = mysqli_fetch_assoc($result);
    $response['success'] = true;
  }
}

function updateUserInfo($col, $val, $user_id){
  global $conn, $response;

  $query = "UPDATE users SET {$col}='{$val}' WHERE id={$user_id}";

  if( $result = mysqli_query($conn, $query) ){
    $response['success'] = true;
  }
}

function getUsers(){
  global $conn, $response;

  $query = "SELECT * FROM users";

  if( $result = mysqli_query($conn, $query) ){

    if( mysqli_num_rows($result) > 0 ){
      $response['success'] = true;

      while( $user = mysqli_fetch_assoc($result) ) {
        $users[] = $user;
      }

      $response['success'] = true;
      $response['body'] = $users;
    }
  }
}

function getRoomCode($room) {
  return $room["level"].$room["department"].$room["number"];
}

function getRooms(){
  global $conn, $response;

  $query = "SELECT * FROM rooms";

  if( $result = mysqli_query($conn, $query) ){

    if( mysqli_num_rows($result) > 0 ){
      $response['success'] = true;

      while( $room = mysqli_fetch_assoc($result) ) {
        $room['code'] = getRoomCode($room);
        $room['image'] = getPostImages($room['id']);
        $rooms[] = $room;
      }

      $response['success'] = true;
      $response['body'] = $rooms;
    }
  }
}

function getSingleRoom($room_id){
  global $conn, $response;

  $query = "SELECT * FROM rooms WHERE id={$room_id}";
  $result = mysqli_query($conn, $query);

  if( mysqli_num_rows($result) > 0 ){

    $room = mysqli_fetch_assoc($result);
    $room['code'] = getRoomCode($room);
    $room['image'] = getPostImages($room['id']);

    $response['success'] = true;
    $response['body'] = $room;
  }
}


function getPostImages($room_id){
  global $conn;

  $query = "SELECT * FROM images WHERE room_id={$room_id}";

  if( $result = mysqli_query($conn, $query) ){
    return mysqli_fetch_assoc($result);
  }
}


function deleteRoom($room_id){
  global $conn, $response, $post_image_target;

  $query = "DELETE FROM rooms WHERE id={$room_id}";
  mysqli_query($conn, $query);

  $query = "SELECT * FROM rooms WHERE id={$room_id}";
  $result = mysqli_query($conn, $query);
  if( mysqli_num_rows($result) < 1 ){
    deleteRoomImages($room_id);
    $response['success'] = true;
    $response['message'] = "Rummet har raderats!";
  }
}

function deleteRoomImages($room_id) {
  global $conn, $post_image_target; 
  
  $query = "SELECT id FROM images WHERE room_id={$room_id}";
  
  if( $result = mysqli_query($conn, $query) ){
    while( $image_id = mysqli_fetch_assoc($result)['id'] ){

      $query = "DELETE FROM images WHERE id={$image_id}";

      if( mysqli_query($conn, $query) ){

        $image = $post_image_target.$image_id;
        $files = glob($image."*");
        foreach ($files as $file) {
          unlink($file);
        }

        return true;
      }
    }
  }

  return false;
}

function createRoom($number, $level, $department, $description, $status, $files){
  global $conn, $response;
  
  if( checkFileErrors() ){
    $query = "SELECT * FROM rooms WHERE number='{$number}' AND level='{$level}' AND department='{$department}'";
    $result = mysqli_query($conn, $query);

    if( mysqli_num_rows($result) < 1 ){
      
      $query = "INSERT INTO rooms (number, level, department, description, status) VALUES ('{$number}', '{$level}', '{$department}', '{$description}', '{$status}')";

      if( mysqli_query($conn, $query) ){

        $room_id = mysqli_insert_id($conn);

        if( uploadRoomImage($room_id, $files) ){
          $response['success'] = true;
          $response['message'] = "Ditt rum har skapats!";
        } else {
          $query = "DELETE FROM rooms WHERE id={$room_id}";
          mysqli_query($conn, $query);

          $response['message'] = "Fel vid uppladdning av bild.";
        }
      }
    } else {
      $response['message'] = "Detta rum finns redan.";
    }
  }
}

function updateRoom($room_id, $number, $level, $department, $status, $description, $files = null) {
  global $conn, $response;
  
  $query = "SELECT * FROM rooms WHERE number='{$number}' AND level='{$level}' AND department='{$department}' AND id!={$room_id}";
  $result = mysqli_query($conn, $query);

  if( mysqli_num_rows($result) < 1 ){
    $query = "UPDATE rooms SET number='{$number}', level='{$level}', department='{$department}', description='{$description}', status='{$status}' WHERE id={$room_id}";

    if( mysqli_query($conn, $query) ){

      $pass = false;

      if(!empty($files['name'])) {
        if( checkFileErrors() ){
          if( deleteRoomImages($room_id) ) {
            if( uploadRoomImage($room_id, $files) ){
              $pass = true; 
            } else {
              $query = "DELETE FROM rooms WHERE id={$room_id}";
              mysqli_query($conn, $query);

              $response['message'] = "Fel vid uppladdning av bild.";
            }
          }
        }
      } else {
        $pass = true;
      }

      if($pass) {
        $response['success'] = true;
        $response['message'] = "Ditt rum har uppdaterats!";
        return true;
      }
      
    }
  } else {
    $response['message'] = "Detta rum finns redan.";
  }
}

function changeRoomStatus($room_id, $status) {
  global $conn, $response;

  $query = "UPDATE rooms SET status='{$status}' WHERE id={$room_id}";

  if( mysqli_query($conn, $query) ){
    if($status == 0) {
      $response['message'] = "Rummet är inte städat längre.";
    } else {
      $response['message'] = "Rummet är nu städat!";
    }
    $response['success'] = true;
  }
}


function uploadRoomImage($room_id, $image) {
  global $conn, $response, $full_target, $thumb_target;

  $name = addslashes($image['name']);
  $tmp = addslashes($image['tmp_name']);

  if( $img_info = getimagesize( $tmp ) ){

    $query = "INSERT INTO images (name, room_id) VALUES ('{$name}','{$room_id}')";

    if( mysqli_query($conn, $query) ){

      $image_id = mysqli_insert_id($conn);

      $ext = image_type_to_extension($img_info[2]);
      $full = $full_target.$image_id.$ext;
      $thumb = $thumb_target.$image_id.$ext;

      if( move_uploaded_file($tmp, $full)){

        if( createThumb($img_info, $thumb, $full) ){

          $query = "UPDATE images SET full='{$full}',thumb='{$thumb}' WHERE id={$image_id}";

          if( mysqli_query($conn, $query) ){
            return true;
          }
        }
      }
    }
  }

  $query = "DELETE FROM images WHERE id={$room_id}";
  mysqli_query($conn, $query);
  
  return false;
}

function createThumb($img, $thumb, $full) {

    $thumb_width = 150;
    $thumb_height = 150;

    $org_width = $img[0];
    $org_height = $img[1];

    $aspect_ratio = $org_width / $org_height;

    if ($org_width > $org_height) {
        $new_width = $thumb_width;
        $new_height = intval($thumb_width / $aspect_ratio);
    } else {
        $new_height = $thumb_height;
        $new_width = intval($thumb_height * $aspect_ratio);
    }

    switch ($img[2]) {
      case IMAGETYPE_GIF:
        $imgt = "ImageGIF";
        $imgcreatefrom = "ImageCreateFromGIF";
      break;
      case IMAGETYPE_JPEG:
        $imgt = "ImageJPEG";
        $imgcreatefrom = "ImageCreateFromJPEG";
      break;
      case IMAGETYPE_PNG:
        $imgt = "ImagePNG";
        $imgcreatefrom = "ImageCreateFromPNG";
      break;
    }

    if ($imgt) {
        $old_image = $imgcreatefrom($full);
        $new_image = imagecreatetruecolor($new_width, $new_height);

        imagecopyresized($new_image, $old_image, 0, 0, 0, 0, $new_width, $new_height, $org_width, $org_height);
        if( $imgt($new_image, $thumb) ) {
          return true;
        }
    }

    return false;
}


function errorMessage($code) { 
  switch ($code) { 
    case UPLOAD_ERR_INI_SIZE: 
        $message = "The uploaded file exceeds the upload_max_filesize directive in php.ini"; 
        break; 
    case UPLOAD_ERR_FORM_SIZE: 
        $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form"; 
        break; 
    case UPLOAD_ERR_PARTIAL: 
        $message = "The uploaded file was only partially uploaded"; 
        break; 
    case UPLOAD_ERR_NO_FILE: 
        $message = "Du har inte valt någon bild."; 
        break; 
    case UPLOAD_ERR_NO_TMP_DIR: 
        $message = "Missing a temporary folder"; 
        break; 
    case UPLOAD_ERR_CANT_WRITE: 
        $message = "Failed to write file to disk"; 
        break; 
    case UPLOAD_ERR_EXTENSION: 
        $message = "File upload stopped by extension"; 
        break; 

    default: 
        $message = "Unknown upload error"; 
        break; 
  } 

  return $message; 
} 

echo json_encode($response);
