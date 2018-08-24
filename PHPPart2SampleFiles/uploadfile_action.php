<?php
    session_start();

   include("top.txt");
   include("scripts.txt");

   if(isset($_FILES['ufile']['name'])){
       echo "<p>Uploading: ".$_FILES['ufile']['name']."</p>";

       $tmpName = $_FILES['ufile']['tmp_name'];
       $newName = UPLOADEDFILES . $_FILES['ufile']['name'];

       if(!is_uploaded_file($tmpName) ||
                            !move_uploaded_file($tmpName, $newName)){
            echo "FAILED TO UPLOAD " . $_FILES['ufile']['name'] .
                 "<br>Temporary Name: $tmpName <br>";
       } else {

           save_document_info_json($_FILES['ufile']);

           echo "<h3>Available Files</h3>";

           display_files();
       }

   } else {
     echo "You need to select a file.  Please try again.";
  }
   include("bottom.txt");
?>