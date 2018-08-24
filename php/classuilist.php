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

        


    class ui_list
    {
        public function listUSStates($us_state_abbrevs_names, $usst_abbre)
        {
            for ($st=0; $st<count($us_state_abbrevs_names); $st++)
            {
                print("<option value = ".$usst_abbre[$st].">".$us_state_abbrevs_names[$usst_abbre[$st]]."</option>");
            }
        }
        
        public function countyarray($db, $stateabbre)
        {
            try
            {
                $stmt=$db->prepare("SELECT countyname "
                        . " FROM "
                        . "usstatecounty "
                        . "WHERE "
                        . "stateabbrev= "
                        . ":stateabb "
                        . "ORDER BY "
                        . "countyname");
                $stmt->execute(array(
                    ":stateabb" => $stateabbre
                ));
                $rows = $stmt->fetchall();
            }catch(PDOException $e) {$error[] = $e->getMessage();}
            
            return $rows;
        }
        
        
        
        public function listCounties($db, $counties, $stateabbre, $default)
        {

                for ($rid=0; $rid<count($counties); $rid++)
                {
                    $county = $counties[$rid]["countyname"];
                    
                    if ($default != null) 
                    {
                        if ($rid == $default){print("<option value=".$rid." selected>" . $county . "</option>");}
                        else {print("<option  value=".$rid.">" . $county . "</option>");}
                    }
                    else 
                    {
                        if ($Row == 0){print("<option  value=".$rid." selected>" . $county . "</option>");}
                        else {print("<option  value=".$rid.">" . $county . "</option>");}
                    }
                }

        }
        
        
        public function ziparray($db, $stateabbre, $county)
        {
            try
            {
                $stmt=$db->prepare("SELECT zipcode "
                        . " FROM "
                        . "usstctyzipcode "
                        . "WHERE "
                        . "stateabbrev= "
                        . ":stateabb "
                        . "AND "
                        . "countyname= "
                        . ":countyname "
                        . "ORDER BY "
                        . "zipcode");
                $stmt->execute(array(
                    ":stateabb" => $stateabbre,
                    ":countyname" => $county,
                ));
                $rows = $stmt->fetchall();
            }catch(PDOException $e) {$error[] = $e->getMessage();}
            return $rows;
        }
        
        public function listZipcode($db, $ziparrays, $default)
        {
            for ($rid=0; $rid<count($ziparrays); $rid++)
            {
                $zipcode = $ziparrays[$rid]["zipcode"];

                if ($default != null) 
                {
                    if ($rid == $default){print("<option value=".$rid." selected>" . $zipcode . "</option>");}
                    else {print("<option  value=".$rid.">" . $zipcode . "</option>");}
                }
                else 
                {
                    if ($Row == 0){print("<option  value=".$rid." selected>" . $zipcode . "</option>");}
                    else {print("<option  value=".$rid.">" . $zipcode . "</option>");}
                }
            }
        } 
        
        public function listSoil($Connection, $state, $county, $zipcode, $default)
        {
            $statelower = strtolower($state);	
            $sqlstmt = "SELECT DISTINCT soilname, mukey from " . $statelower . "_ctyzipmky where stateabbrev ='" . $state . "' and countyname = '" . $county . "'and zipcode = '" . $zipcode . "' and soilname <> 'Water' order by soilname";        if(!($Result = pg_exec($Connection, $sqlstmt)))
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

            for ($Row=0; $Row < pg_numrows($Result); $Row++) {
               $soilnamelist = pg_result($Result,$Row,"soilname");
                       $soilmukey = pg_result($Result,$Row,"mukey");
               if ($default != null) {
                    if ($soilnamelist == $default)
                       print("<option value = ". $soilmukey ." selected>" . $soilnamelist . "</option>");
                    else
                       print("<option value = ". $soilmukey ." >" . $soilnamelist . "</option>");
               } else {
                  if ($Row == 0)
                     print("<option value = ". $soilmukey ." selected>" . $soilnamelist . "</option>");
                  else
                     print("<option value = ". $soilmukey ." >" . $soilnamelist . "</option>");
               }
            }

            pg_freeresult($Result);
            }


        public function listOutVars()
        {
            print("<option value = 1>Precipitation (in)</option>");
            print("<option value = 2>Surface Runoff (in)</option>");
            print("<option value = 3>Flow from Tile Drainage (in)</option>");
            print("<option value = 4>Soil Erosion (ton/acre)</option>");
            print("<option value = 5>N loss with Surface Runoff (lb/acre)</option>");
            print("<option value = 6>N loss with Sediment (lb/acre)</option>");
            print("<option value = 7>N loss with Tile Flow (lb/acre)</option>");
            print("<option value = 8>Total N Loss (lb/acre)</option>");
            print("<option value = 9>P loss with Surface Runoff (lb/acre)</option>");
            print("<option value = 10>P loss with Sediment (lb/acre)</option>");
            print("<option value = 11>P loss with Tile Flow (lb/acre)</option>");    
            print("<option value = 12>Total P loss (lb/acre)</option>");    
        }
        
        
        public function listOutTimeScale()
        {        
            print("<option value = 1>Annual </option>");
            print("<option value = 2>Monthly</option>");
        }
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
    }






?>