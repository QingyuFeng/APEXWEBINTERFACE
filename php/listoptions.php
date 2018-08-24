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




function listStates()
{print("<option>Connecticut</option>");
        print("<option>Alabama</option>");
        //print("<option>Alaska</option>");
        print("<option>Arizona</option>");
	print("<option>Arkansas</option>");
        print("<option>California</option>");
        print("<option>Colorado</option>");
        
        print("<option>Delaware</option>");
        print("<option>Florida</option>");
    	print("<option>Georgia</option>");
    	//print("<option>Hawaii</option>");
    	print("<option>Idaho</option>");
    	print("<option>Illinois</option>");
    	print("<option>Indiana</option>");
        print("<option>Iowa</option>");
        print("<option>Kansas</option>");
        print("<option>Kentucky</option>");
        print("<option>Louisiana</option>\n");
        print("<option>Maine</option>");
        print("<option>Maryland</option>");
    	print("<option>Massachusetts</option>");
        print("<option>Michigan</option>");
        print("<option>Minnesota</option>");
        print("<option>Mississippi</option>");
        print("<option>Missouri</option>");
        print("<option>Montana</option>\n");
        print("<option>Nebraska</option>");
        print("<option>Neveda</option>");
        print("<option>New Hampshire</option>");
        print("<option>New Jersey</option>");
        print("<option>New Mexico</option>");
        print("<option>New York</option>");
        print("<option>North Carolina</option>");
        print("<option>North Dakota</option>");
        print("<option>Ohio</option>");
        print("<option>Oklahoma</option>");
        print("<option>Oregon</option>");
        print("<option>Pennsylvania</option>");
        print("<option>Rhode Island</option>");
        print("<option>South Carolina</option>");
        print("<option>South Dakota</option>");
        print("<option>Tennessee</option>");
        print("<option>Texas</option>");
        print("<option>Utah</option>");
        print("<option>Vermont</option>");
        print("<option>Virginia</option>");
        print("<option>Washington</option>");
        print("<option>West Virginia</option>");
        print("<option>Wisconsin</option>");
        print("<option>Wyoming</option>");
}

function toStateAbbr($stateLong)
{
 	if ($stateLong != "") { 
	    switch ($stateLong) {
	       case "Alabama":
	         $state = "AL";
		 break;
    	       case "Alaska":
    		 $state = "AK";
		 break;
		case "Arizona":
		 $state = "AZ";
		 break;
		case "Arkansas":
		 $state = "AR";
                 break;
    		case "California":
    		 $state = "CA";
		 break;
    		case "Colorado":
    		 $state = "CO";
		 break;
	        case "Connecticut":
		 $state = "CT";
		 break;
		case "Delaware":
	    	 $state = "DE";
		 break;
		case "Florida":
		 $state = "FL";
		 break;
		case "Georgia":
		 $state = "GA";
		 break;
		case "Hawaii":
		 $state = "HI";
		 break;
    		case "Idaho":
    		 $state = "ID";
                 break;
		case "Illinois":
		 $state = "IL";
		 break;
		case "Indiana":
		 $state = "IN";
		 break;
		case "Iowa":
		 $state = "IA";
		 break;
		case "Kansas":
		 $state = "KS";
		 break;
		case "Kentucky":
		 $state = "KY";
		 break;
	        case "Louisiana":
		 $state = "LA";
		 break;
	        case "Maine":
		 $state = "ME";
		 break;
   	  	case "Maryland":
   	  	 $state = "MD";
		 break;
		case "Massachusetts":
	         $state = "MA";
		 break;
		case "Michigan":
		 $state = "MI";
		 break;
	        case "Minnesota":
		  $state = "MN";
		  break;
		case "Mississippi":
		  $state = "MS";
		  break;
		case "Missouri":
		  $state = "MO";
		  break;
		 case "Montana":
		  $state = "MT";
		  break;
	         case "Nebraska":
		  $state = "NE";
		  break;
		 case "Neveda":
		  $state = "NV";
		  break;
		 case "New Hampshire":
		  $state = "NH";
		  break;
	         case "New Jersey":
	          $state = "NJ";
		  break;
		 case "New Mexico":
		  $state = "NM";
		  break;
		 case "New York":
		   $state = "NY";
		   break;
		 case "North Carolina":
		   $state = "NC";
		   break;
		 case "North Dakota":
		   $state = "ND";
		   break;
	         case "Ohio":
		   $state = "OH";
		   break;
		 case "Oklahoma":
	           $state = "OK";
		   break;
		  case "Oregon":
		   $state = "OR";
		   break;
	   	  case "Pennsylvania":
		    $state = "PA";
		    break;
		  case "Rhode Island":
		    $state = "RI";
		    break;
		  case "South Carolina":
		    $state = "SC";
		    break;
		  case "South Dakota":
		    $state = "SD";
		    break;
		  case "Tennessee":
		    $state = "TN";
		    break;
		  case "Texas":
		    $state = "TX";
		    break;
	          case "Utah":
		    $state = "UT";
		    break;
		  case "Vermont":
		    $state = "VT";
		    break;
	          case "Virginia":
		    $state = "VA";
		    break;
		  case "Washington":
		    $state = "WA";
		    break;
		  case "West Virigina":
		    $state = "WV";
	 	    break;
		  case "Wisconsin":
		    $state = "WI";
		    break;
		  case "Wyoming":
		    $state = "WY";
		    break;
		  default:
		    $state = "AL";
		}
	} else
          $state = "AL";

	return $state;
}

function listManagements_dft($Connection,$man)
{
	$sqlstmt = "SELECT opsschdlname, opsschdlseriesid from apexmgtlistdefault order by opsschdlname";

	if(!($Result = pg_exec($Connection, $sqlstmt)))
        {
		print("</select>");
                print("Could not execute query: ");
                print($sqlstmt);
                print(pg_errormessage($Connection));
                pg_close($Connection);
                print("<BR>\n");
                exit;
        }

        $Rows = pg_numrows($Result);
        if ($Rows == 0) {
		print("</select>");
                print("<center>");
                print("There are no records.");
                print($sqlstmt);
                print("</center>");
		pg_freeresult($Result);

                exit;
	}
	for ($Row=0; $Row < pg_numrows($Result); $Row++) {
           $manname = pg_result($Result,$Row,"opsschdlname");
            $manabbrev = pg_result($Result,$Row,"opsschdlseriesid");
           if ($man != null) {
             if ($man == $manabbrev)
                print("<option value = ". $manabbrev ." selected>" . $manname . "</option>");
             else
                print("<option value = ". $manabbrev .">" . $manname . "</option>");
           } else {
	     if ($Row == 0)
		print("<option value = ". $manabbrev ." selected>" . $manname . "</option>");
	     else
	        print("<option value = ". $manabbrev .">" . $manname . "</option>");
           }
	}

	pg_freeresult($Result);
}

