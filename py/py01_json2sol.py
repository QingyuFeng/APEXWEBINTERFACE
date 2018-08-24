#!usr/bin/python
"""# Created on Oct 28, 2016
# By Qingyu Feng
This script was generated to convert json file updated
by the webpage to APEX SOL files.

The data is currently stored in the SSURGO table int he sql database.
The specific soil will be identified by mukey. 

"""
# System setup
#####################################################################
import os, sys

# Input files and variables definition
#####################################################################

# There are two system agrument taken from php
# 1. user run folder
# 2. Json file name with directory
#usr_runfd = r"userruns/Indiana_2016_11_15_k599294ljjkd1s75db7c6dqe40"
usr_runfd = sys.argv[1]
if not os.path.isdir(usr_runfd):
    print("Folder for runs doesn't exist, please check")
#jsonrun_sol = "%s/run_sol.json" %(usr_runfd)
jsonrun_sol = sys.argv[2]
if not os.path.isfile(jsonrun_sol):
    print("Input json file doesn't exist, please check")   

# Functions
#####################################################################
def read_json(jsonrun_sol):
    
    import json
    import pprint
    inf_usrjson = 0
    with open(jsonrun_sol) as json_file:    
        inf_usrjson = json.loads(json_file.read())
    pprint.pprint(inf_usrjson)
    
    return inf_usrjson


