<!doctype html>
<html>
    <head>
        <title>Social Communicator</title>
        <style>
            @media only screen and (max-width : 540px)
            {
                .chat-sidebar
                {
                    display: none !important;
                }

                .chat-popup
                {
                    display: none !important;
                }
            }

            body
            {
                background-color: #e9eaed;
            }

            .chat-sidebar
            {
                width: 200px;
                position: fixed;
                height: 100%;
                right: 0px;
                top: 0px;
                bottom: 0px;
                padding-top: 10px;
                padding-bottom: 10px;
                border: 1px solid rgba(29, 49, 91, .3);
                overflow-y: scroll;
            }

            .sidebar-name
            {
                padding-left: 10px;
                padding-right: 10px;
                margin-bottom: 4px;
                font-size: 12px;
            }

            .sidebar-name span
            {
                padding-left: 5px;
            }

            .sidebar-name a
            {
                display: block;
                height: 100%;
                text-decoration: none;
                color: inherit;
            }

            .sidebar-name:hover
            {
                background-color:#e1e2e5;
            }

            .sidebar-name img
            {
                width: 32px;
                height: 32px;
                vertical-align:middle;
            }

            .popup-box
            {
                display: none;
                position: fixed;
                bottom: 0px;
                right: 220px;
                height: 285px;
                background-color: rgb(237, 239, 244);
                width: 300px;
                border: 1px solid rgba(29, 49, 91, .3);
            }

            .popup-box .popup-head
            {
                background-color: #6d84b4;
                padding: 5px;
                color: white;
                font-weight: bold;
                font-size: 14px;
                clear: both;
            }

            .popup-box .popup-head .popup-head-left
            {
                float: left;
            }

            .popup-box .popup-head .popup-head-right
            {
                float: right;
                opacity: 0.5;
            }

            .popup-box .popup-head .popup-head-right a
            {
                text-decoration: none;
                color: inherit;
            }

            .popup-box .popup-messages
            {
                height: 100%;
                overflow-y: scroll;
                position: relative;
            
            }
            .popup-text
            {
                position: absolute;
                bottom: 0px;
                
            }
            
            .send {
                -webkit-appearance: none;
                width: 70px;
                height: 20px;
            }




        </style>
        
        <script language="javascript" type="text/javascript">
          
          <?php
              session_start();
              ?>
              var user = "<?php echo($_SESSION['login_user']) ?>";
              var community = "<?php echo($_SESSION['community']) ?>";
              var role = "<?php echo($_SESSION['role']) ?>";
              var inuser = "";
              var optext = [];
          function init()
          {
        	
              doConnect();
          }
          function doConnect()
          {
            websocket = new WebSocket("ws://35.197.90.146:9000/");
            websocket.onopen = function(evt) { onOpen(evt) };
            websocket.onclose = function(evt) { onClose(evt) };
            websocket.onmessage = function(evt) { onMessage(evt) };
            websocket.onerror = function(evt) { onError(evt) };
          }
          function onOpen(evt)
          {
            websocket.send(user + ";" + "TEST");
        	
          }
          function onClose(evt)
          {
            writeToScreen("disconnected\n");
        	
          }
          function onMessage(evt)
          {
              found = 0;
              inuser = evt.data.split(";",3)[0];
            for(var iii = 0; iii < popups.length; iii++)
                {
                    optext[iii] = document.getElementById(popups[iii].concat("optext")).value;
                    //already registered. Bring it to front.
                    if(inuser == popups[iii])
                    {
                        Array.remove(popups, iii);
                        temphold = optext[iii];
                        Array.remove(optext, iii);
                        popups.unshift(inuser);
                        optext.unshift(temphold);
                        calculate_popups();
                        found = 1;
                        for(var iii = 0; iii < popups.length; iii++)
                        {
                            document.getElementById(popups[iii].concat("optext")).value = optext[iii];
                        }
                    }
                }
            if (found == 0){
                <?php 
                    $inuser = "<script>document.write(inuser)</script>";
                ?>
                <?php
                $connection = mysqli_connect("localhost", "admin", "redhat");
                // Selecting Database
                    $db = mysqli_select_db($connection, "cmpe281");
                    $community = $_SESSION['community'];
                    // SQL query to fetch information of registerd users and finds user match.
                    $query = mysqli_query($connection, "select `username`, `first name`, `last name`, `picurl` from userdata where username = '$inuser';");
                    $rows = mysqli_num_rows($query);
                    if ($rows > 0) {
                        while ($user = $query->fetch_assoc()) {
                ?>
                var fname = "<?php echo($user["first name"]) ?>";
                var lname = "<?php echo($user["last name"]) ?>"
                register_popup(inuser, fname.concat(" ".concat(lname)));
                <?php
                        }
                    }
                ?>
            }
            writeToScreen(evt.data.split(";",3)[0]+': '+evt.data.split(";",3)[2] +'\n');
          }
          function onError(evt)
          {
            writeToScreen('error: ' + evt.data + '\n');
        	websocket.close();
        	
          }
          function doSend(message)
          {
            writeToScreen("You: " + message.split(";",2)[1] + '\n');
            websocket.send(user + ";" + message);
            document.getElementById(inuser.concat("iptext")).value = ""
          }
          function writeToScreen(message)
          {
            document.getElementById(inuser.concat("optext")).value += message
        	document.getElementById(inuser.concat("optext")).scrollTop = document.getElementById(inuser.concat("optext")).scrollHeight;
          }
          window.addEventListener("load", init, false);
           function sendText(id) {
                   inuser = id;
        		doSend(id + ";" + document.getElementById(id.concat("iptext")).value);
           }
          function clearText() {
        		document.myform.outputtext.value = "";
           }
           function doDisconnect() {
        		websocket.close();
           }
        </script>


        <script>
            //this function can remove a array element.
            Array.remove = function(array, from, to) {
                var rest = array.slice((to || from) + 1 || array.length);
                array.length = from < 0 ? array.length + from : from;
                return array.push.apply(array, rest);
            };

            //this variable represents the total number of popups can be displayed according to the viewport width
            var total_popups = 0;

            //arrays of popups ids
            var popups = [];

            //this is used to close a popup
            function close_popup(id)
            {
                for(var iii = 0; iii < popups.length; iii++)
                {
                    optext[iii] = document.getElementById(popups[iii].concat("optext")).value;
                    if(id == popups[iii])
                    {
                        Array.remove(popups, iii);
                        Array.remove(optext, iii);
                        document.getElementById(id).style.display = "none";

                        calculate_popups();
                        for(var iii = 0; iii < popups.length; iii++)
                        {
                            document.getElementById(popups[iii].concat("optext")).value = optext[iii];
                        }
                        return;
                    }
                }
            }

            //displays the popups. Displays based on the maximum number of popups that can be displayed on the current viewport width
            function display_popups()
            {
                var right = 220;

                var iii = 0;
                for(iii; iii < total_popups; iii++)
                {
                    if(popups[iii] != undefined)
                    {
                        var element = document.getElementById(popups[iii]);
                        element.style.right = right + "px";
                        right = right + 320;
                        element.style.display = "block";
                    }
                }

                for(var jjj = iii; jjj < popups.length; jjj++)
                {
                    var element = document.getElementById(popups[jjj]);
                    element.style.display = "none";
                }
            }

            //creates markup for a new popup. Adds the id to popups array.
            function register_popup(id, name)
            {

                for(var iii = 0; iii < popups.length; iii++)
                {
                    optext[iii] = document.getElementById(popups[iii].concat("optext")).value;
                    //already registered. Bring it to front.
                    if(id == popups[iii])
                    {
                        
                        Array.remove(popups, iii);
                        var temphold = optext[iii];
                        Array.remove(optext, iii);
                        popups.unshift(id);
                        optext.unshift(temphold);

                        calculate_popups();
                        for(var iii = 0; iii < popups.length; iii++)
                        {
                            document.getElementById(popups[iii].concat("optext")).value = optext[iii];
                        }

                        return;
                    }
                }

                var element = '<div class="popup-box chat-popup" id="'+ id +'">';
                element = element + '<div class="popup-head">';
                element = element + '<div class="popup-head-left">'+ name +'</div>';
                element = element + '<div class="popup-head-right"><a href="javascript:close_popup(\''+ id +'\');">&#10005;</a></div>';
                element = element + '<div style="clear: both"></div></div><div class="popup-messages">';
                element = element + '<form id='+id.concat("form")+'><textarea readonly id='+ id.concat("optext") + ' rows="16" cols="47"></textarea></textarea><textarea id='+ id.concat("iptext") + ' rows="2" cols="33"></textarea> <input class="send" type="button" name=sendButton id="send" value="Send" onClick="sendText(\''+ id +'\');"></form></div></div>';
                
                document.getElementsByTagName("body")[0].innerHTML = document.getElementsByTagName("body")[0].innerHTML + element;

                popups.unshift(id);
                optext.unshift("");

                calculate_popups();
                for(var iii = 0; iii < popups.length; iii++)
                        {
                            document.getElementById(popups[iii].concat("optext")).value = optext[iii];
                        }

            }

            //calculate the total number of popups suitable and then populate the toatal_popups variable.
            function calculate_popups()
            {
                var width = window.innerWidth;
                if(width < 540)
                {
                    total_popups = 0;
                }
                else
                {
                    width = width - 200;
                    //320 is width of a single popup box
                    total_popups = parseInt(width/320);
                }

                display_popups();

            }

            //recalculate when window is loaded and also when window is resized.
            window.addEventListener("resize", calculate_popups);
            window.addEventListener("load", calculate_popups);

        </script>
    </head>
    <body>
        <div class="chat-sidebar">
            <?php
                $connection = mysqli_connect("localhost", "admin", "redhat");
                // Selecting Database
                    $db = mysqli_select_db($connection, "cmpe281");
                    $community = $_SESSION['community'];
                    // SQL query to fetch information of registerd users and finds user match.
                    $query = mysqli_query($connection, "select `groupname` from groups where community = '$community';");
                    $rows = mysqli_num_rows($query);
                    if ($rows > 0) {
                        while ($user = $query->fetch_assoc()) {
                ?>
                    <div class="sidebar-name">
                        <!-- Pass username and display name to register popup -->
                        <a href="javascript:register_popup('<?php echo($user["groupname"]) ?>', '<?php echo($user["groupname"]) ?>');">
                            <img width="30" height="30" src="http://4c3f12db975a1c8b62fd-ee282e5b70d98fac94cba689ef7806d7.r43.cf1.rackcdn.com/default_group_normal.png" />
                            <span><?php echo($user["groupname"]) ?></span>
                        </a>
                    </div>
            
                <?php
                        }
                    }
                ?>
            
            <?php
                $connection = mysqli_connect("localhost", "admin", "redhat");
                // Selecting Database
                    $db = mysqli_select_db($connection, "cmpe281");
                    $community = $_SESSION['community'];
                    // SQL query to fetch information of registerd users and finds user match.
                    $query = mysqli_query($connection, "select login.`username`, `first name`, `last name`, `picurl` from userdata, login where login.username = userdata.username and login.community_name = '$community';");
                    $rows = mysqli_num_rows($query);
                    if ($rows > 0) {
                        while ($user = $query->fetch_assoc()) {
                ?>
                    <div class="sidebar-name">
                        <!-- Pass username and display name to register popup -->
                        <a href="javascript:register_popup('<?php echo($user["username"]) ?>', '<?php echo($user["first name"]." ".$user["last name"]) ?>');">
                            <img width="30" height="30" src="<?php echo($user["picurl"]) ?>" />
                            <span><?php echo($user["first name"]) ?></span>
                        </a>
                    </div>
            
                <?php
                        }
                    }
                ?>
        </div>
        <?php
        if ($_SESSION['role'] == "admin"){
            ?>
            <h1>You are logged in as Administrator</h1>
            <ul>
                <li><h3><a href = "createcomm.php">Create a community</a></h3></li>
                <li><h3><a href = "editcomm.php">Edit Community Managers</a></h3></li>
            </ul>
            <?php
        }
        ?>
        
        <?php
        if ($_SESSION['role'] == "manager"){
            ?>
            <h1>You are logged in as Community Manager of <?php echo($_SESSION['community']);?></h1>
            <ul>
                <li><h3><a href = "creategroup.php">Create a Group</a></h3></li>
                <li><h3><a href = "editgroups.php">Remove Groups</a></h3></li>
                <li><h3><a href = "mnggroups.php">Manage Group Members</a></h3></li>
                <li><h3><a href = "remmembers.php">Remove Community Members</a></h3></li>
            </ul>
            <?php
        }
        ?>
    </body>
</html>