function listManagements_usr($Connection,$manid)
{
	$sqlstmt = "SELECT * from apexmgtlist_user order by managementname";

	if(!($Result = pg_exec($Connection, $sqlstmt)))
        {
		print("</select>");
                print("Could not execute query: ");
                print($sqlstmt);
                print(pg_errormessage($Connection));
                pg_close($Connection);
                print("<BR>\n");
                exit;
        }

        $Rows = pg_numrows($Result);
        if ($Rows == 0) {
		print("</select>");
                print("<center>");
                print("There are no records.");
                print($sqlstmt);
                print("</center>");
		pg_freeresult($Result);

                exit;
	}
	for ($Row=0; $Row < pg_numrows($Result); $Row++) {
           $manname = pg_result($Result,$Row,"managementname");
		   $manabbrev = pg_result($Result,$Row,"managementabbrev");
           if ($man != null) {
             if ($man == $manname)
                print("<option value = ". $manabbrev ." selected>" . $manname . "</option>");
             else
                print("<option value = ". $manabbrev .">" . $manname . "</option>");
           } else {
	     if ($Row == 0)
		print("<option value = ". $manabbrev ." selected>" . $manname . "</option>");
	     else
	        print("<option value = ". $manabbrev .">" . $manname . "</option>");
           }
	}

	pg_freeresult($Result);
}

function listManagementDetail_dft($Connection,$manid, $mgtarray)
{
        
	$sqlstmt = "SELECT * from apexmgtdetailsdefault where operationid ='" . $manid . "'  order by operationseriesid";
	
	if(!($Result = pg_exec($Connection, $sqlstmt)))
        {
		print("</select>");
                print("Could not execute query: ");
                print($sqlstmt);
                print(pg_errormessage($Connection));
                pg_close($Connection);
                print("<BR>\n");
                exit;
        }

        $Rows = pg_numrows($Result);
        if ($Rows == 0) {
		print("</select>");
                print("<center>");
                print("There are no records.");
                print($sqlstmt);
                print("</center>");
		pg_freeresult($Result);

                exit;
	}

	for ($Row=0; $Row < pg_numrows($Result); $Row++) {
		
		$mgtarray[$Row]["operationid"] = pg_result($Result,$Row,"operationid");
		$mgtarray[$Row]["opercatgries"] = pg_result($Result,$Row,"opercatgries");
		$mgtarray[$Row]["lun_landuseno"] = pg_result($Result,$Row,"lun_landuseno");
		$mgtarray[$Row]["iaui_autoirr"] = pg_result($Result,$Row,"iaui_autoirr");
		$mgtarray[$Row]["iauf_autofert"] = pg_result($Result,$Row,"iauf_autofert");
		$mgtarray[$Row]["iamf_automanualdepos"] = pg_result($Result,$Row,"iamf_automanualdepos");
		$mgtarray[$Row]["ispf_autosolman"] = pg_result($Result,$Row,"ispf_autosolman");		
		$mgtarray[$Row]["ilqf_atliqman"] = pg_result($Result,$Row,"ilqf_atliqman");
		$mgtarray[$Row]["iaul_atlime"] = pg_result($Result,$Row,"iaul_atlime");
		$mgtarray[$Row]["jx1_year"] = pg_result($Result,$Row,"jx1_year");
		$mgtarray[$Row]["jx2_month"] = pg_result($Result,$Row,"jx2_month");
		$mgtarray[$Row]["jx3_day"] = pg_result($Result,$Row,"jx3_day");
		$mgtarray[$Row]["jx4_tillid"] = pg_result($Result,$Row,"jx4_tillid");
		$mgtarray[$Row]["jx5_tractid"] = pg_result($Result,$Row,"jx5_tractid");
		$mgtarray[$Row]["jx6_cropid"] = pg_result($Result,$Row,"jx6_cropid");
		$mgtarray[$Row]["jx7"] = pg_result($Result,$Row,"jx7");
		$mgtarray[$Row]["opv1"] = pg_result($Result,$Row,"opv1");
		$mgtarray[$Row]["opv2"] = pg_result($Result,$Row,"opv2");
		$mgtarray[$Row]["opv3"] = pg_result($Result,$Row,"opv3");
		$mgtarray[$Row]["opv4"] = pg_result($Result,$Row,"opv4");
		$mgtarray[$Row]["opv5"] = pg_result($Result,$Row,"opv5");
		$mgtarray[$Row]["opv6"] = pg_result($Result,$Row,"opv6");
		$mgtarray[$Row]["opv7"] = pg_result($Result,$Row,"opv7");
		$mgtarray[$Row]["opv8"] = pg_result($Result,$Row,"opv8");
		$mgtarray[$Row]["manningn"] = pg_result($Result,$Row,"manningn");                
			}

	pg_freeresult($Result);
	return $mgtarray;
	
	
}