def write_sol(usr_runfd, inf_usrjson):
    
    # I will use a distionary for cont lines
    # It will be initiated first as the template for stop.
    solf_name = 0

    # Test whether new there is soil test n and P
    if (float(inf_usrjson["line17_inisolnconc_cnds"]["z1"]) == 0 and 
        float(inf_usrjson["line18_soilp_ssf"]["z1"]) == 0):
        solf_name = "MK%s.SOL" %(inf_usrjson["solmukey"]) 
    elif (float(inf_usrjson["line17_inisolnconc_cnds"]["z1"]) != 0 and 
        float(inf_usrjson["line18_soilp_ssf"]["z1"]) == 0):
        solf_name = "MK%sSN%i.SOL" %(inf_usrjson["solmukey"],
                    int(inf_usrjson["line17_inisolnconc_cnds"]["z1"]))
    elif (float(inf_usrjson["line17_inisolnconc_cnds"]["z1"]) == 0 and 
        float(inf_usrjson["line18_soilp_ssf"]["z1"]) != 0):
        solf_name = "MK%sSP%i.SOL" %(inf_usrjson["solmukey"],
                    int(inf_usrjson["line18_soilp_ssf"]["z1"]))
    else:
        solf_name = "MK%sSN%iSP%i.SOL" %(inf_usrjson["solmukey"],
                    int(inf_usrjson["line17_inisolnconc_cnds"]["z1"]),
                    int(inf_usrjson["line18_soilp_ssf"]["z1"]))                    
                    
    solf_list.append(solf_name)
    
    print(solf_name)
    # Start writing sol files    
    wfid_sol = 0
    wfid_sol = open(r"%s/%s" %(usr_runfd, solf_name) , "w")

    # Write line 1: desctiption
    sol_l1 = 0
    sol_l1 = "%20s\n" %(inf_usrjson["line1"]["soilname"])
    wfid_sol.writelines(sol_l1)

    # Writing line 2
    sol_l2 = 0
    #    ! SOIL PROPERTIES

        # modify hydrologic soi group from ABCD  to `1234' AS REQUIRED IN APEX.
    if "/" in inf_usrjson["line2"]["hydrologicgroup_hsg"]:
        inf_usrjson["line2"]["hydrologicgroup_hsg"]=\
            inf_usrjson["line2"]["hydrologicgroup_hsg"].split("/")[0]

    if inf_usrjson["line2"]["hydrologicgroup_hsg"] == "A":
        inf_usrjson["line2"]["hydrologicgroup_hsg"] = 1
    elif inf_usrjson["line2"]["hydrologicgroup_hsg"] == "B":
        inf_usrjson["line2"]["hydrologicgroup_hsg"] = 2
    elif inf_usrjson["line2"]["hydrologicgroup_hsg"] == "C":
        inf_usrjson["line2"]["hydrologicgroup_hsg"] = 3        
    else:
        inf_usrjson["line2"]["hydrologicgroup_hsg"] = 4

    sol_l2 = "%8.2f%8.2f%8.2f%8.2f%8.2f%8.2f%8.2f%8.2f%8.2f%8.2f\n"\
                    %(float(inf_usrjson["line2"]["abledo_salb"]),\
                      float(inf_usrjson["line2"]["hydrologicgroup_hsg"]), 0.00,\
                      float(inf_usrjson["line2"]["minwatertabledep_wtmn"]),\
                      0.00, 0.00, 0.00, 0.00, 0.00, 0.00)
    wfid_sol.writelines(sol_l2)

    # Line 3:  Same format as line 2, different parameters. 
    # Some values were set to prevent any potential model run failure.
    # the 5th variable ZQT, should be from 0.01 to 0.25.
    # the 6th and 7th variable ZF should be from 0.05 to 0.25
    # the 8 and 9 should be larger than 0.03 and 0.3
    # The 10th should be left blank
    sol_l3 = 0
    sol_l3 = "%8.2f%8.2f%8.2f%8.2f%8.2f%8.2f%8.2f%8.2f%8.2f        \n"\
                %(float(inf_usrjson["line3"]["min_layerdepth_tsla"]), 
                    float(inf_usrjson["line3"]["weatheringcode_xids"]),
                    float(inf_usrjson["line3"]["cultivationyears_rtn1"]),
                    float(inf_usrjson["line3"]["grouping_xidk"]),
                    float(inf_usrjson["line3"]["min_maxlayerthick_zqt"]),
                    float(inf_usrjson["line3"]["minprofilethick_zf"]),
                    float(inf_usrjson["line3"]["minlayerthick_ztk"]),
                    float(inf_usrjson["line3"]["org_c_biomass_fbm"]),
                    float(inf_usrjson["line3"]["org_c_passive_fhp"])
                    )
    wfid_sol.writelines(sol_l3)

    # Starting from line 4, the variables will be writen for 
    # properties for eacy layer, and each column represent one layer.
    # It is better to use a loop to do the writing.

    sol_layer_pro = [""]*52
    layeridxlst = [int(float(inf_usrjson["layerid"]["z1"])),
                   int(float(inf_usrjson["layerid"]["z2"])),
                    int(float(inf_usrjson["layerid"]["z3"])),
                    int(float(inf_usrjson["layerid"]["z4"])),
                    int(float(inf_usrjson["layerid"]["z5"])),
                    int(float(inf_usrjson["layerid"]["z6"])),
                    int(float(inf_usrjson["layerid"]["z7"])),
                    int(float(inf_usrjson["layerid"]["z8"])),
                    int(float(inf_usrjson["layerid"]["z9"])),
                    int(float(inf_usrjson["layerid"]["z10"]))
                    ]
    
    for layeridx in range(0, max(layeridxlst)):
        if layeridx < max(layeridxlst)-1:
            print(layeridx)
            
    #  !  4  Z    = DEPTH TO BOTTOM OF LAYERS(m)            
            sol_layer_pro[3] = sol_layer_pro[3] + "%8.2f" \
                %(float(inf_usrjson["line4_layerdepth"]["z%i" %(layeridx+1)])/100)
#  !  5  BD   = BULK DENSITY(t/m3)                
            sol_layer_pro[4] = sol_layer_pro[4] + "%8.2f" \
                %(float(inf_usrjson["line5_moistbulkdensity"]["z%i" %(layeridx+1)]))
#  !  6  UW   = SOIL WATER CONTENT AT WILTING POINT(1500 KPA)(m/m)                                             
#  !            (BLANK IF UNKNOWN)                
            sol_layer_pro[5] = sol_layer_pro[5] + "%8.2f" \
                %(float(inf_usrjson["line6_wiltingpoint"]["z%i" %(layeridx+1)])/100)
