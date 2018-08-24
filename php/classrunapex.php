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

class runapex
{

    public function getziplatlong($db, $st, $cty, $zip)
    {
        try
        {
            $stmt=$db->prepare("SELECT latitude, longitude "
                    . " FROM "
                    . "usstctyzipcode "
                    . "WHERE "
                    . "stateabbrev= "
                    . ":stateabb "
                    . "AND "
                    . "countyname= "
                    . ":countyname "
                    . "AND "
                    . "zipcode= :zip ");
            $stmt->execute(array(
                ":stateabb" => $st,
                ":countyname" => $cty,
                ":zip" => $zip
            ));
            $rows = $stmt->fetch();
        }catch(PDOException $e) {$error[] = $e->getMessage();}
        return $rows;
    }

    public function getsoilmukeyname($Connection, $state, $county, $zipcode, $solidx)
    {
        $statelower = strtolower($state);	
        $sqlstmt = "SELECT DISTINCT soilname, mukey from " . $statelower . "_ctyzipmky where stateabbrev ='" . $state . "' and countyname = '" . $county . "'and zipcode = '" . $zipcode . "' and soilname <> 'Water' order by soilname";
        if(!($Result = pg_exec($Connection, $sqlstmt)))
        {
                print("Could not execute query: ");
                print($sqlstmt);
                print(pg_errormessage($Connection));
                pg_close($Connection);
                print("<BR>\n");
                exit;
        }

        $Rows = pg_numrows($Result);
        if ($Rows == 0) {
                echo "<center>";
                echo "There are no records.";
                echo $sqlstmt;
                echo "</center>";
                pg_freeresult($Result);

                exit;
        }
        
        $soilnmmk = array();
        $soilnmmk["name"] = pg_result($Result,$solidx,"soilname");
        $soilnmmk["mukey"] = pg_result($Result,$solidx,"mukey");
        return $soilnmmk;
    }

    public function parsewsseachrun($sessrunlist, 
            $rundir,
            $hydro_wq_wss)
           