function listManagementDetail_usr($Connection,$manabb, $mgtarray)
{

	$sqlstmt = "SELECT * from apexmgtdetails_user where managementabbrev ='" . $manabb . "'  order by operationid";
	
	if(!($Result = pg_exec($Connection, $sqlstmt)))
        {
		print("</select>");
                print("Could not execute query: ");
                print($sqlstmt);
                print(pg_errormessage($Connection));
                pg_close($Connection);
                print("<BR>\n");
                exit;
        }

        $Rows = pg_numrows($Result);
        if ($Rows == 0) {
		print("</select>");
                print("<center>");
                print("There are no records.");
                print($sqlstmt);
                print("</center>");
		pg_freeresult($Result);

                exit;
	}

	for ($Row=0; $Row < pg_numrows($Result); $Row++) {
		
		$mgtarray[$Row]["operationid"] = pg_result($Result,$Row,"operationid");
		$mgtarray[$Row]["managementname"] = pg_result($Result,$Row,"managementname");
		$mgtarray[$Row]["managementabbrev"] = pg_result($Result,$Row,"managementabbrev");
		$mgtarray[$Row]["operationyear"] = pg_result($Result,$Row,"operationyear");
		$mgtarray[$Row]["operationmonth"] = pg_result($Result,$Row,"operationmonth");
		$mgtarray[$Row]["operationday"] = pg_result($Result,$Row,"operationday");
		$mgtarray[$Row]["operationtype"] = pg_result($Result,$Row,"operationtype");	
		$mgtarray[$Row]["operationtypeid"] = pg_result($Result,$Row,"operationtypeid");		
		$mgtarray[$Row]["operationname"] = pg_result($Result,$Row,"operationname");
		$mgtarray[$Row]["detailsname"] = pg_result($Result,$Row,"detailsname");
		$mgtarray[$Row]["detailid"] = pg_result($Result,$Row,"detailid");
		$mgtarray[$Row]["detailsamount"] = pg_result($Result,$Row,"detailsamount");
		$mgtarray[$Row]["landusenumber"] = pg_result($Result,$Row,"landusenumber");
		$mgtarray[$Row]["tillageid"] = pg_result($Result,$Row,"tillageid");
		$mgtarray[$Row]["cropid"] = pg_result($Result,$Row,"cropid");
		$mgtarray[$Row]["chemicalid"] = pg_result($Result,$Row,"chemicalid");
		$mgtarray[$Row]["potentialheatunit"] = pg_result($Result,$Row,"potentialheatunit");
		$mgtarray[$Row]["tractorname"] = pg_result($Result,$Row,"tractorname");
		$mgtarray[$Row]["tractorid"] = pg_result($Result,$Row,"tractorid");
		$mgtarray[$Row]["fractionphu"] = pg_result($Result,$Row,"fractionphu");
		$mgtarray[$Row]["manningn"] = pg_result($Result,$Row,"manningn");
		
		
			}

	pg_freeresult($Result);
	return $mgtarray;
	
	
}

function listCountiesArray($Connection, $state, $default)
{
  $sqlstmt = "SELECT countyname from usstatecounty where stateabbrev ='" . $state . "' order by countyname";

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
           $countylist1 = pg_result($Result,$Row,"countyname");
	   if ($default != null) {
		if ($countylist1 == $default)
			$ctylstarray[] =  $countylist1;  
			//print("<option selected>" . $counties . "</option>");
		else
			$ctylstarray[] = $countylist1;  
            //print("<option>" . $counties . "</option>");
	   } else {
              if ($Row == 0)
				  $ctylstarray[] = $countylist1;  
                  //print("<option selected>" . $counties . "</option>");
              else
				 $ctylstarray[] = $countylist1;  
                 //print("<option>" . $counties . "</option>");
	   }
        }

        pg_freeresult($Result);
		return $ctylstarray;
		//print_r($ctylstarray);

}

function listCounties($Connection, $state, $default)
{
  $sqlstmt = "SELECT countyname from usstatecounty where stateabbrev ='" . $state . "' order by countyname";

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
           $countylist = pg_result($Result,$Row,"countyname");
	   if ($default != null) {
		if ($countylist == $default)
		   print("<option selected>" . $countylist . "</option>");
		else
                   print("<option>" . $countylist . "</option>");
	   } else {
              if ($Row == 0)
                 print("<option selected>" . $countylist . "</option>");
              else
                 print("<option>" . $countylist . "</option>");
	   }
        }

        pg_freeresult($Result);


}

function listZipcodearray($Connection, $state, $county, $default)
{
$sqlstmt = "SELECT zipcode from usstctyzipcode where stateabbrev ='" . $state . "' and countyname = '" . $county . "' order by zipcode";

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

        for ($Row=0; $Row < pg_numrows($Result); $Row++) {
           $zipcodelist = pg_result($Result,$Row,"zipcode");
	   if ($default != null) {
		if ($zipcodelist == $default)
			$ziplstarray[] =  $zipcodelist; 
		   //print("<option selected>" . $zipcodelist . "</option>");
		else
            $ziplstarray[] =  $zipcodelist; 
			//print("<option>" . $zipcodelist . "</option>");
	   } else {
              if ($Row == 0)
              $ziplstarray[] =  $zipcodelist;    
				//print("<option selected>" . $zipcodelist . "</option>");
              else
               $ziplstarray[] =  $zipcodelist;   
			 //print("<option>" . $zipcodelist . "</option>");
	   }
        }

        pg_freeresult($Result);
		return $ziplstarray;
		//print_r($ziplstarray);

}

function listZipcode($Connection, $state, $county, $default)
{
$sqlstmt = "SELECT zipcode from usstctyzipcode where stateabbrev ='" . $state . "' and countyname = '" . $county . "' order by zipcode";

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

        for ($Row=0; $Row < pg_numrows($Result); $Row++) {
           $zipcodelist2 = pg_result($Result,$Row,"zipcode");
	   if ($default != null) {
		if ($zipcodelist2 == $default)
		   print("<option selected>" . $zipcodelist2 . "</option>");
		else
                   print("<option>" . $zipcodelist2 . "</option>");
	   } else {
              if ($Row == 0)
                 print("<option selected>" . $zipcodelist2 . "</option>");
              else
                 print("<option>" . $zipcodelist2 . "</option>");
	   }
        }

        pg_freeresult($Result);

}

function listSoils($Connection, $state, $county, $zipcode, $default)
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

function listTillage_cate($Connection, $default)
{  
    $sqlstmt = "SELECT DISTINCT ON (tillcategories)"
            . " tillcategories, tillcategoryid "
            . "FROM "
            . "apexdbtillage "
            . "ORDER BY "
            . "tillcategories, "
            . "tillcategoryid";

    
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
           $tillcateg = pg_result($Result,$Row,"tillcategories");
           $tillcategid = pg_result($Result,$Row,"tillcategoryid");
           
			if ($default != null) {
				if (strcmp(trim($default),trim($tillcateg)) == 0){
                                    print("<option  value = ". $tillcategid ."  selected>" . $tillcateg . "</option>");}
				else {print("<option  value = ". $tillcategid ."  >" . $tillcateg . "</option>");}
				}
			else {
				if ($Row == 0){print("<option  value = ". $tillcategid ."  selected>" . $tillcateg . "</option>");}
				else {print("<option  value = ". $tillcategid ."  >" . $tillcateg . "</option>");}
				}			
			}

        pg_freeresult($Result);
}