#  !  7  FC   = WATER CONTENT AT FIELD CAPACITY(33KPA)(m/m)                                                    
#  !            (BLANK IF UNKNOWN)                
            sol_layer_pro[6] = sol_layer_pro[6] + "%8.2f" \
                %(float(inf_usrjson["line7_fieldcapacity"]["z%i" %(layeridx+1)])/100)
#  !  8  SAN  = % SAND                 
            sol_layer_pro[7] = sol_layer_pro[7] + "%8.2f" \
                %(float(inf_usrjson["line8_sand"]["z%i" %(layeridx+1)]))
#  !  9  SIL  = % SILT                
            sol_layer_pro[8] = sol_layer_pro[8] + "%8.2f" \
                %(float(inf_usrjson["line9_silt"]["z%i" %(layeridx+1)]))
#  ! 10  WN   = INITIAL ORGANIC N CONC(g/t)       (BLANK IF UNKNOWN)                
            sol_layer_pro[9] = sol_layer_pro[9] + "%8.2f" \
                %(0.00)
#  ! 11  PH   = SOIL PH                
            sol_layer_pro[10] = sol_layer_pro[10] + "%8.2f" \
                %(float(inf_usrjson["line11_ph"]["z%i" %(layeridx+1)]))
#  ! 12  SMB  = SUM OF BASES(cmol/kg)              (BLANK IF UNKNOWN)
            sol_layer_pro[11] = sol_layer_pro[11] + "%8.2f" \
                %(float(inf_usrjson["line12_sumofbase_smb"]["z%i" %(layeridx+1)]))
#  ! 13  WOC  = ORGANIC CARBON CONC(%)                
            sol_layer_pro[12] = sol_layer_pro[12] + "%8.2f" \
                %(float(inf_usrjson["line13_orgc_conc_woc"]["z%i" %(layeridx+1)]))
#  ! 14  CAC  = CALCIUM CARBONATE(%)                 
            sol_layer_pro[13] = sol_layer_pro[13] + "%8.2f" \
                %(float(inf_usrjson["line14_caco3_cac"]["z%i" %(layeridx+1)]))
#  ! 15  CEC  = CATION EXCHANGE CAPACITY(cmol/kg)(BLANK IF UNKNOWN                
            sol_layer_pro[14] = sol_layer_pro[14] + "%8.2f" \
                %(float(inf_usrjson["line15_cec"]["z%i" %(layeridx+1)]))
#  ! 16  ROK  = COARSE FRAGMENTS(% VOL)              (BLANK IF UNKNOWN)           
            sol_layer_pro[15] = sol_layer_pro[15] + "%8.2f" \
                %(100-float(inf_usrjson["line16_rock_rok"]["z%i" %(layeridx+1)]))
#  ! 17  CNDS = INITIAL SOL N CONC(g/t)            (BLANK IF UNKNOWN) 
            sol_layer_pro[16] = sol_layer_pro[16] + "%8.2f" \
                %(float(inf_usrjson["line17_inisolnconc_cnds"]["z%i" %(layeridx+1)]))
#  ! 18  SSF  = INITIAL SOL P CONC(g/t)       (BLANK IF UNKNOWN)
            sol_layer_pro[17] = sol_layer_pro[17] + "%8.2f" \
                %(float(inf_usrjson["line18_soilp_ssf"]["z%i" %(layeridx+1)]))
#  ! 19  RSD  = CROP RESIDUE(t/ha)                (BLANK IF UNKNOWN)   
            sol_layer_pro[18] = sol_layer_pro[18] + "%8.2f" \
                %(0.00)
#  ! 20  BDD  = BULK DENSITY(OVEN DRY)(t/m3)   (BLANK IF UNKNOWN)                
            sol_layer_pro[19] = sol_layer_pro[19] + "%8.2f" \
                %(float(inf_usrjson["line20_drybd_bdd"]["z%i" %(layeridx+1)]))
