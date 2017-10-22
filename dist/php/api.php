<?php
// Created by: Christopher Gauffin
// Description: Core API functionality, contains cleaning method, session check, profile edit, post retrieval, post creation and post deletion.

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once("connect.php");

$post_image_target = "img/post_images/post_image_";

$response = array(
  "success" => false
);

$clean = array();
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
    case 'createPost':
    case 'deletePost':
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
        case 'createPost':
          if( checkParams($_POST, array("title", "description", "body")) && isset($_FILES['image']) ){
            createPost($user_id, $_POST['title'], $_POST['description'], $_POST['body'], $_FILES);
          } else {
              $response['message'] = "Fyll i alla fält för att skapa inlägg.";
          }
        break;
        case 'deletePost':
          if( checkParams($_POST, array("post_id")) ){
            deletePost($_POST['post_id']);
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
        case 'getSinglePost':
          if(checkParams($_GET, array("post_id"))){
            getSinglePost($_GET['post_id']);
          }
        break;
        case 'getMainPosts':
          if(checkParams($_GET, array("page", "items"))){
            getPosts($_GET['page'], $_GET['items']);
          }
        break;
        case 'getUserPosts':
          if(checkParams($_GET, array("page", "items", "user_id"))){
            getPosts($_GET['page'], $_GET['items'], array("user_id" => $_GET['user_id']));
          }
        break;
        case 'getTrendingPosts':
          if(checkParams($_GET, array("page", "items"))){
            getPosts($_GET['page'], $_GET['items'], array("trending" => true));
          }
        break;
        case 'getUserInfo':
          if(checkParams($_GET, array("user_id"))){
            getUserInfo($_GET['user_id']);
          }
        break;
        case 'getUsers':
          getUsers();
        break;

        // PROFILE METHODS
        case 'getProfilePosts':
          if(checkParams($_GET, array("page", "items"))){
            getPosts($_GET['page'], $_GET['items'], array(
              "user_id" => $user_id,
              "black_id" => isset($_GET['black_id']) ? $_GET['black_id'] : 0
            ));
          }
        break;
        case 'getProfileInfo':
          getUserInfo($user_id);
        break;
      }
    }
  }
}

function checkParams($data, $params){
  foreach ($params as $param) {
    if(!isset($data[$param])){
      return false;
    }
  }

  return true;
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

  $query = "SELECT id, name, username, timestamp, role FROM users";

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


function getSinglePost($post_id){
  global $conn, $response;

  $query = "SELECT * FROM posts WHERE id={$post_id}";

  if( $result = mysqli_query($conn, $query) ){

    $post = mysqli_fetch_assoc($result);
    $post['image'] = getPostImages($post['id']);

    $response['success'] = true;
    $response['body'] = $post;
  }
}


function getPosts($page, $items, $optional = array()){
  global $conn, $response;

  $query = "SELECT * FROM posts";

  if(!empty($optional['trending'])) $query .= " ORDER BY views DESC";

  $where = "";
  if(!empty($optional['user_id'])) $where .= " WHERE user_id={$optional['user_id']}";
  if(!empty($optional['black_id'])) $where .= " AND id<{$optional['black_id']}";

  $query .= $where;

  $offset = $page * $items;
  $limit = " LIMIT {$offset}, {$items}";

  if( $result = mysqli_query($conn, $query.$limit) ){

    if( mysqli_num_rows($result) > 0 ){
      $response['success'] = true;

      while( $post = mysqli_fetch_assoc($result) ) {
        $posts[] = $post;
      }

      foreach($posts as $i => $post) {
        $posts[$i]['image'] = getPostImages($post['id']);
        $posts[$i]['creator'] = getUserName($post['user_id']);
      }

      $offset += $items;
      $limit = " LIMIT {$offset}, 1";

      if( $result = mysqli_query($conn, $query.$limit) ){
        $response['more'] = mysqli_num_rows($result) > 0;
        $response['success'] = true;
        $response['body'] = $posts;
      }
    } else {
      if(!empty($optional['user_id'])) $response['message'] = "Du har inte gjort några inlägg än";
      else $response['message'] = 'Inga nya inlägg';
    }
  }
}

function getPostImages($post_id){
  global $conn;

  $query = "SELECT * FROM images WHERE post_id={$post_id}";

  if( $result = mysqli_query($conn, $query) ){
    return mysqli_fetch_assoc($result);
  }
}

function getUserName($user_id){
  global $conn;

  $query = "SELECT name FROM users WHERE id={$user_id}";

  if( $result = mysqli_query($conn, $query) ){
    return mysqli_fetch_assoc($result)['name'];
  }
}

function deletePost($post_id){
  global $conn, $response, $post_image_target;

  $query = "SELECT id FROM images WHERE post_id={$post_id}";

  if( $result = mysqli_query($conn, $query) ){
    while( $image_id = mysqli_fetch_assoc($result)['id'] ){

      $query = "DELETE FROM images WHERE id={$image_id}";

      if( mysqli_query($conn, $query) ){

        $image = $post_image_target.$image_id;
        $files = glob($image."*");
        foreach ($files as $file) {
          unlink($file);
        }
      }
    }

    $query = "DELETE FROM posts WHERE id={$post_id}";

    if( mysqli_query($conn, $query) ){
        $response['success'] = true;
    }
  }
}

function createPost($user_id, $title, $description, $body, $files){
  global $conn, $response;

  $query = "INSERT INTO posts (title, description, body, user_id) VALUES ('{$title}', '{$description}', '{$body}', '{$user_id}')";

  if( mysqli_query($conn, $query) ){

    $post_id = mysqli_insert_id($conn);
    $name = addslashes($files['image']['name']);
    $tmp = addslashes($files['image']['tmp_name']);

    if( uploadPostImage($name, $tmp, $post_id) ){
      $response['success'] = true;
      $response['message'] = "Ditt inlägg har skapats!";
      $response['body'] = array("post_id"=>$post_id);
    } else {
      $response['message'] = "Bilden får max väga 2 MB";
      $query = "DELETE FROM posts WHERE id={$post_id}";
      mysqli_query($conn, $query);
    }
  }
}



function uploadPostImage($name, $tmp, $post_id) {
  global $conn, $response, $post_image_target;

  if( $img = @getimagesize( $tmp ) ){

    $query = "INSERT INTO images (name, post_id) VALUES ('{$name}','{$post_id}')";

    if( mysqli_query($conn, $query) ){

      $image_id = mysqli_insert_id($conn);

      $ext = ".".pathinfo($name, PATHINFO_EXTENSION);
      $target = $post_image_target.$image_id.$ext;

      $query = "UPDATE images SET src='{$target}' WHERE id={$image_id}";

      if( mysqli_query($conn, $query) ){
        if( move_uploaded_file($tmp, $target) ){
          return true;
        }
      } else {
        
      }
    }
  }

  return false;
}

echo json_encode($response);
