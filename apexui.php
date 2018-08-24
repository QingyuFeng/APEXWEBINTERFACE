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
    
    $idsp_usrinput = "none";
    
    if (!isset($_SESSION["PROJDATA"]))
    {
        $error[]="You do not have a project yet. Please go back to the home "
                . "page and create one!!!";
    }
    
    else
    {
        $idsp_usrinput = "block";
        // Local updating variables
        // Getting location variables
        if (!isset($_REQUEST['IX']))
        {$stIndex=$_SESSION["PROJDATA"]["stateindx"];}
        else
        {
            // When the state is updated, the index for
            // county and zipcode and soil shall all be set back to 0
            $stIndex = $_GET["IX"];
            $_SESSION["PROJDATA"]["stateindx"]=$_GET["IX"];
            // When the new county is updated,
            // It is set 0 here first and updated later.
            $_SESSION["PROJDATA"]["countyid"] = 0;
            $_SESSION["PROJDATA"]["zipcodeid"] = 0;
            $_SESSION["PROJDATA"]["soilid"] = 0;
        }	

        $counties = $uilist->countyarray($db, $usst_abbre[$stIndex]);

        if (!isset($_REQUEST['CtyIdx']))
        {$countyIndex=$_SESSION["PROJDATA"]["countyid"];}
        else
        {
            $countyIndex = $_GET["CtyIdx"];
            $_SESSION["PROJDATA"]["countyid"]=$_GET["CtyIdx"];
            $_SESSION["PROJDATA"]["zipcodeid"] = 0;
            $_SESSION["PROJDATA"]["soilid"] = 0;
        }	

        $county = $counties[$countyIndex]["countyname"];

        if (!isset($_REQUEST['ZipIdx']))
        {$zipIndex=$_SESSION["PROJDATA"]["zipcodeid"];}
        else
        {
            $zipIndex = $_GET["ZipIdx"];
            $_SESSION["PROJDATA"]["zipcodeid"]=$_GET["ZipIdx"];
            $_SESSION["PROJDATA"]["soilid"] = 0;
        }	  

        $ziparray = $uilist->ziparray($db, $usst_abbre[$stIndex], $county);

        $zipcode = $ziparray[$zipIndex]["zipcode"];

        // Get field characteristics
        if (!isset($_REQUEST['FdArea']))
        {$fldArea=$_SESSION["PROJDATA"]["fieldarea"];}
        else
        {
            $fldArea = $_GET["FdArea"];
            $_SESSION["PROJDATA"]["fieldarea"]=$_GET["FdArea"];
        }	

        if (!isset($_REQUEST['SteepIdx']))
        {$slpStpIdx=$_SESSION["PROJDATA"]["fieldslopeid"];}
        else
        {
            $slpStpIdx = $_GET["SteepIdx"];
            $_SESSION["PROJDATA"]["fieldslopeid"]=$_GET["SteepIdx"];
        }	

        if (!isset($_REQUEST['slplength']))
        {$slpLen=$_SESSION["PROJDATA"]["fieldslplen"];}
        else
        {
            $slpLen = $_GET["slplength"];
            $_SESSION["PROJDATA"]["fieldslplen"]=$slpLen;
        }

        // Get soil variables
        if (!isset($_REQUEST['soltn']))
        {$soltestn=$_SESSION["PROJDATA"]["soiltestn"];}
        else
        {
            $soltestn = $_GET["soltn"];
            $_SESSION["PROJDATA"]["soiltestn"]=$_GET["soltn"];
            
            
        }	

        if (!isset($_REQUEST['soltp']))
        {$soltestp=$_SESSION["PROJDATA"]["soiltestp"];}
        else
        {
            $soltestp = $_GET["soltp"];
            $_SESSION["PROJDATA"]["soiltestp"]=$_GET["soltp"];
        }	

        if (!isset($_REQUEST['SolIdx']))
        {$soilIndex=$_SESSION["PROJDATA"]["soilid"];}
        else
        {
            $soilIndex = $_GET["SolIdx"];
            $_SESSION["PROJDATA"]["soilid"]=$_GET["SolIdx"];
        }	

        // Management
        if (!isset($_REQUEST['DrDpt']))
        {$drainDepth=$_SESSION["PROJDATA"]["draindpth"];}
        else
        {
            $drainDepth = $_GET["DrDpt"];
            $_SESSION["PROJDATA"]["draindpth"]=$_GET["DrDpt"];
        }	

        // The management key shall be get from the management tabl
        $dfmk = $_SESSION["PROJDATA"]["dfmk"];      
        $man_name = $_SESSION["PROJDATA"]["management"][$dfmk]["mgtname"];

        // Weather data related variables
        if (!isset($_REQUEST['WeaDtChoi']))
        {$weadatachoice=$_SESSION["PROJDATA"]["weasource"];}
        else
        {
            $weadatachoice = $_GET["WeaDtChoi"];
            $_SESSION["PROJDATA"]["weasource"]=$_GET["WeaDtChoi"];
        }	

        if (!isset($_REQUEST['GenWeaSimYrs']))
        {$geneweasimyrs=$_SESSION["PROJDATA"]["weasimyrs"];}
        else
        {
            $geneweasimyrs = $_GET["GenWeaSimYrs"];
            $_SESSION["PROJDATA"]["weasimyrs"]=$_GET["GenWeaSimYrs"];
        }

        if (!isset($_REQUEST['ObsWeaSttYr']))
        {$obsweastrtyr=$_SESSION["PROJDATA"]["weastartyr"];}
        else
        {
            $obsweastrtyr = $_GET["ObsWeaSttYr"];
            $_SESSION["PROJDATA"]["weastartyr"]=$_GET["ObsWeaSttYr"];
        }

        if (!isset($_REQUEST['ObsWeaEndYr']))
        {$obsdweaendyr=$_SESSION["PROJDATA"]["weaendyr"];}
        else
        {
            $obsdweaendyr = $_GET["ObsWeaEndYr"];
            $_SESSION["PROJDATA"]["weaendyr"]=$_GET["ObsWeaEndYr"];
        }  
        
        // The session variable is converted to json 
        // for download purpose
        $json_proj = json_encode($_SESSION["PROJDATA"]);
        
        // When the run apex model is pressed, 
        // Update the user input variables using post
        // Redirect the page into the results page.
        if (!isset($_REQUEST['isb']))
        {$isubmit=null;}
        else{$isubmit = $_GET["isb"];}  
                
        if(!is_null($isubmit))
        {
            // Mark that the run has been made by press the button
            $_SESSION["PROJDATA"]["irun"]="yes";
            $_SESSION["PROJDATA"]["newrun"]="yes";
            header("Location: results.php");
            exit;
        }
        
    }
