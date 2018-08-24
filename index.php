<?php

/*  License:

#############################################################################
#
#   APEX Web Interface
#   Copyright (C) Qingyu Feng
#
#   This is free software protected under the terms of the GNU General Public
#   License (GPL) as published by the Free Software Foundation (i.e. a free
#   and open source software (FOSS) license).
#
#   Under this license YOU CAN FREELY:
#         Execute, study, copy, modify, or distribute the software or any
#         derivatives thereof ONLY UNDER THE CONDITIONS OF THIS LICENSE
#         (i.e. under this FOSS license).
#
#   Under this license YOU CANNOT:
#         Sell THIS SOFTWARE OR ANY DERIVATIVES (i.e. your own modifications)
#         thereof without the explicit permission of the author/developer.
#
#   If you do use, test, or distribute this software, please provide an
#   appropriate citation in published work as follows:
#
#   Qingyu Feng
#   Department of Agricultural and biological Engineering
#	Purdue University
#	225 South University Street
#	West Lafayette, IN47906
#   E-mail: qyfeng86@hotmail.com
#
#   This program is distributed in the hope that it will be useful,
#   but WITHOUT ANY WARRANTY; without even the implied warranty of
#   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
#   GNU GPL for more details.
#
#############################################################################
*/















    // Get config file
    require_once("config.php");
    // Get header files  
    include("html/header.html");
    
    // If the upload button is pressed
    if (isset($_POST['btn_uploadproj']))
    {
        // Check whether file is selected
        if(isset($_FILES['ufile']['name']))
        {
            $JS_UPLOAD = $_FILES['ufile']['tmp_name'];
            // is_uploaded_file: tells whether the file 
            // was uploaded via HTTP POST
            // If not, display error
            if(!is_uploaded_file($JS_UPLOAD))
            {
                 $error[] = "FAILED TO UPLOAD " . $_FILES['ufile']['name'] .
                      "<br>Temporary Name: $JS_UPLOAD <br>";
            }
            else 
            {
                // If uploaded succeed,
                // get the file information into php array
                $_SESSION["PROJDATA"] = json_decode(file_get_contents($JS_UPLOAD), true);
                
                header('Location: apexui.php');
                exit;
            }

        }
        else 
        {
            $error[] = "You need to select a file.  Please try again.";
        }
        
        
    }
    
    elseif(isset($_POST['btn_newproj']))
    {
        //very basic validation
        if(strlen($_POST['usrprojnm']) < 3)
        {
            $error[] = 'Username is too short.';
        } 
        if(!isset($error))
        {
            // Read in default json file
            $_SESSION["PROJDATA"] = json_decode(file_get_contents(JS_DFTPROJ), true);
            
            //redirect to index page
            $_SESSION["PROJDATA"]["projectname"] = str_replace(' ','',$_POST['usrprojnm']);
            
            // With this template ID, the user mgtarray will be generated
            // for further manipulation and store in user project
            // Get value detail from default mgt detail table
            // For management, if the user used default value, management details
            // will not be populated. The management values will be get from
            // the database while writing OPS files.
            $tmpmgtid = $_SESSION["PROJDATA"]["management"]["default"]["templateid"];
            //$mgtarrayusr = $managefuns->gettemplatevalue($tmpmgtid, $db);
            // Get mgt name from default list table
            $tmplatename = $managefuns->getmgttmpnm($tmpmgtid, $db);

            
            // Update default man information from database
            $man_name = "Default_".$managefuns->getmgttmpnm($tmpmgtid, $db)["opsschdlname"];
            $_SESSION["PROJDATA"]["management"]["default"]["mgtname"] =$man_name;
            // Populate management information to the project data
            //$managefuns->updateProjectMan("man0", $mgtarrayusr);
            
            // Direct to the interface
            header('Location: apexui.php');
            exit;
        }
    }   
    
    
    
?>
    <h1>Agricultural Policy Environmental eXtender (APEX)</h1>
    <?php
        //check for any errors
        if(isset($error)){
                foreach($error as $error){
                        echo '<p class="bg-danger">'.$error.'</p>';
                }
        }

    ?>

    



    <form method="POST" 
          enctype="multipart/form-data">
        
        <h2>Enter a name to start a new project</h2>
        <input type="text" 
               name="usrprojnm" 
               id="usrprojnm" 
               class="input" 
               placeholder="Project Name" 
               value="" 
               tabindex="1">
        <input type="submit" 
               name="btn_newproj" 
               value="Start new project">  
        <br>
        <h2>Or, upload a project file to start</h2>
        <input type="file" 
               name="ufile">
        <input type="submit" 
               name="btn_uploadproj" 
               value="Upload old project">
    </form>


<?php
    include("html/footer.html");
?>