    {
        if (file_exists($sessrunlist)) {

        $runlst = file($sessrunlist, FILE_IGNORE_NEW_LINES);
        }
        
        else
        {
            echo "No run has been made";
            return;
        }
        
        for ($runlst_idx=0; $runlst_idx < count($runlst); $runlst_idx++){
            $runlst[$runlst_idx] = explode(",", $runlst[$runlst_idx]);
            $fn_runwss = "" . $rundir . "/".$runlst[$runlst_idx][0].""; 
            
            if (file_exists($fn_runwss))
            {
                $fid_runwss = fopen($fn_runwss, "r");
                while (!feof ($fid_runwss)){ 
                        $lif_runwss = fgets($fid_runwss, 4096); 
                        $hydrowqlst[$runlst_idx][] = $lif_runwss; 
                } 
                $flen_hydrowq = count($hydrowqlst[$runlst_idx]);
                fclose ($fid_runwss); 
            }

            //echo $flen_hydrowq."<br>";
            
            sscanf(substr($hydrowqlst[$runlst_idx][$flen_hydrowq-2], 50, 8),"%s", $hydro_wq_wss[$runlst_idx][]);
            sscanf(substr($hydrowqlst[$runlst_idx][$flen_hydrowq-2], 74, 8),"%s", $hydro_wq_wss[$runlst_idx][]);
            sscanf(substr($hydrowqlst[$runlst_idx][$flen_hydrowq-2], 99, 8),"%s", $hydro_wq_wss[$runlst_idx][]);
            sscanf(substr($hydrowqlst[$runlst_idx][$flen_hydrowq-2], 138),"%s %s %s %s %s %s %s %s",$hydro_wq_wss[$runlst_idx][3],
                                                                                        $hydro_wq_wss[$runlst_idx][4], 
                                                                                        $hydro_wq_wss[$runlst_idx][5], 
                                                                                        $hydro_wq_wss[$runlst_idx][6], 
                                                                                        $hydro_wq_wss[$runlst_idx][7], 
                                                                                        $hydro_wq_wss[$runlst_idx][8], 
                                                                                        $hydro_wq_wss[$runlst_idx][9], 
                                                                                        $hydro_wq_wss[$runlst_idx][10]);		

            


            // Unit conversion  
            // mm to inch
            // 0 is index for runoff
            $hydro_wq_wss[$runlst_idx][0] = floatval($hydro_wq_wss[$runlst_idx][0])*0.0393701;
            $hydro_wq_wss[$runlst_idx][0] = number_format($hydro_wq_wss[$runlst_idx][0], 1, '.', '');
            // 1 is index for tile drain
            $hydro_wq_wss[$runlst_idx][1] = floatval($hydro_wq_wss[$runlst_idx][1])*0.0393701;
            $hydro_wq_wss[$runlst_idx][1] = number_format($hydro_wq_wss[$runlst_idx][1], 2, '.', '');

            // ton/ha to ton/acre
            //2 is the index for soil loss
            $hydro_wq_wss[$runlst_idx][2] = floatval($hydro_wq_wss[$runlst_idx][2])*0.44609;
            $hydro_wq_wss[$runlst_idx][2] = number_format($hydro_wq_wss[$runlst_idx][2], 2, '.', '');  


            // kg/ha to lb/acre
            // 3 to 10 are: yn, qn, yp, qp, ssfn, qrfn, qdrn, rftn
            $hydro_wq_wss[$runlst_idx][3] = floatval($hydro_wq_wss[$runlst_idx][3])*0.892179;
            $hydro_wq_wss[$runlst_idx][3] = number_format($hydro_wq_wss[$runlst_idx][3], 2, '.', '');

            $hydro_wq_wss[$runlst_idx][4] = floatval($hydro_wq_wss[$runlst_idx][4])*0.892179;
            $hydro_wq_wss[$runlst_idx][4] = number_format($hydro_wq_wss[$runlst_idx][4], 2, '.', '');	

            $hydro_wq_wss[$runlst_idx][5] = floatval($hydro_wq_wss[$runlst_idx][5])*0.892179;
            $hydro_wq_wss[$runlst_idx][5] = number_format($hydro_wq_wss[$runlst_idx][5], 2, '.', '');		


            $hydro_wq_wss[$runlst_idx][6] = floatval($hydro_wq_wss[$runlst_idx][6])*0.892179;
            $hydro_wq_wss[$runlst_idx][6] = number_format($hydro_wq_wss[$runlst_idx][6], 2, '.', '');		

            $hydro_wq_wss[$runlst_idx][7] = floatval($hydro_wq_wss[$runlst_idx][7])*0.892179;
            $hydro_wq_wss[$runlst_idx][7] = number_format($hydro_wq_wss[$runlst_idx][7], 2, '.', '');		

            $hydro_wq_wss[$runlst_idx][8] = floatval($hydro_wq_wss[$runlst_idx][8])*0.892179;
            $hydro_wq_wss[$runlst_idx][8] = number_format($hydro_wq_wss[$runlst_idx][8], 2, '.', '');	

            $hydro_wq_wss[$runlst_idx][9] = floatval($hydro_wq_wss[$runlst_idx][9])*0.892179;
            $hydro_wq_wss[$runlst_idx][9] = number_format($hydro_wq_wss[$runlst_idx][9], 2, '.', '');		


            $hydro_wq_wss[$runlst_idx][10] = floatval($hydro_wq_wss[$runlst_idx][10])*0.892179;
            $hydro_wq_wss[$runlst_idx][10] = number_format($hydro_wq_wss[$runlst_idx][10], 2, '.', '');		

            $hydro_wq_wss[$runlst_idx][11] = $hydro_wq_wss[$runlst_idx][3] +
                                                                             $hydro_wq_wss[$runlst_idx][4]+
                                                                             $hydro_wq_wss[$runlst_idx][7]+
                                                                             $hydro_wq_wss[$runlst_idx][8]+
                                                                             $hydro_wq_wss[$runlst_idx][9]+
                                                                             $hydro_wq_wss[$runlst_idx][10];
            $hydro_wq_wss[$runlst_idx][12] = $hydro_wq_wss[$runlst_idx][5] +
                                                                             $hydro_wq_wss[$runlst_idx][6];																				   	
            // 13 is the name of the run
            $hydro_wq_wss[$runlst_idx][13] = $runlst[$runlst_idx][0];

            // Get precipitation 14
            sscanf(substr($hydrowqlst[$runlst_idx][$flen_hydrowq-25], 114, 9),"%s", $hydro_wq_wss[$runlst_idx][]);
            //Conver precipitation from mm to inches
            $hydro_wq_wss[$runlst_idx][14] = $hydro_wq_wss[$runlst_idx][14]*0.0393701;
            $hydro_wq_wss[$runlst_idx][14] = number_format($hydro_wq_wss[$runlst_idx][14], 0, '.', '');
            
            // I will try to include more variables to this list for the display of 
            // run list.
            // 15: original subarea no in acre as user inputed
            // 16: slope length
            // 17: management name
            // 18: soil name
            // 19: soil tn
            // 20: soil tp
            // 21: tile depth
            $hydro_wq_wss[$runlst_idx][15] = $runlst[$runlst_idx][1];
            $hydro_wq_wss[$runlst_idx][16] = $runlst[$runlst_idx][2];
            $hydro_wq_wss[$runlst_idx][17] = $runlst[$runlst_idx][3];
            $hydro_wq_wss[$runlst_idx][18] = $runlst[$runlst_idx][4];
            $hydro_wq_wss[$runlst_idx][19] = $runlst[$runlst_idx][5];
            $hydro_wq_wss[$runlst_idx][20] = $runlst[$runlst_idx][6];
            $hydro_wq_wss[$runlst_idx][21] = $runlst[$runlst_idx][7];

            
            
        }

        return  $hydro_wq_wss;

    }

