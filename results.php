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
   
    $idsp_result = "none";

    if (!isset($_SESSION["PROJDATA"]))
    {
        $error[]="ERROR: You do not have a project yet. Please go back to the home "
                . "page and create one!!!";
    }
    
    else
    {
        if(strcmp($_SESSION["PROJDATA"]["irun"], "no")==0)
        {
            $error[]="ERROR: You haven't run your model yet! Please setup your "
                    . "model in the \"Project Page\" and click the \"Run APEX Model\""
                    . "to get results";
        }
        elseif(strcmp($_SESSION["PROJDATA"]["irun"], "yes")==0)
        {


            //// Now the variables are send to the 
            // user project input array.
            // In this step, since all of the selection data are send
            // as index, they need to be converted to
            // values. These include state, county, zip, and soil.
            // Conver these to local variables
            $stabbr = $usst_abbre[$_SESSION["PROJDATA"]["stateindx"]];
            $state = $us_state_abbrevs_names[$stabbr];

            $counties = $uilist->countyarray($db, $stabbr);
            $county = $counties[$_SESSION["PROJDATA"]["countyid"]]["countyname"];

            $ziparray = $uilist->ziparray($db, $stabbr, $county);
            $zipcode = $ziparray[$_SESSION["PROJDATA"]["zipcodeid"]]["zipcode"];

            $ziplatlon = $runapex->getziplatlong($db, $stabbr, $county, $zipcode);
            $ziplat = $ziplatlon["latitude"];
            $ziplong = $ziplatlon["longitude"];

            /////////////////////////////////////////////////
            // Soil variables
            $solidx = $_SESSION["PROJDATA"]["soilid"];
            $soilnmmk = $runapex->getsoilmukeyname($Connection, $stabbr, $county, $zipcode, $solidx);

            $solmk = $soilnmmk["mukey"];
            $solnm = $soilnmmk["name"];
            //Other use input could be input here:
            // Soil test values
            $soiltest_n = $_SESSION["PROJDATA"]["soiltestn"];
            $soiltest_p = $_SESSION["PROJDATA"]["soiltestp"];
            $int_soln = intval($soiltest_n);
            $int_solp = intval($soiltest_p);

            /////////////////////////////////////////////////
            // Weather variables
            $dlystartyr = $_SESSION["PROJDATA"]["weastartyr"];
            $dlyendyr = $_SESSION["PROJDATA"]["weaendyr"];      
            $genweasimyr = $_SESSION["PROJDATA"]["weasimyrs"];        
            // Start processing daily weather files if selected
            $weadatascflag = $_SESSION["PROJDATA"]["weasource"];
            $displayyrs = 0;
            if (strcmp($weadatascflag, 1) == 0)
            {
                $displayyrs = $genweasimyr;
            }
            elseif (strcmp($weadatascflag, 2) == 0)
            {

                $dlystartdate = strtotime("" . $dlystartyr . "-01-01");
                $dlyenddate = strtotime("" . $dlyendyr . "-12-31");
                $displayyrs =  $jsonupdate->yearsDifference($dlyendyr, $dlystartyr);
            }

            if ($genweasimyr > 1)
            {$yearUnit = "Years";}
            else
            {$yearUnit = "Year";}
            

            /////////////////////////////////////////////////
            // Management variable
            $mankey=$_SESSION["PROJDATA"]["dfmk"];
            $mgtname = $_SESSION["PROJDATA"]["management"][$mankey]["mgtname"];
            $drainDft = intval($_SESSION["PROJDATA"]["draindpth"]);
            $drainDepth = $drainDft*304.8;

            /////////////////////////////////////////////////
            // Topographic variable        
            // Update the json variables for specific parameters
            // Area from acre to ha
            $subArea = intval($_SESSION["PROJDATA"]["fieldarea"])*0.40468;
            // Drainage depth from feet to meter
            $subSlpLen = intval($_SESSION["PROJDATA"]["fieldslplen"])*0.3048;
            $subSlpStp = intval($_SESSION["PROJDATA"]["fieldslopeid"]);


            //Create run folder for each session.
            $runfdname = date('Ymd')."_"
                    . $_SESSION["PROJDATA"]['projectname']."_"
                    . session_id();
            $rundir = FD_USRRUN. "/" . $runfdname;

            if (!file_exists($rundir)) 
            {
                mkdir($rundir, 0777);
            }

            $sessrunlist = $rundir . "/SESSIONRUN.LIST"; 
            
            $hydro_wq_wss = array();
            

            // This run list was generated by the run python functions.
            // If it exists, and contains the run name, directly get the results
            // else, run and update.
            
            // Modified on May 15 by Qingyu Feng
            // Goal: add a variable to run name to make sure runs with the 
            // same parameters will not have the same name.
            // The variable will be added to the last so that the 
            // code modification do not affect later runs.
            $wsscount = 0;
            if (glob($rundir."/*.WSS") != false)
            {
                $wsscount = count(glob($rundir . "/*.WSS"))+1;
            }


            // Generate runname
            // When the user changed one of the variable,
            // A new run is made for comparison issue

            $runname = $stabbr."_"
                    . "".$county."_"
                    . "".$zipcode."_"
                    . "".round($subArea)."_"
                    . "".round($subSlpStp)."_"
                    . "".round($subSlpLen)."_"
                    . "".round($solmk)."_"
                    . "".round($int_soln)."_"
                    . "".round($int_solp)."_"
                    . "".$mankey."_"
                    . "".round($drainDft)."_"
                    . "".$weadatascflag."_"
                    . "".$genweasimyr."_"
                    . "".$dlystartyr."_"
                    . "".$dlyendyr."_"
                    . "".$wsscount."";
            
            if(strcmp($_SESSION["PROJDATA"]["newrun"], "yes")==0)
            {
            
            /*
            **------------------------------------------------------------------
            ** Step 1: Readin, modify, write sol file for model user input
            **------------------------------------------------------------------
            */	        
            //Copy json template to userrun
            $jsondft_sol = FD_JSON.'/tmp1_solfile.json'; 
            $jsonrunf_sol = $rundir . "/run_sol.json";		
            if (!copy($jsondft_sol, $jsonrunf_sol))
            {
                echo "failed to copy $jsondft_sol...\n";
            }

            /*
            ** Processing Soil files
            */		
            //get contents of your json file and store it in a string
            $jsonupdate_sol = json_decode(file_get_contents($jsonrunf_sol), true);	
            // decode it  
            // The json will be updated in the function to get the value for selected soil
            $jsonupdate_sol = $jsonupdate->UpdateJsonSol_newsol($Connection, 
                    $solmk, 
                    $jsonupdate_sol, 
                    null);	
            //echo $jsonupdate_sol["line1"];


            if (!$soiltest_n == 0) 
               {
                    $jsonupdate_sol =  $jsonupdate->UpdateJsonSol_soiltestN($jsonupdate_sol, $soiltest_n);	
                    }

            if (!$soiltest_p == 0) 
               {
                    $jsonupdate_sol =  $jsonupdate->UpdateJsonSol_soiltestP($jsonupdate_sol, $soiltest_p);	
                    }	


            // The new json contains user input values and will be written back.
            $jsonupdate_sol = json_encode($jsonupdate_sol, true, JSON_UNESCAPED_UNICODE);
            //now send evrything to ur data.json file using folowing code
            // Write json output into the run folder
            if (json_decode($jsonupdate_sol) != null)
            {
                $file = fopen($jsonrunf_sol,'w');
                fwrite($file, $jsonupdate_sol);
                fclose($file);
            }
            else
            {
                echo "json was wrong"; 
            }        
            /*
            **------------------------------------------------------------------
            ** Step 2: Generate weather input user input
            **------------------------------------------------------------------
            */	
            //There are several files to be prepared.
            // 1. Monthly weather datafile. This have to be prepared
            // 2. Wind files, prepared
            // 3. Daily observed files: depends on user selection
            // All data are now in the database. The job here include:
            // a. Decide the station name based on latitude and longitude.
            // b. extract data and write them into format required by APEX.

            // Get latitude and longitude: ziplat ziplong, $stateabb 
            // Find station name based on latitude and longitude, this needs James function
            $weastnlist = FD_CLINEAR."/stations2015.db";
            exec(FD_CLINEAR."/climNearest.exe $ziplong $ziplat $rundir $weastnlist"); 

            $stnlistfile = $rundir . "/station.txt";
            $stnlist = file($stnlistfile);

            $stnname = $stnlist[3];
            $stnloc = $stnlist[1];
            $fn_wp1 = "" . trim($rundir) . "/" . trim($stnname) . ".WP1";
            $fn_wnd = "" . trim($rundir) . "/" . trim($stnname) . ".WND";

            $fn_wp1lst = "" . trim($rundir) . "/WPM1WEPP.DAT";
            $fn_wndlst = "" . trim($rundir) . "/WINDWEPP.DAT";

            // Get monthly stat data from database
            //echo "stateabbr:  ".$stabbr."<br>";

            $jsonupdate->WriteWP1WNDfile($Connection, 
                $stnname, 
                $stabbr,
                $fn_wp1, 
                $fn_wnd, 
                $fn_wp1lst, 
                $fn_wndlst);


            if (strcmp($weadatascflag, "2") == 0)   
            {

                $fn_dlylst = "" . trim($rundir) . "/WDLSTCOM.DAT";
                $fn_dly = "" . trim($rundir) . "/" . trim($stnname) . ".DLY";
                $jsonupdate->WriteDLYfile($Connection,
                                $stnname, 
                                $stabbr,
                                $dlystartyr, 
                                $dlyendyr, 
                                $fn_dlylst, 
                                $fn_dly, 
                                $ziplat, 
                                $ziplong, 
                                $stnloc);
            }
            elseif (strcmp($weadatascflag, "1") == 0)
            {
                $dlystartyr = 2000;
                $dlyendyr = 2005;
                $fn_dlylst = "" . trim($rundir) . "/WDLSTCOM.DAT";
                $fn_dly = "" . trim($rundir) . "/" . trim($stnname) . ".DLY";
                $jsonupdate->WriteDLYfile($Connection, 
                                $stnname, 
                                $stabbr, 
                                $dlystartyr, 
                                $dlyendyr, 
                                $fn_dlylst, 
                                $fn_dly, 
                                $ziplat, 
                                $ziplong, 
                                $stnloc);
            }


            /*
            **------------------------------------------------------------------
            ** Step 3: Readin, modify, write OPC file for model user input
            **------------------------------------------------------------------
            */	

            // With the modification of the user project in, 
            // data for management are now stored in the user 
            // project file. 
            // The data depends on whether the man is user or
            // default.
            // If default, us the template id to get all of 
            // the informatio to write the ops file.
            // If user modified, the user information will be used
            // Either one, the json file is needed as a bridge.

            //Copy json template to userrun
            $jsondft_mgtops = FD_JSON.'/tmp2_opsfile.json'; 
            $jsonrunf_mgtops= $rundir . "/run_mgtops.json";		

            if (!copy($jsondft_mgtops, $jsonrunf_mgtops))
            {
                echo "failed to copy $jsondft_mgtops...\n";
            }

            //get contents of your json file and store it in a string
            $jsonupdate_mgtops = json_decode(
                    file_get_contents($jsonrunf_mgtops), true);	
            // decode it  
            // The json will be updated in the function to get the value for selected soil


            //echo "json template".var_dump($jsonupdate_mgtops)."<br>";
            //echo "Man key: ".$mankey."<br>";
            if (strcmp($_SESSION["PROJDATA"]["management"][$mankey]["mansource"], "dft")==0)
            {
                // Using default by getting the data from the detail database.
                // Create a function to update json using database information    
                //echo "Using default...............<br>";
                $mgtname = $_SESSION["PROJDATA"]["management"][$mankey]["mgtname"];
                $templateid = $_SESSION["PROJDATA"]["management"][$mankey]["templateid"];
                $jsonupdate_mgtops=$jsonupdate->updateManDatabase($db, 
                        $templateid, $mankey, 
                        $mgtname, 
                        $jsonupdate_mgtops 
                        );
            }
            elseif(strcmp($_SESSION["PROJDATA"]["management"][$mankey]["mansource"], "user")==0)
            {
                // using the user edit management
                //echo "Using user man<br>....";
                // At this time the user mgtarray will be used.
                $mgtarrayusr = $_SESSION["PROJDATA"]["management"][$mankey]["mgtarray"];


                $jsonupdate_mgtops=$jsonupdate->updateManUser( 
                        $mgtarrayusr, $mankey, $mgtname,
                        $jsonupdate_mgtops 
                        );

            }
            //echo "...............................................<br>";
            //echo "json template".var_dump($jsonupdate_mgtops)."<br>";        
            // The new json contains user input values and will be written back.
            $jsonupdate_mgtops = json_encode($jsonupdate_mgtops, true, JSON_UNESCAPED_UNICODE);
            //now send evrything to ur data.json file using folowing code
            // Write json output into the run folder
            if (json_decode($jsonupdate_mgtops) != null)
            {
                $file = fopen($jsonrunf_mgtops,'w');
                fwrite($file, $jsonupdate_mgtops);
                fclose($file);
            }
            else
            {
                echo "json was wrong"; 
            }

            /*
            **------------------------------------------------------------------
            ** Step 4: Readin, modify, write SUB file for model user input
            **------------------------------------------------------------------
            */	
            //Copy json template to userrun
            $jsondft_sub = FD_JSON.'/tmp3_subfile.json'; 
            $jsonrunf_sub= $rundir . "/run_sub.json";		

            if (!copy($jsondft_sub, $jsonrunf_sub)){
            echo "failed to copy $jsondft_sub...\n"	;
            }

            //get contents of your json file and store it in a string
            $jsonupdate_sub = json_decode(file_get_contents($jsonrunf_sub), true);	
            $jsonupdate_sub = $jsonupdate->UpdateJsonSub($ziplat, $ziplong, $runname, $subArea, $subSlpStp, $drainDepth, $subSlpLen, $weadatascflag);
            // The new json contains user input values and will be written back.
            $jsonupdate_sub = json_encode($jsonupdate_sub, true, JSON_UNESCAPED_UNICODE);
            //now send evrything to ur data.json file using folowing code
            // Write json output into the run folder
            if (json_decode($jsonupdate_sub) != null)
            {
                $file = fopen($jsonrunf_sub,'w');
                fwrite($file, $jsonupdate_sub);
                fclose($file);
            }
            else
            {
                echo "json was wrong"; 
            }

            // The next step is to call python function to write the .sol file.
            // call the python script to run  

            /*
            **------------------------------------------------------------------
            ** Step 5: Readin, modify, write SITE file for model user input
            **------------------------------------------------------------------
            */	
            $jsondft_sit = FD_JSON.'/tmp4 _sitefile.json'; 
            //Copy json template to userrun
            $jsonrunf_sit= $rundir . "/run_site.json";		

            if (!copy($jsondft_sit, $jsonrunf_sit))
            {
                echo "failed to copy $jsondft_sit...\n";
            }

            //get contents of your json file and store it in a string
            $jsonupdate_site = json_decode(file_get_contents($jsonrunf_sit), true);	

            $site_elev = $runapex->getelevfrmssurgo($solmk, $db)["elev_r"];

            // Update the json variables for specific parameters
            $jsonupdate_site = $jsonupdate->UpdateJsonSite($ziplat, $ziplong, $runname, $site_elev);

            // The new json contains user input values and will be written back.
            $jsonupdate_site = json_encode($jsonupdate_site, true, JSON_UNESCAPED_UNICODE);
            //now send evrything to ur data.json file using folowing code
            // Write json output into the run folder
            if (json_decode($jsonupdate_site) != null)
            {
                $file = fopen($jsonrunf_sit,'w');
                fwrite($file, $jsonupdate_site);
                fclose($file);
            }
            else
            {
                echo "json was wrong"; 
            }


            /*
            **------------------------------------------------------------------
            ** Step 6: Readin, modify, write CONT file for model user input
            **------------------------------------------------------------------
            */	
            //Copy json template to userrun
            $jsondft_cont = FD_JSON.'/tmp5_contfile.json'; 
            $jsonrunf_cont= $rundir . "/run_cont.json";		

            if (!copy($jsondft_cont, $jsonrunf_cont)){
            echo "failed to copy $jsondft_cont...\n"	;
            }

            //get contents of your json file and store it in a string
            $jsonupdate_cont = json_decode(file_get_contents($jsonrunf_cont), true);	

            $jsonupdate_cont = $jsonupdate->UpdateJsonCont($genweasimyr, $weadatascflag, $dlystartyr, $dlyendyr);

            // The new json contains user input values and will be written back.
            $jsonupdate_cont = json_encode($jsonupdate_cont, true, JSON_UNESCAPED_UNICODE);
            //now send evrything to ur data.json file using folowing code
            // Write json output into the run folder
            if (json_decode($jsonupdate_cont) != null)
            {
                $file = fopen($jsonrunf_cont,'w');
                fwrite($file, $jsonupdate_cont);
                fclose($file);
            }
            else
            {
                echo "json was wrong"; 
            }        


            /*
            **------------------------------------------------------------------
            ** Step 7: Copy common database to the user folder.
            **------------------------------------------------------------------
            */
            $commondata = array(
                "apexfile"=>"APEXFILE.DAT",
                "apexdim"=>"APEXDIM.DAT",
                "cropcom"=>"CROPCOM.DAT",
                "tillcom"=>"TILLCOM.DAT",
                "pestcom"=>"PESTCOM.DAT",
                "fertcom"=>"FERTCOM.DAT",
                "tr55com"=>"TR55COM.DAT",
                "prnt"=>"PRNT0806.DAT",
                "parm"=>"PARM0806.DAT",
                "mlrn"=>"MLRN0806.DAT",
                "herd"=>"HERD0806.DAT",
                "psocom"=>"PSOCOM.DAT",
                "apexexe"=>APEXEXE);

            foreach($commondata as $cmndt)
            {
                if (!file_exists($rundir."/".$cmndt))
                {copy(FD_APEXDB."/".$cmndt, $rundir."/".$cmndt);}
            }


            /*
            **------------------------------------------------------------------
            ** Step 8: Readin, modify, write RUN file for model user input 
            **------------------------------------------------------------------
            */	
            //Copy json template to userrun
            $jsondft_other = FD_JSON.'/tmp6_other.json'; 
            $jsonrunf_other = $rundir . "/run_other.json";		

            if (!copy($jsondft_other, $jsonrunf_other)){
            echo "failed to copy $jsondft_cont...\n";
            }

            //get contents of your json file and store it in a string
            $jsonupdate_other = json_decode(file_get_contents($jsonrunf_other), true);	

            $jsonupdate_other = $jsonupdate->UpdateJsonOther($ziplat, 
                    $ziplong, 
                    $runname,
                    $solnm,
                    $soiltest_n,
                    $soiltest_p,
                    $mgtname,
                    $_SESSION["PROJDATA"]["draindpth"],
                    $_SESSION["PROJDATA"]["fieldarea"],
                    $_SESSION["PROJDATA"]["fieldslplen"]
                    );


            // The new json contains user input values and will be written back.
            $jsonupdate_other = json_encode($jsonupdate_other, true, JSON_UNESCAPED_UNICODE);
            //now send evrything to ur data.json file using folowing code
            // Write json output into the run folder
            if (json_decode($jsonupdate_other) != null)
            {
                $file = fopen($jsonrunf_other,'w');
                fwrite($file, $jsonupdate_other);
                fclose($file);
            }
            else
            {
                echo "json was wrong"; 
            }        

            /*
            **------------------------------------------------------------------
            ** Step 9: Run python to generate input files. 
            **------------------------------------------------------------------
             * This was put together to facilitate the modification
             * of the code when it is incorporated into the linux server
            */        
            // The next step is to call python function to write the .sol file.
            // call the python script to run  
            exec(FD_PY."/py01_json2sol.py "
                        . "$rundir "
                        . "$jsonrunf_sol");         


            // The next step is to call python function to write the .sol file.
            // call the python script to run         
            exec(FD_PY."/py02_json2mgtopc.py "
                    . "$rundir "
                    . "$jsonrunf_mgtops "); 

            // The next step is to call python function to write the .sol file.
            // call the python script to run  
            exec(FD_PY."/py03_json2sub.py $rundir $jsonrunf_sub"); 

            // The next step is to call python function to write the .sol file.
            // call the python script to run  
            exec(FD_PY."/py04_json2site.py $rundir $jsonrunf_sit"); 

            // The next step is to call python function to write the .sol file.
            // call the python script to run  
            exec(FD_PY."/py05_json2cont.py $rundir $jsonrunf_cont"); 

            // The next step is to call python function to write the .sol file.
            // call the python script to run  
            
            exec(FD_PY."/py06_json2runcopyrun.py $rundir $jsonrunf_other");
            

            // Reset the new run variable to prevent new run when the page is refreshed.
            $_SESSION["PROJDATA"]["newrun"]="no";
            }
            
            /*
            **------------------------------------------------------------------
            ** Step 10: Get output files and put them on the webpage.
            **------------------------------------------------------------------
            
             * Based on request, the simulation will be conduct every time. 
             * And the results will be displayed. This will be realized by
             * writing an extra file storing the simulation at each time
             * for one line, then the file will be passed to the hydro_wq_wss
             * variable here. One issue is two runs with same run name. 
             * Actaully, this can be done by adding another variable in the 
             * run name without change a lot.
            */

            // Get the result from each run
            $hydro_wq_wss = $runapex->parsewsseachrun($sessrunlist, 
                    $rundir, 
                    $hydro_wq_wss);

            
            
            // Added on June 6, 2017
            
            // Setup the value of run number for 
            // graph to show 
            // The logic is:
            // 1. If the user does not select, the graph will
            // always be the newest 
            // 2. If the user selected run, update the graph run
            // number.
            
            // Define four variables for runs user will select
            // For default value, the left 2 graphs will be 
            // for the previous run, the right 2 graphs will 
            // be for new runs
            // 1: upper left graph
            // 2: upper right graph
            // 3: lower left graph
            // 4: lower right graph
            $GrRNo1 = 0;
            $GrRNo2 = 0;
            $GrRNo3 = 0;
            $GrRNo4 = 0;
            
            // First count the total run number
            $totalrunno = 0;
            if (glob($rundir."/*.WSS") != false)
            {
                $totalrunno = count(glob($rundir . "/*.WSS"));
            }
            
            // For default values
            if ($totalrunno == 1)
            {
                $GrRNo1 = $totalrunno;
                $GrRNo2 = $totalrunno;
                $GrRNo3 = $totalrunno;
                $GrRNo4 = $totalrunno;
            }
            else
            {
                $GrRNo1 = $totalrunno-1;
                $GrRNo3 = $totalrunno-1;
                $GrRNo2 = $totalrunno;
                $GrRNo4 = $totalrunno;
            }
            
            // Setup the default value of graphrunno
            if (!isset($_REQUEST['GRNO1']))
            {$GrRNo1=$GrRNo1;}
            else
            {$GrRNo1 = floatval($_GET["GRNO1"])+1;}
           
            if (!isset($_REQUEST['GRNO2']))
            {$GrRNo2=$GrRNo2;}
            else
            {$GrRNo2 = floatval($_GET["GRNO2"])+1;}   
            
            if (!isset($_REQUEST['GRNO3']))
            {$GrRNo3=$GrRNo3;}
            else
            {$GrRNo3 = floatval($_GET["GRNO3"])+1;}
           
            if (!isset($_REQUEST['GRNO4']))
            {$GrRNo4=$GrRNo4;}
            else
            {$GrRNo4 = floatval($_GET["GRNO4"])+1;}              

            // Setup the default value of variable
            if (!isset($_REQUEST['GRV1']))
            {$GrV1=1;}
            else
            {$GrV1 = floatval($_GET["GRV1"])+1;}
            
            if (!isset($_REQUEST['GRV2']))
            {$GrV2=1;}
            else
            {$GrV2 = floatval($_GET["GRV2"])+1;}

            if (!isset($_REQUEST['GRV3']))
            {$GrV3=2;}
            else
            {$GrV3 = floatval($_GET["GRV3"])+1;}
            
            if (!isset($_REQUEST['GRV4']))
            {$GrV4=2;}
            else
            {$GrV4 = floatval($_GET["GRV4"])+1;}

            
            
            // Setup the default value of time scale
            if (!isset($_REQUEST['GRT1']))
            {$GrT1=1;}
            else
            {$GrT1 = floatval($_GET["GRT1"])+1;}
            
            if (!isset($_REQUEST['GRT2']))
            {$GrT2=1;}
            else
            {$GrT2 = floatval($_GET["GRT2"])+1;}            
            
            if (!isset($_REQUEST['GRT3']))
            {$GrT3=1;}
            else
            {$GrT3 = floatval($_GET["GRT3"])+1;}
            
            if (!isset($_REQUEST['GRT4']))
            {$GrT4=1;}
            else
            {$GrT4 = floatval($_GET["GRT4"])+1;}              
            
            
            // To include more results and display charts.
            // Generate arrays for output values. The run process
            // will not be changed.
            // We mainly need additional functions to 
            // get the results into arrays and parse to google charts.
            // Two time frequency: annual and monthly (add control 
            // if user want to see.)
            // Annual results will be get from the AWP file.
            // Monthly resutls will need to be got from the MSA file.
            // The average annual files will still be reported but calculated
            // from annual results, instead of reading from the WSS file.            
            // Get runname list
            $sessrunlist = $rundir . "/SESSIONRUN.LIST";
            $runlist = array();
            $runlist = $runapex->generaterunlist($sessrunlist, $runlist);
            
            
            //print_r($runlist);

            // We will have multiple results, but php function 
            // are only able to return one value. This container 
            // array will contain other arrays.
            $annual_results1 = array();
            $annual_results1 = $runapex->parseawp($sessrunlist, $runlist, $rundir, $GrRNo1);
                        
            $monthly_results1 = array();
            $monthly_results1 = $runapex->parsemsa($sessrunlist, $runlist, $rundir, $GrRNo1);            
            
            $annual_results2 = array();
            $annual_results2 = $runapex->parseawp($sessrunlist, $runlist, $rundir, $GrRNo2);
                        
            $monthly_results2 = array();
            $monthly_results2 = $runapex->parsemsa($sessrunlist, $runlist, $rundir, $GrRNo2);            
         
            $annual_results3 = array();
            $annual_results3 = $runapex->parseawp($sessrunlist, $runlist, $rundir, $GrRNo3);
                        
            $monthly_results3 = array();
            $monthly_results3 = $runapex->parsemsa($sessrunlist, $runlist, $rundir, $GrRNo3);            
            
            $annual_results4 = array();
            $annual_results4 = $runapex->parseawp($sessrunlist, $runlist, $rundir, $GrRNo4);
                        
            $monthly_results4 = array();
            $monthly_results4 = $runapex->parsemsa($sessrunlist, $runlist, $rundir, $GrRNo4);  
            
            
            $idsp_result = "block";
        }
    }

