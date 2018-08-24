
<?php

   include("top.txt");

?>

<h1>Register for an Account:</h1>
<form action="registration_action.php" method="POST">

   Username: <input type="text" name="name" /><br />
   Email: <input type="text" name="email" /><br />
   Password: <input type="password" name="pword[]" /><br />
   Password (again): <input type="password" name="pword[]" /><br />
   <input type="submit" value="GO" />

</form>
                                                                 vu
<?php

   include("bottom.txt");

?>