function listTillage_name($Connection, $manedttillcat, $default)
{
    
    echo "<option>";
    echo $manedttillcat;
    echo "</option>";
    $sqlstmt = "SELECT * FROM "
            . "apexdbtillage"
            . " WHERE "
            . "tillcategories = '".$manedttillcat."'"
            . " ORDER BY "
            . "tillshowname";
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
    echo "<option>";
    echo $Rows;
    echo "</option>";
            if ($Rows == 0) {
                echo "<center>";
                echo "There are no records.";
                echo $sqlstmt;
                echo "</center>";
                pg_freeresult($Result);
                exit;
        }
    echo "<option>";
    echo "tese";
    echo "</option>";    
        
//
//
//        for ($Row=0; $Row < pg_numrows($Result); $Row++) {
//           $tillid = pg_result($Result,$Row,"tilldbnumber");
//           $tillshownnm = pg_result($Result,$Row,"tillshowname");
//
//            if ($default != null) {
//                    if (strcmp(trim($default),trim($tillid)) == 0){
//                            print("<option  value = ". $tillid ."  selected>" . $tillshownnm . "</option>");}
//                    else {print("<option  value = ". $tillid ."  >" . $tillshownnm . "</option>");}
//                    }
//            else {
//                    if ($Row == 0){print("<option  value = ". $tillid ."  selected>" . $tillshownnm . "</option>");}
//                    else {print("<option  value = ". $tillid ."  >" . $tillshownnm . "</option>");}
//                    }			
//            }
//
//        pg_freeresult($Result);
}

function listTillage_namearray($Connection, $tillagearray)
{
	$sqlstmt = "SELECT DISTINCT tilldbnumber, tillshowname from apexdbtillage order by tillshowname"; 
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
            $tillagearray[trim(pg_result($Result,$Row,"tilldbnumber"))]["tillshowname"] = pg_result($Result,$Row,"tillshowname"); 
        }

        pg_freeresult($Result);
        return $tillagearray;
        
        
}

function listcrop_name($Connection, $default)
{
	$sqlstmt = "SELECT cropid, cropshowname from apexdbcrop"; 
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
           $cropid = pg_result($Result,$Row,"cropid");
           $cropshownnm = pg_result($Result,$Row,"cropshowname");
			if ($default != null) {
				if (strcmp(trim($default),trim($cropid)) == 0){
					print("<option  value = ". $cropid ."  selected>" . $cropshownnm . "</option>");}
				else {print("<option  value = ". $cropid ."  >" . $cropshownnm . "</option>");}
				}
			else {
				if ($Row == 0){print("<option  value = ". $cropid ."  selected>" . $cropshownnm . "</option>");}
				else {print("<option  value = ". $cropid ."  >" . $cropshownnm . "</option>");}
				}			
			}

        pg_freeresult($Result);
}

function listcrop_namearray($Connection, $cropnamearray)
{
	$sqlstmt = "SELECT cropid, cropshowname from apexdbcrop"; 
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
            $cropnamearray[trim(pg_result($Result,$Row,"cropid"))]["cropshowname"] = pg_result($Result,$Row,"cropshowname"); 
       }
        
                    pg_freeresult($Result);
        return $cropnamearray;
}

function listfert_name($Connection, $default)
{
	$sqlstmt = "SELECT fertilizerid, fertdbname from apexdbfert"; 
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
           $fertid = pg_result($Result,$Row,"fertilizerid");
           $fertshownnm = pg_result($Result,$Row,"fertdbname");
			if ($default != null) {
				if (strcmp(trim($default),trim($fertid)) == 0){
					print("<option  value = ". $fertid ."  selected>" . $fertshownnm . "</option>");}
				else {print("<option  value = ". $fertid ."  >" . $fertshownnm . "</option>");}
				}
			else {
				if ($Row == 0){print("<option  value = ". $fertid ."  selected>" . $fertshownnm . "</option>");}
				else {print("<option  value = ". $fertid ."  >" . $fertshownnm . "</option>");}
				}			
			}

        pg_freeresult($Result);
}

function listfert_namearray($Connection, $fertarray)
{
	$sqlstmt = "SELECT fertilizerid, fertdbname from apexdbfert order by fertdbname"; 
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
           $fertarray[trim(pg_result($Result,$Row,"fertilizerid"))]['fertdbname'] = pg_result($Result,$Row,"fertdbname");;
        }
        pg_freeresult($Result);
        return $fertarray;
}

function listpest_name($Connection, $default)
{
	$sqlstmt = "SELECT pestid, pestname from apexdbpesticide"; 
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
           $pestid = pg_result($Result,$Row,"pestid");
           $pestshownnm = pg_result($Result,$Row,"pestname");
			if ($default != null) {
				if (strcmp(trim($default),trim($pestid)) == 0){
					print("<option  value = ". $pestid ."  selected>" . $pestshownnm . "</option>");}
				else {print("<option  value = ". $pestid ."  >" . $pestshownnm . "</option>");}
				}
			else {
				if ($Row == 0){print("<option  value = ". $pestid ."  selected>" . $pestshownnm . "</option>");}
				else {print("<option  value = ". $pestid ."  >" . $pestshownnm . "</option>");}
				}			
			}

        pg_freeresult($Result);
}

function listpest_namearray($Connection, $pestarray)
{
	$sqlstmt = "SELECT pestid, pestname from apexdbpesticide"; 
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
           $pestarray[trim(pg_result($Result,$Row,"pestid"))]['pestname'] = pg_result($Result,$Row,"pestname");		
			}

        pg_freeresult($Result);
        return $pestarray;
}