#  ! 21  PSP  = P SORPTION RATIO                   (BLANK IF UNKNOWN)                  
            sol_layer_pro[20] = sol_layer_pro[20] + "%8.2f" \
                %(0.00) 
#  ! 22  SATC = SATURATED CONDUCTIVITY(mm/h)     (BLANK IF UNKNOWN)
            sol_layer_pro[21] = sol_layer_pro[21] + "%8.2f" \
                %(float(inf_usrjson["line22_ksat"]["z%i" %(layeridx+1)]))
#  ! 23  HCL  = LATERAL HYDRAULIC CONDUCTIVITY(mm/h)                
            sol_layer_pro[22] = sol_layer_pro[22] + "%8.2f" \
                %(float(inf_usrjson["line22_ksat"]["z%i" %(layeridx+1)])/2)
#  ! 24  WPO  = INITIAL ORGANIC P CONC(g/t)      (BLANK IF UNKNOWN)                
            sol_layer_pro[23] = sol_layer_pro[23] + "%8.2f" \
                %(float(inf_usrjson["line24_orgp_wpo"]["z%i" %(layeridx+1)]))
#  ! 25  DHN  = EXCHANGEABLE K CONC (g/t)                
            sol_layer_pro[24] = sol_layer_pro[24] + "%8.2f" \
                %(0.00)
#  ! 26  ECND = ELECTRICAL COND (mmho/cm)                
            sol_layer_pro[25] = sol_layer_pro[25] + "%8.2f" \
                %(float(inf_usrjson["line26_electricalcond_ec"]["z%i" %(layeridx+1)]))
#  ! 27  STFR = FRACTION OF STORAGE INTERACTING WITH NO3 LEACHING                                              
#  !                                               (BLANK IF UNKNOWN)                
            sol_layer_pro[26] = sol_layer_pro[26] + "%8.2f" \
                %(0.00)
#  ! 28  SWST = INITIAL SOIL WATER STORAGE (m/m)                
            sol_layer_pro[27] = sol_layer_pro[27] + "%8.2f" \
                %(0.00)
#  ! 29  CPRV = FRACTION INFLOW PARTITIONED TO VERTICLE CRACK OR PIPE FLOW                
            sol_layer_pro[28] = sol_layer_pro[28] + "%8.2f" \
                %(0.00)
#  ! 30  CPRH = FRACTION INFLOW PARTITIONED TO HORIZONTAL CRACK OR PIPE                                        
#  !            FLOW                 
            sol_layer_pro[29] = sol_layer_pro[29] + "%8.2f" \
                %(0.00)
#  ! 31  WLS  = STRUCTURAL LITTER(kg/ha)           (BLANK IF UNKNOWN)                
            sol_layer_pro[30] = sol_layer_pro[30] + "%8.2f" \
                %(0.00)
#  ! 32  WLM  = METABOLIC LITTER(kg/ha)            (BLANK IF UNKNOWN)            
            sol_layer_pro[31] = sol_layer_pro[31] + "%8.2f" \
                %(0.00)
#  ! 33  WLSL = LIGNIN CONTENT OF STRUCTURAL LITTER(kg/ha)(B I U)                
            sol_layer_pro[32] = sol_layer_pro[32] + "%8.2f" \
                %(0.00)
#  ! 34  WLSC = CARBON CONTENT OF STRUCTURAL LITTER(kg/ha)(B I U) 
            sol_layer_pro[33] = sol_layer_pro[33] + "%8.2f" \
                %(0.00)
#  ! 35  WLMC = C CONTENT OF METABOLIC LITTER(kg/ha)(B I U)
            sol_layer_pro[34] = sol_layer_pro[34] + "%8.2f" \
                %(0.00)
#  ! 36  WLSLC= C CONTENT OF LIGNIN OF STRUCTURAL LITTER(kg/ha)(B I U)
            sol_layer_pro[35] = sol_layer_pro[35] + "%8.2f" \
                %(0.00)
