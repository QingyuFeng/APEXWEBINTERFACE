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

class jsonupdater
{

    public function UpdateJsonSol_newsol($Connection, $solmukeyselected, $jsonupdate_sol, $default)
    {
            $jsonupdate_sol["solmukey"]= $solmukeyselected;
            $sqlstmt = "SELECT * from ssurgo2apex where mukey = '" . $solmukeyselected . "' order by mukey"; 
            
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

                            $jsonupdate_sol["line1"]["soilname"]= pg_result($Result,$Row,"muname");

                            $jsonupdate_sol["line2"]["abledo_salb"]= pg_result($Result,$Row,"albedodry_r");
                            $jsonupdate_sol["line2"]["hydrologicgroup_hsg"]= pg_result($Result,$Row,"hydgrpdcd");
                            $jsonupdate_sol["line2"]["initialwatercontent_ffc"]= "0.00";
                            $jsonupdate_sol["line2"]["minwatertabledep_wtmn"]= pg_result($Result,$Row,"wtdepannmin");
                            $jsonupdate_sol["line2"]["maxwatertabledep_wtmx"]= "0.00";
                            $jsonupdate_sol["line2"]["initialwatertable_wtbl"]= "0.00";
                            $jsonupdate_sol["line2"]["groundwaterstorage_gwst"]= "0.00";
                            $jsonupdate_sol["line2"]["max_groundwater_gwmx"]= "0.00";
                            $jsonupdate_sol["line2"]["gw_residenttime_rftt"]= "0.00";
                            $jsonupdate_sol["line2"]["return_overtotalflow_rfpk"] = "0.00";

                            $jsonupdate_sol["line3"]["min_layerdepth_tsla"]= "10.00";
                            $jsonupdate_sol["line3"]["weatheringcode_xids"]= "0.00";
                            $jsonupdate_sol["line3"]["cultivationyears_rtn1"]= "50.00";
                            $jsonupdate_sol["line3"]["grouping_xidk"]= "2.00";
                            $jsonupdate_sol["line3"]["min_maxlayerthick_zqt"]= "0.01";
                            $jsonupdate_sol["line3"]["minprofilethick_zf"]= "0.05";
                            $jsonupdate_sol["line3"]["minlayerthick_ztk"]= "0.05";
                            $jsonupdate_sol["line3"]["org_c_biomass_fbm"]= "0.03";
                            $jsonupdate_sol["line3"]["org_c_passive_fhp"]= "0.30";

                            $jsonupdate_sol["line4_layerdepth"]["z1"]= pg_result($Result,$Row,"l1_layerdepth");
                            $jsonupdate_sol["line4_layerdepth"]["z2"]= pg_result($Result,$Row,"l2_layerdepth");
                            $jsonupdate_sol["line4_layerdepth"]["z3"]= pg_result($Result,$Row,"l3_layerdepth");
                            $jsonupdate_sol["line4_layerdepth"]["z4"]= pg_result($Result,$Row,"l4_layerdepth");
                            $jsonupdate_sol["line4_layerdepth"]["z5"]= pg_result($Result,$Row,"l5_layerdepth");
                            $jsonupdate_sol["line4_layerdepth"]["z6"]= pg_result($Result,$Row,"l6_layerdepth");
                            $jsonupdate_sol["line4_layerdepth"]["z7"]= pg_result($Result,$Row,"l7_layerdepth");
                            $jsonupdate_sol["line4_layerdepth"]["z8"]= pg_result($Result,$Row,"l8_layerdepth");
                            $jsonupdate_sol["line4_layerdepth"]["z9"]= pg_result($Result,$Row,"l9_layerdepth");
                            $jsonupdate_sol["line4_layerdepth"]["z10"]= pg_result($Result,$Row,"l10_layerdepth");

                            $jsonupdate_sol["line5_moistbulkdensity"]["z1"]= pg_result($Result,$Row,"l1_bulkdensity");
                            $jsonupdate_sol["line5_moistbulkdensity"]["z2"]= pg_result($Result,$Row,"l2_bulkdensity");
                            $jsonupdate_sol["line5_moistbulkdensity"]["z3"]= pg_result($Result,$Row,"l3_bulkdensity");
                            $jsonupdate_sol["line5_moistbulkdensity"]["z4"]= pg_result($Result,$Row,"l4_bulkdensity");
                            $jsonupdate_sol["line5_moistbulkdensity"]["z5"]= pg_result($Result,$Row,"l5_bulkdensity");
                            $jsonupdate_sol["line5_moistbulkdensity"]["z6"]= pg_result($Result,$Row,"l6_bulkdensity");
                            $jsonupdate_sol["line5_moistbulkdensity"]["z7"]= pg_result($Result,$Row,"l7_bulkdensity");
                            $jsonupdate_sol["line5_moistbulkdensity"]["z8"]= pg_result($Result,$Row,"l8_bulkdensity");
                            $jsonupdate_sol["line5_moistbulkdensity"]["z9"]= pg_result($Result,$Row,"l9_bulkdensity");
                            $jsonupdate_sol["line5_moistbulkdensity"]["z10"]= pg_result($Result,$Row,"l10_bulkdensity");

                            $jsonupdate_sol["line6_wiltingpoint"]["z1"]= pg_result($Result,$Row,"l1_wiltingpoint");
                            $jsonupdate_sol["line6_wiltingpoint"]["z2"]= pg_result($Result,$Row,"l2_wiltingpoint");
                            $jsonupdate_sol["line6_wiltingpoint"]["z3"]= pg_result($Result,$Row,"l3_wiltingpoint");
                            $jsonupdate_sol["line6_wiltingpoint"]["z4"]= pg_result($Result,$Row,"l4_wiltingpoint");
                            $jsonupdate_sol["line6_wiltingpoint"]["z5"]= pg_result($Result,$Row,"l5_wiltingpoint");
                            $jsonupdate_sol["line6_wiltingpoint"]["z6"]= pg_result($Result,$Row,"l6_wiltingpoint");
                            $jsonupdate_sol["line6_wiltingpoint"]["z7"]= pg_result($Result,$Row,"l7_wiltingpoint");
                            $jsonupdate_sol["line6_wiltingpoint"]["z8"]= pg_result($Result,$Row,"l8_wiltingpoint");
                            $jsonupdate_sol["line6_wiltingpoint"]["z9"]= pg_result($Result,$Row,"l9_wiltingpoint");
                            $jsonupdate_sol["line6_wiltingpoint"]["z10"]= pg_result($Result,$Row,"l10_wiltingpoint");

                            $jsonupdate_sol["line7_fieldcapacity"]["z1"]= pg_result($Result,$Row,"l1_fieldcapacity");
                            $jsonupdate_sol["line7_fieldcapacity"]["z2"]= pg_result($Result,$Row,"l2_fieldcapacity");
                            $jsonupdate_sol["line7_fieldcapacity"]["z3"]= pg_result($Result,$Row,"l3_fieldcapacity");
                            $jsonupdate_sol["line7_fieldcapacity"]["z4"]= pg_result($Result,$Row,"l4_fieldcapacity");
                            $jsonupdate_sol["line7_fieldcapacity"]["z5"]= pg_result($Result,$Row,"l5_fieldcapacity");
                            $jsonupdate_sol["line7_fieldcapacity"]["z6"]= pg_result($Result,$Row,"l6_fieldcapacity");
                            $jsonupdate_sol["line7_fieldcapacity"]["z7"]= pg_result($Result,$Row,"l7_fieldcapacity");
                            $jsonupdate_sol["line7_fieldcapacity"]["z8"]= pg_result($Result,$Row,"l8_fieldcapacity");
                            $jsonupdate_sol["line7_fieldcapacity"]["z9"]= pg_result($Result,$Row,"l9_fieldcapacity");
                            $jsonupdate_sol["line7_fieldcapacity"]["z10"]= pg_result($Result,$Row,"l10_fieldcapacity");

                            $jsonupdate_sol["line8_sand"]["z1"]= pg_result($Result,$Row,"l1_sandtotal");
                            $jsonupdate_sol["line8_sand"]["z2"]= pg_result($Result,$Row,"l2_sandtotal");
                            $jsonupdate_sol["line8_sand"]["z3"]= pg_result($Result,$Row,"l3_sandtotal");
                            $jsonupdate_sol["line8_sand"]["z4"]= pg_result($Result,$Row,"l4_sandtotal");
                            $jsonupdate_sol["line8_sand"]["z5"]= pg_result($Result,$Row,"l5_sandtotal");
                            $jsonupdate_sol["line8_sand"]["z6"]= pg_result($Result,$Row,"l6_sandtotal");
                            $jsonupdate_sol["line8_sand"]["z7"]= pg_result($Result,$Row,"l7_sandtotal");
                            $jsonupdate_sol["line8_sand"]["z8"]= pg_result($Result,$Row,"l8_sandtotal");
                            $jsonupdate_sol["line8_sand"]["z9"]= pg_result($Result,$Row,"l9_sandtotal");
                            $jsonupdate_sol["line8_sand"]["z10"]= pg_result($Result,$Row,"l10_sandtotal");

                            $jsonupdate_sol["line9_silt"]["z1"]= pg_result($Result,$Row,"l1_silttotal");
                            $jsonupdate_sol["line9_silt"]["z2"]= pg_result($Result,$Row,"l2_silttotal");
                            $jsonupdate_sol["line9_silt"]["z3"]= pg_result($Result,$Row,"l3_silttotal");
                            $jsonupdate_sol["line9_silt"]["z4"]= pg_result($Result,$Row,"l4_silttotal");
                            $jsonupdate_sol["line9_silt"]["z5"]= pg_result($Result,$Row,"l5_silttotal");
                            $jsonupdate_sol["line9_silt"]["z6"]= pg_result($Result,$Row,"l6_silttotal");
                            $jsonupdate_sol["line9_silt"]["z7"]= pg_result($Result,$Row,"l7_silttotal");
                            $jsonupdate_sol["line9_silt"]["z8"]= pg_result($Result,$Row,"l8_silttotal");
                            $jsonupdate_sol["line9_silt"]["z9"]= pg_result($Result,$Row,"l9_silttotal");
                            $jsonupdate_sol["line9_silt"]["z10"]= pg_result($Result,$Row,"l10_silttotal");


                            $jsonupdate_sol["line11_ph"]["z1"]= pg_result($Result,$Row,"l1_ph");
                            $jsonupdate_sol["line11_ph"]["z2"]= pg_result($Result,$Row,"l2_ph");
                            $jsonupdate_sol["line11_ph"]["z3"]= pg_result($Result,$Row,"l3_ph");
                            $jsonupdate_sol["line11_ph"]["z4"]= pg_result($Result,$Row,"l4_ph");
                            $jsonupdate_sol["line11_ph"]["z5"]= pg_result($Result,$Row,"l5_ph");
                            $jsonupdate_sol["line11_ph"]["z6"]= pg_result($Result,$Row,"l6_ph");
                            $jsonupdate_sol["line11_ph"]["z7"]= pg_result($Result,$Row,"l7_ph");
                            $jsonupdate_sol["line11_ph"]["z8"]= pg_result($Result,$Row,"l8_ph");
                            $jsonupdate_sol["line11_ph"]["z9"]= pg_result($Result,$Row,"l9_ph");
                            $jsonupdate_sol["line11_ph"]["z10"]= pg_result($Result,$Row,"l10_ph");

                            $jsonupdate_sol["line12_sumofbase_smb"]["z1"]= pg_result($Result,$Row,"l1_sumofbases");
                            $jsonupdate_sol["line12_sumofbase_smb"]["z2"]= pg_result($Result,$Row,"l2_sumofbases");
                            $jsonupdate_sol["line12_sumofbase_smb"]["z3"]= pg_result($Result,$Row,"l3_sumofbases");
                            $jsonupdate_sol["line12_sumofbase_smb"]["z4"]= pg_result($Result,$Row,"l4_sumofbases");
                            $jsonupdate_sol["line12_sumofbase_smb"]["z5"]= pg_result($Result,$Row,"l5_sumofbases");
                            $jsonupdate_sol["line12_sumofbase_smb"]["z6"]= pg_result($Result,$Row,"l6_sumofbases");
                            $jsonupdate_sol["line12_sumofbase_smb"]["z7"]= pg_result($Result,$Row,"l7_sumofbases");
                            $jsonupdate_sol["line12_sumofbase_smb"]["z8"]= pg_result($Result,$Row,"l8_sumofbases");
                            $jsonupdate_sol["line12_sumofbase_smb"]["z9"]= pg_result($Result,$Row,"l9_sumofbases");
                            $jsonupdate_sol["line12_sumofbase_smb"]["z10"]= pg_result($Result,$Row,"l10_sumofbases");

                            $jsonupdate_sol["line13_orgc_conc_woc"]["z1"]= pg_result($Result,$Row,"l1_organicmatter");
                            $jsonupdate_sol["line13_orgc_conc_woc"]["z2"]= pg_result($Result,$Row,"l2_organicmatter");
                            $jsonupdate_sol["line13_orgc_conc_woc"]["z3"]= pg_result($Result,$Row,"l3_organicmatter");
                            $jsonupdate_sol["line13_orgc_conc_woc"]["z4"]= pg_result($Result,$Row,"l4_organicmatter");
                            $jsonupdate_sol["line13_orgc_conc_woc"]["z5"]= pg_result($Result,$Row,"l5_organicmatter");
                            $jsonupdate_sol["line13_orgc_conc_woc"]["z6"]= pg_result($Result,$Row,"l6_organicmatter");
                            $jsonupdate_sol["line13_orgc_conc_woc"]["z7"]= pg_result($Result,$Row,"l7_organicmatter");
                            $jsonupdate_sol["line13_orgc_conc_woc"]["z8"]= pg_result($Result,$Row,"l8_organicmatter");
                            $jsonupdate_sol["line13_orgc_conc_woc"]["z9"]= pg_result($Result,$Row,"l9_organicmatter");
                            $jsonupdate_sol["line13_orgc_conc_woc"]["z10"]= pg_result($Result,$Row,"l10_organicmatter");

                            $jsonupdate_sol["line14_caco3_cac"]["z1"]= pg_result($Result,$Row,"l1_caco3");
                            $jsonupdate_sol["line14_caco3_cac"]["z2"]= pg_result($Result,$Row,"l2_caco3");
                            $jsonupdate_sol["line14_caco3_cac"]["z3"]= pg_result($Result,$Row,"l3_caco3");
                            $jsonupdate_sol["line14_caco3_cac"]["z4"]= pg_result($Result,$Row,"l4_caco3");
                            $jsonupdate_sol["line14_caco3_cac"]["z5"]= pg_result($Result,$Row,"l5_caco3");
                            $jsonupdate_sol["line14_caco3_cac"]["z6"]= pg_result($Result,$Row,"l6_caco3");
                            $jsonupdate_sol["line14_caco3_cac"]["z7"]= pg_result($Result,$Row,"l7_caco3");
                            $jsonupdate_sol["line14_caco3_cac"]["z8"]= pg_result($Result,$Row,"l8_caco3");
                            $jsonupdate_sol["line14_caco3_cac"]["z9"]= pg_result($Result,$Row,"l9_caco3");
                            $jsonupdate_sol["line14_caco3_cac"]["z10"]= pg_result($Result,$Row,"l10_caco3");

                            $jsonupdate_sol["line15_cec"]["z1"]= pg_result($Result,$Row,"l1_cec");
                            $jsonupdate_sol["line15_cec"]["z2"]= pg_result($Result,$Row,"l2_cec");
                            $jsonupdate_sol["line15_cec"]["z3"]= pg_result($Result,$Row,"l3_cec");
                            $jsonupdate_sol["line15_cec"]["z4"]= pg_result($Result,$Row,"l4_cec");
                            $jsonupdate_sol["line15_cec"]["z5"]= pg_result($Result,$Row,"l5_cec");
                            $jsonupdate_sol["line15_cec"]["z6"]= pg_result($Result,$Row,"l6_cec");
                            $jsonupdate_sol["line15_cec"]["z7"]= pg_result($Result,$Row,"l7_cec");
                            $jsonupdate_sol["line15_cec"]["z8"]= pg_result($Result,$Row,"l8_cec");
                            $jsonupdate_sol["line15_cec"]["z9"]= pg_result($Result,$Row,"l9_cec");
                            $jsonupdate_sol["line15_cec"]["z10"]= pg_result($Result,$Row,"l10_cec");

                            $jsonupdate_sol["line16_rock_rok"]["z1"]= pg_result($Result,$Row,"l1_croasefragment");
                            $jsonupdate_sol["line16_rock_rok"]["z2"]= pg_result($Result,$Row,"l2_croasefragment");
                            $jsonupdate_sol["line16_rock_rok"]["z3"]= pg_result($Result,$Row,"l3_croasefragment");
                            $jsonupdate_sol["line16_rock_rok"]["z4"]= pg_result($Result,$Row,"l4_croasefragment");
                            $jsonupdate_sol["line16_rock_rok"]["z5"]= pg_result($Result,$Row,"l5_croasefragment");
                            $jsonupdate_sol["line16_rock_rok"]["z6"]= pg_result($Result,$Row,"l6_croasefragment");
                            $jsonupdate_sol["line16_rock_rok"]["z7"]= pg_result($Result,$Row,"l7_croasefragment");
                            $jsonupdate_sol["line16_rock_rok"]["z8"]= pg_result($Result,$Row,"l8_croasefragment");
                            $jsonupdate_sol["line16_rock_rok"]["z9"]= pg_result($Result,$Row,"l9_croasefragment");
                            $jsonupdate_sol["line16_rock_rok"]["z10"]= pg_result($Result,$Row,"l10_croasefragment");

                            $jsonupdate_sol["line17_inisolnconc_cnds"]["z1"]= 0.00;
                            $jsonupdate_sol["line17_inisolnconc_cnds"]["z2"]= 0.00;
                            $jsonupdate_sol["line17_inisolnconc_cnds"]["z3"]= 0.00;
                            $jsonupdate_sol["line17_inisolnconc_cnds"]["z4"]= 0.00;
                            $jsonupdate_sol["line17_inisolnconc_cnds"]["z5"]= 0.00;
                            $jsonupdate_sol["line17_inisolnconc_cnds"]["z6"]= 0.00;
                            $jsonupdate_sol["line17_inisolnconc_cnds"]["z7"]= 0.00;
                            $jsonupdate_sol["line17_inisolnconc_cnds"]["z8"]= 0.00;
                            $jsonupdate_sol["line17_inisolnconc_cnds"]["z9"]= 0.00;
                            $jsonupdate_sol["line17_inisolnconc_cnds"]["z10"]= 0.00;

                            $jsonupdate_sol["line18_soilp_ssf"]["z1"]= pg_result($Result,$Row,"l1_ph2osoluble_r");
                            $jsonupdate_sol["line18_soilp_ssf"]["z2"]= pg_result($Result,$Row,"l2_ph2osoluble_r");
                            $jsonupdate_sol["line18_soilp_ssf"]["z3"]= pg_result($Result,$Row,"l3_ph2osoluble_r");
                            $jsonupdate_sol["line18_soilp_ssf"]["z4"]= pg_result($Result,$Row,"l4_ph2osoluble_r");
                            $jsonupdate_sol["line18_soilp_ssf"]["z5"]= pg_result($Result,$Row,"l5_ph2osoluble_r");
                            $jsonupdate_sol["line18_soilp_ssf"]["z6"]= pg_result($Result,$Row,"l6_ph2osoluble_r");
                            $jsonupdate_sol["line18_soilp_ssf"]["z7"]= pg_result($Result,$Row,"l7_ph2osoluble_r");
                            $jsonupdate_sol["line18_soilp_ssf"]["z8"]= pg_result($Result,$Row,"l8_ph2osoluble_r");
                            $jsonupdate_sol["line18_soilp_ssf"]["z9"]= pg_result($Result,$Row,"l9_ph2osoluble_r");
                            $jsonupdate_sol["line18_soilp_ssf"]["z10"]= pg_result($Result,$Row,"l10_ph2osoluble_r");

                            $jsonupdate_sol["line20_drybd_bdd"]["z1"]= pg_result($Result,$Row,"l1_drybulkdensity");
                            $jsonupdate_sol["line20_drybd_bdd"]["z2"]= pg_result($Result,$Row,"l2_drybulkdensity");
                            $jsonupdate_sol["line20_drybd_bdd"]["z3"]= pg_result($Result,$Row,"l3_drybulkdensity");
                            $jsonupdate_sol["line20_drybd_bdd"]["z4"]= pg_result($Result,$Row,"l4_drybulkdensity");
                            $jsonupdate_sol["line20_drybd_bdd"]["z5"]= pg_result($Result,$Row,"l5_drybulkdensity");
                            $jsonupdate_sol["line20_drybd_bdd"]["z6"]= pg_result($Result,$Row,"l6_drybulkdensity");
                            $jsonupdate_sol["line20_drybd_bdd"]["z7"]= pg_result($Result,$Row,"l7_drybulkdensity");
                            $jsonupdate_sol["line20_drybd_bdd"]["z8"]= pg_result($Result,$Row,"l8_drybulkdensity");
                            $jsonupdate_sol["line20_drybd_bdd"]["z9"]= pg_result($Result,$Row,"l9_drybulkdensity");
                            $jsonupdate_sol["line20_drybd_bdd"]["z10"]= pg_result($Result,$Row,"l10_drybulkdensity");

                            $jsonupdate_sol["line22_ksat"]["z1"]= pg_result($Result,$Row,"l1_ksat");
                            $jsonupdate_sol["line22_ksat"]["z2"]= pg_result($Result,$Row,"l2_ksat");
                            $jsonupdate_sol["line22_ksat"]["z3"]= pg_result($Result,$Row,"l3_ksat");
                            $jsonupdate_sol["line22_ksat"]["z4"]= pg_result($Result,$Row,"l4_ksat");
                            $jsonupdate_sol["line22_ksat"]["z5"]= pg_result($Result,$Row,"l5_ksat");
                            $jsonupdate_sol["line22_ksat"]["z6"]= pg_result($Result,$Row,"l6_ksat");
                            $jsonupdate_sol["line22_ksat"]["z7"]= pg_result($Result,$Row,"l7_ksat");
                            $jsonupdate_sol["line22_ksat"]["z8"]= pg_result($Result,$Row,"l8_ksat");
                            $jsonupdate_sol["line22_ksat"]["z9"]= pg_result($Result,$Row,"l9_ksat");
                            $jsonupdate_sol["line22_ksat"]["z10"]= pg_result($Result,$Row,"l10_ksat");

                            
                            
                            $jsonupdate_sol["line24_orgp_wpo"]["z1"]= pg_result($Result,$Row,"l1_ptotal") - pg_result($Result,$Row,"l1_ph2osoluble_r");
                            $jsonupdate_sol["line24_orgp_wpo"]["z2"]= pg_result($Result,$Row,"l2_ptotal") - pg_result($Result,$Row,"l2_ph2osoluble_r");
                            $jsonupdate_sol["line24_orgp_wpo"]["z3"]= pg_result($Result,$Row,"l3_ptotal") - pg_result($Result,$Row,"l3_ph2osoluble_r");
                            $jsonupdate_sol["line24_orgp_wpo"]["z4"]= pg_result($Result,$Row,"l4_ptotal") - pg_result($Result,$Row,"l4_ph2osoluble_r");
                            $jsonupdate_sol["line24_orgp_wpo"]["z5"]= pg_result($Result,$Row,"l5_ptotal") - pg_result($Result,$Row,"l5_ph2osoluble_r");
                            $jsonupdate_sol["line24_orgp_wpo"]["z6"]= pg_result($Result,$Row,"l6_ptotal") - pg_result($Result,$Row,"l6_ph2osoluble_r");
                            $jsonupdate_sol["line24_orgp_wpo"]["z7"]= pg_result($Result,$Row,"l7_ptotal") - pg_result($Result,$Row,"l7_ph2osoluble_r");
                            $jsonupdate_sol["line24_orgp_wpo"]["z8"]= pg_result($Result,$Row,"l8_ptotal") - pg_result($Result,$Row,"l8_ph2osoluble_r");
                            $jsonupdate_sol["line24_orgp_wpo"]["z9"]= pg_result($Result,$Row,"l9_ptotal") - pg_result($Result,$Row,"l9_ph2osoluble_r");
                            $jsonupdate_sol["line24_orgp_wpo"]["z10"]= pg_result($Result,$Row,"l10_ptotal") - pg_result($Result,$Row,"l10_ph2osoluble_r");

                            $jsonupdate_sol["line26_electricalcond_ec"]["z1"]= pg_result($Result,$Row,"l1_ec");
                            $jsonupdate_sol["line26_electricalcond_ec"]["z2"]= pg_result($Result,$Row,"l2_ec");
                            $jsonupdate_sol["line26_electricalcond_ec"]["z3"]= pg_result($Result,$Row,"l3_ec");
                            $jsonupdate_sol["line26_electricalcond_ec"]["z4"]= pg_result($Result,$Row,"l4_ec");
                            $jsonupdate_sol["line26_electricalcond_ec"]["z5"]= pg_result($Result,$Row,"l5_ec");
                            $jsonupdate_sol["line26_electricalcond_ec"]["z6"]= pg_result($Result,$Row,"l6_ec");
                            $jsonupdate_sol["line26_electricalcond_ec"]["z7"]= pg_result($Result,$Row,"l7_ec");
                            $jsonupdate_sol["line26_electricalcond_ec"]["z8"]= pg_result($Result,$Row,"l8_ec");
                            $jsonupdate_sol["line26_electricalcond_ec"]["z9"]= pg_result($Result,$Row,"l9_ec");
                            $jsonupdate_sol["line26_electricalcond_ec"]["z10"]= pg_result($Result,$Row,"l10_ec");

                            $jsonupdate_sol["layerid"]["z1"]= pg_result($Result,$Row,"l1_layerid");
                            $jsonupdate_sol["layerid"]["z2"]= pg_result($Result,$Row,"l2_layerid");
                            $jsonupdate_sol["layerid"]["z3"]= pg_result($Result,$Row,"l3_layerid");
                            $jsonupdate_sol["layerid"]["z4"]= pg_result($Result,$Row,"l4_layerid");
                            $jsonupdate_sol["layerid"]["z5"]= pg_result($Result,$Row,"l5_layerid");
                            $jsonupdate_sol["layerid"]["z6"]= pg_result($Result,$Row,"l6_layerid");
                            $jsonupdate_sol["layerid"]["z7"]= pg_result($Result,$Row,"l7_layerid");
                            $jsonupdate_sol["layerid"]["z8"]= pg_result($Result,$Row,"l8_layerid");
                            $jsonupdate_sol["layerid"]["z9"]= pg_result($Result,$Row,"l9_layerid");
                            $jsonupdate_sol["layerid"]["z10"]= pg_result($Result,$Row,"l10_layerid");			

                    }