function listManagementDetail_usr_select($Connection, $tillagecatgid, $rowid, $default)
{
	if (trim($tillagecatgid) == 5 or trim($tillagecatgid) == 25) {
		echo "<select size=\"1\" name=\"fintilldtlnm\">";
		listcrop_name($Connection, $default);
		echo "</select>";
		}
		
	elseif (trim($tillagecatgid) == 6) {
		echo "<select size=\"1\" name=\"fintilldtlnm\">";
		listpest_name($Connection, $default);
		echo "</select>";
		}
	elseif  (trim($tillagecatgid) == 7) {
		echo "<select size=\"1\" name=\"fintilldtlnm\">";
		listfert_name($Connection, $default);
		echo "</select>";
		}
	elseif  (trim($tillagecatgid) == 24){
		echo "<input  style\"text-align\: center\"  type=\"text\" name=\"fintilldtlnm\"  size=\"5\" value=";
		echo trim($default);
		echo ">seed/ha";
		}
	else {

		echo null;
			}

}

function listManagementDetail_usr_dtlamt($Connection, $tillagecatgid, $rowid, $default)
{
	if (trim($tillagecatgid) == 5 or trim($tillagecatgid) == 25) {
		echo "<input  style\"text-align\: center\"  type=\"text\" name=\"fintilldtlamt\"  size=\"5\" value=";
		echo trim($default);
		echo ">seed/ha";
		}
		
	elseif (trim($tillagecatgid) == 6) {
		echo "<input  style\"text-align\: center\"  type=\"text\" name=\"fintilldtlamt\"  size=\"1\" value=";
		echo trim($default);
		echo ">kg/ha";		}
	elseif  (trim($tillagecatgid) == 7) {
		echo "<input  style\"text-align\: center\"  type=\"text\" name=\"fintilldtlamt\"  size=\"1\" value=";
		echo trim($default);
		echo ">kg/ha";		}
	elseif  (trim($tillagecatgid) == 24){
		echo "<input  style\"text-align\: center\"  type=\"text\" name=\"fintilldtlamt\"  size=\"1\" value=";
		echo trim($default);
		echo ">";		}
	else {
		
		echo null;
	}
}













function extractNED($state,$workingDir,$imgext,&$hasProj)
{
        global $globgisdir,$globtmpdir;
/*
        print("<p>-------Extracting NED----------<p>");
        $nedFile =  $globgisdir . $state . "/data/" . $state . "_ned.tif";
        $sliceFile = $workingDir . "/slice_ned.tif";

        sscanf($imgext, "%f %f %f %f", &$XLL, &$YLL, &$XUR, &$YUR);
        $projwin = $XLL . " " . $YUR . " " . $XUR . " " . $YLL;
        $cmd = "gdal_translate -projwin " . $projwin . " " . $nedFile . " " . $sliceFile;

        print($cmd);
        print("\n");
        if (system($cmd,$rc) == FALSE) {
           print("<p>***Could not execute: " . $rc . "***<p>");
        }
*/	
        $hasProj = TRUE;
	// Check for Hawaii, if this is the case then we can just get the data from the merged ned's
	if ($state == "HI") {
 	   print("<p>-------Extracting NED----------<p>");
           $nedFile =  $globgisdir . "merge/HI/ned_HI.tif";
           $sliceFile = $workingDir . "/slice_ned.tif";

           sscanf($imgext, "%f %f %f %f", $XLL, $YLL, $XUR, $YUR);
           $projwin = $XLL . " " . $YUR . " " . $XUR . " " . $YLL;
           $cmd = "gdal_translate -projwin " . $projwin . " " . $nedFile . " " . $sliceFile;

           print($cmd);
           print("\n");
           system($cmd,$rc);
           if ($rc != 0) {
              print("<p>***Could not execute: " . $rc . "***<p>");
	   }
	   return;
	}

	// New procedure to work directly with the raw NED slices
	// Step 1: consult the ned_index tif to get a grid of ned indexes which will map to file names
	if ($state == "AK")
	   $nedFile = $globgisdir . "ned_AKindex.tif";
	else
	   $nedFile = $globgisdir . "ned_index.tif";
	$nedIndexFile = $workingDir . "/nindex.tif";
	
	sscanf($imgext, "%f %f %f %f", $XLL, $YLL, $XUR, $YUR);
	$XLL2 = $XLL-0.05;
	$YUR2 = $YUR+0.05;
	$XUR2 = $XUR+0.05;
	$YLL2 = $YLL-0.05;
        $projwin = $XLL2 . " " . $YUR2 . " " . $XUR2 . " " . $YLL2;
        $cmd = "gdal_translate -projwin " . $projwin . " " . $nedFile . " " . $nedIndexFile;

        print($cmd);
        print("\n");
        system($cmd,$rc);
        if ($rc != 0) {
           print("<p>***Could not execute: " . $rc . "***" . $cmd . "***<p>");
        }

       // Step 2: Convert the ned index map from a tif to ascii so that we can work with it direclty
       $indexFile = $workingDir . "/nindex.tif";
       $indexAsc = $workingDir . "/nindex.asc";
       $cmd = "gdal_translate -of AAIGrid " . $indexFile . " " . $indexAsc;
       print($cmd);
       print("\n");
       if (system($cmd,$rc) == FALSE) {
	  print("<p>***Could not execute: " . $rc . "***" . $cmd . "***<p>");
       }

       // Step 3: Call a C++ program that goes through the grid and pulls unique indices and looks up the 
       // file name in the corresponding dbf file. Output is sent to a file.
       $outFile = $workingDir . "/nindexout.txt";
       if ($state == "AK") {
          $dbfFile = $globgisdir . "ned_AKindex2.dbf";
          $inxFile = $globgisdir . "ned_AKindex2.ndx";
       } else {
          $dbfFile = $globgisdir . "ned_index2.dbf";
          $inxFile = $globgisdir . "ned_index2.ndx";
       }

       $cmd = "/home/wepp/wepp/getNEDFiles " . $dbfFile . " " . $inxFile . " " . $indexAsc . " " . $outFile;

       print("<p>" . $cmd . "</p>");
       if (system($cmd,$rc) == FALSE) {
          print("<p>***Could not execute: " . $rc . "***" . $cmd . "***<p>");
       }

       // Step 4: Now the list of files are in the $outFile, we now need to extract each of the areas from the neds.
       // Almost all the time we will have only 1 ned to look it is only when the watershed boundary encompasses several
       // neds (at most 4) that we would need to extract more. 
       $fp = fopen($outFile,"r");
       if ($fp) {
	  $ind=0;
          $buf = fgets($fp,1024);
          while (!feof($fp)) {
		$ind++;
		$nedFile = $globgisdir;
		$nedFile = $nedFile . trim($buf);
                $sliceFile = $workingDir . "/slice_ned" . $ind . ".tif";

		$allFiles = $allFiles . " " . $sliceFile;

                sscanf($imgext, "%f %f %f %f", $XLL, $YLL, $XUR, $YUR);
                $projwin = $XLL . " " . $YUR . " " . $XUR . " " . $YLL;
                $cmd = "gdal_translate -projwin " . $projwin . " " . $nedFile . " " . $sliceFile;

		print("<p>" . $cmd . "</p>");

                if (system($cmd,$rc) == FALSE) {
                   print("<p>***Could not execute: " . $rc . "***" . $cmd . "***<p>");
                }
                $buf = fgets($fp,1024);

          }
          fclose($fp);

	  // Step 5: For each of the areas that were extracted merge them into a single tif
	  if ($ind > 1) {
	     // Files need to be merged before continuing into a file called slice_ned.tif
             unlink($workingDir . "/slice_ned.tif");
             $cmd = $globgisdir . "gdal_merge.py -o " . $workingDir . "/slice_ned.tif " . $allFiles;		
             print("<br>" . $cmd . "<br>");
             system($cmd,$rc);
             if ($rc != 0) {
                   print("<p>***Could not execute: " . $rc . "***" . $cmd . "***<p>");
                   return FALSE;
             }
             // assume that this may not have a projection which could be gdal_merge bug.
             $hasProj = FALSE;

	  } else {
	     // no need to merge, just move slice_ned1.tif slice_ned.tif
	     rename($workingDir . "/slice_ned1.tif", $workingDir . "/slice_ned.tif");
	  }
       } else
         print("<p>Can not find " . $outFile . "</p>");

       return TRUE; 
       // End of new procedure to get NED data from raw USGS NED slices.
}
function extractPRISM($workingDir,$parFile)
{
        global $globgisdir,$globtmpdir;

        $outX = $_SESSION['outletX'];
        $outY = $_SESSION['outletY'];

        $cmd = $globgisdir . "prism/getPoint " . $outX . " " . $outY . " " . $parFile;
        print($cmd);
        print("\n");
        system($cmd);
}

