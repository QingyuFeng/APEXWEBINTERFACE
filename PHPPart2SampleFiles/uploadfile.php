<?php

session_start();

include("top.txt");
?>

<h3>Upload a file</h3>

<p>You can add files to the system for review by an administrator.
    Click <b>Browse</b> to select the file you'd like to upload,
    and then click <b>Upload</b>.</p>

<form action="uploadfile_action.php" method="POST"
      enctype="multipart/form-data">
    <input type="file" name="ufile" \>
    <input type="submit" value="Upload" \>
</form>

<?php
   include("bottom.txt");
?>