    public function getelevfrmssurgo($mukey, $db)
    {
        $stmt = $db->prepare('SELECT elev_r'
                . ' FROM '
                . 'ssurgo2apex '
                . 'WHERE '
                . 'mukey = '
                . ':mukey');
        $stmt->execute(array(':mukey' => $mukey 
                            
                            ));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    }
    
    
    // Added to include annual results
    public function generaterunlist($sessrunlist, $runlist)
    {
        if (file_exists($sessrunlist)) {

        $runlist = file($sessrunlist, FILE_IGNORE_NEW_LINES);
        }
        
        else
        {
            echo "No run has been made";
            return;
        }
        
        return $runlist;
    }
    
    public function parseawp($sessrunlist, $runlist, $rundir, $GrRNo)
    {
        // Defining arrays for results. Arrays are 2 dimensions: 
        // scenarios and time
        // Hydrology
        $annual_prcp = array();
        $annual_surfq = array();
        $annual_tileq = array();

        // Soil Erosion
        $annual_erosion = array();

        // Nitrogen
        $annual_qn = array();
        $annual_yn = array();
        $annual_qdrn = array();
        $annual_totaln = array();

        // Phosphorus
        $annual_qp = array();
        $annual_yp = array();
        $annual_qdrp = array();
        $annual_totalp = array();

        $annual_totalqdrp = 0;
        
        // One array will store the simulation years
        $annual_yearlst = array();
        // Loop though each line to get the results
        $awp_array = array();
        
        // Get the name of the run file        
        $fn_nosuffix = explode(".",$runlist[$GrRNo-1])[0];
        $fn_awp = "" . $rundir . "/".$fn_nosuffix.".AWP";
        
        // Get the content of the AWP file for each run. 
        if (file_exists($fn_awp))
        {
            $fid_awp = fopen($fn_awp, "r");
            while (!feof ($fid_awp)){ 
                    $lif_awp = fgets($fid_awp, 4096); 
                    $awp_array[] = $lif_awp; 
            } 
            //$flen_hydrowq = count($hydrowqlst[$runlst_idx]);
            fclose ($fid_awp); 
        }
            
        // Next step is to process the data in the AWP files.
        // I will try with preg_grep
        $pattern = "/\d[0-9]{0,5}\.\d[0-9]{0,5}/";
        $awp_array = preg_grep($pattern, $awp_array);
        $awp_keys = array_keys($awp_array);
           
         
        // Now I need to separate each line into values and give them to
        // corresponding array.*0.03937010.892179
        
        // During the processing, I will need to convert the values from
        // mm to inches *0.0393701 and kg/ha to lb/acre *0.892179
        // ton/ha to ton/acre *0.44609;
        for ($lidx=0; $lidx < count($awp_array); $lidx++)
        {
            $awp_array[$awp_keys[$lidx]] = str_split($awp_array[$awp_keys[$lidx]], 10);
            $annual_yearlst[$lidx] = floatval($awp_array[$awp_keys[$lidx]][0]);
            $annual_prcp[$lidx] = floatval($awp_array[$awp_keys[$lidx]][1])*0.0393701;
            $annual_surfq[$lidx] = floatval($awp_array[$awp_keys[$lidx]][3])*0.0393701;
            $annual_tileq[$lidx] = floatval($awp_array[$awp_keys[$lidx]][7])*0.0393701;

            $annual_erosion[$lidx] = floatval($awp_array[$awp_keys[$lidx]][11])*0.44609;

            $annual_qn[$lidx] = floatval($awp_array[$awp_keys[$lidx]][13])*0.892179;
            $annual_yn[$lidx] = floatval($awp_array[$awp_keys[$lidx]][17])*0.892179;
            $annual_qdrn[$lidx] = floatval($awp_array[$awp_keys[$lidx]][19])*0.892179;
            $annual_totaln[$lidx] = (floatval($awp_array[$awp_keys[$lidx]][13])
                                        + floatval($awp_array[$awp_keys[$lidx]][14])
                                        + floatval($awp_array[$awp_keys[$lidx]][15])
                                        + floatval($awp_array[$awp_keys[$lidx]][16])
                                        + floatval($awp_array[$awp_keys[$lidx]][17])
                                        + floatval($awp_array[$awp_keys[$lidx]][18])
                                        + floatval($awp_array[$awp_keys[$lidx]][19]))
                                        *0.892179;

            $annual_qp[$lidx] = floatval($awp_array[$awp_keys[$lidx]][27])*0.892179;
            $annual_yp[$lidx] = floatval($awp_array[$awp_keys[$lidx]][28])*0.892179;
            $annual_qdrp[$lidx] = floatval($awp_array[$awp_keys[$lidx]][54])*0.892179;
            $annual_totalp[$lidx] = (floatval($awp_array[$awp_keys[$lidx]][27])
                                        + floatval($awp_array[$awp_keys[$lidx]][28])
                                        + floatval($awp_array[$awp_keys[$lidx]][29])
                                        + floatval($awp_array[$awp_keys[$lidx]][30])
                                        + floatval($awp_array[$awp_keys[$lidx]][54]))
                                        *0.892179;              
        } 
        
        // QDRP is only printable from this table, the total annual value
        // will be calculated from here.
        $annualavg_totalqdrp = array_sum($annual_qdrp)/count($annual_qdrp);
        
        
        return array($annual_prcp, 
            $annual_surfq, 
            $annual_tileq, 
            $annual_erosion, 
            $annual_qn, 
            $annual_yn, 
            $annual_qdrn, 
            $annual_totaln, 
            $annual_qp, 
            $annual_yp, 
            $annual_qdrp, 
            $annual_totalp, 
            $annualavg_totalqdrp, 
            $annual_yearlst);
        
        }
        
        
//        
//        print_r($annual_yn);
//        break;
//        
//            
//        for ($ridx=0; $ridx < count($runlist); $ridx++)
//        {
//            
//            $awp_array = array();
//            // Generate the name of the AWP files
//            $runlist[$ridx] = explode(".",$runlist[$ridx])[0];
//            $fn_awp = "" . $rundir . "/".$runlist[$ridx].".AWP";
//            
//            // Get the content of the AWP file for each run. 
//            if (file_exists($fn_awp))
//            {
//                $fid_awp = fopen($fn_awp, "r");
//                while (!feof ($fid_awp)){ 
//                        $lif_awp = fgets($fid_awp, 4096); 
//                        $awp_array[] = $lif_awp; 
//                } 
//                //$flen_hydrowq = count($hydrowqlst[$runlst_idx]);
//                fclose ($fid_awp); 
//            }
//
//            // Next step is to process the data in the AWP files.
//            // I will try with preg_grep
//            $pattern = "/\d[0-9]{0,5}\.\d[0-9]{0,5}/";
//            $awp_array = preg_grep($pattern, $awp_array);
//            $awp_keys = array_keys($awp_array);
//            
//            // Now I need to separate each line into values and give them to
//            // corresponding array.
//            for ($lidx=0; $lidx < count($awp_array); $lidx++)
//            {
//                $awp_array[$awp_keys[$lidx]] = str_split($awp_array[$awp_keys[$lidx]], 10);
//                $annual_yearlst[$ridx][$lidx] = floatval($awp_array[$awp_keys[$lidx]][0]);
//                $annual_prcp[$ridx][$lidx] = floatval($awp_array[$awp_keys[$lidx]][1]);
//                $annual_surfq[$ridx][$lidx] = floatval($awp_array[$awp_keys[$lidx]][3]);
//                $annual_tileq[$ridx][$lidx] = floatval($awp_array[$awp_keys[$lidx]][7]);
//                
//                $annual_erosion[$ridx][$lidx] = floatval($awp_array[$awp_keys[$lidx]][11]);
//                
//                $annual_qn[$ridx][$lidx] = floatval($awp_array[$awp_keys[$lidx]][13]);
//                $annual_yn[$ridx][$lidx] = floatval($awp_array[$awp_keys[$lidx]][17]);
//                $annual_qdrn[$ridx][$lidx] = floatval($awp_array[$awp_keys[$lidx]][19]);
//                $annual_totaln[$ridx][$lidx] = floatval($awp_array[$awp_keys[$lidx]][13])
//                                            + floatval($awp_array[$awp_keys[$lidx]][14])
//                                            + floatval($awp_array[$awp_keys[$lidx]][15])
//                                            + floatval($awp_array[$awp_keys[$lidx]][16])
//                                            + floatval($awp_array[$awp_keys[$lidx]][17])
//                                            + floatval($awp_array[$awp_keys[$lidx]][18])
//                                            + floatval($awp_array[$awp_keys[$lidx]][19]);
//
//                $annual_qp[$ridx][$lidx] = floatval($awp_array[$awp_keys[$lidx]][27]);
//                $annual_yp[$ridx][$lidx] = floatval($awp_array[$awp_keys[$lidx]][28]);
//                $annual_qdrp[$ridx][$lidx] = floatval($awp_array[$awp_keys[$lidx]][54]);
//                $annual_totalp[$ridx][$lidx] = floatval($awp_array[$awp_keys[$lidx]][27])
//                                            + floatval($awp_array[$awp_keys[$lidx]][28])
//                                            + floatval($awp_array[$awp_keys[$lidx]][29])
//                                            + floatval($awp_array[$awp_keys[$lidx]][30])
//                                            + floatval($awp_array[$awp_keys[$lidx]][54]);              
//            } 
//            
//
//            // After getting the value, I need to calculate the annual average 
//            // values. The annual average is a two dimension array with one 
//            // dimension as scenarios and the other as variable.
//            
//            $annual_avgallvar[$ridx][0] = array_sum($annual_prcp[$ridx])/count($annual_prcp[$ridx]);
//            $annual_avgallvar[$ridx][1] = array_sum($annual_surfq[$ridx])/count($annual_surfq[$ridx]);
//            $annual_avgallvar[$ridx][2] = array_sum($annual_tileq[$ridx])/count($annual_tileq[$ridx]);
//            $annual_avgallvar[$ridx][3] = array_sum($annual_erosion[$ridx])/count($annual_erosion[$ridx]);
//            $annual_avgallvar[$ridx][4] = array_sum($annual_qn[$ridx])/count($annual_qn[$ridx]);
//            $annual_avgallvar[$ridx][5] = array_sum($annual_yn[$ridx])/count($annual_yn[$ridx]);
//            $annual_avgallvar[$ridx][6] = array_sum($annual_qdrn[$ridx])/count($annual_qdrn[$ridx]);
//            $annual_avgallvar[$ridx][7] = array_sum($annual_totaln[$ridx])/count($annual_totaln[$ridx]);
//            $annual_avgallvar[$ridx][8] = array_sum($annual_qp[$ridx])/count($annual_qp[$ridx]);
//            $annual_avgallvar[$ridx][9] = array_sum($annual_yp[$ridx])/count($annual_yp[$ridx]);
//            $annual_avgallvar[$ridx][10] = array_sum($annual_qdrp[$ridx])/count($annual_qdrp[$ridx]);
//            $annual_avgallvar[$ridx][11] = array_sum($annual_totalp[$ridx])/count($annual_totalp[$ridx]);
//        }
//        