function reprojectNED($workingDir,$zone,$hasProj)
{
     $sliceFile = $workingDir . "/slice_ned.tif";
     $utmSliceFile = $workingDir . "/utmSlice.tif";
     if (file_exists($utmSliceFile))
        unlink($utmSliceFile);

     $proj = "'+proj=utm +zone=" . $zone . " +datum=NAD83 +ellps=GRS80' ";
     if ($hasProj == FALSE)
        $proj = $proj . "-s_srs '+proj=latlong +datum=NAD83 +ellps=GRS80' "; 
     $cmd = "gdalwarp -t_srs " . $proj . "-tr 30 30 " . $sliceFile . " " . $utmSliceFile;
     print($cmd);
     print("\n");
     system($cmd);

//     copy($sliceFile,$utmSliceFile);
}

function reprojectNLCD($workingDir,$zone)
{
     $sliceFile = $workingDir . "/slice_nlcd.tif";
     $utmSliceFile = $workingDir . "/utmSliceNLCD.tif";
     if (file_exists($utmSliceFile))
        unlink($utmSliceFile);

     $proj = "'+proj=utm +zone=" . $zone . " +datum=NAD27 +ellps=GRS80' ";
     $cmd = "gdalwarp -t_srs " . $proj . "-tr 30 30 " . $sliceFile . " " . $utmSliceFile;
     print($cmd);
     print("\n");
     system($cmd);

}

function reprojectSTATSGO($workingDir,$zone)
{
     $sliceFile = $workingDir . "/slice_statsgo.tif";
     $utmSliceFile = $workingDir . "/utmSliceSTATSGO.tif";
     if (file_exists($utmSliceFile))
        unlink($utmSliceFile);

     $proj = "'+proj=utm +zone=" . $zone . " +datum=NAD27 +ellps=GRS80' ";
     $cmd = "gdalwarp -t_srs " . $proj . "-tr 30 30 " . $sliceFile . " " . $utmSliceFile;
     print($cmd);
     print("\n");
     system($cmd);
}

function extractNLCD($state,$workingDir,$imgext)
{
         print("<p>-------Extracting NLCD----------<p>");
         global $globgisdir,$globtmpdir;

        $nlcdFile =  $globgisdir . $state . "/data/" . $state . "_nlcd2.tif";
        $sliceFile = $workingDir . "/slice_nlcd.tif";

        sscanf($imgext, "%f %f %f %f", $XLL, $YLL, $XUR, $YUR);
        $projwin = $XLL . " " . $YUR . " " . $XUR . " " . $YLL;
        $cmd = "gdal_translate -projwin " . $projwin . " " . $nlcdFile . " " . $sliceFile;
        print("\n<p>");
        print($cmd);
        print("<p>\n");
        system($cmd);
}

function extractSTATSGO($state,$workingDir,$imgext)
{
        print("<p>--------Extracting statsgo------------<p>");
	global $globgisdir,$globtmpdir;

        $soilFile =  $globgisdir . $state . "/data/" . $state . "_statsgo2.tif";
        $sliceFile = $workingDir . "/slice_statsgo.tif";

        sscanf($imgext, "%f %f %f %f", $XLL, $YLL, $XUR, $YUR);
        $projwin = $XLL . " " . $YUR . " " . $XUR . " " . $YLL;
        $cmd = "gdal_translate -projwin " . $projwin . " " . $soilFile . " " . $sliceFile;
        print("\n<p>");
        print($cmd);
        print("<p>\n");
        system($cmd);
}