#  ! 37  WLSLNC=N CONTENT OF LIGNIN OF STRUCTURAL LITTER(kg/ha)(BIU)
            sol_layer_pro[36] = sol_layer_pro[36] + "%8.2f" \
                %(0.00)
#  ! 38  WBMC = C CONTENT OF BIOMASS(kg/ha)(BIU)
            sol_layer_pro[37] = sol_layer_pro[37] + "%8.2f" \
                %(0.00)
#  ! 39  WHSC = C CONTENT OF SLOW HUMUS(kg/ha)(BIU)
            sol_layer_pro[38] = sol_layer_pro[38] + "%8.2f" \
                %(0.00)
#  ! 40  WHPC = C CONTENT OF PASSIVE HUMUS(kg/ha)(BIU)
            sol_layer_pro[39] = sol_layer_pro[39] + "%8.2f" \
                %(0.00)
#  ! 41  WLSN = N CONTENT OF STRUCTURAL LITTER(kg/ha)(BIU)
            sol_layer_pro[40] = sol_layer_pro[40] + "%8.2f" \
                %(0.00)
#  ! 42  WLMN = N CONTENT OF METABOLIC LITTER(kg/ha)(BIU)
            sol_layer_pro[41] = sol_layer_pro[41] + "%8.2f" \
                %(0.00)
#  ! 43  WBMN = N CONTENT OF BIOMASS(kg/ha)(BIU)
            sol_layer_pro[42] = sol_layer_pro[42] + "%8.2f" \
                %(0.00)
#  ! 44  WHSN = N CONTENT OF SLOW HUMUS(kg/ha)(BIU)
            sol_layer_pro[43] = sol_layer_pro[43] + "%8.2f" \
                %(0.00)
#  ! 45  WHPN = N CONTENT OF PASSIVE HUMUS(kg/ha)(BIU)
            sol_layer_pro[44] = sol_layer_pro[44] + "%8.2f" \
                %(0.00)
#  ! 46  FE26 = IRON CONTENT(%)
            sol_layer_pro[45] = sol_layer_pro[45] + "%8.2f" \
                %(0.00)
#  ! 47  SULF = SULFUR CONTENT(%)                 
            sol_layer_pro[46] = sol_layer_pro[46] + "%8.2f" \
                %(0.00)
#  ! 48  ASHZ = SOIL HORIZON(A,B,C)                                                                            
            sol_layer_pro[47] = sol_layer_pro[47] + "%8s" \
                %(" ")
#   ! 49  CGO2 = O2 CONC IN GAS PHASE (g/m3 OF SOIL AIR)
            sol_layer_pro[48] = sol_layer_pro[48] + "%8.2f" \
                %(0.00)
#   ! 50  CGCO2= CO2 CONC IN GAS PHASE (g/m3 OF SOIL AIR)                                                       
            sol_layer_pro[49] = sol_layer_pro[49] + "%8.2f" \
                %(0.00)
#   ! 51  CGN2O= N2O CONC IN GAS PHASE (g/m3 OF SOIL AIR)                 
            sol_layer_pro[50] = sol_layer_pro[50] + "%8.2f" \
                %(0.00)
        else:
    #  !  4  Z    = DEPTH TO BOTTOM OF LAYERS(m)            
            sol_layer_pro[3] = sol_layer_pro[3] + "%8.2f\n" \
                %(float(inf_usrjson["line4_layerdepth"]["z%i" %(layeridx+1)])/100)
#  !  5  BD   = BULK DENSITY(t/m3)                
            sol_layer_pro[4] = sol_layer_pro[4] + "%8.2f\n" \
                %(float(inf_usrjson["line5_moistbulkdensity"]["z%i" %(layeridx+1)]))
#  !  6  UW   = SOIL WATER CONTENT AT WILTING POINT(1500 KPA)(m/m)                                             
#  !            (BLANK IF UNKNOWN)                
            sol_layer_pro[5] = sol_layer_pro[5] + "%8.2f\n" \
                %(float(inf_usrjson["line6_wiltingpoint"]["z%i" %(layeridx+1)])/100)