?>

<SCRIPT LANGAUGE="JavaScript">
    
function init()
{
    document.apexin.StateList.selectedIndex = <?=$stIndex?>;
    document.apexin.CountyList.selectedIndex = <?=$countyIndex?>;
    document.apexin.ZipcodeList.selectedIndex = <?=$zipIndex?>;
    
    document.apexin.FieldArea.value = <?=$fldArea?>;
    document.apexin.SlopeSteepness.selectedIndex = <?=$slpStpIdx?>;
    document.apexin.SlopeLength.value = <?=$slpLen?>;
    
    document.apexin.SoilList.selectedIndex = <?=$soilIndex?>;
    document.apexin.SoilTestN.value = <?=$soltestn?>;
    document.apexin.SoilTestP.value = <?=$soltestp?>;
    
    document.apexin.DrainageDpt.value = <?=$drainDepth?>;
    
    document.apexin.genweasimyear.value = <?=$geneweasimyrs?>;
    document.apexin.weadatasource.value = <?=$weadatachoice?>;
    document.apexin.obsweastartyr.value = <?=$obsweastrtyr?>;
    document.apexin.obsweaendyr.value = <?=$obsdweaendyr?>;    
}




function newState()
{
    index = document.apexin.StateList.selectedIndex;
    loc = "apexui.php?IX=" + index;

    loc = loc + "&FdArea=" + document.apexin.FieldArea.value;
    loc = loc + "&SteepIdx=" + document.apexin.SlopeSteepness.selectedIndex;
    loc = loc + "&slplength=" + document.apexin.SlopeLength.value;    
    
    loc = loc + "&soltn=" + document.apexin.SoilTestN.value;
    loc = loc + "&soltp=" + document.apexin.SoilTestP.value;

    loc = loc + "&DrDpt=" + document.apexin.DrainageDpt.value;
    
    if (document.getElementById("genewea").checked)
    {weachoi = document.getElementById("genewea").value;}
    else if(document.getElementById("obswea").checked)
    {weachoi = document.getElementById("obswea").value;}
    loc = loc + "&WeaDtChoi=" + weachoi;
    
    loc = loc + "&GenWeaSimYrs=" + document.apexin.genweasimyear.value;
    loc = loc + "&ObsWeaSttYr=" + document.apexin.obsweastartyr.value;
    loc = loc + "&ObsWeaEndYr=" + document.apexin.obsweaendyr.value;
    
    parent.location = loc;	
}