function convertToASCIIGrid($workingDir)
{
    $utmSliceFile = $workingDir . "/utmSliceSTATSGO.tif";
    $utmSliceAsc = $workingDir . "/utmSliceSTATSGO.asc";
    $cmd = "gdal_translate -of AAIGrid " . $utmSliceFile . " " . $utmSliceAsc;
    print($cmd);
    print("\n");
    system($cmd);

    $utmSliceFile = $workingDir . "/utmSliceNLCD.tif";
    $utmSliceAsc = $workingDir . "/utmSliceNLCD.asc";
    $cmd = "gdal_translate -of AAIGrid " . $utmSliceFile . " " . $utmSliceAsc;
    print($cmd);
    print("\n");
    system($cmd);

    $utmSliceFile = $workingDir . "/utmSlice.tif";
    $utmSliceAsc = $workingDir . "/utmSlice.asc";
    $cmd = "gdal_translate -of AAIGrid " . $utmSliceFile . " " . $utmSliceAsc;
    print($cmd);
    print("\n");
    system($cmd);

}
function updateMapFile($state,$workingDir,$mapFile,$addNet,$addOutlet,$addSubCatch,$addFlow,$addRep,$zone)
{
     global $globgisdir,$globtmpdir;

     $baseMapFile = $globgisdir . $state . "/" . $state . "_baseline.map";
     $sessionMapFile = $workingDir . "/" . $mapFile;

     if (!copy($baseMapFile,$sessionMapFile))
         echo("copy failed: " . $baseMapFile . " " . $sessionMapFile);

    // Need to modify the session map file to pick up the approiate files
    $handle = fopen($sessionMapFile,"a");
    if ($handle) {
       if ($addNet) {
          fwrite($handle,"LAYER\nName network\nType RASTER\n");
          fwrite($handle,"DATA \"" . $workingDir . "/NETFUL.tif\"\n");
          fwrite($handle,"OFFSITE  0 0 0\n");
          fwrite($handle,"STATUS OFF\n");
	  fwrite($handle,"PROCESSING \"SCALE=0,255\"\n");
          fwrite($handle,"PROCESSING \"SCALE_BUCKETS=2\"\n");
          fwrite($handle,"CLASSITEM \"Value\"\n");
          fwrite($handle,"CLASS\nEXPRESSION ([pixel] > 0)\n");
          fwrite($handle,"COLOR 0 0 255\nEND\n");
          fwrite($handle,"PROJECTION\n  \"proj=utm\"\n  \"zone=" . $zone . "\"\nEND\nEND\n");
       }
       if ($addOutlet) {
         $outPoint = $_SESSION['outletX'] . " " . $_SESSION['outletY'];
      
         fwrite($handle,"\nLAYER\n");
         fwrite($handle,"   NAME  outlet\n   TYPE POINT\n   STATUS DEFAULT\n");
         fwrite($handle,"   CLASS\n     NAME Outlet\n     SYMBOL  'circle'\n");
         fwrite($handle,"     COLOR 255 102 0\n     SIZE 6\n   END\n");
         fwrite($handle,"   FEATURE\n     POINTS " . $outPoint . " END\n   END\n");
         fwrite($handle,"   PROJECTION\n     \"proj=latlong\"\n    \"ellps=GRS80\"\n    \"datum=NAD27\"\n   END\nEND\n");
         $outfile = $workingDir . "/outlet.txt";
         $hout = fopen($outfile,"w");
         if ($hout) {
            fwrite($hout,$outPoint . "\n");
            fclose($hout);
         }
       }
       if ($addSubCatch) {
         fwrite($handle,"LAYER\nName subwta\nType RASTER\n");
         fwrite($handle,"DATA \"" . $workingDir . "/SUBWTA.tif\"\n");
         fwrite($handle,"TRANSPARENCY 70\n");
         fwrite($handle,"OFFSITE  0 0 0\n");
         fwrite($handle,"STATUS OFF\n");
         fwrite($handle,"CLASSITEM \"[pixel]\"\n");
         $colors = fopen("/home/wepp/wepp/classes.txt","r");
         if ($colors) {
           while (!feof($colors)) {
             $cbuf = fgets($colors,256);
             fwrite($handle,$cbuf);
           }
           fclose($colors);
         }
         fwrite($handle,"\nPROJECTION\n  \"proj=utm\"\n  \"zone=" . $zone . "\"\nEND\nEND\n");

         fwrite($handle,"LAYER\nName bound\nType RASTER\n");
         fwrite($handle,"DATA \"" . $workingDir . "/BOUND.tif\"\n");
         fwrite($handle,"TRANSPARENCY 70\n");
         fwrite($handle,"OFFSITE  0 0 0\n");
         fwrite($handle,"STATUS OFF\n");
         fwrite($handle,"CLASSITEM \"[pixel]\"\n");
         fwrite($handle,"CLASS\nEXPRESSION ([pixel] > 0)\n");
         fwrite($handle,"COLOR 200 100 255\nEND\n");
         fwrite($handle,"PROJECTION\n  \"proj=utm\"\n  \"zone=" . $zone . "\"\nEND\nEND\n");

       }
       if ($addFlow) {

       }
       if ($addRep) {

       }
       fwrite($handle,"\nEND\n");

       fclose($handle);
    } else
       echo("could not open " . $sessionMapFile . "\n");
}
function copyTOPAZFiles($workingDir)
{
    if (!copy("/home/wepp/wepp/dednm",$workingDir . "/dednm"))
       echo("copy failed: dednm " . $workingDir . "/dednm");

    chmod($workingDir . "/dednm", 0755);

    if (!copy("/home/wepp/wepp/rasfor",$workingDir . "/rasfor"))
       echo("copy failed: rasfor " . $workingDir . "/rasfor");

    chmod($workingDir . "/rasfor", 0755);

    if (!copy("/home/wepp/wepp/dnmcnt.txt",$workingDir . "/dnmcnt.txt"))
       echo("copy failed: dnmcnt.txt " . $workingDir . "/dnmcnt.txt");

    if (!copy("/home/wepp/wepp/RASFOR.INP",$workingDir . "/RASFOR.INP"))
       echo("copy failed: RASFOR.INP " . $workingDir . "/RASFOR.INP");

    if (!copy("/home/wepp/wepp/raspro",$workingDir . "/raspro"))
       echo("copy failed: raspro " . $workingDir . "/raspro");

    chmod($workingDir . "/raspro", 0755);

    if (!copy("/home/wepp/wepp/RASPRO.INP",$workingDir . "/RASPRO.INP"))
       echo("copy failed: RASPRO.INP " . $workingDir . "/RASPRO.INP");

    if (!copy("/home/wepp/wepp/mapColors",$workingDir . "/mapColors"))
       echo("copy failed: mapColors " . $workingDir . "/mapColors");

    chmod($workingDir . "/mapColors", 0755);
}