#  !  7  FC   = WATER CONTENT AT FIELD CAPACITY(33KPA)(m/m)                                                    
#  !            (BLANK IF UNKNOWN)                
            sol_layer_pro[6] = sol_layer_pro[6] + "%8.2f\n" \
                %(float(inf_usrjson["line7_fieldcapacity"]["z%i" %(layeridx+1)])/100)
#  !  8  SAN  = % SAND                 
            sol_layer_pro[7] = sol_layer_pro[7] + "%8.2f\n" \
                %(float(inf_usrjson["line8_sand"]["z%i" %(layeridx+1)]))
#  !  9  SIL  = % SILT                
            sol_layer_pro[8] = sol_layer_pro[8] + "%8.2f\n" \
                %(float(inf_usrjson["line9_silt"]["z%i" %(layeridx+1)]))
#  ! 10  WN   = INITIAL ORGANIC N CONC(g/t)       (BLANK IF UNKNOWN)                
            sol_layer_pro[9] = sol_layer_pro[9] + "%8.2f\n" \
                %(0.00)
#  ! 11  PH   = SOIL PH                
            sol_layer_pro[10] = sol_layer_pro[10] + "%8.2f\n" \
                %(float(inf_usrjson["line11_ph"]["z%i" %(layeridx+1)]))
#  ! 12  SMB  = SUM OF BASES(cmol/kg)              (BLANK IF UNKNOWN)
            sol_layer_pro[11] = sol_layer_pro[11] + "%8.2f\n" \
                %(float(inf_usrjson["line12_sumofbase_smb"]["z%i" %(layeridx+1)]))
#  ! 13  WOC  = ORGANIC CARBON CONC(%)                
            sol_layer_pro[12] = sol_layer_pro[12] + "%8.2f\n" \
                %(float(inf_usrjson["line13_orgc_conc_woc"]["z%i" %(layeridx+1)]))
#  ! 14  CAC  = CALCIUM CARBONATE(%)                 
            sol_layer_pro[13] = sol_layer_pro[13] + "%8.2f\n" \
                %(float(inf_usrjson["line14_caco3_cac"]["z%i" %(layeridx+1)]))
#  ! 15  CEC  = CATION EXCHANGE CAPACITY(cmol/kg)(BLANK IF UNKNOWN                
            sol_layer_pro[14] = sol_layer_pro[14] + "%8.2f\n" \
                %(float(inf_usrjson["line15_cec"]["z%i" %(layeridx+1)]))
#  ! 16  ROK  = COARSE FRAGMENTS(% VOL)              (BLANK IF UNKNOWN)           
            sol_layer_pro[15] = sol_layer_pro[15] + "%8.2f\n" \
                %(100-float(inf_usrjson["line16_rock_rok"]["z%i" %(layeridx+1)]))
#  ! 17  CNDS = INITIAL SOL N CONC(g/t)            (BLANK IF UNKNOWN) 
            sol_layer_pro[16] = sol_layer_pro[16] + "%8.2f\n" \
                %(float(inf_usrjson["line17_inisolnconc_cnds"]["z%i" %(layeridx+1)]))
#  ! 18  SSF  = INITIAL SOL P CONC(g/t)       (BLANK IF UNKNOWN)
            sol_layer_pro[17] = sol_layer_pro[17] + "%8.2f\n" \
                %(float(inf_usrjson["line18_soilp_ssf"]["z%i" %(layeridx+1)]))
#  ! 19  RSD  = CROP RESIDUE(t/ha)                (BLANK IF UNKNOWN)   
            sol_layer_pro[18] = sol_layer_pro[18] + "%8.2f\n" \
                %(0.00)
#  ! 20  BDD  = BULK DENSITY(OVEN DRY)(t/m3)   (BLANK IF UNKNOWN)                
            sol_layer_pro[19] = sol_layer_pro[19] + "%8.2f\n" \
                %(float(inf_usrjson["line20_drybd_bdd"]["z%i" %(layeridx+1)]))
