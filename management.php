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
    
    // First, check whether the project hame has been named
    // and initiated.
    $idsp_mgtcontdiv = "none";
    $idsp_mgtlistdiv = "none";
    $idsp_mgtdtldiv = "none";
    $idsp_mgteditdiv = "none";
    $idsp_editbtn = "none";
    $idsp_usrmgtlstbtn = "none";
    if (!isset($_SESSION["PROJDATA"]))
    {
        $error[]="ERROR: You do not have a project yet. Please go back to the home "
                . "page and create one!!!";
    }
    
    else
    {
        // First set display of the webpage
        $idsp_mgtcontdiv = "block";
        $idsp_mgtlistdiv = "block";

        // Incoming variables from url
        // management key (man_key) is the communication between
        // the apexui.php and this page. Both page depends on 
        // this key to get the name and other information of the 
        // selected managements.
        if (!isset($_REQUEST['mk']))
        {$usr_mankey=$_SESSION["PROJDATA"]["dfmk"];}
        else{$usr_mankey = $_GET["mk"];}
        
        if (!isset($_REQUEST['rowid']))
        {$rowid=null;}
        else{$rowid = $_GET["rowid"];}
                   
        // Default values for the selection list
        $tmplateidx = null;
        $usrmanlstidx = null;
        // Set the default value of display array for mgt
        // Also a reset the value for prevention of messing up.
        $mgtarrayusr = array();

        // Get the basic information of current user management
        // 1. Get the list of mgt
        // 2. Get the maximum order number for inserting
        // 3. Get the total management number
        // 4. Get the number of user management. Since default will also
        // be inserted with only four values.
        $usrmgttotalno = count($_SESSION["PROJDATA"]["management"]);
        $usrmgtno = 0;
        $manlistmaxorder = 0;
        $usrmgtlist = array();
        $projmankeys = array_keys($_SESSION["PROJDATA"]["management"]);
        
        for ($pmid=0; $pmid<$usrmgttotalno; $pmid++)
        {
            $tk = $projmankeys[$pmid];
            if (strcmp($_SESSION["PROJDATA"]["management"][$tk]["mansource"], "user")==0)
            {
                $usrmgtlist[$tk] = $_SESSION["PROJDATA"]["management"][$tk]["mgtname"];
                $usrmgtno = $usrmgtno+1;
            }
            // Update the maximum order number
            if ($_SESSION["PROJDATA"]["management"][$tk]["usrmanorder"]>$manlistmaxorder)
            {
                $manlistmaxorder = $_SESSION["PROJDATA"]["management"][$tk]["usrmanorder"];
            }
        }
        if ($usrmgtno >0)
        {
            $idsp_usrmgtlstbtn = "block";
        }

        // This page offers several functions:
        // 1. Show template details in display table
        // 2. Create new management
        // 3. Create from blank (Only one line with edit table displaying)
        // 4. Select user management to show details
        // 5. Select to edit
        // 6. Select to delete
        // 7. Select to use in simulation
        // Each action will work on the user project input array.
        // All action was ordered by pressing the button, submit corresponding
        // button, send commond through url, get commond and run block of code.
        if (!isset($_REQUEST['iact']))
        {$iact=null;}
        else{$iact = $_GET["iact"];}

        if (!is_null($iact))
        {
            switch ($iact)
            {
                case "showtmpdtl":
                    $mgtarrayusr = array();
                    // The selection is can be made by two ways:
                    // 1. user click on it.
                    // 2. Get from the apexui.php through man key.

                    if (!isset($_REQUEST["tmpIdx"]))
                    {
                        // If not set, from tmpidx, the user pressed the detail button
                        // in the apexui.php. 
                        // Then, check whether the management from the apexui.php is 
                        // user or dft. If user, get from the project input.
                        if (strcmp($_SESSION["PROJDATA"]["management"][$usr_mankey]["mansource"], "user")==0)
                        {
                            // Set the default value of user mgt selection
                            $usrmanlstidx = $usr_mankey;
                            $tmplateidx = null;
                            $mgtarrayusr = $_SESSION["PROJDATA"]["management"][$usr_mankey]["mgtarray"];
                        }
                        // else, if it is from the default database, get the default 
                        // template id and get data from database for display.
                        // This is from the apexui.php page.
                        elseif(strcmp($_SESSION["PROJDATA"]["management"][$usr_mankey]["mansource"], "dft")==0)
                        {
                            $tmplateidx=$_SESSION["PROJDATA"]["management"][$usr_mankey]["templateid"];
                            $mgtarrayusr = $managefuns->gettemplatevalue($tmplateidx, $db);
                            $usrmanlstidx = null;
                        }
                    }
                    else
                    {   
                        // If the user pressed the show detailed button,
                        // update template idx and get from the detailed database.
                        $tmplateidx = $_GET["tmpIdx"];
                        $mgtarrayusr = $managefuns->gettemplatevalue($tmplateidx, $db);
                        $usrmanlstidx = null;
                    }
                    
                    // Setup the table for display
                    $idsp_mgtdtldiv = "block";
                    break;
                    
                case "createtempman":
                    // Create takes the following tasks
                    // 1. get the user type name
                    $usrtypename = "User_".$_GET["typenm"];

                    foreach ($usrmgtlist as $usmgt)
                    {
                        if (strcmp($usmgt, $usrtypename)==0)
                        {
                            $error[] = "You already has one management that "
                                    . "has the name".$usrtypename." in your database."
                                    . "Please create a different name or select"
                                    . "to edit the existing one.";
                            break;
                            
                        }
                    }
                    
                    if (!isset($error))
                    {
                        
                        // Reset the mgtarrayuser everytime it is updated
                        $mgtarrayusr = array();
                        // If there is no repetation of the name, go update
                        // the user project input database.
                        $tmplateidx = $_GET["tmpIdx"];
                        
                        // Get the template values
                        $mgtarrayusr = $managefuns->gettemplatevalue($tmplateidx, $db);
                        // Update input value
                        // Need man key
                        
                        $manlistmaxorder = $manlistmaxorder+1;
                        $create_mankey = "man".$manlistmaxorder;
                        // Update the user project input array for the name
                        $_SESSION["PROJDATA"]["management"][$create_mankey]["mgtname"] = $usrtypename;
                        $_SESSION["PROJDATA"]["management"][$create_mankey]["usrmanorder"]=$manlistmaxorder;
                        $_SESSION["PROJDATA"]["management"][$create_mankey]["templateid"]=intval($tmplateidx);
                        $_SESSION["PROJDATA"]["management"][$create_mankey]["mansource"]="user";
                        //echo "Modifying<br>";

                        $managefuns->updateProjectMan($create_mankey, $mgtarrayusr);
                        
                        // Update the default value of 
                        // user management selection list.
                        // Display settings
                        $usrmanlstidx = $create_mankey;
                    }
                    elseif(isset($error))
                    {
                        break;
                    }
                    // When directing, not run the create block
                    // of code.
                    if ($usrmgtno >0)
                    {
                        $idsp_usrmgtlstbtn = "block";
                        $idsp_mgtdtldiv = "block";
                    }
                    header("Location: management.php?mk=".$create_mankey."&iact=showtmpdtl");
                    exit;
                
                case "createblankman":
                    echo "under development...";
                    break;
                
                case "useTemplate":
                    
                    // use template does the following things:
                    // 1. Get the template id.
                    // 2. Update the database by creating a new man name
                    // 3. redirect the page to the  apexui.php
                    $tmplateidx = $_GET["tmpIdx"];
                    // Check whether this exists in the user database
                    // If yes, skip updating it, directly go to the apexui.php
                    if ($_SESSION["PROJDATA"]["management"]["default"]["templateid"]==$tmplateidx)
                    {
                        echo "using default<br>";
                        header("Location: apexui.php?mk=default");
                        exit;
                    }
                    else
                    {
                        $iexist = null;
                        $sendkey = null;
                        // Check the existence with other man
                        if ($usrmgttotalno>2)
                        {
                            for ($ckid=1; 
                                $ckid < $usrmgttotalno;
                                $ckid++)
                                {
                                    $ch_mankey = "man".$ckid;
                                    if ($_SESSION["PROJDATA"]["management"][$ch_mankey]["templateid"]==$tmplateidx)
                                    {
                                        $iexist = "YES";
                                        $sendkey = $ch_mankey;
                                        break;
                                    }

                                }
                        }
                        if(is_null($iexist))
                        {
                            // If it has not been used, update the user project input
                            // Get the management name
                            $man_name = "Default_".$managefuns->getmgttmpnm($tmplateidx, $db)["opsschdlname"];
                            
                            $manlistmaxorder = $manlistmaxorder+1;
                            $ckey= "man".$manlistmaxorder;
                            
                            $_SESSION["PROJDATA"]["management"][$ckey]["usrmanorder"]=$manlistmaxorder;
                            $_SESSION["PROJDATA"]["management"][$ckey]["templateid"]=$tmplateidx;
                            $_SESSION["PROJDATA"]["management"][$ckey]["mansource"]="dft";
                            $_SESSION["PROJDATA"]["management"][$ckey]["mgtname"]=$man_name;
                            // Then, redirect the page
                            
                            // Before redirecting, update the default man key value
                            $_SESSION["PROJDATA"]["dfmk"]=$ckey;
                            header("Location: apexui.php");
                            exit;
                        }
                        elseif(strcmp($iexist, "YES")==0)
                        {
                            // If exists, do not insert new, direct to the page directly
                            $_SESSION["PROJDATA"]["dfmk"]=$sendkey;
                            header("Location: apexui.php");
                            exit;
                        }
                    }

                case "editUsrMan":
                    
                    // These following things need to be in this task
                    // Only user management will show up.
                    // 1. Get the mgt array from the user mgt by the key.
                    // 2. Display: 
                    //    Enable display table and editbutton 
                    
                    // Get the array
                    $mgtarrayusr = array();
                    $mgtarrayusr = $_SESSION["PROJDATA"]["management"][$usr_mankey]["mgtarray"];
                    
                    // Update display:
                    $usrmanlstidx = $usr_mankey;
                    $idsp_mgtdtldiv = "block";
                    $idsp_editbtn = "block";
                    
                    // Get the second variable for further edits
                    // These include:
                    // 1. delete
                    // 2. add
                    // 3. editrow
                    // 4. save edits
                    // 5. cancel edits
                    // 6. reset edits 
                    if (!isset($_REQUEST['iactedt']))
                    {$iactedt=null;}
                    else{$iactedt = $_GET["iactedt"];}

                    if (!is_null($iactedt))
                    {
                    // Under edit, there will be a series of operation again
                    switch ($iactedt)
                    {
                        case "delete":
                            // To delete, these things need to be done
                            // 1. Delete the array element in the array, 
                            // 2. unset user project mgtarray
                            // 3. update user project mgtarray with new array
                            $delrowid = $rowid;
                            // 2. modify array using splice
                            array_splice($mgtarrayusr, intval($delrowid)-1, 1);
                            unset($_SESSION["PROJDATA"]["management"][$usr_mankey]["mgtarray"]);
                            $managefuns->updateProjectMan($usr_mankey, $mgtarrayusr);
                            $rowid = null;
                            break;
                            
                        case "add":
                            // To add, similar like delete
                            // 1. delete array element
                            // 2. unset user project mg
                            // 3. update user project input array
                            $addrowid = $rowid;
                            
                            // Also the mgtarrayusr need to be updated for further operation
                            // like add or edit. Every operation refer back to the source.
                            // the project user input array.
                            $mgtarrayusr = array();
                            $mgtarrayusr = $_SESSION["PROJDATA"]["management"][$usr_mankey]["mgtarray"];
                       
                            $insertrow = array();
                            $insertrow[]=$mgtarrayusr[(intval($addrowid)-1)];
                            array_splice($mgtarrayusr, intval($addrowid), 0, $insertrow);
        
                            unset($_SESSION["PROJDATA"]["management"][$usr_mankey]["mgtarray"]);
                            $managefuns->updateProjectMan($usr_mankey, $mgtarrayusr);
                            
                            $mgtarrayusr = array();
                            $mgtarrayusr = $_SESSION["PROJDATA"]["management"][$usr_mankey]["mgtarray"];
                       
                            break;
                        
                        case "edit":
                            // Show the edit table
                            $idsp_mgteditdiv = "block";
                            
                            // Get the row id
                            $edtrowid = intval($rowid)-1;
                            
                            // Initialize the mgtarray from the project input
                            $mgtarrayusr = array();
                            $mgtarrayusr = $_SESSION["PROJDATA"]["management"][$usr_mankey]["mgtarray"];

                            // Initialize get the variables for display.
                            // At this time, no edits is done and only display is
                            // required, 
                            $manedttillcat=$mgtarrayusr[$edtrowid]["opercatgries"];
                            $tillnameid=$mgtarrayusr[$edtrowid]["jx4_tillid"];
                            $cropid=$mgtarrayusr[$edtrowid]["jx6_cropid"];
                            $chemid=$mgtarrayusr[$edtrowid]["jx7"];
                            $chmamt=$mgtarrayusr[$edtrowid]["opv1"];

                            // Get the new tillage category value
                            if (!isset($_REQUEST['inwyr']))
                            {$manedtyr=$mgtarrayusr[$edtrowid]["jx1_year"];}
                            else{$manedtyr = $_GET["inwyr"];}
                            
                            // Get the new tillage category value
                            if (!isset($_REQUEST['inwmon']))
                            {$manedtmon=$mgtarrayusr[$edtrowid]["jx2_month"];}
                            else{$manedtmon = $_GET["inwmon"];}

                            // Get the new tillage category value
                            if (!isset($_REQUEST['inwdy']))
                            {$manedtday=$mgtarrayusr[$edtrowid]["jx3_day"];}
                            else{$manedtday = $_GET["inwdy"];}
                            
                            // Get the new tillage category value
                            if (!isset($_REQUEST['inwtl']))
                            {$inewtl=null;}
                            else{$inewtl = $_GET["inwtl"];}

                            //Run the following code to update the category
                            if (!is_null($inewtl))
                            {
                                // Get the new selected value
                                if (!isset($_REQUEST['ntlcid']))
                                {$nwtlcid=null;}
                                else{$nwtlcid = $_GET["ntlcid"];}
                                // Get the category name
                                $tillcat = $managefuns->gettillcatnm($db, $nwtlcid);
                                $manedttillcat=$tillcat["tillcategories"];
                            }
                            
                            // The next step was to check whether the save
                            // row edits button was clicked.
                            if (!isset($_REQUEST['isaverow']))
                            {$isave=null;}
                            else{$isave = $_GET["isaverow"];}
            
                            if (!is_null($isave))
                            {
                                // Update the values for display
                                if (!isset($_POST['finyear']))
                                {$manedtyr=$mgtarrayusr[$edtrowid]["jx1_year"];}
                                else
                                {
                                    $manedtyr = $_POST['finyear'];
                                    $mgtarrayusr[$edtrowid]["jx1_year"]=$_POST['finyear'];
                                }
                                

                                if (!isset($_POST['finmon']))
                                {$manedtmon=$mgtarrayusr[$edtrowid]["jx2_month"];}
                                else
                                {
                                    $manedtmon = $_POST['finmon'];
                                    $mgtarrayusr[$edtrowid]["jx2_month"]=$_POST['finmon'];
                                }	

                                if (!isset($_POST['finday']))
                                {$manedtday=$mgtarrayusr[$edtrowid]["jx3_day"];}
                                else
                                {
                                    $manedtday = $_POST['finday'];
                                    $mgtarrayusr[$edtrowid]["jx3_day"] = $_POST['finday'];
                                }

                                if (!isset($_POST['fintillcateg']))
                                {$tlctid=$mgtarrayusr[$edtrowid]["opercatgries"];}
                                else
                                {
                                    $tlctid = $_POST['fintillcateg'];
                                    $tlcnm = $managefuns->gettillcatnm($db, $tlctid);
                                    $manedttillcat=$tlcnm["tillcategories"];
                                    $mgtarrayusr[$edtrowid]["opercatgries"]=$tlcnm["tillcategories"];
                                }	

                                if (!isset($_POST['fintillname']))
                                {$tillnameid=$mgtarrayusr[$edtrowid]["jx4_tillid"];}
                                else
                                {
                                    $tillnameid = $_POST['fintillname'];
                                    $mgtarrayusr[$edtrowid]["jx4_tillid"]=$_POST['fintillname'];
                                }

                                if (!isset($_POST['cropid']))
                                {$cropid=$mgtarrayusr[$edtrowid]["jx6_cropid"];}
                                else
                                {
                                    $cropid = $_POST['cropid'];
                                    $mgtarrayusr[$edtrowid]["jx6_cropid"] = $_POST['cropid'];
                                }

                                if (!isset($_POST['chemid']))
                                {$chemid=$mgtarrayusr[$edtrowid]["jx7"];}
                                else
                                {
                                    $chemid = $_POST['chemid'];
                                    $mgtarrayusr[$edtrowid]["jx7"] = $_POST['chemid'];
                                }

                                if (!isset($_POST['chemamt']))
                                {$chmamt=$mgtarrayusr[$edtrowid]["opv1"];}
                                else
                                {
                                    $chmamt = $_POST['chemamt'];
                                    $mgtarrayusr[$edtrowid]["opv1"] = $_POST['chemamt'];
                                }

                                // After getting all of the information from the user
                                // input table.
                                // Update the user project input array;
                                unset($_SESSION["PROJDATA"]["management"][$usr_mankey]["mgtarray"]);
                                
                                // The user input date should be checked and adjusted based on dates.
                                // All the array are there, so they should be reordered.
                                //
                                echo "checking order by date<br>";
                                $mgtsorting = $managefuns->sortMgtbyDate($mgtarrayusr, $edtrowid);
                                $mgtarrayusr = $mgtsorting["newarray"];
                                $newid = intval($mgtsorting["newrowid"])+1;
                                
                                $managefuns->updateProjectMan($usr_mankey, $mgtarrayusr);
                                
                                $mgtarrayusr = array();
                                $mgtarrayusr = $_SESSION["PROJDATA"]["management"][$usr_mankey]["mgtarray"];
                                            
                                // Also, the page need to be directed to
                                // the state where no edits was performed.
                                header("Location: management.php?mk=".$usr_mankey."&iact=editUsrMan&rowid=".$newid);
                                exit;                                
                            }
                    }       
                    }
                    break;
                    
                case "delUsrMan":
                    echo "deleting<br>";
                    // Deleting user management requires the following:
                    // 1. Unset the corresponding man name in the user project input
                    // 2. redirect the page to original, so that the display will also be
                    // updated.
                    unset($_SESSION["PROJDATA"]["management"][$usr_mankey]);
                    
                    // Redirect
                    header("Location: management.php");
                    exit;
                
                case "useUsrMan":
                    // Use User man requires sending the
                    // selected man key to the apexui.php
                    $_SESSION["PROJDATA"]["dfmk"]=$usr_mankey;
                    header("Location: apexui.php");
                    exit;                    
                            
                case "renameUsrMan":
                    echo "renaming";
                    
                    // Rename does the following:
                    // 1. Get the new name
                    // 2. Update the user project
                    // 3. redirect the page
                    $newusrmanmame = $_GET["nm"];
                    $_SESSION["PROJDATA"]["management"][$usr_mankey]["mgtname"]="User_".$newusrmanmame;
                    // Redirect
                    header("Location: management.php");
                    exit;                    
                    
                case "cancelEditMan":    
                    // To cancel
                    // Disable the buttons
                    echo "cancel";
                    // Get the array
                    $mgtarrayusr = array();
                    $mgtarrayusr = $_SESSION["PROJDATA"]["management"][$usr_mankey]["mgtarray"];
                    
                    // Update display:
                    $usrmanlstidx = $usr_mankey;
                    $idsp_mgtdtldiv = "block";
                    
            }
        }
   
    
        
        

        // Get database array for display
        $tillagearray = array();
        $tillagearray = listTillage_namearray($Connection, $tillagearray);

        $cropnamearray = array();
        $cropnamearray = listcrop_namearray($Connection, $cropnamearray);

        $fertarray = array();
        $pestarray = array();
        $fertarray = listfert_namearray($Connection, $fertarray);
        $pestarray = listpest_namearray($Connection, $pestarray);
        
        
        $tmplist = $managefuns->getdftmgtlist($db);
    }
