<?php
//login.php
include('database_connection.php');
if(isset($_SESSION["type"]))
{
 header("location: index.php");
}
$message = '';

if(isset($_POST["login"]))
{
 if(empty($_POST["user_email"]) || empty($_POST["user_password"]))
 {
  $message = "<label>Both Fields are required</label>";
 }
 else
 {
  $query = "
  SELECT * FROM user_details 
  WHERE user_email = :user_email
  ";
  $statement = $connect->prepare($query);
  $statement->execute(
   array(
    'user_email' => $_POST["user_email"]
   )
  );
  $count = $statement->rowCount();
  if($count > 0)
  {
   $result = $statement->fetchAll();
   foreach($result as $row)
   {
    if(password_verify($_POST["user_password"], $row["user_password"]))
    {
     $insert_query = "
     INSERT INTO login_details (
      user_id, last_activity,user_email) VALUES (
      :user_id, :last_activity, :user_email)
     ";
     date_default_timezone_set('Asia/Calcutta');
     $statement = $connect->prepare($insert_query);
     $statement->execute(
      array(
       'user_id'  => $row["user_id"],
       'last_activity' => date("Y-m-d H:i:s", STRTOTIME(date('h:i:sa'))),
       'user_email'  => $row["user_email"]
      )
     );
     $login_id = $connect->lastInsertId();
     if(!empty($login_id))
     {
      $_SESSION["type"] = $row["user_type"];
      $_SESSION["login_id"] = $login_id;
      header("location: index.php");
     }
    }
    else
    {
     $message = "<label>Wrong Password</label>";
    }
   }
  }
  else
  {
   $message = "<label>Wrong Email Address</labe>";
  }
 }
}


?>

<!DOCTYPE html>
<html>
 <head>
  <title> KARTHIK NAVIGUS ASSIGNMENT</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <style>
body {
 background-color: coral;
  background-image: url("https://wallpaperaccess.com/full/56751.jpg");
}

</style>
 </head>
 <body>
  <br />
  <div class="container">
   <h2 align="center">NAVIGUS ASSIGNMENT-1</h2>
   <br />

   <div class="panel panel-default">
    <div class="panel-heading">Login</div>
    <div class="panel-body">
     <form method="post">
      <span class="text-danger"><?php echo $message; ?></span>
      <div class="form-group">
       <label>User Email</label>
       <input type="text" name="user_email" class="form-control" />
      </div>
      <div class="form-group">
       <label>Password</label>
       <input type="password" name="user_password" class="form-control" />
      </div>
      <div class="form-group">
       <input type="submit" name="login" value="Login" class="btn btn-info" />
      </div>
     </form>
    </div>
    <h3><b><u><div align="right">
    <a href="register.php">NEW REGISTRATION</a>
   </div></b></u></h3>
   </div>
  </div>

   <H3><div align="right"><p>16MIS0387 G KARTHIKEYAN</p></div></H3>
   <br />
 </body>
</html>