function runTopazPass1($workingDir,$csa,$mcl,$zone)
{
    $cmd = "/home/wepp/wepp/preptopaz " . $workingDir . " " . $csa . " " . $mcl . " 3 3 1 " . $zone . " 0 0";
    print("\n");
    print($cmd);
    if (system($cmd) == FALSE)
       echo("Error running preptopaz\n");

    chdir($workingDir);
    print("<p>\n");
    system("./dednm");
    print("<p>\n");
    system("./rasfor");

    print("<p>\n");

    // need to convert to mapserver will display it correctly with NODATA being transparent
    # $cmd = "gdal_translate -ot Byte -of GTiff NETFUL.ARC NETFUL.tif";
    $cmd = "gdal_translate -of GTiff NETFUL.ARC NETFUL.tif";


    $_SESSION['outletX'] = NULL;
    $_SESSION['outletY'] = NULL;

    system($cmd);
}
function runTopazPass2($workingDir,$zone)
{
    $outLong = $_SESSION['outletX'];
    $outLat = $_SESSION['outletY'];
    $csa = $_SESSION['csa'];
    $mcl = $_SESSION['mcl'];

    if (($outLat == NULL) || ($outLong == NULL))
      return false;

    $cmd = "/home/wepp/wepp/preptopaz " . $workingDir . " " . $csa . " " . $mcl . " 3 3 2 " . $zone . " " . $outLong . " " . $outLat;

    print("\n");
    print($cmd);
    if (system($cmd) == FALSE) {
       echo("Error running preptopaz\n");
       return false;
    }

    chdir($workingDir);
    print("<p>\n");
    print("<pre>\n");
    system("echo 1 | ./dednm");

    print("\n\n");
    system("./raspro");

    print("\n\n");
    system("./rasfor");

    print("\n\n");

    // need to convert to mapserver will display it correctly with NODATA being transparent
    $cmd = "./mapColors SUBWTA.ARC subwta2.arc";
    system($cmd);
    $cmd = "gdal_translate -ot Byte -of GTiff subwta2.arc SUBWTA.tif";

    //$cmd = "gdal_translate -of GTiff SUBWTA.ARC SUBWTA.tif";
    system($cmd);

    print("\n\n");

    $cmd = "gdal_translate -ot Byte -of GTiff BOUND.ARC BOUND.tif";
    system($cmd);
    print("</pre>\n");

    return true;
}

function getSTATSGO_IDS($workingDir,$state)
{
     chdir($workingDir);
     print("<p>Getting STATSGO soil ids for area</p>");
     $cmd = "./dbfextract BOUND.ARC utmSliceSTATSGO.asc " . "/var/www/localhost/gis/" . $state . "/data" . " " . $state;
     print("<p>'" . $cmd . "'</p>");
     system($cmd,$rc);
}

function makeClassifyFile($tvalue,$classFile)
{
  $min = array();
  $max = array();
  $clname = array();
  $r = array();
  $g = array();
  $b = array();

  $min[0] = -999;
  $max[0] = -$tvalue;
  $clname[0] = "Soil Deposition > " . $tvalue . " t/ha/yr";

  $min[1] = $max[0];
  $max[1] = -0.0001;
  $clname[1] = "Soil Deposition " . $tvalue . " - " . -$max[1] . " t/ha/yr";

  $min[2] = $max[1];
  $max[2] = $tvalue / 4;
  if ($min[2] < 0.01)
    $clname[2] = "Soil Loss 0 - " . $max[2] . " t/ha/yr";
  else
    $clname[2] = "Soil Loss " . $min[2] . " - " . $max[2] . " t/ha/yr";

  $min[3] = $max[2];
  $max[3] = $min[3] + ($tvalue/4);
  $clname[3] = "Soil Loss " . $min[3] . " - " . $max[3] . " t/ha/yr";

  $min[4] = $max[3];
  $max[4] = $min[4] + ($tvalue/4);
  $clname[4] = "Soil Loss " . $min[4] . " - " . $max[4] . " t/ha/yr";

  $min[5] = $max[4];
  $max[5] = $tvalue;
  $clname[5] = "Soil Loss " . $min[5] . " - " . $max[5] . " t/ha/yr";

  $min[6] = $max[5];
  $max[6] = $min[6] + $tvalue;
  $clname[6] = "Soil Loss " . $min[6] . " - " . $max[6] . " t/ha/yr";

  $min[7] = $max[6];
  $max[7] = $min[7] + $tvalue;
  $clname[7] = "Soil Loss " . $min[7] . " - " . $max[7] . " t/ha/yr";

  $min[8] = $max[7];
  $max[8] = $min[8] + $tvalue;
  $clname[8] = "Soil Loss " . $min[8] . " - " . $max[8] . " t/ha/yr";

  $min[9] = $max[8];
  if ($min[9] < 1000)
    $max[9] = 1000;
  else
    $max[9] = $min[9] + $tvalue;

  $clname[9] = "Soil Loss " . $min[9] . " - " . $max[9] . " t/ha/yr";

  // read in the class file - pick up the r g b values.
  $fp = fopen($classFile,"r");
  if ($fp) {
     $numClasses = 0;
     $ind=0;
     $buf = fgets($fp,1024);
     $data = sscanf($buf,"%f");
     while (!feof($fp)) {
        $buf = fgets($fp,1024);
        $data = sscanf($buf,"%d %f %f %d %d %d");
        if (count($data) == 6) {
           $r[$ind] = $data[3];
           $g[$ind] = $data[4];
           $b[$ind] = $data[5];
        } else {
           $r[$ind] = 0;
           $g[$ind] = 0;
           $b[$ind] = 0;
	}
	$ind++;
     }
     fclose($fp);
  }


   echo("<br>" . $classFile . "<br>");
   // Write out the classify file
  $fp = fopen($classFile,"w");
  if ($fp) {
    fwrite($fp,$tvalue . "\n");
    for ($i=0;$i<10;$i++) {
      fwrite($fp,$i+1 . " " . $min[$i] . " " . $max[$i] . " " . $r[$i] . " " . $g[$i] . " " . $b[$i] . " \"" .$clname[$i] . "\"\n");
    } 
    fclose($fp);
  }

  echo("<br>T Value: " . $tvalue . "<br");
}
?>