function newCounty()
{
    index = document.apexin.StateList.selectedIndex;
    loc = "apexui.php?IX=" + index;

    // Added by Qingyu Feng Oct 14, 2016
    indexcty = document.apexin.CountyList.selectedIndex;
    loc = loc + "&CtyIdx=" + indexcty;

    loc = loc + "&FdArea=" + document.apexin.FieldArea.value;
    loc = loc + "&SteepIdx=" + document.apexin.SlopeSteepness.selectedIndex;
    loc = loc + "&slplength=" + document.apexin.SlopeLength.value;    

    loc = loc + "&soltn=" + document.apexin.SoilTestN.value;
    loc = loc + "&soltp=" + document.apexin.SoilTestP.value;
     
    loc = loc + "&DrDpt=" + document.apexin.DrainageDpt.value;
    
    if (document.getElementById("genewea").checked)
    {weachoi = document.getElementById("genewea").value;}
    else if(document.getElementById("obswea").checked)
    {weachoi = document.getElementById("obswea").value;}
    loc = loc + "&WeaDtChoi=" + weachoi;
    
    loc = loc + "&GenWeaSimYrs=" + document.apexin.genweasimyear.value;
    loc = loc + "&ObsWeaSttYr=" + document.apexin.obsweastartyr.value;
    loc = loc + "&ObsWeaEndYr=" + document.apexin.obsweaendyr.value;
    
    
    parent.location = loc;	
}


function newZipcode()
{
    index = document.apexin.StateList.selectedIndex;
    loc = "apexui.php?IX=" + index;

    // Added by Qingyu Feng Oct 14, 2016
    indexcty = document.apexin.CountyList.selectedIndex;
    loc = loc + "&CtyIdx=" + indexcty;

    indexzip = document.apexin.ZipcodeList.selectedIndex;
    loc = loc + "&ZipIdx=" + indexzip;

    loc = loc + "&FdArea=" + document.apexin.FieldArea.value;
    loc = loc + "&SteepIdx=" + document.apexin.SlopeSteepness.selectedIndex;
    loc = loc + "&slplength=" + document.apexin.SlopeLength.value;    

    loc = loc + "&soltn=" + document.apexin.SoilTestN.value;
    loc = loc + "&soltp=" + document.apexin.SoilTestP.value;
    
    loc = loc + "&DrDpt=" + document.apexin.DrainageDpt.value;
    
    if (document.getElementById("genewea").checked)
    {weachoi = document.getElementById("genewea").value;}
    else if(document.getElementById("obswea").checked)
    {weachoi = document.getElementById("obswea").value;}
    loc = loc + "&WeaDtChoi=" + weachoi;
    
    loc = loc + "&GenWeaSimYrs=" + document.apexin.genweasimyear.value;
    loc = loc + "&ObsWeaSttYr=" + document.apexin.obsweastartyr.value;
    loc = loc + "&ObsWeaEndYr=" + document.apexin.obsweaendyr.value;
    
    parent.location = loc;	
}