?>   
    





<?php
    //check for any errors
    if(isset($error))
    {
        foreach($error as $error)
        {
            echo '<h1>'.$error.'</h1>';
        }
    }
    
    unset($error);
?>

<SCRIPT LANGAUGE="JavaScript">
    function init()
    {
        document.graphouts.graphrunlst1.selectedIndex = <?=$GrRNo1-1?>;
        document.graphouts.graphrunlst2.selectedIndex = <?=$GrRNo2-1?>;
        document.graphouts.graphrunlst3.selectedIndex = <?=$GrRNo3-1?>;
        document.graphouts.graphrunlst4.selectedIndex = <?=$GrRNo4-1?>;        
        
        document.graphouts.graphvar1.selectedIndex = <?=$GrV1-1?>;
        document.graphouts.graphvar2.selectedIndex = <?=$GrV2-1?>;    
        document.graphouts.graphvar3.selectedIndex = <?=$GrV3-1?>;
        document.graphouts.graphvar4.selectedIndex = <?=$GrV4-1?>;            
        
        document.graphouts.graphvarts1.selectedIndex = <?=$GrT1-1?>;
        document.graphouts.graphvarts2.selectedIndex = <?=$GrT2-1?>;  
        document.graphouts.graphvarts3.selectedIndex = <?=$GrT3-1?>;
        document.graphouts.graphvarts4.selectedIndex = <?=$GrT4-1?>;        
        
    }
    
    function displaygraph()
    {
        runidx1 = document.graphouts.graphrunlst1.selectedIndex;
        runidx2 = document.graphouts.graphrunlst2.selectedIndex;
        runidx3 = document.graphouts.graphrunlst3.selectedIndex;
        runidx4 = document.graphouts.graphrunlst4.selectedIndex;        
        
        vidx1 = document.graphouts.graphvar1.selectedIndex;
        vidx2 = document.graphouts.graphvar2.selectedIndex;
        vidx3 = document.graphouts.graphvar3.selectedIndex;
        vidx4 = document.graphouts.graphvar4.selectedIndex;        
        
        tsidx1 = document.graphouts.graphvarts1.selectedIndex;
        tsidx2 = document.graphouts.graphvarts2.selectedIndex;
        tsidx3 = document.graphouts.graphvarts3.selectedIndex;
        tsidx4 = document.graphouts.graphvarts4.selectedIndex;        
        
        loc = "results.php?GRNO1=" + runidx1;
        loc = loc + "&GRNO2=" + runidx2;
        loc = loc + "&GRNO3=" + runidx3;
        loc = loc + "&GRNO4=" + runidx4;
        
        loc = loc + "&GRV1=" + vidx1;
        loc = loc + "&GRV2=" + vidx2;
        loc = loc + "&GRV3=" + vidx3;
        loc = loc + "&GRV4=" + vidx4;        
        
        loc = loc + "&GRT1=" + tsidx1;
        loc = loc + "&GRT2=" + tsidx2;
        loc = loc + "&GRT3=" + tsidx3;
        loc = loc + "&GRT4=" + tsidx4;
        
        parent.location = loc;
    }



