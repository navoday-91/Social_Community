<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>Social Communicator Login</title>
  
  
  <link rel='stylesheet prefetch' href='https://www.google.com/fonts#UsePlace:use/Collection:Roboto:400,300,100,500'>
<link rel='stylesheet prefetch' href='http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css'>
<link rel='stylesheet prefetch' href='https://www.google.com/fonts#UsePlace:use/Collection:Roboto+Slab:400,700,300,100'>

      <link rel="stylesheet" href="css/style.css">

  
</head>
<div id="dialog" class="dialog dialog-effect-in">
  <div class="dialog-front">
    <div class="dialog-content">
      <form id="login_form" class="dialog-form" action="php/cmpe281login.php" method="POST">
        <fieldset>
          <legend>Administrator Log in</legend>
          <div class="form-group">
            <label for="user_username" class="control-label">Username:</label>
            <input type="text" id="user_username" class="form-control" name="user_username" autofocus/>
          </div>
          <div class="form-group">
            <label for="user_password" class="control-label">Password:</label>
            <input type="password" id="user_password" class="form-control" name="user_password"/>
          </div>
          
          <?php
            session_start();
          ?>
          <?php if (isset($_SESSION['error'])){ ?>
          <div class="text-center pad-top-20">
            <p><font color="red"><strong><?php echo($_SESSION['error']); ?></strong></font></p>
          </div>
          <?php } ?>
          <div class="pad-top-20 pad-btm-20">
            <input type="submit" class="btn btn-default btn-block btn-lg" name="Login" value="Login">
          </div>
          
        </fieldset>
      </form>
      <form id="gotoform" class="dialog-form" action="redirect.php" method="POST">
        <fieldset>
          <legend>Community Log in</legend>
          <?php
                $connection = mysqli_connect("localhost", "root", "redhat");
                if ($connection->connect_error) {
                    die("Connection failed: " . $connection->connect_error);
                    echo('connection to db failed');
                    echo($connection);
                }
                $db = mysqli_select_db($connection, "cmpe281");
                // SQL query to fetch communities.
                $query = mysqli_query($connection, "select * from community_details;");
                $rows = mysqli_num_rows($query);

          ?>

          <div class="field-wrap">
              <select name = "community" id = "community"> Community
                  <option value = ""> Select Community</option>
                <?php if ($rows > 0) {
                    while ($user = $query->fetch_assoc()) { ?>
                        <option value = "<?php echo($user['comm_name']); ?>"> <?php echo($user['comm_name']); ?></option>
                    <?php } }
                    else{?>
                        <option value = ""> No Communities Available</option>
                    <?php } ?>
            </select>
          </div>
          <div class="pad-top-20 pad-btm-20">
            <input type="submit" class="btn btn-default btn-block btn-lg" name="Ok" value="Ok">
          </div>
        </fieldset>
      </form>
    </div>
  </div>
</div>
  <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

    <script  src="php/cmpe281login.php"></script>

</body>
</html>