?>


<script>
    function showTemplateDtl()
    {
        var tmpselidx = document.getElementById("tmpmgtlst").selectedIndex;
        var tmpid = document.getElementById("tmpmgtlst").options[tmpselidx].value;
        
        var mankey = "<?php echo $usr_mankey;?>";
        loc = "management.php?tmpIdx=" + escape(tmpid);
        loc = loc + "&mk=" + escape(mankey);
        loc = loc + "&iact=showtmpdtl";
        
        parent.location = loc;
    }
    
    function createTempMan()
    {
        var usrtypenm = document.getElementById("typedmgtnm").value;
        if (usrtypenm.length === 0)
        {
            alert('Management name is empty!!! Please enter a valid name');
            return;
        }

        var tmpselidx = document.getElementById("tmpmgtlst").selectedIndex;
        if (-1 === tmpselidx)
        {
            alert('Template is not selected. Please make a selection');
            return;
        }
        
        var tmpid = document.getElementById("tmpmgtlst").options[tmpselidx].value;
        
        loc = "management.php?tmpIdx=" + escape(tmpid);
        loc = loc + "&typenm=" + escape(usrtypenm);
        loc = loc + "&iact=createtempman";
        
        parent.location = loc;
    }
    


    function useTemplate()
    {
        var tmpselidx = document.getElementById("tmpmgtlst").selectedIndex;
        if (-1 === tmpselidx)
        {
            alert('Template is not selected. Please make a selection');
            return;
        }
        
        var tmpid = document.getElementById("tmpmgtlst").options[tmpselidx].value;
        var loc = "management.php";

        loc = loc + "?tmpIdx=" + tmpid;
        loc = loc + "&iact=useTemplate";
        parent.location = loc;
    }

    function showUsrManDtl()
    {
        var usrmanidx = document.getElementById("usrmgtlst").selectedIndex;
        var usrmankey = document.getElementById("usrmgtlst").options[usrmanidx].value;
        loc = "management.php";
        loc = loc + "?mk=" + escape(usrmankey);
        loc = loc + "&iact=showtmpdtl";
        parent.location = loc;
    }

    function editUserMan()
    {
        var usrmanidx = document.getElementById("usrmgtlst").selectedIndex;
        
        if (-1 === usrmanidx)
        {
            alert('Management is not selected. Please make a selection');
            return;
        }
        var usrmankey = document.getElementById("usrmgtlst").options[usrmanidx].value;
        loc = "management.php";
        loc = loc + "?mk=" + escape(usrmankey);
        loc = loc + "&iact=editUsrMan";
        parent.location = loc;
    }

    function deleteRow(obj)
    {
        var rowidj = obj.parentNode.parentNode.rowIndex; 
        var usrmankey = "<?php echo $usr_mankey; ?>";
        
        loc = "management.php";
        loc = loc + "?mk=" + escape(usrmankey);
        loc = loc+ "&rowid=" + rowidj;
        loc = loc + "&iact=editUsrMan";
        loc = loc + "&iactedt=delete";
        parent.location = loc;
    }

    function addRow(obj)
    {
        var rowidj = obj.parentNode.parentNode.rowIndex; 
        var usrmankey = "<?php echo $usr_mankey; ?>";
        
        loc = "management.php";
        loc = loc + "?mk=" + escape(usrmankey);
        loc = loc+ "&rowid=" + rowidj;
        loc = loc + "&iact=editUsrMan";
        loc = loc + "&iactedt=add";
        parent.location = loc;
    }
    
    function editRow(obj)
    {
        var rowidj = obj.parentNode.parentNode.rowIndex; 
        var usrmankey = "<?php echo $usr_mankey; ?>";
        
        loc = "management.php";
        loc = loc + "?mk=" + escape(usrmankey);
        loc = loc+ "&rowid=" + rowidj;
        loc = loc + "&iact=editUsrMan";
        loc = loc + "&iactedt=edit";
        parent.location = loc;
    }
    
    function newTillCate()
    {
        var rowidj = "<?php echo $rowid; ?>";
        var usrmankey = "<?php echo $usr_mankey; ?>";
        loc = "management.php";
        loc = loc + "?mk=" + escape(usrmankey);
        loc = loc+ "&rowid=" + rowidj;
        loc = loc + "&iact=editUsrMan";
        loc = loc + "&iactedt=edit";
        loc = loc + "&inwtl=yes";
        
        var newyr = document.getElementById("finyear").value;
        var newmon = document.getElementById("finmon").value;
        var newday = document.getElementById("finday").value;
        loc = loc + "&inwyr=" + newyr;
        loc = loc + "&inwmon=" + newmon;
        loc = loc + "&inwdy=" + newday;
        
        var newtlcselidx = document.getElementById("fintillcateg").selectedIndex;
        var newtlcid = document.getElementById("fintillcateg").options[newtlcselidx].value;
        loc = loc + "&ntlcid=" + newtlcid;

        
        
        parent.location = loc;
    }
        
    function validmon(obj) 
    {
        // First check whether enter is number
        if (!/^[0-9]+$/.test(obj.value)) 
        { 
            alert("Please enter only numbers.");
            obj.value = obj.value.substring(0,obj.value.length-1);
        }
        
        if(obj.value>12)
        {
            alert("Month values can not be larger than 12.");
            obj.value = obj.value.substring(0,obj.value.length-1);
        }
    }

    function validday(obj) 
    {
        // First check whether enter is number
        if (!/^[0-9]+$/.test(obj.value)) 
        { 
            alert("Please enter only numbers.");
            obj.value = obj.value.substring(0,obj.value.length-1);
        }
        
        var mon31 = [1, 3, 5, 7, 8, 10, 12];
        var mon30 = [4, 6, 8, 10];
        
        var mon = document.getElementById("finmon").value;
        
        var iexist = "no";
        for (m31 in mon31)
        {
            if (mon31[m31].toString()=== mon)
            {
                iexist = "yes";
                if(obj.value>31)
                    {
                        alert("In month "+ mon +", you can have at most 31 days.");
                        obj.value = obj.value.substring(0,obj.value.length-1);
                    }
                break;
            };
        }
        
        if (!iexist==="no")
        {return;}
        else
        {
            for (m30 in mon30)
            {
                if (mon30[m30].toString()=== mon)
                {
                    iexist = "yes";
                    if(obj.value>30)
                        {
                            alert("In month "+ mon +", you can have at most 30 days.");
                            obj.value = obj.value.substring(0,obj.value.length-1);
                        }     
                    return;
                };
            }
        }
        
        if (!iexist==="no")
        {
            return;
        }
        else
        {
            iexist = "yes";
            if(obj.value>29)
            {
                alert("In month 2, you can have at most 29 days.");
                obj.value = obj.value.substring(0,obj.value.length-1);
            } 
        }
    }
    
    function delUserMan()
    {
        var usrmanidx = document.getElementById("usrmgtlst").selectedIndex;
        
        if (-1 === usrmanidx)
        {
            alert('Management is not selected. Please make a selection');
            return;
        }
        var usrmankey = document.getElementById("usrmgtlst").options[usrmanidx].value;
        loc = "management.php";
        loc = loc + "?mk=" + escape(usrmankey);
        loc = loc + "&iact=delUsrMan";
        parent.location = loc;
    }

    function useUserMan()
    {
        var usrmanidx = document.getElementById("usrmgtlst").selectedIndex;
        
        if (-1 === usrmanidx)
        {
            alert('Management is not selected. Please make a selection');
            return;
        }
        var usrmankey = document.getElementById("usrmgtlst").options[usrmanidx].value;
        loc = "management.php";
        loc = loc + "?mk=" + escape(usrmankey);

        loc = loc + "&iact=useUsrMan";
        parent.location = loc;
    }    
    
    function renameUserMan()
    {
        var usrmanidx = document.getElementById("usrmgtlst").selectedIndex;
        
        if (-1 === usrmanidx)
        {
            alert('Management is not selected. Please make a selection');
            return;
        }
        var usrmankey = document.getElementById("usrmgtlst").options[usrmanidx].value;
        
        var newname = prompt("Enter your name : ", "your name here");
        if (newname.length === 0)
        {
            alert('Management name is empty!!! Please enter a valid name');
            return;
        }

        loc = "management.php";
        loc = loc + "?mk=" + escape(usrmankey);
        loc = loc + "&nm=" + escape(newname);
        loc = loc + "&iact=renameUsrMan";
        parent.location = loc;
    }   

    function cancelEditRow()
    {
        var rowidj = "<?php echo $rowid; ?>"; 
        var usrmankey = "<?php echo $usr_mankey; ?>";
        loc = "management.php";
        loc = loc + "?mk=" + escape(usrmankey);
        loc = loc+ "&rowid=" + rowidj;
        loc = loc + "&iact=editUsrMan";
        parent.location = loc;
    }

    function cancelEditMan()
    {
        var usrmankey = "<?php echo $usr_mankey; ?>";
        loc = "management.php";
        loc = loc + "?mk=" + escape(usrmankey);
        loc = loc + "&iact=cancelEditMan";
        parent.location = loc;
    }    
