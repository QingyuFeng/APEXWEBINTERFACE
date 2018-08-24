<?php

   include("top.txt");
   require("scripts.txt");
?>

<h1>Please log in</h1>
<form action="login_action.php" method="post">
Username: <input type="text" name="username" /><br />
Password: <input type="password" name="password" /><br />
<input type="submit" value="Log In" />
</form>

<?php
   include("bottom.txt");
?>