function newSoil()
{
    index = document.apexin.StateList.selectedIndex;
    loc = "apexui.php?IX=" + index;

    // Added by Qingyu Feng Oct 14, 2016
    indexcty = document.apexin.CountyList.selectedIndex;
    loc = loc + "&CtyIdx=" + indexcty;

    indexzip = document.apexin.ZipcodeList.selectedIndex;
    loc = loc + "&ZipIdx=" + indexzip;

    loc = loc + "&FdArea=" + document.apexin.FieldArea.value;
    loc = loc + "&SteepIdx=" + document.apexin.SlopeSteepness.selectedIndex;
    loc = loc + "&slplength=" + document.apexin.SlopeLength.value;    

    loc = loc + "&soltn=" + document.apexin.SoilTestN.value;
    loc = loc + "&soltp=" + document.apexin.SoilTestP.value;
    
    indexsol = document.apexin.SoilList.selectedIndex;
    loc = loc + "&SolIdx=" + indexsol;

    loc = loc + "&DrDpt=" + document.apexin.DrainageDpt.value;
    
    if (document.getElementById("genewea").checked)
    {weachoi = document.getElementById("genewea").value;}
    else if(document.getElementById("obswea").checked)
    {weachoi = document.getElementById("obswea").value;}
    loc = loc + "&WeaDtChoi=" + weachoi;
    
    loc = loc + "&GenWeaSimYrs=" + document.apexin.genweasimyear.value;
    loc = loc + "&ObsWeaSttYr=" + document.apexin.obsweastartyr.value;
    loc = loc + "&ObsWeaEndYr=" + document.apexin.obsweaendyr.value;
    
    
    parent.location = loc;	
}



function showManagement()
{
    loc = "management.php?iact=showtmpdtl";
    parent.location = loc;
    
}

function runAPEX()
{
    index = document.apexin.StateList.selectedIndex;
    loc = "apexui.php?IX=" + index;

    indexcty = document.apexin.CountyList.selectedIndex;
    loc = loc + "&CtyIdx=" + indexcty;

    indexzip = document.apexin.ZipcodeList.selectedIndex;
    loc = loc + "&ZipIdx=" + indexzip;

    loc = loc + "&FdArea=" + document.apexin.FieldArea.value;
    loc = loc + "&SteepIdx=" + document.apexin.SlopeSteepness.selectedIndex;
    loc = loc + "&slplength=" + document.apexin.SlopeLength.value;    

    loc = loc + "&soltn=" + document.apexin.SoilTestN.value;
    loc = loc + "&soltp=" + document.apexin.SoilTestP.value;
    
    indexsol = document.apexin.SoilList.selectedIndex;
    loc = loc + "&SolIdx=" + indexsol;

    loc = loc + "&DrDpt=" + document.apexin.DrainageDpt.value;
    
    if (document.getElementById("genewea").checked)
    {weachoi = document.getElementById("genewea").value;}
    else if(document.getElementById("obswea").checked)
    {weachoi = document.getElementById("obswea").value;}
    loc = loc + "&WeaDtChoi=" + weachoi;
    
    loc = loc + "&GenWeaSimYrs=" + document.apexin.genweasimyear.value;
    loc = loc + "&ObsWeaSttYr=" + document.apexin.obsweastartyr.value;
    loc = loc + "&ObsWeaEndYr=" + document.apexin.obsweaendyr.value;

    loc = loc + "&isb=YES";
    
    parent.location = loc;
}

function saveProjJson()
{
    var jsonproj = JSON.parse('<?= $json_proj; ?>');
    var fileNameToSaveAs = "APEXOLProj_" + jsonproj.projectname+".json";
    
    var json = JSON.stringify(jsonproj);
    var textFileAsBlob = new Blob([json], {type: "application/json"});
    var url  = URL.createObjectURL(textFileAsBlob);

    var  downloadLink = document.createElement('a');
    downloadLink.download = fileNameToSaveAs;
    downloadLink.innerHTML = "Download File";
    
    if (window.webkitURL != null)
    {
        // Chrome allows the link to be clicked
        // without actually adding it to the DOM.
        downloadLink.href = window.webkitURL.createObjectURL(textFileAsBlob);
    }
    else
    {
        // Firefox requires the link to be added to the DOM
        // before it can be clicked.
        downloadLink.href = window.URL.createObjectURL(textFileAsBlob);
        downloadLink.onclick = destroyClickedElement;
        downloadLink.style.display = "none";
        document.body.appendChild(downloadLink);
    }

    downloadLink.click();    
}
function destroyClickedElement(event)
{
    document.body.removeChild(event.target);
}