#  ! 21  PSP  = P SORPTION RATIO                   (BLANK IF UNKNOWN)                  
            sol_layer_pro[20] = sol_layer_pro[20] + "%8.2f\n" \
                %(0.00) 
#  ! 22  SATC = SATURATED CONDUCTIVITY(mm/h)     (BLANK IF UNKNOWN)
            sol_layer_pro[21] = sol_layer_pro[21] + "%8.2f\n" \
                %(float(inf_usrjson["line22_ksat"]["z%i" %(layeridx+1)]))
#  ! 23  HCL  = LATERAL HYDRAULIC CONDUCTIVITY(mm/h)                
            sol_layer_pro[22] = sol_layer_pro[22] + "%8.2f\n" \
                %(float(inf_usrjson["line22_ksat"]["z%i" %(layeridx+1)])/2)
#  ! 24  WPO  = INITIAL ORGANIC P CONC(g/t)      (BLANK IF UNKNOWN)                
            sol_layer_pro[23] = sol_layer_pro[23] + "%8.2f\n" \
                %(float(inf_usrjson["line24_orgp_wpo"]["z%i" %(layeridx+1)]))
#  ! 25  DHN  = EXCHANGEABLE K CONC (g/t)                
            sol_layer_pro[24] = sol_layer_pro[24] + "%8.2f\n" \
                %(0.00)
#  ! 26  ECND = ELECTRICAL COND (mmho/cm)                
            sol_layer_pro[25] = sol_layer_pro[25] + "%8.2f\n" \
                %(float(inf_usrjson["line26_electricalcond_ec"]["z%i" %(layeridx+1)]))
#  ! 27  STFR = FRACTION OF STORAGE INTERACTING WITH NO3 LEACHING                                              
#  !                                               (BLANK IF UNKNOWN)                
            sol_layer_pro[26] = sol_layer_pro[26] + "%8.2f\n" \
                %(0.00)
#  ! 28  SWST = INITIAL SOIL WATER STORAGE (m/m)                
            sol_layer_pro[27] = sol_layer_pro[27] + "%8.2f\n" \
                %(0.00)
#  ! 29  CPRV = FRACTION INFLOW PARTITIONED TO VERTICLE CRACK OR PIPE FLOW                
            sol_layer_pro[28] = sol_layer_pro[28] + "%8.2f\n" \
                %(0.00)
#  ! 30  CPRH = FRACTION INFLOW PARTITIONED TO HORIZONTAL CRACK OR PIPE                                        
#  !            FLOW                 
            sol_layer_pro[29] = sol_layer_pro[29] + "%8.2f\n" \
                %(0.00)
#  ! 31  WLS  = STRUCTURAL LITTER(kg/ha)           (BLANK IF UNKNOWN)                
            sol_layer_pro[30] = sol_layer_pro[30] + "%8.2f\n" \
                %(0.00)
#  ! 32  WLM  = METABOLIC LITTER(kg/ha)            (BLANK IF UNKNOWN)            
            sol_layer_pro[31] = sol_layer_pro[31] + "%8.2f\n" \
                %(0.00)
#  ! 33  WLSL = LIGNIN CONTENT OF STRUCTURAL LITTER(kg/ha)(B I U)                
            sol_layer_pro[32] = sol_layer_pro[32] + "%8.2f\n" \
                %(0.00)
#  ! 34  WLSC = CARBON CONTENT OF STRUCTURAL LITTER(kg/ha)(B I U) 
            sol_layer_pro[33] = sol_layer_pro[33] + "%8.2f\n" \
                %(0.00)
#  ! 35  WLMC = C CONTENT OF METABOLIC LITTER(kg/ha)(B I U)
            sol_layer_pro[34] = sol_layer_pro[34] + "%8.2f\n" \
                %(0.00)
#  ! 36  WLSLC= C CONTENT OF LIGNIN OF STRUCTURAL LITTER(kg/ha)(B I U)
            sol_layer_pro[35] = sol_layer_pro[35] + "%8.2f\n" \
                %(0.00)
