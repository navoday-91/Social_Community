<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>Sign-Up</title>
  <link href='https://fonts.googleapis.com/css?family=Titillium+Web:400,300,600' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">

  
      <link rel="stylesheet" href="css/signup.css">

  
</head>

<body>
  <div class="form">
      
      <ul class="tab-group">
        <li class="tab active"><a href="#signup">Sign Up</a></li>
        <li class="tab"><a href="#login">Login</a></li>
      </ul>
      
      <div class="tab-content">
        <div id="signup">   
          <h1>Sign Up</h1>
          
          <form action="php/signup.php" method="post">
          
          <div class="top-row">
            <div class="field-wrap">
              <label>
                User Name<span class="req">*</span>
              </label>
              <input type="text" required autocomplete="off" name="user_username"/>
            </div>
            
            <div class="field-wrap">
              <label>
                First Name<span class="req">*</span>
              </label>
              <input type="text" required autocomplete="off" name="first_name"/>
            </div>
        
            <div class="field-wrap">
              <label>
                Last Name<span class="req">*</span>
              </label>
              <input type="text"required autocomplete="off" name="last_name"/>
            </div>
          </div>

          <div class="field-wrap">
            <label>
              Email Address<span class="req">*</span>
            </label>
            <input type="email"required autocomplete="off" name="email"/>
          </div>
          
          <div class="field-wrap">
              <label>
                Address<span class="req">*</span>
              </label>
              <input type="text" required autocomplete="off" name="address"/>
          </div>
          
            <div class="field-wrap">
              <label>
                Phone<span class="req">*</span>
              </label>
              <input type="text" required autocomplete="off" name="phone"/>
            </div>
            
          <div class="field-wrap">
            <label>
              Set A Password<span class="req">*</span>
            </label>
            <input type="password"required autocomplete="off" name="user_password"/>
          </div>
          
          <div class="field-wrap">
            <label>
              Profile Picture URL<span class=""></span>
            </label>
            <input type="text" autocomplete="off" name="pic_url"/>
          </div>
          <?php
                $connection = mysqli_connect("localhost", "admin", "redhat");
                if ($connection->connect_error) {
                    die("Connection failed: " . $connection->connect_error);
                    echo('connection to db failed');
                    echo($connection);
                }
                $db = mysqli_select_db($connection, "cmpe281");
                // SQL query to fetch communities.
                $query = mysqli_query($connection, "select * from communities;");
                $rows = mysqli_num_rows($query);
                
          ?>
          
          <div class="field-wrap">
              <select name = "community"> Community
                  <option value = ""> Select Community</option>
                <?php if ($rows > 0) {
                    while ($user = $query->fetch_assoc()) { ?>
                        <option value = "<?php echo($user['community_name']); ?>"> <?php echo($user['community_name']); ?></option>
                    <?php } } 
                    else{?>
                        <option value = ""> No Communities Available</option>
                    <?php } ?>
            </select>
          </div>
          <button type="submit" class="button button-block" name="Register"/>Signup</button>
          
          </form>

        </div>
        
        <div id="login">   
          <h1>Sign-in</h1>
          
          <form action="php/cmpe281login.php" method="post">
          
            <div class="field-wrap">
            <label>
              Username<span class="req"></span>
            </label>
            <input type="text" autocomplete="off" name="user_username"/>
          </div>
          
          <div class="field-wrap">
            <label>
              Password<span class="req"></span>
            </label>
            <input type="password" autocomplete="off" name="user_password"/>
          </div>
          
          <?php 
          session_start();
          if (isset($_SESSION['error'])){ ?>
          <div class="text-center pad-top-20">
            <p><font color="red"><strong><?php echo($_SESSION['error']); ?></strong></font></p>
          </div>
          <?php } 
          $_SESSION['error'] = '';
          ?>
          
          <button class="button button-block" name="Login"/>Sign-in</button>
          
          </form>

        </div>
        
      </div><!-- tab-content -->
      
</div> <!-- /form -->
  <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

    <script  src="js/signup.js"></script>

</body>
</html>