</script>


    <?php
    //check for any errors
    if(isset($error)){
        foreach($error as $error){
            echo '<h1>'.$error.'</h1>';
        }
    }
    unset($error);
    ?>


<div id="man_container" style="display:<?php echo $idsp_mgtcontdiv;?>">

    <div id="man_lists" style="display:<?php echo $idsp_mgtlistdiv;?>">
    <form method="POST"
        name="mgtnmlist" 
        >
        <table id="tb_createmgt">
        <tr>
            <th>
                Template list&nbsp;
            </th>
            <th>
                User management list&nbsp;
            </th>


        </tr>

        <tr>
            <td>
                <select size=5
                        name="tmpmgtlst"
                        id="tmpmgtlst"
                        >
                    <?php 
                        $managefuns->listdftmgtlist($db, $tmplateidx);
                    ?>
                </select>
            </td>
            <td>
                <?php 
                    if ($usrmgtno>0)
                    {
                        echo "<select size=5 "
                        . "name='usrmgtlst' "
                                . "id='usrmgtlst'"
                                . ">";
                            $managefuns->listUserMan($usrmgtlist, $usrmanlstidx);
                        echo "</select>";
                    }
                    else
                    {
                        echo "You currently do not have any management yet,"
                        . "you can create your own management using the left panel.";
                    }

                ?>

            </td>

        </tr>

        <tr>
            <td>
                <input type="button"  
                        value="Show details"
                        name="btn_showtmpdtl"
                        id="btn_showtmpdtl"
                        onClick="Javacsript:showTemplateDtl()"
                        >
            </td>

            <td>
                <input type="button"  
                        value="Show details"
                        name="btn_showusrdtl"
                        id="btn_showusrdtl"
                        onClick="Javacsript:showUsrManDtl()"
                        style="display: <?php echo $idsp_usrmgtlstbtn;?>"
                    >
            </td>
        </tr>

        <tr>

            <td>
                New management name:
                <input type='text' name="typedmgtnm" value="" id="typedmgtnm">
            </td>

            <td>
                <input type="button"  
                        value="Select to edit"
                        name="btn_editusrman"
                        id="btn_editusrman"
                        onClick="Javacsript:editUserMan()"
                        style="display: <?php echo $idsp_usrmgtlstbtn;?>"
                    >
                <input type="button"  
                        value="Cancel edit"
                        name="btn_canceledit"
                        id="btn_canceledit"
                        onClick="Javacsript:cancelEditMan()"
                        style="display: <?php echo $idsp_usrmgtlstbtn;?>"
                    >
            </td>

        </tr>

        <tr>

            <td>
                <input type="button"  
                        value="Create with selected template"
                        name="btn_createtemp"
                        id="btn_createtemp"
                        onClick="Javacsript:createTempMan()"
                        >
            </td>
            <td>
                <input type="button"  
                        value="Select to delete"
                        name="btn_delusrmgt"
                        onClick="Javacsript:delUserMan()"
                        style="display: <?php echo $idsp_usrmgtlstbtn;?>"
                    >
            </td>

        </tr>

        <tr>

            <td>
               
            </td>
            <td>
                <input type="button"  
                        value="Select to use"
                        name="btn_useusermgt"
                        onClick="Javacsript:useUserMan()"
                        style="display: <?php echo $idsp_usrmgtlstbtn;?>"
                    >
            </td>                        
        </tr> 

        <tr>

            <td>
                <input type="button"  
                        value="Use selected template"
                        name="btn_usetemp"
                        onClick="Javacsript:useTemplate()"
                        >
            </td>
            <td>
                <input type="button"  
                        value="Select to rename"
                        name="btn_rename"
                        onClick="Javacsript:renameUserMan()"
                        style="display: <?php echo $idsp_usrmgtlstbtn;?>"
                        >
            </td>                        
        </tr>                       
    </table>
    </form>  
    </div>
    <hr style="display:<?php echo $idsp_mgteditdiv;?>">

    <div id="man_edit" style="display:<?php echo $idsp_mgteditdiv;?>">
       
    <form method="POST"
        name="maneditormain"
        action ="management.php?mk=<?php echo $usr_mankey;?>&rowid=<?php echo $rowid;?>&iact=editUsrMan&iactedt=edit&isaverow=yes"
        >                   
        <table>
            <tr>
                <th bgcolor="#C0C0C0" 
                    colspan = "6"  
                    style = "text-align:left"
                    ><font size="3">
                    <b>Add or edit management operations</b></font></th>	
            </tr>	
            
            <tr>
                <td colspan ="1"  
                    bgcolor="#C0C0C0" 
                    style = "text-align:center">
                    Year&nbsp;
                </td>					

                <td  colspan ="1"
                     bgcolor="#FFFFCC" 
                     style = "text-align:center">
                    <input style="text-align: center" onkeyup= 'validinteger(this)'
                        type="text"
                        name="finyear"
                        id="finyear"
                        size="1" 
                        value="<?php echo $manedtyr;?>">&nbsp;
                </td>

                <td  colspan ="1" 
                     bgcolor="#C0C0C0" 
                     style = "text-align:center">
                        Month&nbsp;
                </td>					

                <td  colspan ="1"
                     bgcolor="#FFFFCC" 
                     id = "timon"  
                     style = "text-align:center">
                    <input style="text-align: center" onkeyup= 'validmon(this)'
                            type="text"
                            name="finmon"
                            id="finmon"
                            size="1" 
                            value="<?php echo $manedtmon;?>">
                </td>

                <td  colspan ="1"  
                     bgcolor="#C0C0C0" 
                     style = "text-align:center">
                        Day&nbsp;
                </td>					

                <td  colspan ="1"   
                     bgcolor="#FFFFCC" 
                     id = "tiday"  
                     style = "text-align:center">
                    <input style="text-align: center" 
                            onkeyup= "validday(this)"
                            type="text"
                            name="finday"
                            id="finday"
                            size="1" 
                            value="<?php echo $manedtday;?>">
                </td>		
            </tr>            
            
            <tr>
                <td  colspan ="1"  
                     bgcolor="#C0C0C0"
                     style = "text-align:center" >
                        Operation type&nbsp;
                </td>					
                <td  colspan ="2"
                    bgcolor="#FFFFCC"
                    style = "text-align:center"
                    colspan = "2">
                    <select size="1" 
                        name="fintillcateg"
                        id ="fintillcateg"
                        onChange="javascript:newTillCate();"
                        >
                        <?php 
