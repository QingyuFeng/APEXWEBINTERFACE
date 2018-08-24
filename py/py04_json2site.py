

# Created on April 29, 2016
# By Qingyu Feng
#
# This script is created to write the AEPXRUN.DAT file for
# the run of APEX at one field site.
# The input will be the output of parameters from JSON.
# The output will be a series of APEX input file written
# The output will be a APEXRUN.DAT containing the running information
# for each field.

# This code only worked for a single field/subarea. This indicated
# that the CHL=RCHL =0 as specified in the use manual. If the subarea
# is larger than 20 ha, there will be one channel assumed based on the 
# area, which will be dealt with later.

# All the input will be input through the Json file, which contains
# a structure of all parameters for each run. 
# The parameters that need to be specified refers to my master 
# thesis. Appendix D. More might be added.

# All the databases will be included in the folder
# db01: sol us from gssurgo, identify by mukey
# db02&03: wp1 and wnd us from JSON cligen monthly file
# db04: dly files
# db05: hly files 
# System setup
#####################################################################
import os, sys


# Input files and variables definition
#####################################################################
# Input folder containing the prj file from the online JSON
#usr_runfd = "userruns/Alabama36003_Area500_Slope5_sol328065_ag2rafh39bfbtpj60q4ksmvm24"


usr_runfd = sys.argv[1]
if not os.path.isdir(usr_runfd):
    print("Folder for runs doesn't exist, please check")

#jsonrun_site = "%s/run_site.json" %(usr_runfd)
jsonrun_site = sys.argv[2]
if not os.path.isfile(jsonrun_site):
    print("Input json file doesn't exist, please check")   

    
# Functions
#####################################################################
def read_json(jsonrun_site):
    
    import json
    import pprint
    inf_usrjson = 0
    with open(jsonrun_site) as json_file:    
        inf_usrjson = json.loads(json_file.read())
    pprint.pprint(inf_usrjson)
    json_file.close()
    
    return inf_usrjson


def write_site_com(inf_usrjson):
    
    outfn_sit = "SIT%s.SIT" %(inf_usrjson["model_setup"]["description_line1"])
    # Write the Site file
    outfid_sit = open(r"%s/%s" %(usr_runfd, outfn_sit), "w")
    # APEXRUN is read with free format in APEX.exe
    # Write line 1:
    outfid_sit.writelines("%s\n".rjust(74, " ") %(outfn_sit[:-4]))
    # Write line 2:
    outfid_sit.writelines("%s\n".rjust(70, " ") %(outfn_sit))
    # Write line 3:
    outfid_sit.writelines("Outlet 1\n".rjust(74, " "))
    # Write line 4
    print(type(inf_usrjson["irrigation"]["auto_irrig_adj_fir0"]))
    outfid_sit.writelines(u"%8.3f%8.3f%8.2f%8s%8s%8s%8s%8s%8s%8s\n" %(\
                        float(inf_usrjson["geographic"]["latitude_ylat"]),\
                        float(inf_usrjson["geographic"]["longitude_xlog"]),\
                        float(inf_usrjson["geographic"]["elevation_elev"]),\
                        inf_usrjson["runoff"]["peakrunoffrate_apm"],\
                        inf_usrjson["co2"]["co2conc_atmos_co2x"],\
                        inf_usrjson["nitrogen"]["no3n_irrigation_cqnx"],\
                        inf_usrjson["nitrogen"]["nitrogen_conc_rainfall_rfnx"],\
                        inf_usrjson["manure"]["manure_p_app_upr"],\
                        inf_usrjson["manure"]["manure_n_app_unr"],\
                        inf_usrjson["irrigation"]["auto_irrig_adj_fir0"]\
                        ))
    # Write Line 5
    outfid_sit.writelines(u"%8s%8s%8s%8s%8s%8s%8s%8s%8s%8s\n" %(\
                        0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00,\
                        inf_usrjson["channel"]["basin_channel_length_bchl"],\
                        inf_usrjson["channel"]["basin_chalnel_slp_bchs"]\
                        ))
    # Write Line 6
    outfid_sit.writelines("\n")
    # Write Line 7
    outfid_sit.writelines("%8i%8i%8i%8i%8i%8i%8i%8i%8i%8i\n" %(\
                        0, 0, 0, 0, 0, 0, 0, 0, 0, 0\
                        ))
    # Write Line 8
    outfid_sit.writelines("%8.2f%8.2f%8.2f%8.2f%8.2f%8.2f%8.2f%8.2f%8.2f%8.2f\n" %(\
                        0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00,0.00, 0.00\
                        ))
    # Write Line 9
    outfid_sit.writelines("\n")     
    # Write Line 10
    outfid_sit.writelines("\n")  
    # Write Line 11
    outfid_sit.writelines("\n")       
     
    outfid_sit.close()
    
    outfn_sitcom = "SITECOM.DAT"
    outfid_sitcom = open(r"%s/%s" %(usr_runfd, outfn_sitcom), "w")
    # APEXRUN is read with free format in APEX.exe
    # Write line 1:
    outfid_sitcom.writelines("%4i\t%s\n" %(1, outfn_sit))
    outfid_sitcom.close()
    
inf_usrjson = read_json(jsonrun_site)
write_site_com(inf_usrjson)