</SCRIPT>


 <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">

      // Load the Visualization API and the corechart package.
      google.charts.load('current', {'packages':['corechart']});
      
      
      // prepare json variables from PHP array
            // Qingyu Feng learning
        // Try with annual data
      // Convert php array to java array, using json_encode
        <?php 
            // Depends on the user choice:
            // If they selected to see annual:
            // 1. Annual list will be generated
            // 2. Variable will be extracted from annual results
            // Else
            // 1. Monthly list will be generated
            // 2. Variable will be extracted from monthly results.
           
            // For graph 1:
            if ($GrT1 == 1)
            {
                // 1 for annual
                $parm1 = explode("_",$runlist[$GrRNo1-1]);
                $simyrs = count($annual_results1[13]);
                $jsyrlst1 = array();
                
                for ( $yi1 = 0; $yi1<$simyrs; $yi1++)
                {
                    if ($parm1[12]==1)
                    {array_push($jsyrlst1, $yi1+1);}
                    else
                    {array_push($jsyrlst1, $annual_results1[13][$yi1]);}
                }
                
                // Convert php array to java array
                $json_tmlst1 = json_encode($jsyrlst1);
                echo "var jstmlst1 = ". $json_tmlst1 . ";\n";
                
                // Get annual results and convert to java array
                $js_vardt1 = json_encode($annual_results1[$GrV1-1]);
                echo "var jsvardt1 = ". $js_vardt1 . ";\n";
                
                // Get the indicator
                $js_tsleg1 = json_encode("Year");
                echo "var jstsleg1 = ". $js_tsleg1 . ";\n";
                
            }
            else
            {
                // 2 for monthly
                // Generate monthly list
                $jsmonlst1 = array();
                for ( $moni1 = 0; $moni1<12; $moni1++)
                {array_push($jsmonlst1, $moni1+1);}

                // Convert php array to java array
                $json_tmlst1 = json_encode($jsmonlst1);
                echo "var jstmlst1 = ". $json_tmlst1 . ";\n";
                
                // Get annual results and convert to java array
                $js_vardt1 = json_encode($monthly_results1[$GrV1-1]);
                echo "var jsvardt1 = ". $js_vardt1 . ";\n";    
                
                // Get the indicator
                $js_tsleg1 = json_encode("Month");
                echo "var jstsleg1 = ". $js_tsleg1 . ";\n";
                
            }

            // For the right column:
            if ($GrT2 == 1)
            {
                // 1 for annual
                $parm2 = explode("_",$runlist[$GrRNo2-1]);
                $simyrs2 = count($annual_results2[13]);
                $jsyrlst2 = array();
                
                for ( $yi2 = 0; $yi2<$simyrs2; $yi2++)
                {
                    if ($parm2[12]==1)
                    {array_push($jsyrlst2, $yi2+1);}
                    else
                    {array_push($jsyrlst2, $annual_results2[13][$yi2]);}
                }
                
                // Convert php array to java array
                $json_tmlst2 = json_encode($jsyrlst2);
                echo "var jstmlst2 = ". $json_tmlst2 . ";\n";
                
                // Get annual results and convert to java array
                $js_vardt2 = json_encode($annual_results2[$GrV2-1]);
                echo "var jsvardt2 = ". $js_vardt2 . ";\n";
                
                // Get the indicator
                $js_tsleg2 = json_encode("Year");
                echo "var jstsleg2 = ". $js_tsleg2 . ";\n";
            }
            else
            {
                // 2 for monthly
                // Generate monthly list
                $jsmonlst2 = array();
                for ( $moni2 = 0; $moni2<12; $moni2++)
                {array_push($jsmonlst2, $moni2+1);}

                // Convert php array to java array
                $json_tmlst2 = json_encode($jsmonlst2);
                echo "var jstmlst2 = ". $json_tmlst2 . ";\n";
                
                // Get annual results and convert to java array
                $js_vardt2 = json_encode($monthly_results2[$GrV2-1]);
                echo "var jsvardt2 = ". $js_vardt2 . ";\n"; 
                
                // Get the indicator
                $js_tsleg2 = json_encode("Month");
                echo "var jstsleg2 = ". $js_tsleg2 . ";\n";
            }
            
            // For graph 3:
            if ($GrT3 == 1)
            {
                // 1 for annual
                $parm3 = explode("_",$runlist[$GrRNo3-1]);
                $simyrs3 = count($annual_results3[13]);
                $jsyrlst3 = array();
                
                for ( $yi3 = 0; $yi3<$simyrs3; $yi3++)
                {
                    if ($parm3[12]==1)
                    {array_push($jsyrlst3, $yi3+1);}
                    else
                    {array_push($jsyrlst3, $annual_results3[13][$yi3]);}
                }
                
                // Convert php array to java array
                $json_tmlst3 = json_encode($jsyrlst3);
                echo "var jstmlst3 = ". $json_tmlst3 . ";\n";
                
                // Get annual results and convert to java array
                $js_vardt3 = json_encode($annual_results3[$GrV3-1]);
                echo "var jsvardt3 = ". $js_vardt3 . ";\n";
                
                // Get the indicator
                $js_tsleg3 = json_encode("Year");
                echo "var jstsleg3 = ". $js_tsleg3 . ";\n";
                
            }
            else
            {
                // 2 for monthly
                // Generate monthly list
                $jsmonlst3 = array();
                for ( $moni3 = 0; $moni3<12; $moni3++)
                {array_push($jsmonlst3, $moni3+1);}

                // Convert php array to java array
                $json_tmlst3 = json_encode($jsmonlst3);
                echo "var jstmlst3 = ". $json_tmlst3 . ";\n";
                
                // Get annual results and convert to java array
                $js_vardt3 = json_encode($monthly_results3[$GrV3-1]);
                echo "var jsvardt3 = ". $js_vardt3 . ";\n";    
                
                // Get the indicator
                $js_tsleg3 = json_encode("Month");
                echo "var jstsleg3 = ". $js_tsleg3 . ";\n";
                
            }            
            
            // For graph 4:
            if ($GrT4 == 1)
            {
                // 1 for annual
                $parm4 = explode("_",$runlist[$GrRNo4-1]);
                $simyrs4 = count($annual_results4[13]);
                $jsyrlst4 = array();
                
                for ( $yi4 = 0; $yi4<$simyrs4; $yi4++)
                {
                    if ($parm4[12]==1)
                    {array_push($jsyrlst4, $yi4+1);}
                    else
                    {array_push($jsyrlst4, $annual_results4[13][$yi4]);}
                }
                
                // Convert php array to java array
                $json_tmlst4 = json_encode($jsyrlst4);
                echo "var jstmlst4 = ". $json_tmlst4 . ";\n";
                
                // Get annual results and convert to java array
                $js_vardt4 = json_encode($annual_results4[$GrV4-1]);
                echo "var jsvardt4 = ". $js_vardt4 . ";\n";
                
                // Get the indicator
                $js_tsleg4 = json_encode("Year");
                echo "var jstsleg4 = ". $js_tsleg4 . ";\n";
                
            }
            else
            {
                // 2 for monthly
                // Generate monthly list
                $jsmonlst4 = array();
                for ( $moni4 = 0; $moni4<12; $moni4++)
                {array_push($jsmonlst4, $moni4+1);}

                // Convert php array to java array
                $json_tmlst4 = json_encode($jsmonlst4);
                echo "var jstmlst4 = ". $json_tmlst4 . ";\n";
                
                // Get annual results and convert to java array
                $js_vardt4 = json_encode($monthly_results4[$GrV4-1]);
                echo "var jsvardt4 = ". $js_vardt4 . ";\n";    
                
                // Get the indicator
                $js_tsleg4 = json_encode("Month");
                echo "var jstsleg4 = ". $js_tsleg4 . ";\n";
                
            }                        

            // Now I will need to deal with the display part
            // Get the corresponding variable name
            $js_varnm1 = json_encode($outvarlst[$GrV1]);
            echo "var jsvarnm1 = ". $js_varnm1 . ";\n";              

            $js_varnm2 = json_encode($outvarlst[$GrV2]);
            echo "var jsvarnm2 = ". $js_varnm2 . ";\n";              
            
            $js_varnm3 = json_encode($outvarlst[$GrV3]);
            echo "var jsvarnm3 = ". $js_varnm3 . ";\n";              

            $js_varnm4 = json_encode($outvarlst[$GrV4]);
            echo "var jsvarnm4 = ". $js_varnm4 . ";\n";    
            
            
        ?>
            
         
        // Set a callback to run when the Google Visualization API is loaded.
        // Draw annual results table
        google.charts.setOnLoadCallback(
            function(){ drawGoogleChartsBarAnnual(
                'chartupperleft',
                jsvarnm1,
                jstsleg1,
                jstmlst1,
                jsvardt1,
                'LightBlue',
                  );});

        google.charts.setOnLoadCallback(
            function(){ drawGoogleChartsBarAnnual(
                'chartupperright',
                jsvarnm2,
                jstsleg2,
                jstmlst2,
                jsvardt2,
                'LightBlue',
                  );});

        google.charts.setOnLoadCallback(
            function(){ drawGoogleChartsBarAnnual(
                'chartlowerleft',
                jsvarnm3,
                jstsleg3,
                jstmlst3,
                jsvardt3,
                'LightBlue',
                  );});

        google.charts.setOnLoadCallback(
            function(){ drawGoogleChartsBarAnnual(
                'chartlowerright',
                jsvarnm4,
                jstsleg4,
                jstmlst4,
                jsvardt4,
                'LightBlue',
                  );});

      function drawGoogleChartsBarAnnual(id,yname,xname,xdat,ydat,bcolor) 
      {
        // From the HTML code, find the div to put the chart into
        var chartDiv = document.getElementById(id);

        // Create a google charts data table
        var data = new google.visualization.DataTable();
        data.addColumn('number', xname);
        data.addColumn('number', yname);
        
        // We create a standard javascript array in the [x,y] pair format
        // that Google Charts wants, which we will pass to the DataTable
        var dataRows = [];
        for(var i = 0; i < xdat.length && i < ydat.length; i++) {
            dataRows.push([xdat[i], parseFloat(ydat[i])]);
            
        }
        data.addRows(dataRows);
		
        // Create the Chart Options that we will pass to the Line Chart
        // - Set axis titles
        // - Do not show legend
        var options = {
                series: { 0: {color: bcolor} },
                hAxis: {
                        title: xname,
                        format:'#'
                        },
                vAxis: {
                        title: yname,
                        },
                        
                'chartArea': {
                    left: 50, 
                    top: 20, 
                    width: '80%',
                    'height': '75%'
                    },
                bar: {groupWidth: '90%'},
                legend: {
                        position: 'none'
                },
                explorer: { 
                        actions: ['dragToZoom', 'rightClickToReset'],
                        axis: 'horizontal',
                        keepInBounds: true,
                        maxZoomIn: 4.0
                }
        };
		
		// Create and draw the chart
		var chart = new google.visualization.ColumnChart(chartDiv);
		chart.draw(data, options);
	}
    </script>