    public function parsemsa($sessrunlist, $runlist, $rundir, $GrRNo)
    {
        // Defining arrays for results. Arrays are 2 dimensions: 
        // scenarios and time
        // Hydrology
        $avgmon_prcp = array();
        $avgmon_surfq = array();
        $avgmon_tileq = array();

        // Soil Erosion
        $avgmon_erosion = array();

        // Nitrogen
        $avgmon_qn = array();
        $avgmon_yn = array();
        $avgmon_qdrn = array();            
        $avgmon_totaln = array();

        // Phosphorus
        $avgmon_qp = array();
        $avgmon_yp = array();
        $avgmon_qdrp = array();
        $avgmon_totalp = array();

        $avgmon_monlst = array();
        
        // This array will contain temporarily all data from
        // the MSA file.
        $msa_array = array();        
        
        // Get the corresponding output name for 
        // selected run number whose graph results will
        // be displayed.
        $fn_nosuffix = explode(".",$runlist[$GrRNo-1])[0];
        $fn_msa = "" . $rundir . "/".$fn_nosuffix.".MSA";

        // Get the content of the AWP file for each run. 
        if (file_exists($fn_msa))
        {
            $fid_msa = fopen($fn_msa, "r");
            while (!feof ($fid_msa)){ 
                    $lif_msa = fgets($fid_msa, 4096); 
                    $msa_array[] = $lif_msa; 
            } 
            fclose ($fid_msa); 
        }        
        

        // Next step is to process the data in the AWP files.
        // I will try with preg_grep
        $pattern = "/[0]\.[0-9]{0,5}[E]/";
        $msa_array = preg_grep($pattern, $msa_array);
        $msa_keys = array_keys($msa_array);

        // Now I need to separate each line into values and give them to
        // corresponding array.
        for ($lidx=0; $lidx < count($msa_array); $lidx++)
        {
            // This will help get each line as numbers.
            $msa_array[$msa_keys[$lidx]] = str_split(substr($msa_array[$msa_keys[$lidx]], 31), 13);
        } 

        // The next step is to put corresponding variables into 
        // separate arrays for calculation of monthly average values
        // The simulation years can be calculated by dividing the 
        // total line number by 96, since there are 12 variables, .i.e 
        // lines for each year. These variables was printed out by 
        // modifying the PRNT.DAT file as only interested variables 
        // in line 1 and 7. Numbers in line 1 are: 
        //    4  13  17  27  38  37  39  40  47  48  49 143 
        // numbers in 7 were all deleted, but the line need to be kept.
        $sim_yrs = count($msa_keys)/12;
        // Loop though years, the value of each month in one year will 
        // be put into a temp array. This array will have two dimensions: 
        // with the first dimension as month, and then as years.
        
        // Each variable has a different index, thus they will be calculated
        // individually.
        // During the processing, I will need to convert the values from
        // mm to inches *0.0393701 and kg/ha to lb/acre *0.892179
        // ton/ha to ton/acre *0.44609;
        // Precipitation          
        for($midx=0; $midx < 12; $midx++)
        {
            $avgmon_monlst[$midx] = $midx;
            for ($yidx=0; $yidx < $sim_yrs; $yidx++)
            {
                $avgmon_prcp[$midx][$yidx] = floatval($msa_array[10+12*$yidx][$midx])*0.0393701;
                $avgmon_surfq[$midx][$yidx] = floatval($msa_array[11+12*$yidx][$midx])*0.0393701;
                $avgmon_tileq[$midx][$yidx] = floatval($msa_array[12+12*$yidx][$midx])*0.0393701;
                $avgmon_erosion[$midx][$yidx] = floatval($msa_array[13+12*$yidx][$midx])*0.44609;
                $avgmon_qn[$midx][$yidx] = floatval($msa_array[14+12*$yidx][$midx])*0.892179;
                $avgmon_yn[$midx][$yidx] = floatval($msa_array[15+12*$yidx][$midx])*0.892179;
                $avgmon_qdrn[$midx][$yidx] = floatval($msa_array[18+12*$yidx][$midx])*0.892179;

                $avgmon_totaln[$midx][$yidx] = (floatval($msa_array[14+12*$yidx][$midx])
                                            + floatval($msa_array[15+12*$yidx][$midx])
                                            + floatval($msa_array[16+12*$yidx][$midx])
                                            + floatval($msa_array[17+12*$yidx][$midx])
                                            + floatval($msa_array[18+12*$yidx][$midx]))*0.892179;

                $avgmon_qp[$midx][$yidx] = floatval($msa_array[20+12*$yidx][$midx])*0.892179;
                $avgmon_yp[$midx][$yidx] = floatval($msa_array[19+12*$yidx][$midx])*0.892179;
                $avgmon_qdrp[$midx][$yidx] = floatval($msa_array[21+12*$yidx][$midx])*0.892179;

                $avgmon_totalp[$midx][$yidx] = (floatval($msa_array[19+12*$yidx][$midx])
                                            + floatval($msa_array[20+12*$yidx][$midx])
                                            + floatval($msa_array[21+12*$yidx][$midx]))*0.892179;
            }

            // Calculate the monthly average over the simulation period.
            $avgmon_prcp[$midx] = array_sum($avgmon_prcp[$midx])/count($avgmon_prcp[$midx]);
            $avgmon_surfq[$midx] = array_sum($avgmon_surfq[$midx])/count($avgmon_surfq[$midx]);
            $avgmon_tileq[$midx] = array_sum($avgmon_tileq[$midx])/count($avgmon_tileq[$midx]);
            $avgmon_erosion[$midx] = array_sum($avgmon_erosion[$midx])/count($avgmon_erosion[$midx]);
            $avgmon_qn[$midx] = array_sum($avgmon_qn[$midx])/count($avgmon_qn[$midx]);
            $avgmon_yn[$midx] = array_sum($avgmon_yn[$midx])/count($avgmon_yn[$midx]);
            $avgmon_qdrn[$midx] = array_sum($avgmon_qdrn[$midx])/count($avgmon_qdrn[$midx]);
            $avgmon_totaln[$midx] = array_sum($avgmon_totaln[$midx])/count($avgmon_totaln[$midx]);
            $avgmon_qp[$midx] = array_sum($avgmon_qp[$midx])/count($avgmon_qp[$midx]);
            $avgmon_yp[$midx] = array_sum($avgmon_yp[$midx])/count($avgmon_yp[$midx]);
            $avgmon_qdrp[$midx] = array_sum($avgmon_qdrp[$midx])/count($avgmon_qdrp[$midx]);
            $avgmon_totalp[$midx] = array_sum($avgmon_totalp[$midx])/count($avgmon_totalp[$midx]);
        }


    return array($avgmon_prcp, 
        $avgmon_surfq, 
        $avgmon_tileq, 
        $avgmon_erosion, 
        $avgmon_qn, 
        $avgmon_yn, 
        $avgmon_qdrn, 
        $avgmon_totaln, 
        $avgmon_qp, 
        $avgmon_yp, 
        $avgmon_qdrp, 
        $avgmon_totalp, 
        $avgmon_monlst);

    }
        
        
        
        
//        
//        
//        
//        for ($ridx=0; $ridx < count($runlist); $ridx++)
//        {
//
//            // Generate the name of the AWP files
//            $runlist[$ridx] = explode(".",$runlist[$ridx])[0];
//            $fn_msa = "" . $rundir . "/".$runlist[$ridx].".MSA";
//            
//            // Get the content of the AWP file for each run. 
//            if (file_exists($fn_msa))
//            {
//                $fid_msa = fopen($fn_msa, "r");
//                while (!feof ($fid_msa)){ 
//                        $lif_msa = fgets($fid_msa, 4096); 
//                        $msa_array[] = $lif_msa; 
//                } 
//                fclose ($fid_msa); 
//            }
//            
//            // Next step is to process the data in the AWP files.
//            // I will try with preg_grep
//            $pattern = "/[0]\.[0-9]{0,5}[E]/";
//            $msa_array = preg_grep($pattern, $msa_array);
//            $msa_keys = array_keys($msa_array);
//
//            // Now I need to separate each line into values and give them to
//            // corresponding array.
//            for ($lidx=0; $lidx < count($msa_array); $lidx++)
//            {
//                // This will help get each line as numbers.
//                $msa_array[$msa_keys[$lidx]] = str_split(substr($msa_array[$msa_keys[$lidx]], 31), 13);
//            } 
//            
//            // The next step is to put corresponding variables into 
//            // separate arrays for calculation of monthly average values
//            // The simulation years can be calculated by dividing the 
//            // total line number by 96, since there are 12 variables, .i.e 
//            // lines for each year. These variables was printed out by 
//            // modifying the PRNT.DAT file as only interested variables 
//            // in line 1 and 7. Numbers in line 1 are: 
//            //    4  13  17  27  38  37  39  40  47  48  49 143 
//            // numbers in 7 were all deleted, but the line need to be kept.
//            $sim_yrs = count($msa_keys)/12;
//            // Loop though years, the value of each month in one year will 
//            // be put into a temp array. This array will have two dimensions: 
//            // with the first dimension as month, and then as years.
//            
//            
//            // Each variable has a different index, thus they will be calculated
//            // individually.
//
//            // Precipitation          
//            for($midx=0; $midx < 12; $midx++)
//            {
//                $avgmon_monlst[$ridx][$midx] = $midx;
//                for ($yidx=0; $yidx < $sim_yrs; $yidx++)
//                {
//                    
//                    $avgmon_prcp[$ridx][$midx][$yidx] = floatval($msa_array[10+12*$yidx][$midx]);
//                    $avgmon_surfq[$ridx][$midx][$yidx] = floatval($msa_array[11+12*$yidx][$midx]);
//                    $avgmon_tileq[$ridx][$midx][$yidx] = floatval($msa_array[12+12*$yidx][$midx]);
//                    $avgmon_erosion[$ridx][$midx][$yidx] = floatval($msa_array[13+12*$yidx][$midx]);
//                    $avgmon_qn[$ridx][$midx][$yidx] = floatval($msa_array[14+12*$yidx][$midx]);
//                    $avgmon_yn[$ridx][$midx][$yidx] = floatval($msa_array[15+12*$yidx][$midx]);
//                    $avgmon_qdrn[$ridx][$midx][$yidx] = floatval($msa_array[18+12*$yidx][$midx]);
//
//                    $avgmon_totaln[$ridx][$midx][$yidx] = floatval($msa_array[14+12*$yidx][$midx])
//                                                + floatval($msa_array[15+12*$yidx][$midx])
//                                                + floatval($msa_array[16+12*$yidx][$midx])
//                                                + floatval($msa_array[17+12*$yidx][$midx])
//                                                + floatval($msa_array[18+12*$yidx][$midx]);
//                    
//                    $avgmon_qp[$ridx][$midx][$yidx] = floatval($msa_array[20+12*$yidx][$midx]);
//                    $avgmon_yp[$ridx][$midx][$yidx] = floatval($msa_array[19+12*$yidx][$midx]);
//                    $avgmon_qdrp[$ridx][$midx][$yidx] = floatval($msa_array[21+12*$yidx][$midx]);
//                    
//                    $avgmon_totalp[$ridx][$midx][$yidx] = floatval($msa_array[19+12*$yidx][$midx])
//                                                + floatval($msa_array[20+12*$yidx][$midx])
//                                                + floatval($msa_array[21+12*$yidx][$midx]);
//                }
//                
//                // Calculate the monthly average over the simulation period.
//                $avgmon_prcp[$ridx][$midx] = array_sum($avgmon_prcp[$ridx][$midx])/count($avgmon_prcp[$ridx][$midx]);
//                $avgmon_surfq[$ridx][$midx] = array_sum($avgmon_surfq[$ridx][$midx])/count($avgmon_surfq[$ridx][$midx]);
//                $avgmon_tileq[$ridx][$midx] = array_sum($avgmon_tileq[$ridx][$midx])/count($avgmon_tileq[$ridx][$midx]);
//                $avgmon_erosion[$ridx][$midx] = array_sum($avgmon_erosion[$ridx][$midx])/count($avgmon_erosion[$ridx][$midx]);
//                $avgmon_qn[$ridx][$midx] = array_sum($avgmon_qn[$ridx][$midx])/count($avgmon_qn[$ridx][$midx]);
//                $avgmon_yn[$ridx][$midx] = array_sum($avgmon_yn[$ridx][$midx])/count($avgmon_yn[$ridx][$midx]);
//                $avgmon_qdrn[$ridx][$midx] = array_sum($avgmon_qdrn[$ridx][$midx])/count($avgmon_qdrn[$ridx][$midx]);
//                $avgmon_totaln[$ridx][$midx] = array_sum($avgmon_totaln[$ridx][$midx])/count($avgmon_totaln[$ridx][$midx]);
//                $avgmon_qp[$ridx][$midx] = array_sum($avgmon_qp[$ridx][$midx])/count($avgmon_qp[$ridx][$midx]);
//                $avgmon_yp[$ridx][$midx] = array_sum($avgmon_yp[$ridx][$midx])/count($avgmon_yp[$ridx][$midx]);
//                $avgmon_qdrp[$ridx][$midx] = array_sum($avgmon_qdrp[$ridx][$midx])/count($avgmon_qdrp[$ridx][$midx]);
//                $avgmon_totalp[$ridx][$midx] = array_sum($avgmon_totalp[$ridx][$midx])/count($avgmon_totalp[$ridx][$midx]);
//            }
//        }
//        
//        return array($avgmon_prcp, 
//            $avgmon_surfq, 
//            $avgmon_tileq, 
//            $avgmon_erosion, 
//            $avgmon_qn, 
//            $avgmon_yn, 
//            $avgmon_qdrn, 
//            $avgmon_totaln, 
//            $avgmon_qp, 
//            $avgmon_yp, 
//            $avgmon_qdrp, 
//            $avgmon_totalp, 
//            $avgmon_monlst);
//        





    
    
    

}
    
    
    
    
    
?>