#  ! 37  WLSLNC=N CONTENT OF LIGNIN OF STRUCTURAL LITTER(kg/ha)(BIU)
            sol_layer_pro[36] = sol_layer_pro[36] + "%8.2f\n" \
                %(0.00)
#  ! 38  WBMC = C CONTENT OF BIOMASS(kg/ha)(BIU)
            sol_layer_pro[37] = sol_layer_pro[37] + "%8.2f\n" \
                %(0.00)
#  ! 39  WHSC = C CONTENT OF SLOW HUMUS(kg/ha)(BIU)
            sol_layer_pro[38] = sol_layer_pro[38] + "%8.2f\n" \
                %(0.00)
#  ! 40  WHPC = C CONTENT OF PASSIVE HUMUS(kg/ha)(BIU)
            sol_layer_pro[39] = sol_layer_pro[39] + "%8.2f\n" \
                %(0.00)
#  ! 41  WLSN = N CONTENT OF STRUCTURAL LITTER(kg/ha)(BIU)
            sol_layer_pro[40] = sol_layer_pro[40] + "%8.2f\n" \
                %(0.00)
#  ! 42  WLMN = N CONTENT OF METABOLIC LITTER(kg/ha)(BIU)
            sol_layer_pro[41] = sol_layer_pro[41] + "%8.2f\n" \
                %(0.00)
#  ! 43  WBMN = N CONTENT OF BIOMASS(kg/ha)(BIU)
            sol_layer_pro[42] = sol_layer_pro[42] + "%8.2f\n" \
                %(0.00)
#  ! 44  WHSN = N CONTENT OF SLOW HUMUS(kg/ha)(BIU)
            sol_layer_pro[43] = sol_layer_pro[43] + "%8.2f\n" \
                %(0.00)
#  ! 45  WHPN = N CONTENT OF PASSIVE HUMUS(kg/ha)(BIU)
            sol_layer_pro[44] = sol_layer_pro[44] + "%8.2f\n" \
                %(0.00)
#  ! 46  FE26 = IRON CONTENT(%)
            sol_layer_pro[45] = sol_layer_pro[45] + "%8.2f\n" \
                %(0.00)
#  ! 47  SULF = SULFUR CONTENT(%)                 
            sol_layer_pro[46] = sol_layer_pro[46] + "%8.2f\n" \
                %(0.00)
#  ! 48  ASHZ = SOIL HORIZON(A,B,C)                                                                            
            sol_layer_pro[47] = sol_layer_pro[47] + "%8s\n" \
                %(" ")
#   ! 49  CGO2 = O2 CONC IN GAS PHASE (g/m3 OF SOIL AIR)
            sol_layer_pro[48] = sol_layer_pro[48] + "%8.2f\n" \
                %(0.00)
#   ! 50  CGCO2= CO2 CONC IN GAS PHASE (g/m3 OF SOIL AIR)                                                       
            sol_layer_pro[49] = sol_layer_pro[49] + "%8.2f\n" \
                %(0.00)
#   ! 51  CGN2O= N2O CONC IN GAS PHASE (g/m3 OF SOIL AIR)                 
            sol_layer_pro[50] = sol_layer_pro[50] + "%8.2f\n" \
                %(0.00)

    for layproidx in range(3, 51):
        wfid_sol.writelines(sol_layer_pro[layproidx])

    
    wfid_sol.close()
    
def write_soilcom(inf_usrjson):
    
    outfn_soilcom = "SOILCOM.DAT"
    outfid_soilcom = open(r"%s/%s" %(usr_runfd, outfn_soilcom), "w")
    # APEXRUN is read with free format in APEX.exe
    # Write line 1:

    for slidx in xrange(len(solf_list)): 
        outfid_soilcom.writelines("%4i\t%s\n" %(1, solf_list[slidx]))
    outfid_soilcom.close()

    
####
## Call functions
inf_usrjson = read_json(jsonrun_sol)
#
## Write sol file
solf_list = []
write_sol(usr_runfd, inf_usrjson)

write_soilcom(inf_usrjson)