//                                echo "<option>";
//                                echo "test";
//                                echo "</option>";
                                $managefuns->listtillagecate($db, $manedttillcat);
                                //listTillage_cate($Connection, null);
                        ?>
                        </select>
                </td>
                <td  colspan ="1" 
                    bgcolor="#C0C0C0" 
                    style = "text-align:center">
                       Operation name&nbsp;
                </td>				
                <td  colspan ="2"
                    bgcolor="#FFFFCC"
                    id = "titillname" 
                    style = "text-align:center">
                    <select size="1"
                        name="fintillname"
                        id ="fintillname"
                        >
                        <?php 
//                            echo "<option>";
//                            echo $manedttillcat;
//                            echo "</option>";
                            $managefuns->listtillagename($db, $manedttillcat, $tillnameid);
//                            listTillage_name($Connection, $manedttillcat, null); 
                        ?>
                        </select>
                </td>
            </tr>	

            <tr>
                <td colspan ="1"  
                    bgcolor="#C0C0C0" 
                    style = "text-align:center">
                        Crop name&nbsp;
                </td>					

                <td colspan ="1"  
                    bgcolor="#FFFFCC" 
                    id = "titillname"
                    style = "text-align:center" colspan = "2">

                    <?php
                        if (strpos($manedttillcat, 'Plant') !== false){
                            echo "<select size=\"1\" name=\"cropid\" id=\"cropid\">";
                            $managefuns->listcropnm($db, $cropid);
                            //listcrop_name($Connection, null);
                            echo "<\/select> ";
                            }
                    ?>
                </td>
                <td colspan ="1" 
                    bgcolor="#C0C0C0" 
                    style = "text-align:center" >
                        Chemical name&nbsp;
                </td>					

                <td colspan ="1"  
                    bgcolor="#FFFFCC" 
                    id = "titillcateg"  
                    style = "text-align:center" 
                    colspan = "2"  >
                    <?php
                        if (strpos($manedttillcat, 'Fertilize') !== false){
                            echo "<select size=\"1\" name=\"chemid\" id=\"chemid\">";
                                $managefuns->listfertnm($db, $chemid);
                            echo "<\/select>";
                            }
                        elseif(strpos($manedttillcat, 'pesticide') !== false)
                        {
                            echo "<select size=\"1\" name=\"chemid\" id=\"chemid\">";
                            $managefuns->listpestnm($db, $chemid);
                            echo "<\/select>";
                        }
                    ?>
                </td>

                <td colspan ="1" 
                    bgcolor="#C0C0C0"
                    style = "text-align:center">
                        Chemical Amount&nbsp;
                </td>					

                <td colspan ="1"  
                    bgcolor="#FFFFCC"
                    id = "titillname" 
                    style = "text-align:center"
                    colspan = "2">
                    <?php
                        if (strpos($manedttillcat, 'Fertilize') !== false ||
                            strpos($manedttillcat, 'pesticide') !== false){
                            echo "<input style=\"text-align: center\"";
                            echo "onkeyup= \"validinteger(this)\"";
                            echo "type=\"text\"";
                            echo "name=\"chemamt\"";
                            echo "id=\"chemamt\"";
                            echo "size=\"1\" ";
                            echo "onkeyup = \"validinteger(this)\"";
                            echo "value=\""
                            . "".$chmamt.""
                            . "\">";
                            }

                    ?>
                </td>       
            </tr>	            
        </table>
        
        <input type="submit"  
            value="Save row edits"
            id ="btn_saverowedit"
            name="btn_saverowedit"
            style="display: <?php echo $edittb;?>"
            >
        
        <input type="button"  
            value="Cancel row edits"
            id ="btn_cancelrowedit"
            name="btn_cancelrowedit"
            onClick="Javacsript:cancelEditRow()"
            style="display: <?php echo $edittb;?>"
            >
    </form>
    </div> 
    <hr>

    <div id="man_dtl" style="display:<?php echo $idsp_mgtdtldiv;?>">
        <table border="1"  
               id = "tiddisplay" 
               style="display: <?php echo $idsp_disptb;?>">
            <center>
                <tr>
                    <th bgcolor="#C0C0C0" style="display: <?php echo $idsp_editbtn;?>"><font size="3"><b>Act</b></font></th>
                    <th bgcolor="#C0C0C0"><font size="3"><b>Y</b></font></th>
                    <th bgcolor="#C0C0C0"><font size="3"><b>M</b></font></th>
                    <th bgcolor="#C0C0C0"><font size="3"><b>D</b></font></th>
                    <th bgcolor="#C0C0C0"><font size="3"><b>Operation</b></font></th>
                    <th bgcolor="#C0C0C0"><font size="3"><b>Type</b></font></th>
                    <th bgcolor="#C0C0C0"><font size="3"><b>Crop</b></font></th>
                    <th bgcolor="#C0C0C0"><font size="3"><b>Chemical</b></font></th>		
                    <th bgcolor="#C0C0C0"><font size="3"><b>Amt</b></font></th>		
                </tr>

		<?php 
                $lks = array_keys($mgtarrayusr);
                for ($rid=0; $rid<count($mgtarrayusr); $rid++) {
                    //I also would like to change the background color 
                    // to highlight the lines affected
                    $bgclr="#FFFFCC";
                    if (!is_null($rowid))
                    {
                        if ($rid==intval($rowid)-1)
                        {
                            $bgclr="#FFCCCC";
                        }
                    }
                   
                    ?>
		<tr>
                        <td bgcolor=<?php echo $bgclr; ?> 
                            style="display: <?php echo $idsp_editbtn;?>">
                            <input type="button" value = "Del" onClick="Javacsript:deleteRow(this)">
                            <input type="button" value = "Add" onClick="Javacsript:addRow(this)">
                            <input type="button" value = "Edit" onClick="Javacsript:editRow(this)">
			</td>
			<td bgcolor=<?php echo $bgclr; ?> style = "text-align:center"><?php echo $mgtarrayusr[$lks[$rid]]["jx1_year"]; ?></td>					
			<td bgcolor=<?php echo $bgclr; ?> style = "text-align:center"><?php echo $mgtarrayusr[$lks[$rid]]["jx2_month"]; ?></td>
			<td bgcolor=<?php echo $bgclr; ?> style = "text-align:center"><?php echo $mgtarrayusr[$lks[$rid]]["jx3_day"]; ?></td>
			<td bgcolor=<?php echo $bgclr; ?> style = "text-align:center"><?php echo $mgtarrayusr[$lks[$rid]]["opercatgries"]; ?></td>
			<td bgcolor=<?php echo $bgclr; ?> style = "text-align:center">
                                <?php echo $tillagearray[$mgtarrayusr[$lks[$rid]]["jx4_tillid"]]["tillshowname"]; ?></td>
			<td bgcolor=<?php echo $bgclr; ?> style = "text-align:center">
                                <?php
                                    if (strpos($mgtarrayusr[$lks[$rid]]["opercatgries"], 'Plant') !== false){
                                        echo $cropnamearray[$mgtarrayusr[$lks[$rid]]["jx6_cropid"]]["cropshowname"];
                                    }
                                ?></td>
			<td bgcolor=<?php echo $bgclr; ?> style = "text-align:center">
                            <?php
                                if (strpos($mgtarrayusr[$lks[$rid]]["opercatgries"], 'Fertilize') !== false){
                                    echo $fertarray[$mgtarrayusr[$lks[$rid]]["jx7"]]["fertdbname"]; }
                                elseif (strpos($mgtarrayusr[$lks[$rid]]["opercatgries"], 'pesticide') !== false) {
                                   echo $pestarray[$mgtarrayusr[$lks[$rid]]["jx7"]]["pestname"]; }
                            ?></td>
			<td bgcolor=<?php echo $bgclr; ?> style = "text-align:center">
                                <?php
                                    if (strpos($mgtarrayusr[$lks[$rid]]["opercatgries"], 'Fertilize') !== false){
                                        echo $mgtarrayusr[$lks[$rid]]["opv1"]; }
                                    elseif (strpos($mgtarrayusr[$lks[$rid]]["opercatgries"], 'pesticide') !== false) {
                                        echo $mgtarrayusr[$lks[$rid]]["opv1"]; }
                                ?></td>		
		</tr>
                <?php } ?>
		
	</center>
	</table>
    
    </div>    
</div>





















 
<?php
    include("html/footer.html");
?>