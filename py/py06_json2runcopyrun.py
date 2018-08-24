

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
import shutil


# Input files and variables definition
#####################################################################
# Input folder containing the prj file from the online JSON
#usr_runfd = "userruns/Iowa_2016_11_15_uklhon07ardscnvkpjrc1nj2m2"

usr_runfd = sys.argv[1]

#jsonrun_other = "%s/run_other.json" %(usr_runfd)

jsonrun_other = sys.argv[2]
if not os.path.isfile(jsonrun_other):
    print("Input json file doesn't exist, please check")   


# Folder containing a setup of input files for apex
# database common for all simulation will be copied from this folder


# Functions
#####################################################################
def read_json(user_sjon):
    
    import json
    import pprint
    inf_usrjson = 0
    with open(user_sjon) as json_file:    
        inf_usrjson = json.loads(json_file.read())
    pprint.pprint(inf_usrjson)
    json_file.close()
    
    return inf_usrjson


def copy_database():
    # This function copies necessary database and general files for one run
    common_file = ["APEXFILE.DAT",\
                    "APEXDIM.DAT",\
                    "CROPCOM.DAT",\
                    "TILLCOM.DAT",\
                    "PESTCOM.DAT",\
                    "FERTCOM.DAT",\
                    "TR55COM.DAT",\
                    "PRNT0806.DAT",\
                    "PARM0806.DAT",\
                    "MLRN0806.DAT",\
                    "HERD0806.DAT",\
                    "PSOCOM.DAT",\
                    "APEX1501_64R.exe"\
                    ]
    
    for cfidx in common_file:
        if not os.path.isfile(r"%s/%s" %(usr_runfd, cfidx)):
            shutil.copy2(r"%s/%s" %(infd_commondb, cfidx),\
                        usr_runfd)
                        
                        
def write_apexrun(inf_usrjson):
    
    # I will use a distionary for run lines
    # It will be initiated first as the template for stop.
                    
    # Assign values to a specific run
    # Monthly monthly and wind weather station file will be left blank,

    # Write the APEXRUN file
    outfid_run = 0
    outfid_run = open(r"%s/APEXRUN.DAT" %(usr_runfd), "w")
    # APEXRUN is read with free format in APEX.exe
    outfid_run.writelines(u"%-10s%7i%7i%7i%7i%7i%7i\n" %(\
                        "R_" + inf_usrjson["other"]["runname"],\
                        1,\
                        0,\
                        0,\
                        1,\
                        0, 0\
                        ))
    outfid_run.writelines("%10s%7i%7i%7i%7i%7i%7i\n" %(\
                        "XXXXXXXXXX", 0, 0, 0, 0, 0, 0\
                        ))    
    outfid_run.close()
    


def write_hlycom(inf_usrjson):
    
    outfn_hlycom = "RFDTLST.DAT"
    outfid_hlycom = open(r"%s/%s" %(usr_runfd, outfn_hlycom), "w")
    # APEXRUN is read with free format in APEX.exe
    # Write line 1:
    outfid_hlycom.writelines(u"%4i\t%s\n" %(1,\
            inf_usrjson["other"]["subdaily_rfdt_name"]\
            ))
    outfid_hlycom.close()
    
def write_runlist(inf_usrjson):
    # Write the APEXRUN file
    outfid_runlst = 0
    outfn_runlst = r"%s/SESSIONRUN.LIST" %(usr_runfd)
    newrunline = u"%s, %s, %s, %s, %s, %s, %s, %s\n" %(
			"R_" + inf_usrjson["other"]["runname"]+".WSS",
			inf_usrjson["other"]["subAreaProj"],
			inf_usrjson["other"]["SlopeLenProj"],
			inf_usrjson["other"]["ManNameProj"],
			inf_usrjson["other"]["SoilNameProj"],
			inf_usrjson["other"]["SoilTNProj"],
			inf_usrjson["other"]["SoilTPProj"],
			inf_usrjson["other"]["TileDepthProj"])
    # For the first run, there should not be the file.
    if not os.path.isfile(outfn_runlst):
        outfid_runlst = open(outfn_runlst, "w")
    # APEXRUN is read with free format in APEX.exe
        outfid_runlst.writelines(newrunline)    
                        
        outfid_runlst.close()
    else:
        outfid_runlst = open(outfn_runlst, "a+")
        lif_runlst = outfid_runlst.readlines()
        # If the run does not exist, append to the file
        if not newrunline in lif_runlst:
            print(lif_runlst)
            outfid_runlst.writelines(newrunline)
        outfid_runlst.close()

# Call functions
#####################################################################

## Call functions
inf_usrjson = read_json(jsonrun_other)
#
## Write sol file

print("Writing APEXRUN.DAT")
write_apexrun(inf_usrjson)
print("Finished writing APEXRUN.DAT")

print("Writing RFDTLST")
write_hlycom(inf_usrjson)
print("Finished writing RFDTLST")


import os
os.chdir(usr_runfd)
os.system("APEX1501_64R.exe")
os.chdir(r"../..")
print("APEX run finished")

print("Writing list of runs")
write_runlist(inf_usrjson)

#
#
