<?
  session_start();
   session_destroy();

   include("top.txt");
   echo "Thank you for using the Workflow System.  You may <a href=\"login.php\">log in again</a>.";
   include("bottom.txt");
?>