            pg_freeresult($Result);
                    return $jsonupdate_sol;
    }

    public function UpdateJsonSol_soiltestN($jsonupdate_sol, $soiltest_n)
    {

            $jsonupdate_sol["line17_inisolnconc_cnds"]["z1"]= $soiltest_n;
            $jsonupdate_sol["line17_inisolnconc_cnds"]["z2"]= $soiltest_n;
            $jsonupdate_sol["line17_inisolnconc_cnds"]["z3"]= $soiltest_n;
            $jsonupdate_sol["line17_inisolnconc_cnds"]["z4"]= $soiltest_n;
            $jsonupdate_sol["line17_inisolnconc_cnds"]["z5"]= $soiltest_n;
            $jsonupdate_sol["line17_inisolnconc_cnds"]["z6"]= $soiltest_n;
            $jsonupdate_sol["line17_inisolnconc_cnds"]["z7"]= $soiltest_n;
            $jsonupdate_sol["line17_inisolnconc_cnds"]["z8"]= $soiltest_n;
            $jsonupdate_sol["line17_inisolnconc_cnds"]["z9"]= $soiltest_n;
            $jsonupdate_sol["line17_inisolnconc_cnds"]["z10"]= $soiltest_n;

            return $jsonupdate_sol;
    }

    public function UpdateJsonSol_soiltestP($jsonupdate_sol, $soiltest_p)
    {

            $jsonupdate_sol["line18_soilp_ssf"]["z1"]= $soiltest_p;
            $jsonupdate_sol["line18_soilp_ssf"]["z2"]= $soiltest_p;
            $jsonupdate_sol["line18_soilp_ssf"]["z3"]= $soiltest_p;
            $jsonupdate_sol["line18_soilp_ssf"]["z4"]= $soiltest_p;
            $jsonupdate_sol["line18_soilp_ssf"]["z5"]= $soiltest_p;
            $jsonupdate_sol["line18_soilp_ssf"]["z6"]= $soiltest_p;
            $jsonupdate_sol["line18_soilp_ssf"]["z7"]= $soiltest_p;
            $jsonupdate_sol["line18_soilp_ssf"]["z8"]= $soiltest_p;
            $jsonupdate_sol["line18_soilp_ssf"]["z9"]= $soiltest_p;
            $jsonupdate_sol["line18_soilp_ssf"]["z10"]= $soiltest_p;

            return $jsonupdate_sol;
    }

    public function updateManDatabase($db, $tmpid, $mankey, $mgtname, $jsonupdate_mgtops)
    {
        // To get the database value.
        $stmt = $db->prepare("SELECT * FROM "
        . "apexmgtdetailsdefault "
        . "WHERE "
        . "operationid= "
        . ":operationid");
        $stmt->execute(array(
            "operationid"=>$tmpid
        ));
        $Result = $stmt->fetchall(PDO::FETCH_ASSOC);
        
        $jsonupdate_mgtops["mgtname"] = $mgtname;
        $jsonupdate_mgtops["mgtkey"] = $mankey;
        // Now processing the row values to update the mgt
        for ($Row=0; $Row < count($Result); $Row++) 
        {
            $jsonupdate_mgtops["operationid"]["operationid".$Row]=$Result[$Row]["operationid"];
            $jsonupdate_mgtops["lun_landuseno"]["lun_landuseno".$Row]=$Result[$Row]["lun_landuseno"];
            $jsonupdate_mgtops["iaui_autoirr"]["iaui_autoirr".$Row]=$Result[$Row]["iaui_autoirr"];
            $jsonupdate_mgtops["iauf_autofert"]["iauf_autofert".$Row]=$Result[$Row]["iauf_autofert"];
            $jsonupdate_mgtops["iamf_automanualdepos"]["iamf_automanualdepos".$Row]=$Result[$Row]["iamf_automanualdepos"];
            $jsonupdate_mgtops["ispf_autosolman"]["ispf_autosolman".$Row]=$Result[$Row]["ispf_autosolman"];
            $jsonupdate_mgtops["ilqf_atliqman"]["ilqf_atliqman".$Row]=$Result[$Row]["ilqf_atliqman"];
            $jsonupdate_mgtops["iaul_atlime"]["iaul_atlime".$Row]=$Result[$Row]["iaul_atlime"];
            $jsonupdate_mgtops["jx1_year"]["jx1_year".$Row]=$Result[$Row]["jx1_year"];
            $jsonupdate_mgtops["jx2_month"]["jx2_month".$Row]=$Result[$Row]["jx2_month"];
            $jsonupdate_mgtops["jx3_day"]["jx3_day".$Row]=$Result[$Row]["jx3_day"];
            $jsonupdate_mgtops["jx4_tillid"]["jx4_tillid".$Row]=$Result[$Row]["jx4_tillid"];
            $jsonupdate_mgtops["jx5_tractid"]["jx5_tractid".$Row]=$Result[$Row]["jx5_tractid"];
            $jsonupdate_mgtops["jx6_cropid"]["jx6_cropid".$Row]=$Result[$Row]["jx6_cropid"];
            $jsonupdate_mgtops["jx7"]["jx7".$Row]=$Result[$Row]["jx7"];
            $jsonupdate_mgtops["opv1"]["opv1".$Row]=$Result[$Row]["opv1"];
            $jsonupdate_mgtops["opv2"]["opv2".$Row]=$Result[$Row]["opv2"];
            $jsonupdate_mgtops["opv3"]["opv3".$Row]=$Result[$Row]["opv3"];
            $jsonupdate_mgtops["opv4"]["opv4".$Row]=$Result[$Row]["opv4"];
            $jsonupdate_mgtops["opv5"]["opv5".$Row]=$Result[$Row]["opv5"];
            $jsonupdate_mgtops["opv6"]["opv6".$Row]=$Result[$Row]["opv6"];
            $jsonupdate_mgtops["opv7"]["opv7".$Row]=$Result[$Row]["opv7"];
            $jsonupdate_mgtops["opv8"]["opv8".$Row]=$Result[$Row]["opv8"];            
            $jsonupdate_mgtops["manningn"]["manningn".$Row]=$Result[$Row]["manningn"];                        
        }
        return $jsonupdate_mgtops;
        
    }
    
    public function updateManUser($Result, $mankey, $mgtname, $jsonupdate_mgtops)
    {
        $jsonupdate_mgtops["mgtname"] = $mgtname;
        $jsonupdate_mgtops["mgtkey"] = $mankey;
        for ($Row=0; $Row<count($Result); $Row++)
        {
            $jsonupdate_mgtops["operationid"]["operationid".$Row]=$Result[$Row]["operationid"];
            $jsonupdate_mgtops["lun_landuseno"]["lun_landuseno".$Row]=$Result[$Row]["lun_landuseno"];
            $jsonupdate_mgtops["iaui_autoirr"]["iaui_autoirr".$Row]=$Result[$Row]["iaui_autoirr"];
            $jsonupdate_mgtops["iauf_autofert"]["iauf_autofert".$Row]=$Result[$Row]["iauf_autofert"];
            $jsonupdate_mgtops["iamf_automanualdepos"]["iamf_automanualdepos".$Row]=$Result[$Row]["iamf_automanualdepos"];
            $jsonupdate_mgtops["ispf_autosolman"]["ispf_autosolman".$Row]=$Result[$Row]["ispf_autosolman"];
            $jsonupdate_mgtops["ilqf_atliqman"]["ilqf_atliqman".$Row]=$Result[$Row]["ilqf_atliqman"];
            $jsonupdate_mgtops["iaul_atlime"]["iaul_atlime".$Row]=$Result[$Row]["iaul_atlime"];
            $jsonupdate_mgtops["jx1_year"]["jx1_year".$Row]=$Result[$Row]["jx1_year"];
            $jsonupdate_mgtops["jx2_month"]["jx2_month".$Row]=$Result[$Row]["jx2_month"];
            $jsonupdate_mgtops["jx3_day"]["jx3_day".$Row]=$Result[$Row]["jx3_day"];
            $jsonupdate_mgtops["jx4_tillid"]["jx4_tillid".$Row]=$Result[$Row]["jx4_tillid"];
            $jsonupdate_mgtops["jx5_tractid"]["jx5_tractid".$Row]=$Result[$Row]["jx5_tractid"];
            $jsonupdate_mgtops["jx6_cropid"]["jx6_cropid".$Row]=$Result[$Row]["jx6_cropid"];
            $jsonupdate_mgtops["jx7"]["jx7".$Row]=$Result[$Row]["jx7"];
            $jsonupdate_mgtops["opv1"]["opv1".$Row]=$Result[$Row]["opv1"];
            $jsonupdate_mgtops["opv2"]["opv2".$Row]=$Result[$Row]["opv2"];
            $jsonupdate_mgtops["opv3"]["opv3".$Row]=$Result[$Row]["opv3"];
            $jsonupdate_mgtops["opv4"]["opv4".$Row]=$Result[$Row]["opv4"];
            $jsonupdate_mgtops["opv5"]["opv5".$Row]=$Result[$Row]["opv5"];
            $jsonupdate_mgtops["opv6"]["opv6".$Row]=$Result[$Row]["opv6"];
            $jsonupdate_mgtops["opv7"]["opv7".$Row]=$Result[$Row]["opv7"];
            $jsonupdate_mgtops["opv8"]["opv8".$Row]=$Result[$Row]["opv8"];            
            $jsonupdate_mgtops["manningn"]["manningn".$Row]=$Result[$Row]["manningn"]; 
        }
        return $jsonupdate_mgtops;
    }
    
    public function UpdateJsonSite($ziplat, $ziplong, $runname, $site_elev)
    {

            $jsonupdate_site["model_setup"]["siteid"]= "1";
            $jsonupdate_site["model_setup"]["description_line1"]= $runname;
            $jsonupdate_site["model_setup"]["generation_date"]= date('Y/m/d H:i'); // Updating date;
            $jsonupdate_site["model_setup"]["nvcn"]= "4";
            $jsonupdate_site["model_setup"]["outflow_release_method_isao"]= "0";

            $jsonupdate_site["geographic"]["latitude_ylat"]= $ziplat;
            $jsonupdate_site["geographic"]["longitude_xlog"]= $ziplong;
            $jsonupdate_site["geographic"]["elevation_elev"]= $site_elev;

            $jsonupdate_site["runoff"]["peakrunoffrate_apm"]= "1.00";
            $jsonupdate_site["co2"]["co2conc_atmos_co2x"]= "330.00";
            $jsonupdate_site["nitrogen"]["no3n_irrigation_cqnx"]= "0.00";
            $jsonupdate_site["nitrogen"]["nitrogen_conc_rainfall_rfnx"]= "0.00";
            $jsonupdate_site["manure"]["manure_p_app_upr"]= "0.00";
            $jsonupdate_site["manure"]["manure_n_app_unr"]= "0.00";
            $jsonupdate_site["irrigation"]["auto_irrig_adj_fir0"]= "0.00";
            $jsonupdate_site["channel"]["basin_channel_length_bchl"]= "0.00";
            $jsonupdate_site["channel"]["basin_chalnel_slp_bchs"]= "0.00";


            return $jsonupdate_site;
    }

    public function UpdateJsonSub($ziplat, $ziplong, $runname, $subArea, $subSlpStp, $drainDepth, $subSlpLen, $weadatascflag)
    {

            $jsonupdate_sub["model_setup"]["subid_snum"]= "1";
            $jsonupdate_sub["model_setup"]["description_title"]= $runname;
            $jsonupdate_sub["model_setup"]["owner_id"]= "1";
            $jsonupdate_sub["model_setup"]["nvcn"]= "4";
            $jsonupdate_sub["model_setup"]["outflow_release_method_isao"]= "0.00";

            $jsonupdate_sub["geographic"]["wsa_ha"]= $subArea;
            $jsonupdate_sub["geographic"]["latitude_xct"]= $ziplat;
            $jsonupdate_sub["geographic"]["longitude_yct"]= $ziplong;
            $jsonupdate_sub["geographic"]["avg_upland_slplen_splg"]= $subSlpLen;
            $jsonupdate_sub["geographic"]["avg_upland_slp"]= $subSlpStp;
            $jsonupdate_sub["geographic"]["uplandmanningn_upn"] = "0.12";
            // Channel length is the distance along the channel from the outlet to 
            // the most distant point on the watershed. For areas less than 20ha, use zero
            // Otherwise, it can be estimated from length-width ratio of watershed.
            // Here, I assume it as slope length.
            $jsonupdate_sub["geographic"]["channellength_chl"]= $subSlpLen;
            $jsonupdate_sub["geographic"]["channelslope_chs"]= $subSlpStp;
            // For agricultural land, I will use excavated or dredged, and
            // not maintained. 
            $jsonupdate_sub["geographic"]["channelmanningn_chn"]= "0.075";
            $jsonupdate_sub["geographic"]["channel_depth_chd"]= "0.2";
            // Reach is between where channel starts or enters the subarea
            // and leaves the subarea. For extreme or field simulation here,
            // we use same value as chl.
            $jsonupdate_sub["geographic"]["reach_length_rchl"]= $subSlpLen;
            $jsonupdate_sub["geographic"]["reach_depth_rchd"]= "0.2";
            $jsonupdate_sub["geographic"]["reach_bottom_width_rcbw"]= "0.10";
            $jsonupdate_sub["geographic"]["reach_top_width_rctw"] = "0.50";
            $jsonupdate_sub["geographic"]["reach_slope_rchs"]= $subSlpStp;
            $jsonupdate_sub["geographic"]["reach_manningsn_rchn"]= "0.05";
            $jsonupdate_sub["geographic"]["reach_uslec_rchc"]= "0.005";
            $jsonupdate_sub["geographic"]["reach_uslek_rchk"] = "0.30";
            $jsonupdate_sub["geographic"]["reach_floodplain_rfpw"]= "0.00";
            $jsonupdate_sub["geographic"]["reach_floodplain_length_rfpl"]= "0.00";
            $jsonupdate_sub["geographic"]["rch_ksat_adj_factor_sat1"]= "1.00";

            // This will override that value specified in the ops file.
            // In other words, if I have different values for different tillage, the 
            // values will be modified to this value. If it is set to zero,
            // LUN will not be modified.
            $jsonupdate_sub["land_use_type"]["land_useid_luns"]= "0";
            $jsonupdate_sub["land_use_type"]["standing_crop_residue_stdo"]= "0.00";

            $jsonupdate_sub["soil"]["soilid"]= "1";

            $jsonupdate_sub["management"]["opeartionid_iops"]= "1";
            $jsonupdate_sub["management"]["min_days_automow_imw"]= "0.00";
            $jsonupdate_sub["management"]["min_days_autonitro_ifa"]= "0.00";
            $jsonupdate_sub["management"]["liming_code_lm"]= "1";
            $jsonupdate_sub["management"]["furrow_dike_code_ifd"]= "0.00";
            $jsonupdate_sub["management"]["fd_water_store_fdsf"]= "0.010";
            $jsonupdate_sub["management"]["autofert_lagoon_idf1"]= "0.00";
            $jsonupdate_sub["management"]["auto_manure_feedarea_idf2"]= "0.00";
            $jsonupdate_sub["management"]["auto_commercial_p_idf3"]= "0.00";
            $jsonupdate_sub["management"]["auto_commercial_n_idf4"]= "0.00";
            $jsonupdate_sub["management"]["auto_solid_manure_idf5"]= "0.00";
            $jsonupdate_sub["management"]["auto_commercial_k_idf6"]= "0.00";
            $jsonupdate_sub["management"]["nstress_trigger_auton_bft"]= "0.00";
            $jsonupdate_sub["management"]["auton_rate_fnp4"]= "0.00";
            $jsonupdate_sub["management"]["auton_manure_fnp5"]= "0.00";
            $jsonupdate_sub["management"]["max_annual_auton_fmx"]= "0.00";

            $jsonupdate_sub["drainage"]["drainage_depth_idr"]= $drainDepth;
            $jsonupdate_sub["drainage"]["drain_days_end_w_stress_drt"]= "2.00";

            $jsonupdate_sub["grazing"]["feeding_area_ii"]= "1";
            $jsonupdate_sub["grazing"]["manure_app_area_iapl"]= "0.00";
            $jsonupdate_sub["grazing"]["feedarea_pile_autosolidmanure_rate_fnp2"]= "0.00";
            $jsonupdate_sub["grazing"]["herds_eligible_forgrazing_ny1"]= "1";
            $jsonupdate_sub["grazing"]["herds_eligible_forgrazing_ny2"]= "0.00";
            $jsonupdate_sub["grazing"]["herds_eligible_forgrazing_ny3"]= "0.010";
            $jsonupdate_sub["grazing"]["herds_eligible_forgrazing_ny4"]= "0.00";
            $jsonupdate_sub["grazing"]["herds_eligible_forgrazing_ny5"]= "0.00";
            $jsonupdate_sub["grazing"]["herds_eligible_forgrazing_ny6"]= "0.00";
            $jsonupdate_sub["grazing"]["herds_eligible_forgrazing_ny7"]= "0.00";
            $jsonupdate_sub["grazing"]["herds_eligible_forgrazing_ny8"]= "0.00";
            $jsonupdate_sub["grazing"]["herds_eligible_forgrazing_ny9"]= "0.00";
            $jsonupdate_sub["grazing"]["herds_eligible_forgrazing_ny10"]= "0.00";
            $jsonupdate_sub["grazing"]["grazing_limit_herd_xtp1"]= "1";
            $jsonupdate_sub["grazing"]["grazing_limit_herd_xtp2"]= "0.00";
            $jsonupdate_sub["grazing"]["grazing_limit_herd_xtp3"]= "0.010";
            $jsonupdate_sub["grazing"]["grazing_limit_herd_xtp4"]= "0.00";
            $jsonupdate_sub["grazing"]["grazing_limit_herd_xtp5"]= "0.00";
            $jsonupdate_sub["grazing"]["grazing_limit_herd_xtp6"]= "0.00";
            $jsonupdate_sub["grazing"]["grazing_limit_herd_xtp7"]= "0.00";
            $jsonupdate_sub["grazing"]["grazing_limit_herd_xtp8"]= "0.00";
            $jsonupdate_sub["grazing"]["grazing_limit_herd_xtp9"]= "0.00";
            $jsonupdate_sub["grazing"]["grazing_limit_herd_xtp10"]= "0.00";

            if ($weadatascflag == 1)
            {
            $jsonupdate_sub["weather"]["daily_wea_stnid_iwth"]= "0";
            }
            else
            {
            $jsonupdate_sub["weather"]["daily_wea_stnid_iwth"]= "1";
            }
            $jsonupdate_sub["weather"]["begin_water_in_snow_sno"]= "0.00";

            $jsonupdate_sub["wind_erosion"]["azimuth_land_slope_amz"]= "0";
            $jsonupdate_sub["wind_erosion"]["field_widthkm"]= "0.00";
            $jsonupdate_sub["wind_erosion"]["field_lenthkm_fl"]= "0";
            $jsonupdate_sub["wind_erosion"]["angel_of_fieldlength_angl"]= "0.00";

            $jsonupdate_sub["water_erosion"]["usle_p_pec"]= "1.00";

            $jsonupdate_sub["flood_plain"]["flood_plain_frac_ffpq"]= "0.02";
            $jsonupdate_sub["flood_plain"]["fp_ksat_adj_factor_fps1"]= "1.00";

            $jsonupdate_sub["urban"]["urban_frac_urbf"]= "0.00";

            $jsonupdate_sub["reservoir"]["elev_emers_rsee"]= "0";
            $jsonupdate_sub["reservoir"]["res_area_emers_rsae"]= "0.00";
            $jsonupdate_sub["reservoir"]["runoff_emers_rsve"]= "0";
            $jsonupdate_sub["reservoir"]["elev_prins_rsep"]= "0.00";
            $jsonupdate_sub["reservoir"]["res_area_prins_rsap"]= "0";
            $jsonupdate_sub["reservoir"]["runoff_prins_rsvp"]= "0.00";
            $jsonupdate_sub["reservoir"]["ini_res_volume_rsv"]= "0";
            $jsonupdate_sub["reservoir"]["avg_prins_release_rate_rsrr"]= "0.00";
            $jsonupdate_sub["reservoir"]["ini_sed_res_rsys"]= "0.00";
            $jsonupdate_sub["reservoir"]["ini_nitro_res_rsyn"]= "0";
            $jsonupdate_sub["reservoir"]["hydro_condt_res_bottom_rshc"]= "0.00";
            $jsonupdate_sub["reservoir"]["time_sedconc_tonormal_rsdp"]= "0";
            $jsonupdate_sub["reservoir"]["bd_sed_res_rsbd"]= "0.00";

            $jsonupdate_sub["pond"]["frac_pond_pcof"]= "0";
            $jsonupdate_sub["pond"]["frac_lagoon_dalg"]= "0.00";
            $jsonupdate_sub["pond"]["lagoon_vol_ratio_vlgn"]= "0.00";
            $jsonupdate_sub["pond"]["wash_water_to_lagoon_coww"]= "0";
            $jsonupdate_sub["pond"]["time_reduce_lgstorage_nom_ddlg"]= "0.00";
            $jsonupdate_sub["pond"]["ratio_liquid_manure_to_lg_solq"]= "0";
            $jsonupdate_sub["pond"]["frac_safety_lg_design_sflg"]= "0.00";

            $jsonupdate_sub["buffer"]["frac_buffer_bcof"]= "0.00";
            $jsonupdate_sub["buffer"]["buffer_flow_len_bffl"]= "0.00";

            $jsonupdate_sub["irrigation"]["regidity_irrig_nirr"]= "0.00";
            $jsonupdate_sub["irrigation"]["irrigation_irr"]= "0";
            $jsonupdate_sub["irrigation"]["min_days_btw_autoirr_iri"]= "0.00";
            $jsonupdate_sub["irrigation"]["waterstress_triger_irr_bir"]= "0";
            $jsonupdate_sub["irrigation"]["irr_lost_runoff_efi"]= "0.00";
            $jsonupdate_sub["irrigation"]["max_annual_irri_vol_vimx"]= "0.00";
            $jsonupdate_sub["irrigation"]["min_single_irrvol_armn"]= "0";
            $jsonupdate_sub["irrigation"]["max_single_irrvol_armx"]= "0.00";
            $jsonupdate_sub["irrigation"]["factor_adj_autoirr_firg"]= "0";
            $jsonupdate_sub["irrigation"]["subareaid_irrwater_irrs"]= "0.00";

            $jsonupdate_sub["point_source"]["point_source_ipts"]= "0.00";

            return $jsonupdate_sub;
    }

    public function yearsDifference($endDate, $beginDate)
    {
       $date_parts1=explode("-", $beginDate);
       $date_parts2=explode("-", $endDate);

       return $date_parts2[0] - $date_parts1[0] + 1;
    }

    public function UpdateJsonCont($genweasimyr, $weadatascflag, $dlystartyr, $dlyendyr)
    {


            if ($weadatascflag == 1)
            {
            $jsonupdate_cont["model_setup"]["yearstorun_nbyr"]= $genweasimyr;
            $jsonupdate_cont["model_setup"]["begin_year_iyr"]= "1986";
            }
            else
            {
            $dlystartdate = strtotime("" . $dlystartyr . "-01-01");
            $dlyenddate = strtotime("" . $dlyendyr . "-12-31");
            $jsonupdate_cont["model_setup"]["yearstorun_nbyr"]= $this->yearsDifference($dlyendyr, $dlystartyr);
            $jsonupdate_cont["model_setup"]["begin_year_iyr"]= $dlystartyr;
            }

            $jsonupdate_cont["model_setup"]["begin_month_imo"]= "1";
            $jsonupdate_cont["model_setup"]["begin_day_ida"]= "1";
            $jsonupdate_cont["model_setup"]["output_freq_ipd"]= "3";
            $jsonupdate_cont["model_setup"]["data_dir_idir"]= "0";
            $jsonupdate_cont["model_setup"]["real_time_nstp"]= "0";
            $jsonupdate_cont["model_setup"]["output_subareanum_isap"]= "0";
            $jsonupdate_cont["model_setup"]["leap_year_lpyr"]= "0";

            $jsonupdate_cont["geography"]["latitude_source_iazm"]= "0";
            $jsonupdate_cont["geography"]["average_upland_slope_chso"]= "0.50";
            $jsonupdate_cont["geography"]["channel_bottom_woverd_bwd"]= "5.00";
            $jsonupdate_cont["geography"]["max_groundwater_storage_gwso"]= "50.00";
            $jsonupdate_cont["geography"]["groundwater_resident_rfto"]= "0.00";
            $jsonupdate_cont["geography"]["fraction_ponds_pco0"] = "0.00";


            $jsonupdate_cont["flood_routing"]["flood_routing_ihy"]= "0";
            $jsonupdate_cont["flood_routing"]["floodplain_over_channel_fcw"]= "10.00";
            $jsonupdate_cont["flood_routing"]["floodplain_ksat_fpsc"]= "0.10";
            $jsonupdate_cont["flood_routing"]["routing_threshold_vsc_qth"]= "5.00";
            $jsonupdate_cont["flood_routing"]["interval_floodrouting_dthy"]= "0.00";
            $jsonupdate_cont["flood_routing"]["vsc_threshold_stnd"]= "5.00";

            if ($weadatascflag == 1)
            {
            $jsonupdate_cont["weather"]["weather_in_var_ngn"]= "0";
            }
            else
            {
            $jsonupdate_cont["weather"]["weather_in_var_ngn"]= "2";
            }

            $jsonupdate_cont["weather"]["random_seeds_ign"]= "0";
            $jsonupdate_cont["weather"]["date_weather_duplicate_igsd"]= "0";
            $jsonupdate_cont["weather"]["pet_method_iet"]= "0";
            $jsonupdate_cont["weather"]["number_generator_seeds_igmx"]= "0";
            $jsonupdate_cont["weather"]["yrs_max_mon_rainfall_ywi"]= "10.00";
            $jsonupdate_cont["weather"]["wetdry_prob_bta"]= "0.75";
            $jsonupdate_cont["weather"]["param_exp_rainfall_dist_expk"]= "1.30";
            $jsonupdate_cont["weather"]["coef_rainfalldiretow_bxct"]= "0.00";
            $jsonupdate_cont["weather"]["coef_rainfalldirston_byct"]= "0.00";

            $jsonupdate_cont["runoff_sim"]["stochastic_cn_code_iscn"]= "0";
            $jsonupdate_cont["runoff_sim"]["peak_rate_method_ityp"]= "-1";
            $jsonupdate_cont["runoff_sim"]["non_varying_cn_nvcn"]= "4";
            $jsonupdate_cont["runoff_sim"]["runoff_method_infl"]= "0";
            $jsonupdate_cont["runoff_sim"]["field_capacity_wilting_isw"]= "0";
            $jsonupdate_cont["runoff_sim"]["atecedent_period_iwtb"]= "0";
            $jsonupdate_cont["runoff_sim"]["channel_capacity_flow_qg"]= "25.00";
            $jsonupdate_cont["runoff_sim"]["exp_watershed_area_flowrate_qcf"]= "0.50";
            $jsonupdate_cont["runoff_sim"]["returnflow_ratio_rfpo"]= "0.50";
            $jsonupdate_cont["runoff_sim"]["ksat_adj_sato"]= "1.00";

            $jsonupdate_cont["water_erosion"]["static_soil_code_ista"]= "0";
            $jsonupdate_cont["water_erosion"]["slope_length_steep_islf"]= "0";
            $jsonupdate_cont["water_erosion"]["water_erosion_equation_drv"]= "6.00";
            $jsonupdate_cont["water_erosion"]["usle_c_channel_rcc0"]= "0.70";
            $jsonupdate_cont["water_erosion"]["msi_input_1"]= "0.56";
            $jsonupdate_cont["water_erosion"]["msi_input_2"]= "0.56";
            $jsonupdate_cont["water_erosion"]["msi_input_3"]= "0.12";
            $jsonupdate_cont["water_erosion"]["msi_input_4"]= "0.12";

            $jsonupdate_cont["wind_erosion"]["field_length_fl"]= "2.00";
            $jsonupdate_cont["wind_erosion"]["field_width_fw"]= "1.00";
            $jsonupdate_cont["wind_erosion"]["field_length_angle_ang"]= "0.00";
            $jsonupdate_cont["wind_erosion"]["windspeed_distribution_uxp"]= "0.30";
            $jsonupdate_cont["wind_erosion"]["soil_partical_diameter_diam"]= "500.00";
            $jsonupdate_cont["wind_erosion"]["wind_erosion_adj_acw"]= "1.00";

            $jsonupdate_cont["management"]["automatic_hu_schedule_ihus"]= "0";
            $jsonupdate_cont["management"]["manure_application_mnul"]= "0";
            $jsonupdate_cont["management"]["lagoon_pumping_lpd"]= "0";
            $jsonupdate_cont["management"]["solid_manure_mscp"]= "0";
            $jsonupdate_cont["management"]["minimum_interval_automow_imw"]= "0";
            $jsonupdate_cont["management"]["auto_p_ipat"]= "0";
            $jsonupdate_cont["management"]["grazing_mode_ihrd"]= "0";
            $jsonupdate_cont["management"]["pest_damage_scaling_pstx"]= "1.00";
            $jsonupdate_cont["management"]["grazing_limit_gzl0"]= "0.00";
            $jsonupdate_cont["management"]["cultivation_start_year_rtn0"]= "0.00";

            $jsonupdate_cont["nutrient_loss"]["pesticide_mass_conc_masp"]= "0";
            $jsonupdate_cont["nutrient_loss"]["enrichment_ratio_iert"]= "1";
            $jsonupdate_cont["nutrient_loss"]["soluble_p_estimate_lbp"]= "1";
            $jsonupdate_cont["nutrient_loss"]["n_p_uptake_curve_nupc"]= "1";
            $jsonupdate_cont["nutrient_loss"]["denitrification_idnt"]= "1";
            $jsonupdate_cont["nutrient_loss"]["avg_conc_n_rainfall_rfn"]= "0.80";
            $jsonupdate_cont["nutrient_loss"]["no3n_conc_irrig_cqn"]= "0.00";
            $jsonupdate_cont["nutrient_loss"]["salt_conc_irrig_cslt"]= "1.58";



            $jsonupdate_cont["air_quality"]["air_quality_code_naq"]= "0";
            $jsonupdate_cont["air_quality"]["co2_ico2"]= "0";
            $jsonupdate_cont["air_quality"]["o2_function_iox"]= "0";
            $jsonupdate_cont["air_quality"]["co2_conc_atom_co2"]= "330.00";

            return $jsonupdate_cont;
    }

    public function UpdateJsonOther($ziplat, $ziplong, $runname,
                                $solnm,
                                $soiltest_n,
                                $soiltest_p,
                                $mgtname,
                                $draindepth,
                                $subarea,
                                $sloplen)
    {

            $jsonupdate_cont["other"]["subdaily_rfdt_name"]= "AS1_30Min.HLY";
            $jsonupdate_cont["other"]["latitude"]= $ziplat;
            $jsonupdate_cont["other"]["longitude"]= $ziplong;
            $jsonupdate_cont["other"]["runname"]= $runname;
            $jsonupdate_cont["other"]["subAreaProj"]= $subarea;
            $jsonupdate_cont["other"]["SlopeLenProj"]= $sloplen;
            $jsonupdate_cont["other"]["ManNameProj"]= $mgtname;            
            $jsonupdate_cont["other"]["SoilNameProj"]= $solnm;
            $jsonupdate_cont["other"]["SoilTNProj"]= $soiltest_n;
            $jsonupdate_cont["other"]["SoilTPProj"]= $soiltest_p;
            $jsonupdate_cont["other"]["TileDepthProj"]= $draindepth;            


            return $jsonupdate_cont;
    }

    public function WriteWP1WNDfile($Connection, 
            $stnname, 
            $stateabb, 
            $fn_wp1, 
            $fn_wnd, 
            $fn_wp1lst, 
            $fn_wndlst)
    {
            $stnst = substr($stnname, 0, 2);
            $statelower = strtolower($stnst);
            $stnname_lowerstab = trim($statelower).trim(substr($stnname, 2));
            $sqlstmt = "SELECT * from " . $statelower . "_monstat4wp1 where stationname ='" . $stnname_lowerstab . "'"; 

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

                    $fid_wp1 = fopen($fn_wp1, "w");
                    fprintf($fid_wp1, "%14s%13s%s\n", trim(pg_result($Result,0,"stationname")), 
                                                                            trim($stnst), 
                                                                            pg_result($Result,0,"stationlocation")
                                                                            );
                    fprintf($fid_wp1, "    LAT = %7.2f   LON =   %7.2f    ELEV = %7.2f     \n",
                                                                            trim(pg_result($Result,0,"latitude")), 
                                                                            trim(pg_result($Result,0,"longitude")), 
                                                                            trim(pg_result($Result,0,"elevation"))
                                                                            );						

                    fprintf($fid_wp1, "%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f\n", 
                                                                            trim(pg_result($Result,0,"obmxmon1")), 
                                                                            trim(pg_result($Result,0,"obmxmon2")), 
                                                                            trim(pg_result($Result,0,"obmxmon3")), 
                                                                            trim(pg_result($Result,0,"obmxmon4")), 
                                                                            trim(pg_result($Result,0,"obmxmon5")), 
                                                                            trim(pg_result($Result,0,"obmxmon6")), 
                                                                            trim(pg_result($Result,0,"obmxmon7")), 
                                                                            trim(pg_result($Result,0,"obmxmon8")), 
                                                                            trim(pg_result($Result,0,"obmxmon9")), 
                                                                            trim(pg_result($Result,0,"obmxmon10")), 
                                                                            trim(pg_result($Result,0,"obmxmon11")), 
                                                                            trim(pg_result($Result,0,"obmxmon12"))
                                                                            );									

                    fprintf($fid_wp1, "%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f\n", 
                                                                            trim(pg_result($Result,0,"obmnmon1")), 
                                                                            trim(pg_result($Result,0,"obmnmon2")), 
                                                                            trim(pg_result($Result,0,"obmnmon3")), 
                                                                            trim(pg_result($Result,0,"obmnmon4")), 
                                                                            trim(pg_result($Result,0,"obmnmon5")), 
                                                                            trim(pg_result($Result,0,"obmnmon6")), 
                                                                            trim(pg_result($Result,0,"obmnmon7")), 
                                                                            trim(pg_result($Result,0,"obmnmon8")), 
                                                                            trim(pg_result($Result,0,"obmnmon9")), 
                                                                            trim(pg_result($Result,0,"obmnmon10")), 
                                                                            trim(pg_result($Result,0,"obmnmon11")), 
                                                                            trim(pg_result($Result,0,"obmnmon12"))
                                                                            );										

                    fprintf($fid_wp1, "%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f\n", 
                                                                            trim(pg_result($Result,0,"stdmxmon1")), 
                                                                            trim(pg_result($Result,0,"stdmxmon2")), 
                                                                            trim(pg_result($Result,0,"stdmxmon3")), 
                                                                            trim(pg_result($Result,0,"stdmxmon4")), 
                                                                            trim(pg_result($Result,0,"stdmxmon5")), 
                                                                            trim(pg_result($Result,0,"stdmxmon6")), 
                                                                            trim(pg_result($Result,0,"stdmxmon7")), 
                                                                            trim(pg_result($Result,0,"stdmxmon8")), 
                                                                            trim(pg_result($Result,0,"stdmxmon9")), 
                                                                            trim(pg_result($Result,0,"stdmxmon10")), 
                                                                            trim(pg_result($Result,0,"stdmxmon11")), 
                                                                            trim(pg_result($Result,0,"stdmxmon12"))
                                                                            );										

                    fprintf($fid_wp1, "%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f\n", 
                                                                            trim(pg_result($Result,0,"stdmnmon1")), 
                                                                            trim(pg_result($Result,0,"stdmnmon2")), 
                                                                            trim(pg_result($Result,0,"stdmnmon3")), 
                                                                            trim(pg_result($Result,0,"stdmnmon4")), 
                                                                            trim(pg_result($Result,0,"stdmnmon5")), 
                                                                            trim(pg_result($Result,0,"stdmnmon6")), 
                                                                            trim(pg_result($Result,0,"stdmnmon7")), 
                                                                            trim(pg_result($Result,0,"stdmnmon8")), 
                                                                            trim(pg_result($Result,0,"stdmnmon9")), 
                                                                            trim(pg_result($Result,0,"stdmnmon10")), 
                                                                            trim(pg_result($Result,0,"stdmnmon11")), 
                                                                            trim(pg_result($Result,0,"stdmnmon12"))
                                                                            );												

                    fprintf($fid_wp1, "%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f\n", 
                                                                            trim(pg_result($Result,0,"rmomon1")), 
                                                                            trim(pg_result($Result,0,"rmomon2")), 
                                                                            trim(pg_result($Result,0,"rmomon3")), 
                                                                            trim(pg_result($Result,0,"rmomon4")), 
                                                                            trim(pg_result($Result,0,"rmomon5")), 
                                                                            trim(pg_result($Result,0,"rmomon6")), 
                                                                            trim(pg_result($Result,0,"rmomon7")), 
                                                                            trim(pg_result($Result,0,"rmomon8")), 
                                                                            trim(pg_result($Result,0,"rmomon9")), 
                                                                            trim(pg_result($Result,0,"rmomon10")), 
                                                                            trim(pg_result($Result,0,"rmomon11")), 
                                                                            trim(pg_result($Result,0,"rmomon12"))
                                                                            );												

                    fprintf($fid_wp1, "%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f\n", 
                                                                            trim(pg_result($Result,0,"rst2mon1")), 
                                                                            trim(pg_result($Result,0,"rst2mon2")), 
                                                                            trim(pg_result($Result,0,"rst2mon3")), 
                                                                            trim(pg_result($Result,0,"rst2mon4")), 
                                                                            trim(pg_result($Result,0,"rst2mon5")), 
                                                                            trim(pg_result($Result,0,"rst2mon6")), 
                                                                            trim(pg_result($Result,0,"rst2mon7")), 
                                                                            trim(pg_result($Result,0,"rst2mon8")), 
                                                                            trim(pg_result($Result,0,"rst2mon9")), 
                                                                            trim(pg_result($Result,0,"rst2mon10")), 
                                                                            trim(pg_result($Result,0,"rst2mon11")), 
                                                                            trim(pg_result($Result,0,"rst2mon12"))
                                                                            );												

                    fprintf($fid_wp1, "%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f\n", 
                                                                            trim(pg_result($Result,0,"rst3mon1")), 
                                                                            trim(pg_result($Result,0,"rst3mon2")), 
                                                                            trim(pg_result($Result,0,"rst3mon3")), 
                                                                            trim(pg_result($Result,0,"rst3mon4")), 
                                                                            trim(pg_result($Result,0,"rst3mon5")), 
                                                                            trim(pg_result($Result,0,"rst3mon6")), 
                                                                            trim(pg_result($Result,0,"rst3mon7")), 
                                                                            trim(pg_result($Result,0,"rst3mon8")), 
                                                                            trim(pg_result($Result,0,"rst3mon9")), 
                                                                            trim(pg_result($Result,0,"rst3mon10")), 
                                                                            trim(pg_result($Result,0,"rst3mon11")), 
                                                                            trim(pg_result($Result,0,"rst3mon12"))
                                                                            );												


                    fprintf($fid_wp1, "%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f\n", 
                                                                            trim(pg_result($Result,0,"prw1mon1")), 
                                                                            trim(pg_result($Result,0,"prw1mon2")), 
                                                                            trim(pg_result($Result,0,"prw1mon3")), 
                                                                            trim(pg_result($Result,0,"prw1mon4")), 
                                                                            trim(pg_result($Result,0,"prw1mon5")), 
                                                                            trim(pg_result($Result,0,"prw1mon6")), 
                                                                            trim(pg_result($Result,0,"prw1mon7")), 
                                                                            trim(pg_result($Result,0,"prw1mon8")), 
                                                                            trim(pg_result($Result,0,"prw1mon9")), 
                                                                            trim(pg_result($Result,0,"prw1mon10")), 
                                                                            trim(pg_result($Result,0,"prw1mon11")), 
                                                                            trim(pg_result($Result,0,"prw1mon12"))
                                                                            );												

                    fprintf($fid_wp1, "%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f\n", 
                                                                            trim(pg_result($Result,0,"prw2mon1")), 
                                                                            trim(pg_result($Result,0,"prw2mon2")), 
                                                                            trim(pg_result($Result,0,"prw2mon3")), 
                                                                            trim(pg_result($Result,0,"prw2mon4")), 
                                                                            trim(pg_result($Result,0,"prw2mon5")), 
                                                                            trim(pg_result($Result,0,"prw2mon6")), 
                                                                            trim(pg_result($Result,0,"prw2mon7")), 
                                                                            trim(pg_result($Result,0,"prw2mon8")), 
                                                                            trim(pg_result($Result,0,"prw2mon9")), 
                                                                            trim(pg_result($Result,0,"prw2mon10")), 
                                                                            trim(pg_result($Result,0,"prw2mon11")), 
                                                                            trim(pg_result($Result,0,"prw2mon12"))
                                                                            );


                    fprintf($fid_wp1, "%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f\n", 
                                                                            trim(pg_result($Result,0,"uavmmon1")), 
                                                                            trim(pg_result($Result,0,"uavmmon2")), 
                                                                            trim(pg_result($Result,0,"uavmmon3")), 
                                                                            trim(pg_result($Result,0,"uavmmon4")), 
                                                                            trim(pg_result($Result,0,"uavmmon5")), 
                                                                            trim(pg_result($Result,0,"uavmmon6")), 
                                                                            trim(pg_result($Result,0,"uavmmon7")), 
                                                                            trim(pg_result($Result,0,"uavmmon8")), 
                                                                            trim(pg_result($Result,0,"uavmmon9")), 
                                                                            trim(pg_result($Result,0,"uavmmon10")), 
                                                                            trim(pg_result($Result,0,"uavmmon11")), 
                                                                            trim(pg_result($Result,0,"uavmmon12"))
                                                                            );																			

                    fprintf($fid_wp1, "%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f\n", 
                                                                            trim(pg_result($Result,0,"wimon1")), 
                                                                            trim(pg_result($Result,0,"wimon2")), 
                                                                            trim(pg_result($Result,0,"wimon3")), 
                                                                            trim(pg_result($Result,0,"wimon4")), 
                                                                            trim(pg_result($Result,0,"wimon5")), 
                                                                            trim(pg_result($Result,0,"wimon6")), 
                                                                            trim(pg_result($Result,0,"wimon7")), 
                                                                            trim(pg_result($Result,0,"wimon8")), 
                                                                            trim(pg_result($Result,0,"wimon9")), 
                                                                            trim(pg_result($Result,0,"wimon10")), 
                                                                            trim(pg_result($Result,0,"wimon11")), 
                                                                            trim(pg_result($Result,0,"wimon12"))
                                                                            );												

                    fprintf($fid_wp1, "%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f\n", 
                                                                            trim(pg_result($Result,0,"obslmon1")), 
                                                                            trim(pg_result($Result,0,"obslmon2")), 
                                                                            trim(pg_result($Result,0,"obslmon3")), 
                                                                            trim(pg_result($Result,0,"obslmon4")), 
                                                                            trim(pg_result($Result,0,"obslmon5")), 
                                                                            trim(pg_result($Result,0,"obslmon6")), 
                                                                            trim(pg_result($Result,0,"obslmon7")), 
                                                                            trim(pg_result($Result,0,"obslmon8")), 
                                                                            trim(pg_result($Result,0,"obslmon9")), 
                                                                            trim(pg_result($Result,0,"obslmon10")), 
                                                                            trim(pg_result($Result,0,"obslmon11")), 
                                                                            trim(pg_result($Result,0,"obslmon12"))
                                                                            );												

                    fprintf($fid_wp1, "%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f%6.1f\n", 
                                                                            trim(pg_result($Result,0,"rhmon1")), 
                                                                            trim(pg_result($Result,0,"rhmon2")), 
                                                                            trim(pg_result($Result,0,"rhmon3")), 
                                                                            trim(pg_result($Result,0,"rhmon4")), 
                                                                            trim(pg_result($Result,0,"rhmon5")), 
                                                                            trim(pg_result($Result,0,"rhmon6")), 
                                                                            trim(pg_result($Result,0,"rhmon7")), 
                                                                            trim(pg_result($Result,0,"rhmon8")), 
                                                                            trim(pg_result($Result,0,"rhmon9")), 
                                                                            trim(pg_result($Result,0,"rhmon10")), 
                                                                            trim(pg_result($Result,0,"rhmon11")), 
                                                                            trim(pg_result($Result,0,"rhmon12"))
                                                                            );										

                    fprintf($fid_wp1, "%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f\n", 
                                                                            trim(pg_result($Result,0,"uav0mon1")), 
                                                                            trim(pg_result($Result,0,"uav0mon2")), 
                                                                            trim(pg_result($Result,0,"uav0mon3")), 
                                                                            trim(pg_result($Result,0,"uav0mon4")), 
                                                                            trim(pg_result($Result,0,"uav0mon5")), 
                                                                            trim(pg_result($Result,0,"uav0mon6")), 
                                                                            trim(pg_result($Result,0,"uav0mon7")), 
                                                                            trim(pg_result($Result,0,"uav0mon8")), 
                                                                            trim(pg_result($Result,0,"uav0mon9")), 
                                                                            trim(pg_result($Result,0,"uav0mon10")), 
                                                                            trim(pg_result($Result,0,"uav0mon11")), 
                                                                            trim(pg_result($Result,0,"uav0mon12"))
                                                                            );										

                    fclose($fid_wp1);


                    $fid_wnd = fopen($fn_wnd, "w");
                    fprintf($fid_wnd, "%14s%13s%s\n", trim(pg_result($Result,0,"stationname")), 
                                                                            trim($stnst), 
                                                                            pg_result($Result,0,"stationlocation")
                                                                            );
                    fprintf($fid_wnd, "    LAT = %7.2f   LON =   %7.2f    ELEV = %7.2f     \n",
                                                                            trim(pg_result($Result,0,"latitude")), 
                                                                            trim(pg_result($Result,0,"longitude")), 
                                                                            trim(pg_result($Result,0,"elevation"))
                                                                            );						

                    fprintf($fid_wnd, "%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f\n", 
                                                                            trim(pg_result($Result,0,"uav0mon1")), 
                                                                            trim(pg_result($Result,0,"uav0mon2")), 
                                                                            trim(pg_result($Result,0,"uav0mon3")), 
                                                                            trim(pg_result($Result,0,"uav0mon4")), 
                                                                            trim(pg_result($Result,0,"uav0mon5")), 
                                                                            trim(pg_result($Result,0,"uav0mon6")), 
                                                                            trim(pg_result($Result,0,"uav0mon7")), 
                                                                            trim(pg_result($Result,0,"uav0mon8")), 
                                                                            trim(pg_result($Result,0,"uav0mon9")), 
                                                                            trim(pg_result($Result,0,"uav0mon10")), 
                                                                            trim(pg_result($Result,0,"uav0mon11")), 
                                                                            trim(pg_result($Result,0,"uav0mon12"))
                                                                            );			

                    fprintf($fid_wnd, "%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f\n", 
                                                                            trim(pg_result($Result,0,"d1northmon1")), 
                                                                            trim(pg_result($Result,0,"d1northmon2")), 
                                                                            trim(pg_result($Result,0,"d1northmon3")), 
                                                                            trim(pg_result($Result,0,"d1northmon4")), 
                                                                            trim(pg_result($Result,0,"d1northmon5")), 
                                                                            trim(pg_result($Result,0,"d1northmon6")), 
                                                                            trim(pg_result($Result,0,"d1northmon7")), 
                                                                            trim(pg_result($Result,0,"d1northmon8")), 
                                                                            trim(pg_result($Result,0,"d1northmon9")), 
                                                                            trim(pg_result($Result,0,"d1northmon10")), 
                                                                            trim(pg_result($Result,0,"d1northmon11")), 
                                                                            trim(pg_result($Result,0,"d1northmon12"))
                                                                            );			

                    fprintf($fid_wnd, "%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f\n", 
                                                                            trim(pg_result($Result,0,"d2nnemon1")), 
                                                                            trim(pg_result($Result,0,"d2nnemon2")), 
                                                                            trim(pg_result($Result,0,"d2nnemon3")), 
                                                                            trim(pg_result($Result,0,"d2nnemon4")), 
                                                                            trim(pg_result($Result,0,"d2nnemon5")), 
                                                                            trim(pg_result($Result,0,"d2nnemon6")), 
                                                                            trim(pg_result($Result,0,"d2nnemon7")), 
                                                                            trim(pg_result($Result,0,"d2nnemon8")), 
                                                                            trim(pg_result($Result,0,"d2nnemon9")), 
                                                                            trim(pg_result($Result,0,"d2nnemon10")), 
                                                                            trim(pg_result($Result,0,"d2nnemon11")), 
                                                                            trim(pg_result($Result,0,"d2nnemon12"))
                                                                            );			

                    fprintf($fid_wnd, "%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f\n", 
                                                                            trim(pg_result($Result,0,"d3nemon1")), 
                                                                            trim(pg_result($Result,0,"d3nemon2")), 
                                                                            trim(pg_result($Result,0,"d3nemon3")), 
                                                                            trim(pg_result($Result,0,"d3nemon4")), 
                                                                            trim(pg_result($Result,0,"d3nemon5")), 
                                                                            trim(pg_result($Result,0,"d3nemon6")), 
                                                                            trim(pg_result($Result,0,"d3nemon7")), 
                                                                            trim(pg_result($Result,0,"d3nemon8")), 
                                                                            trim(pg_result($Result,0,"d3nemon9")), 
                                                                            trim(pg_result($Result,0,"d3nemon10")), 
                                                                            trim(pg_result($Result,0,"d3nemon11")), 
                                                                            trim(pg_result($Result,0,"d3nemon12"))
                                                                            );			
                    fprintf($fid_wnd, "%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f\n", 
                                                                            trim(pg_result($Result,0,"d4enemon1")), 
                                                                            trim(pg_result($Result,0,"d4enemon2")), 
                                                                            trim(pg_result($Result,0,"d4enemon3")), 
                                                                            trim(pg_result($Result,0,"d4enemon4")), 
                                                                            trim(pg_result($Result,0,"d4enemon5")), 
                                                                            trim(pg_result($Result,0,"d4enemon6")), 
                                                                            trim(pg_result($Result,0,"d4enemon7")), 
                                                                            trim(pg_result($Result,0,"d4enemon8")), 
                                                                            trim(pg_result($Result,0,"d4enemon9")), 
                                                                            trim(pg_result($Result,0,"d4enemon10")), 
                                                                            trim(pg_result($Result,0,"d4enemon11")), 
                                                                            trim(pg_result($Result,0,"d4enemon12"))
                                                                            );			
                    fprintf($fid_wnd, "%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f\n", 
                                                                            trim(pg_result($Result,0,"d5eastmon1")), 
                                                                            trim(pg_result($Result,0,"d5eastmon2")), 
                                                                            trim(pg_result($Result,0,"d5eastmon3")), 
                                                                            trim(pg_result($Result,0,"d5eastmon4")), 
                                                                            trim(pg_result($Result,0,"d5eastmon5")), 
                                                                            trim(pg_result($Result,0,"d5eastmon6")), 
                                                                            trim(pg_result($Result,0,"d5eastmon7")), 
                                                                            trim(pg_result($Result,0,"d5eastmon8")), 
                                                                            trim(pg_result($Result,0,"d5eastmon9")), 
                                                                            trim(pg_result($Result,0,"d5eastmon10")), 
                                                                            trim(pg_result($Result,0,"d5eastmon11")), 
                                                                            trim(pg_result($Result,0,"d5eastmon12"))
                                                                            );			
                    fprintf($fid_wnd, "%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f\n", 
                                                                            trim(pg_result($Result,0,"d6esemon1")), 
                                                                            trim(pg_result($Result,0,"d6esemon2")), 
                                                                            trim(pg_result($Result,0,"d6esemon3")), 
                                                                            trim(pg_result($Result,0,"d6esemon4")), 
                                                                            trim(pg_result($Result,0,"d6esemon5")), 
                                                                            trim(pg_result($Result,0,"d6esemon6")), 
                                                                            trim(pg_result($Result,0,"d6esemon7")), 
                                                                            trim(pg_result($Result,0,"d6esemon8")), 
                                                                            trim(pg_result($Result,0,"d6esemon9")), 
                                                                            trim(pg_result($Result,0,"d6esemon10")), 
                                                                            trim(pg_result($Result,0,"d6esemon11")), 
                                                                            trim(pg_result($Result,0,"d6esemon12"))
                                                                            );			
                    fprintf($fid_wnd, "%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f\n", 
                                                                            trim(pg_result($Result,0,"d7semon1")), 
                                                                            trim(pg_result($Result,0,"d7semon2")), 
                                                                            trim(pg_result($Result,0,"d7semon3")), 
                                                                            trim(pg_result($Result,0,"d7semon4")), 
                                                                            trim(pg_result($Result,0,"d7semon5")), 
                                                                            trim(pg_result($Result,0,"d7semon6")), 
                                                                            trim(pg_result($Result,0,"d7semon7")), 
                                                                            trim(pg_result($Result,0,"d7semon8")), 
                                                                            trim(pg_result($Result,0,"d7semon9")), 
                                                                            trim(pg_result($Result,0,"d7semon10")), 
                                                                            trim(pg_result($Result,0,"d7semon11")), 
                                                                            trim(pg_result($Result,0,"d7semon12"))
                                                                            );			
                    fprintf($fid_wnd, "%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f\n", 
                                                                            trim(pg_result($Result,0,"d8ssemon1")), 
                                                                            trim(pg_result($Result,0,"d8ssemon2")), 
                                                                            trim(pg_result($Result,0,"d8ssemon3")), 
                                                                            trim(pg_result($Result,0,"d8ssemon4")), 
                                                                            trim(pg_result($Result,0,"d8ssemon5")), 
                                                                            trim(pg_result($Result,0,"d8ssemon6")), 
                                                                            trim(pg_result($Result,0,"d8ssemon7")), 
                                                                            trim(pg_result($Result,0,"d8ssemon8")), 
                                                                            trim(pg_result($Result,0,"d8ssemon9")), 
                                                                            trim(pg_result($Result,0,"d8ssemon10")), 
                                                                            trim(pg_result($Result,0,"d8ssemon11")), 
                                                                            trim(pg_result($Result,0,"d8ssemon12"))
                                                                            );			
                    fprintf($fid_wnd, "%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f\n", 
                                                                            trim(pg_result($Result,0,"d9southmon1")), 
                                                                            trim(pg_result($Result,0,"d9southmon2")), 
                                                                            trim(pg_result($Result,0,"d9southmon3")), 
                                                                            trim(pg_result($Result,0,"d9southmon4")), 
                                                                            trim(pg_result($Result,0,"d9southmon5")), 
                                                                            trim(pg_result($Result,0,"d9southmon6")), 
                                                                            trim(pg_result($Result,0,"d9southmon7")), 
                                                                            trim(pg_result($Result,0,"d9southmon8")), 
                                                                            trim(pg_result($Result,0,"d9southmon9")), 
                                                                            trim(pg_result($Result,0,"d9southmon10")), 
                                                                            trim(pg_result($Result,0,"d9southmon11")), 
                                                                            trim(pg_result($Result,0,"d9southmon12"))
                                                                            );			
                    fprintf($fid_wnd, "%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f\n", 
                                                                            trim(pg_result($Result,0,"d10sswmon1")), 
                                                                            trim(pg_result($Result,0,"d10sswmon2")), 
                                                                            trim(pg_result($Result,0,"d10sswmon3")), 
                                                                            trim(pg_result($Result,0,"d10sswmon4")), 
                                                                            trim(pg_result($Result,0,"d10sswmon5")), 
                                                                            trim(pg_result($Result,0,"d10sswmon6")), 
                                                                            trim(pg_result($Result,0,"d10sswmon7")), 
                                                                            trim(pg_result($Result,0,"d10sswmon8")), 
                                                                            trim(pg_result($Result,0,"d10sswmon9")), 
                                                                            trim(pg_result($Result,0,"d10sswmon10")), 
                                                                            trim(pg_result($Result,0,"d10sswmon11")), 
                                                                            trim(pg_result($Result,0,"d10sswmon12"))
                                                                            );			
                    fprintf($fid_wnd, "%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f\n", 
                                                                            trim(pg_result($Result,0,"d11swmon1")), 
                                                                            trim(pg_result($Result,0,"d11swmon2")), 
                                                                            trim(pg_result($Result,0,"d11swmon3")), 
                                                                            trim(pg_result($Result,0,"d11swmon4")), 
                                                                            trim(pg_result($Result,0,"d11swmon5")), 
                                                                            trim(pg_result($Result,0,"d11swmon6")), 
                                                                            trim(pg_result($Result,0,"d11swmon7")), 
                                                                            trim(pg_result($Result,0,"d11swmon8")), 
                                                                            trim(pg_result($Result,0,"d11swmon9")), 
                                                                            trim(pg_result($Result,0,"d11swmon10")), 
                                                                            trim(pg_result($Result,0,"d11swmon11")), 
                                                                            trim(pg_result($Result,0,"d11swmon12"))
                                                                            );			
                    fprintf($fid_wnd, "%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f\n", 
                                                                            trim(pg_result($Result,0,"d12wswmon1")), 
                                                                            trim(pg_result($Result,0,"d12wswmon2")), 
                                                                            trim(pg_result($Result,0,"d12wswmon3")), 
                                                                            trim(pg_result($Result,0,"d12wswmon4")), 
                                                                            trim(pg_result($Result,0,"d12wswmon5")), 
                                                                            trim(pg_result($Result,0,"d12wswmon6")), 
                                                                            trim(pg_result($Result,0,"d12wswmon7")), 
                                                                            trim(pg_result($Result,0,"d12wswmon8")), 
                                                                            trim(pg_result($Result,0,"d12wswmon9")), 
                                                                            trim(pg_result($Result,0,"d12wswmon10")), 
                                                                            trim(pg_result($Result,0,"d12wswmon11")), 
                                                                            trim(pg_result($Result,0,"d12wswmon12"))
                                                                            );			
                    fprintf($fid_wnd, "%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f\n", 
                                                                            trim(pg_result($Result,0,"d13westmon1")), 
                                                                            trim(pg_result($Result,0,"d13westmon2")), 
                                                                            trim(pg_result($Result,0,"d13westmon3")), 
                                                                            trim(pg_result($Result,0,"d13westmon4")), 
                                                                            trim(pg_result($Result,0,"d13westmon5")), 
                                                                            trim(pg_result($Result,0,"d13westmon6")), 
                                                                            trim(pg_result($Result,0,"d13westmon7")), 
                                                                            trim(pg_result($Result,0,"d13westmon8")), 
                                                                            trim(pg_result($Result,0,"d13westmon9")), 
                                                                            trim(pg_result($Result,0,"d13westmon10")), 
                                                                            trim(pg_result($Result,0,"d13westmon11")), 
                                                                            trim(pg_result($Result,0,"d13westmon12"))
                                                                            );			
                    fprintf($fid_wnd, "%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f\n", 
                                                                            trim(pg_result($Result,0,"d14wnwmon1")), 
                                                                            trim(pg_result($Result,0,"d14wnwmon2")), 
                                                                            trim(pg_result($Result,0,"d14wnwmon3")), 
                                                                            trim(pg_result($Result,0,"d14wnwmon4")), 
                                                                            trim(pg_result($Result,0,"d14wnwmon5")), 
                                                                            trim(pg_result($Result,0,"d14wnwmon6")), 
                                                                            trim(pg_result($Result,0,"d14wnwmon7")), 
                                                                            trim(pg_result($Result,0,"d14wnwmon8")), 
                                                                            trim(pg_result($Result,0,"d14wnwmon9")), 
                                                                            trim(pg_result($Result,0,"d14wnwmon10")), 
                                                                            trim(pg_result($Result,0,"d14wnwmon11")), 
                                                                            trim(pg_result($Result,0,"d14wnwmon12"))
                                                                            );			
                    fprintf($fid_wnd, "%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f\n", 
                                                                            trim(pg_result($Result,0,"d15nwmon1")), 
                                                                            trim(pg_result($Result,0,"d15nwmon2")), 
                                                                            trim(pg_result($Result,0,"d15nwmon3")), 
                                                                            trim(pg_result($Result,0,"d15nwmon4")), 
                                                                            trim(pg_result($Result,0,"d15nwmon5")), 
                                                                            trim(pg_result($Result,0,"d15nwmon6")), 
                                                                            trim(pg_result($Result,0,"d15nwmon7")), 
                                                                            trim(pg_result($Result,0,"d15nwmon8")), 
                                                                            trim(pg_result($Result,0,"d15nwmon9")), 
                                                                            trim(pg_result($Result,0,"d15nwmon10")), 
                                                                            trim(pg_result($Result,0,"d15nwmon11")), 
                                                                            trim(pg_result($Result,0,"d15nwmon12"))
                                                                            );			
                    fprintf($fid_wnd, "%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f\n", 
                                                                            trim(pg_result($Result,0,"d16nnwmon1")), 
                                                                            trim(pg_result($Result,0,"d16nnwmon2")), 
                                                                            trim(pg_result($Result,0,"d16nnwmon3")), 
                                                                            trim(pg_result($Result,0,"d16nnwmon4")), 
                                                                            trim(pg_result($Result,0,"d16nnwmon5")), 
                                                                            trim(pg_result($Result,0,"d16nnwmon6")), 
                                                                            trim(pg_result($Result,0,"d16nnwmon7")), 
                                                                            trim(pg_result($Result,0,"d16nnwmon8")), 
                                                                            trim(pg_result($Result,0,"d16nnwmon9")), 
                                                                            trim(pg_result($Result,0,"d16nnwmon10")), 
                                                                            trim(pg_result($Result,0,"d16nnwmon11")), 
                                                                            trim(pg_result($Result,0,"d16nnwmon12"))
                                                                            );	

                    fclose($fid_wnd);


                    $fid_wp1lst = fopen($fn_wp1lst, "w");
                    fprintf($fid_wp1lst, "    1\t%s.WP1\t%8.2f\t%8.2f\t%s\t%s\n",
                                                                            trim($stnname), 
                                                                            trim(pg_result($Result,0,"latitude")), 
                                                                            trim(pg_result($Result,0,"longitude")), 
                                                                            trim($stateabb), 
                                                                            pg_result($Result,0,"stationlocation")
                                                                            );

                    fclose($fid_wp1lst);							

                    $fid_wndlst = fopen($fn_wndlst, "w");
                    fprintf($fid_wndlst, "    1\t%s.WND\t%8.2f\t%8.2f\t%s\t%s\n",
                                                                            trim($stnname), 
                                                                            trim(pg_result($Result,0,"latitude")), 
                                                                            trim(pg_result($Result,0,"longitude")), 
                                                                            trim($stateabb), 
                                                                            pg_result($Result,0,"stationlocation")
                                                                            );

                    fclose($fid_wndlst);	

                    pg_freeresult($Result);

    }

    public function getdayoftheyear($dateString)
    {
      $day = date('z', strtotime($dateString));
      return $day;
    }

    public function WriteDLYfile($Connection, $stnname, $stateabb, $dlystartyr, $dlyendyr, $fn_dlylst, $fn_dly, $ziplat, $ziplong, $stnloc)
    {

            $stnst = substr($stnname, 0, 2);
            $statelower = strtolower($stnst);
            $stnname_lowerstab = trim($statelower).trim(substr($stnname, 2));

            $sqlstmt = "SELECT * from " . $statelower . "_obspt4dly where stationname ='" . $stnname_lowerstab . "' AND yearid >= '" . $dlystartyr . "' AND yearid <= '" . $dlyendyr . "' order by stnvaryrid"; 
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



                    $fid_dly = fopen($fn_dly, "w");

                    // Generate a range of date series
                    $dlystartdate = "" . $dlystartyr . "-01-01";
                    $dlyenddate = "" . $dlyendyr . "-12-31";

                    $dlyperiod = new DatePeriod(
                                    new DateTime($dlystartdate),
                                    new DateInterval('P1D'),
                                    new DateTime($dlyenddate)
                                    );

                    foreach($dlyperiod as $date){
                            $nodayyr = (int)$this->getdayoftheyear($date->format('d M Y'))+1;
                            for ($Row=0; $Row < pg_numrows($Result); $Row+=3) {
                                    if ((trim(pg_result($Result,$Row+1,"dayiyr".$nodayyr."")) == '9999')
                                            or (trim(pg_result($Result,$Row+2,"dayiyr".$nodayyr."")) == '9999')
                                            or (trim(pg_result($Result,$Row,"dayiyr".$nodayyr."")) == '9999')
                                            )
                                            {
                                            fprintf($fid_dly, "%6s%4s%4s%6.2f%6.0f%6.0f%6.0f%6.2f%6.2f\n", 
                                                                            $date->format('Y'),
                                                                            $date->format('m'),
                                                                            $date->format('d'),
                                                                            0.0, 
                                                                            trim(pg_result($Result,$Row+1,"dayiyr".$nodayyr."")),
                                                                            trim(pg_result($Result,$Row+2,"dayiyr".$nodayyr."")),
                                                                            trim(pg_result($Result,$Row,"dayiyr".$nodayyr."")),
                                                                            0.0, 0.0
                                                            );
                                            }
                                    else {
                                            fprintf($fid_dly, "%6s%4s%4s%6.2f%6.2f%6.2f%6.2f%6.2f%6.2f\n", 
                                                                            $date->format('Y'),
                                                                            $date->format('m'),
                                                                            $date->format('d'),
                                                                            0.0, 
                                                                            trim(pg_result($Result,$Row+1,"dayiyr".$nodayyr."")),
                                                                            trim(pg_result($Result,$Row+2,"dayiyr".$nodayyr."")),
                                                                            trim(pg_result($Result,$Row,"dayiyr".$nodayyr."")),
                                                                            0.0, 0.0									
                                                            );
                                                                            }
                                                                            }
                                                                            }

                    pg_freeresult($Result);
                    fclose($fid_dly);	


                    $fid_dlylst = fopen($fn_dlylst, "w");

                    fprintf($fid_dlylst, "    1\t%s.DLY\t%8.2f\t%8.2f\t%8s\t%s\n",
                                                                            trim($stnname),
                                                                             $ziplat, $ziplong, $statelower, $stnloc
                                                                            );

                    fclose($fid_dlylst);	

    }


        
    
    
    
    
    
}
?>