<div style="display:<?php echo $idsp_result;?>">
    <h2>Average annual model results (table)&nbsp;</h2>
     	<table id="rlttb">
            <tr id="rlttr">
                <th id="rltth" colspan="1" rowspan="2" >Run order&nbsp;</th>
                <th id="rltth" colspan="3" >Hydrology (inch)&nbsp;</th>
                <th id="rltth" colspan="1" rowspan="2">Erosion (ton/ acre)&nbsp;</th>
                <th id="rltth" colspan="6" rowspan="1" >Nitrogen (lb/acre)&nbsp;</th>
                <th id="rltth" colspan="4" rowspan="1" >Phosphorus (lb/acre)&nbsp;</th>
            </tr>
	
            <tr id="rlttr">
                <th id="rltth">Precip&nbsp;</th>
                <th id="rltth">Surface runoff&nbsp;</th>
                <th id="rltth">Tile flow&nbsp;</th>
                <th id="rltth">N in Runoff&nbsp;</th>
                <th id="rltth">N in Tile Flow&nbsp;</th>
                <th id="rltth">N in Lateral Flow&nbsp;</th>
                <th id="rltth">N in Quick Return Flow&nbsp;</th>
                <th id="rltth">N in Sediment&nbsp;</th>
                <th id="rltth">Total N&nbsp;</th>
                <th id="rltth">P in Runoff&nbsp;</th>
                <th id="rltth">P from Tile&nbsp;</th>
                <th id="rltth">P in Sediment&nbsp;</th>
                <th id="rltth">Total P&nbsp;</th>
            </tr>

		<?php for ($hrid=0; $hrid<count($hydro_wq_wss);$hrid++){
                        $order = $hrid+1;
                        ?>
		<tr id="rlttr">
                    <td  id="rlttd"><?php echo "Run".$order; ?></td>
			
                    <td  id="rlttd"><?php echo number_format($hydro_wq_wss[$hrid][14], 0, '.', ''); ?></td>
                    <td  id="rlttd"><?php echo number_format($hydro_wq_wss[$hrid][0], 1, '.', ''); ?></td>					
                    <td  id="rlttd"><?php echo number_format($hydro_wq_wss[$hrid][1], 2, '.', ''); ?></td>

                    <td  id="rlttd"><?php echo number_format($hydro_wq_wss[$hrid][2], 2, '.', '');; ?></td>

                    <td  id="rlttd"><?php echo number_format($hydro_wq_wss[$hrid][4], 1, '.', ''); ?></td>
                    <td  id="rlttd"><?php echo number_format($hydro_wq_wss[$hrid][9], 1, '.', ''); ?></td>					
                    <td  id="rlttd"><?php echo number_format($hydro_wq_wss[$hrid][7], 2, '.', ''); ?></td>
                    <td  id="rlttd"><?php echo number_format($hydro_wq_wss[$hrid][8], 2, '.', ''); ?></td>			
                    <td  id="rlttd"><?php echo number_format($hydro_wq_wss[$hrid][3], 2, '.', ''); ?></td>					
                    <td  id="rlttd"><?php echo number_format($hydro_wq_wss[$hrid][11], 2, '.', ''); ?></td>			

                    <td  id="rlttd"><?php echo number_format($hydro_wq_wss[$hrid][6], 2, '.', ''); ?></td>	
                    <td  id="rlttd"><?php echo number_format($annual_results2[12], 2, '.', ''); ?></td>			
                    <td  id="rlttd"><?php echo number_format($hydro_wq_wss[$hrid][5], 2, '.', ''); ?></td>
                    <td  id="rlttd"><?php 
                        $dummytotalp = $hydro_wq_wss[$hrid][12] + $annual_results2[12];
                        echo number_format($dummytotalp, 2, '.', ''); ?></td>			

		</tr>
                <?php } ?>
        </table>
    
    <form method="POST"
        name="graphouts"
        id="graphouts"
        >
        
    <h2>Annual model results (Graph)</h2>
        <table id="rlttb">
        <tr  id="rlttr">
            <td>
                Select run order: 
                <select size="1" 
                        name="graphrunlst1"
                        id="graphrunlst1"
                        >
                    <?php 
                        for ($rid2=1; $rid2<=count($runlist);$rid2++){
                            print("<option  value=".$rid2.">" . "RUN". $rid2 . "</option>"); }
                            $rid2 = $rid2+1;
                    ?>
                </select>&nbsp;
                <br>
                Select variable:&nbsp;
                <select size="1" 
                        name="graphvar1"
                        id="graphvar1"
                        >
                    <?php 
                        $uilist->listOutVars();
                    ?>
                </select>&nbsp;
                <br>
                Select annual or monthly:&nbsp;
                <select size="1" 
                        name="graphvarts1"
                        id="graphvarts1"
                        >
                    <?php 
                        $uilist->listOutTimeScale();
                    ?>
                </select>&nbsp;
                <br>
                <input type="button"  
                       value="Display" 
                       onClick="Javacsript:displaygraph()"
                       name="button1_displeft"
                       id="button1_displeft"
                       >
                
                
            </td>
            
            <td>
                Select run order: &nbsp;    
                <select size="1" 
                        name="graphrunlst2"
                        id="graphrunlst2"
                        >
                    <?php 
                        for ($rid3=1; $rid3<=count($runlist);$rid3++){
                            print("<option  value=".$rid3.">" . "RUN". $rid3 . "</option>"); }
                            $rid3 = $rid3+1;
                    ?>
                </select>&nbsp;
                <br>
                Select variable:&nbsp;
                <select size="1" 
                        name="graphvar2"
                        id="graphvar2"
                        >
                    <?php 
                        $uilist->listOutVars();
                    ?>
                </select>&nbsp;
                <br>
                Select annual or monthly:&nbsp;
                <select size="1" 
                        name="graphvarts2"
                        id="graphvarts2"
                        >
                    <?php 
                        $uilist->listOutTimeScale();
                    ?>
                </select>&nbsp;
                <br>
                <input type="button"  
                       value="Display" 
                       onClick="Javacsript:displaygraph()"
                       name="button1_dispright"
                       id="button1_dispright"
                       >
            </td>
        </tr>   

        <tr  id="rlttr">
            <td><div id="chartupperleft"></div></td>
            <td><div id="chartupperright"></div></td>
        </tr>

        <tr>
            <td>
                Select run order: 
                <select size="1" 
                        name="graphrunlst3"
                        id="graphrunlst3"
                        >
                    <?php 
                        for ($rid3=1; $rid3<=count($runlist);$rid3++){
                            print("<option  value=".$rid3.">" . "RUN". $rid3 . "</option>"); }
                            $rid3 = $rid3+1;
                    ?>
                </select>&nbsp;
                <br>
                Select variable:&nbsp;
                <select size="1" 
                        name="graphvar3"
                        id="graphvar3"
                        >
                    <?php 
                        $uilist->listOutVars();
                    ?>
                </select>&nbsp;
                <br>
                Select annual or monthly:&nbsp;
                <select size="1" 
                        name="graphvarts3"
                        id="graphvarts3"
                        >
                    <?php 
                        $uilist->listOutTimeScale();
                    ?>
                </select>&nbsp;
                <br>
                <input type="button"  
                       value="Display" 
                       onClick="Javacsript:displaygraph()"
                       name="button3_displeft"
                       id="button3_displeft"
                       >
            </td>
            
            <td>
                Select run order: &nbsp;    
                <select size="1" 
                        name="graphrunlst4"
                        id="graphrunlst4"
                        >
                    <?php 
                        for ($rid4=1; $rid4<=count($runlist);$rid4++){
                            print("<option  value=".$rid4.">" . "RUN". $rid4 . "</option>"); }
                            $rid4 = $rid4+1;
                    ?>
                </select>&nbsp;
                <br>
                Select variable:&nbsp;
                <select size="1" 
                        name="graphvar4"
                        id="graphvar4"
                        >
                    <?php 
                        $uilist->listOutVars();
                    ?>
                </select>&nbsp;
                <br>
                Select annual or monthly:&nbsp;
                <select size="1" 
                        name="graphvarts4"
                        id="graphvarts4"
                        >
                    <?php 
                        $uilist->listOutTimeScale();
                    ?>
                </select>&nbsp;
                <br>
                <input type="button"  
                       value="Display" 
                       onClick="Javacsript:displaygraph()"
                       name="button4_dispright"
                       id="button4_dispright"
                       >                
            </td>
        </tr>   

        <tr  id="rlttr">
            <td width="450"><div id="chartlowerleft"></div></td>
            <td width="450"><div id="chartlowerright"></div></td>
        </tr>        

    </table>

       <h2> Run History 1&nbsp;</h2>

    <table  id="rlttb">
        <tr  id="rlttr">
            <th  id="rltth">Run order</th>
            <th id="rltth">State</th>
            <th id="rltth">County</th>
            <th id="rltth">Zipcode</th>
            <th id="rltth">Field area<br>(acre)</th>
            <th id="rltth">Slope</th>
            <th id="rltth">Slope length<br>(feet)</th>

            <th id="rltth">Soil Test N</th>
            <th id="rltth">Soil Test P</th>

            <th id="rltth">Tile depth</th>
            <th id="rltth">Sim years</th>
            <th id="rltth">Start year</th>
            <th id="rltth">End year</th>

        </tr>

        
        <?php for ($hrid=0; $hrid<count($hydro_wq_wss);$hrid++){
            $order = $hrid+1;
            $namearray = explode("_",$hydro_wq_wss[$hrid][13]);
            
                ?>
        <tr  id="rlttr">
                <td id="rlttd"><?php echo "Run".$order; ?></td>
                <td id="rlttd"><?php echo $namearray[1]; ?></td>
                <td id="rlttd"><?php echo $namearray[2]; ?></td>
                <td id="rlttd"><?php echo $namearray[3]; ?></td>
                <td id="rlttd"><?php echo $hydro_wq_wss[$hrid][15]; ?></td>
                <td id="rlttd"><?php echo $namearray[5]; ?></td>
                <td id="rlttd"><?php echo $hydro_wq_wss[$hrid][16]; ?></td>
                <td id="rlttd"><?php echo $hydro_wq_wss[$hrid][19]; ?></td>
                <td id="rlttd"><?php echo $hydro_wq_wss[$hrid][20]; ?></td>
                <td id="rlttd"><?php echo $hydro_wq_wss[$hrid][21]; ?></td>
                <td id="rlttd"><?php
                    if ($namearray[12]=="1")
                    {echo $namearray[13];}
                    else
                    {echo " ";}
                    ?></td>
                <td id="rlttd"><?php
                    if ($namearray[12]=="2")
                    {echo $namearray[14];}
                   else
                    {echo " ";}
                    ?></td>
                <td id="rlttd"><?php
                    if ($namearray[12]=="2")
                    {echo explode(".",$namearray[15])[0];}
                    else
                    {echo " ";}
                    ?></td>
        </tr>
        <?php } ?>
    </table>


       <h2> Run History 2&nbsp;</h2>

    <table  id="rlttb">
        <tr  id="rlttr">
            <th  id="rltth">Run order</th>
            <th id="rltth">Soil name</th>
            <th id="rltth">Management</th>
        </tr>

        
        <?php for ($hrid=0; $hrid<count($hydro_wq_wss);$hrid++){
            $order = $hrid+1;
            $namearray = explode("_",$hydro_wq_wss[$hrid][13]);
            
                ?>
        <tr  id="rlttr">
                <td id="rlttd"><?php echo "Run".$order; ?></td>
                <td id="rlttd"><?php echo $hydro_wq_wss[$hrid][18]; ?></td>
                <td id="rlttd"><?php echo $hydro_wq_wss[$hrid][17]; ?></td>
        </tr>
        <?php } ?>
    </table>       
       
       
</form>
      
     

</div>
<br>

<p>   </P>
<br>
<p>   </P>
<br>
<p>   </P>
<br>

<?php
    include("html/footer.html");
?>