</SCRIPT>


    
    <h2>Project name: 
        <?php
        //check for any errors
        if(isset($error)){
            foreach($error as $error){
                echo '<p class="bg-danger">'.$error.'</p>';
            }
            
        }
        else
        {
            echo $_SESSION["PROJDATA"]["projectname"];
            echo "<hr>";
        }
        
        ?>
        </h2>
    

   <div style="display: <?php echo $idsp_usrinput;?>">
    <form method="POST"
        name="apexin"
        id="apexin"
        >

        <table>
        <tr>
            <th colspan="6">
                <b><font size="3">Location&nbsp;</font></b>
            </th> 
        </tr>
        <tr>
            <td>
                <b><font size="2">State&nbsp;</font></b>
            </td>
            <td>
                <b><font size="2"><select size="1" 
                                          onChange="javascript:newState();"
                                          name="StateList"
                                          id="StateList"
                                          value="">
                        <?php 
                            
                               $uilist->listUSStates($us_state_abbrevs_names, 
                                       $usst_abbre);
                        ?></select></font></b>
            </td>

            <td>
                <b><font size="2">County&nbsp;</font></b>
            </td>
            <td>
                <b><font size="2"><select size="1" 
                                          onChange="javascript:newCounty();" 
                                          name="CountyList"
                                          id="CountyList"
                                          value ="$_SESSION["PROJDATA"]["countyid"]"
                                          >
                <?php
                    $uilist->listCounties($db, 
                            $counties, 
                            $usst_abbre[$stIndex], 
                            null)
                
                
                ?></select></font></b>
            </td>

            <td>
                <b><font size="2">Zipcode&nbsp;</font></b>
            </td>
            <td>
                <b><font size="2"><select size="1" 
                                          onChange="javascript:newZipcode();" 
                                          name="ZipcodeList"
                                          id="ZipcodeList"
                                          >
                        <?php
                            $uilist->listZipcode($db, 
                                    $ziparray, 
                                    null);
                        ?>
                        </select></font></b>
            </td>
        </tr>			
        </table>

        <hr>

        <table>
            <tr>
                <th colspan="6">
                    <b><font size="3">Topography&nbsp;</font></b>
                </th> 
            </tr>

            <tr>
                <td>
                    <b><font size="2">Area&nbsp;</font></b>
                </td>
                <td>
                    <b><font size="2"><input style="text-align: right" 
                                             type="text" 
                                             name="FieldArea" 
                                             id="FieldArea"
                                             size="7" 
                                             value="100">
                    (acre)</font></b>
                </td>

                <td>
                    <b><font size="2">Avg Slope&nbsp;</font></b>
                </td>
                <td>
                    <b><font size="2"><select size="1"
                                              name="SlopeSteepness"
                                              id="SlopeSteepness"
                                              >
                    <option value=0>0%</option>
                    <option value=1>1%</option>
                    <option value=2>2%</option>
                    <option value=3>3%</option>
                    <option value=4>4%</option>
                    <option value=5>5%</option>
                    <option value=6>6%</option>
                    <option value=7>7%</option>
                    <option value=8>8%</option>
                    <option value=9>9%</option>
                    <option value=10>10%</option>
                    <option value=15>15%</option>
                    <option value=20>20%</option>
                    <option value=25>25%</option>
                    <option value=30>30%</option>
                    <option value=35>35%</option>
                    <option value=40>40%</option>
                    <option value=45>45%</option>
                    <option value=50>50%</option>
                    </select></font></b>
                </td>

                <td>
                    <b><font size="2">Slp length&nbsp;</font></b>
                </td>
                <td>
                    <b><font size="2"><input style="text-align: right" 
                                             type="text" 
                                             name="SlopeLength" 
                                             id="SlopeLength"
                                             size="7" 
                                             value="50"> (feet)	
                    </font></b>
                </td>
                
            </tr>	
        </table>
        <hr>

        <table>
            <tr>
                <th colspan="5">
                        <b><font size="3">Soil&nbsp;</font></b>
                </th> 
            </tr>
            <tr>
                <td  colspan="1">
                    <b><font size="2">Name&nbsp;</font></b>
                </td>
                <td  colspan="4">
                    <b><font size="2"><select size="1" 
                                    onChange="javascript:newSoil();" 
                                    name="SoilList"
                                    id="SoilList"
                                    >
                            <?php
                                $uilist->listSoil($Connection, 
                                        $usst_abbre[$stIndex], 
                                        $county, 
                                        $zipcode, 
                                        null);
                            ?>
                            </select></font></b>
                </td>
            </tr>		

            <tr>
                <td  colspan="1">
                    <b><font size="2">Test N &nbsp;</font></b>
                </td>
                <td  colspan="1">
                    <b><font size="2"><input 
                            style="text-align: right" 
                            type="text" 
                            name="SoilTestN" 
                            id="SoilTestN"
                            size="7" 
                            value="">ppm</font></b>
                </td  colspan="1">

                <td  colspan="1">
                    <b><font size="2">Test P&nbsp;</font></b>
                </td>
                <td  colspan="1">
                    <b><font size="2"><input 
                            style="text-align: right" 
                            type="text" 
                            name="SoilTestP" 
                            id="SoilTestP"
                            size="7" 
                            value="">ppm</font></b>
                </td>

                <td  colspan="1">
                    <b><font size="2">Leave 0 if not available&nbsp;</font></b>
                </td>
            </tr>
        </table>

        <hr>        

        <table>
            <tr>
                <th colspan="3">
                    <b><font size="3">Management&nbsp;</font></b>
                </th> 
            </tr>

            <tr>
                <td colspan="2">
                    <?php
                        // if the user selected user mgt,
                        // print out user mgt name
                        echo $man_name;

                    ?>
                </td>

                
                <td  colspan="1">
                    <input type="button"
                            name="showmandtl"
                            id="showmandtl"
                            value ="Detail"
                            onClick="Javacsript:showManagement()"
                            >                    
                </td>
            </tr>
            
            <tr>
                <td>
                    <b><font size="2">Tile drainage depth&nbsp;</font></b>
                </td>
                <td>
                    <b><font size="2"><input 
                            style="text-align: right" 
                            type="text" 
                            name="DrainageDpt" 
                            id="DrainageDpt" 
                            size="7" 
                            value="0"> (feet, left 0 if not installed)</font></b>
                </td>
            </tr>	

        </table>

        <hr>

        <table>
            <tr>
                <th colspan="3">
                        <b><font size="3">Climate&nbsp;</font></b>
                </th> 
            </tr>
            <tr>
                <td>
                    <input type="radio"
                           name="weadatasource"
                           id ="genewea"
                           value="1" 
                           checked="checked">
                </td>
                <td>
                    <b><font size="2">Weather generator: simulation years (1 to 30 )&nbsp;</font></b>
                </td>
                <td>
                    <b><font size="2"><input
                            style="text-align: right" 
                            type="text" 
                            name="genweasimyear" 
                            size="4" 
                            value=30><br>
                    </font></b>
                </td>
            </tr>

            <tr>
                <td>
                    <input type="radio" 
                           name="weadatasource"
                           id="obswea"
                           value="2">
                </td>
                <td>
                    <b><font size="2">Observed data: simulation years (1974 to 2013)&nbsp;</font></b>
                </td>
                <td>
                    <b><font size="2">
                            <input style="text-align: right" type="text" name="obsweastartyr" size="4" value=2000>
                            to 
                            <input style="text-align: right" type="text" name="obsweaendyr" size="4" value=2010>
                            <br>
                            </font></b>
                </td>
            </tr>			
					
        </table>		

        <p align="center">
                <input type="button"  
                       value="Run APEX Model" 
                       onClick="Javacsript:runAPEX()"
                       name="button1_runapex"
                       id="button1_runapex"
                       >
                
                <input type="button"  
                       value="Save Project" 
                       onClick="Javacsript:saveProjJson()"
                       name="button2_saveapex"
                       id="button2_saveapex"
                       >
            </p>        

        </form>

  </div>
<br>
       

<?php
    include("html/footer.html");
?>