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
#usr_runfd = "C:/xampp/htdocs/apexonlinev4_mgtclicons/userruns/20170316Autauga36003tewser63gilns4hq1rea3v0herfn6ot0"

usr_runfd = sys.argv[1]
if not os.path.isdir(usr_runfd):
    print("Folder for runs doesn't exist, please check")

#jsonrun_mgtops = "%s/run_mgtops.json" %(usr_runfd)
jsonrun_mgtops = sys.argv[2]
if not os.path.isfile(jsonrun_mgtops):
    print("Input json file doesn't exist, please check")   

#mgttb = sys.argv[3]
#mgttb = "usrtb"

#mgtname = "ertsdfg adasfereaser"
#mgtid = sys.argv[4]

# Functions
#####################################################################
def read_json(jsonrun_mgtops):
    
    import json
    import pprint
    inf_usrjson = 0
    with open(jsonrun_mgtops) as json_file:    
        inf_usrjson = json.loads(json_file.read())
    pprint.pprint(inf_usrjson)
    json_file.close()
    
    return inf_usrjson


def write_ops(usr_runfd, inf_usrjson):
    
    # I will use a distionary for cont lines
    # It will be initiated first as the template for stop.
    opsf_name = 0
    opsf_name = "man%s.OPC" %(inf_usrjson["mgtkey"])    
    opsf_list.append(opsf_name)
    
    # Start writing ops files    
    wfid_ops = 0
    wfid_ops = open(r"%s/%s" %(usr_runfd, opsf_name) , "w")
    # Writing line 1: description, this will be the name of the mgt
    wfid_ops.writelines("%s\n" %(inf_usrjson["mgtname"]))
    
    # Writing line 2: general parameters
    # This depends on the land cover and hydrologic soil group.
    # HSG can be get from soil data.
    # In ArcAPEX land use number is set in the database for each operation.
    # In my database, I shall include it in the json file and update
    # this number while user select new land use types.
    # This will be input in the database with the list of mgt table.
    # The model will get hsg from the soil file. I only need to input the 
    # land use number
    ops_l2 = "%4i%4i%4i%4i%4i%4i%4i\n"\
                    %(float(inf_usrjson["lun_landuseno"]["lun_landuseno1"]),\
                      float(inf_usrjson["iaui_autoirr"]["iaui_autoirr1"]),\
			float(inf_usrjson["iauf_autofert"]["iauf_autofert1"]),\
			float(inf_usrjson["iamf_automanualdepos"]["iamf_automanualdepos1"]),\
			float(inf_usrjson["ispf_autosolman"]["ispf_autosolman1"]),\
			float(inf_usrjson["ilqf_atliqman"]["ilqf_atliqman1"]),\
			float(inf_usrjson["iaul_atlime"]["iaul_atlime1"]))
    wfid_ops.writelines(ops_l2)
    
    # Writing line 3 to line N
    # N is the total number of operations, this will be determined as
    # length of one parameter in the json file. Here, I used year
    no_ops = len(inf_usrjson["jx1_year"])
    
    ops_templine = 0

    for opsidx in range(no_ops):
        
        ops_templine = "%3s%3s%3s%5s%5s%5s%5s%8.2f%8.2f%8.2f%8.2f%8.2f%8.2f%8.2f%8.2f\n"\
				%(
				inf_usrjson["jx1_year"]["jx1_year%i" %(opsidx)],\
				inf_usrjson["jx2_month"]["jx2_month%i" %(opsidx)],\
				inf_usrjson["jx3_day"]["jx3_day%i" %(opsidx)],\
				inf_usrjson["jx4_tillid"]["jx4_tillid%i" %(opsidx)],\
				inf_usrjson["jx5_tractid"]["jx5_tractid%i" %(opsidx)],\
				inf_usrjson["jx6_cropid"]["jx6_cropid%i" %(opsidx)],\
				inf_usrjson["jx7"]["jx7%i" %(opsidx)],\
				float(inf_usrjson["opv1"]["opv1%i" %(opsidx)]),\
				float(inf_usrjson["opv2"]["opv2%i" %(opsidx)]),\
				float(inf_usrjson["opv3"]["opv3%i" %(opsidx)]),\
				float(inf_usrjson["opv4"]["opv4%i" %(opsidx)]),\
				float(inf_usrjson["opv5"]["opv5%i" %(opsidx)]),\
				float(inf_usrjson["opv6"]["opv6%i" %(opsidx)]),\
				float(inf_usrjson["opv7"]["opv7%i" %(opsidx)]),\
				float(inf_usrjson["opv8"]["opv8%i" %(opsidx)])
				
                                )
	
        wfid_ops.writelines(ops_templine)

    # write end line
    ops_endline = "%3i%3i%3i%5i%5i%5i%5i%8.2f%8.2f%8.2f%8.2f%8.2f%8.2f%8.2f\n"\
                    %(0, 0, 0, 0, 0, 0, 0,\
                    0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00)
    
    wfid_ops.writelines(ops_endline)

    wfid_ops.close()
    
    
def write_opsccom(inf_usrjson):
    
    outfn_opsccom = "OPSCCOM.DAT"
    outfid_opsccom = open(r"%s/%s" %(usr_runfd, outfn_opsccom), "w")
    # APEXRUN is read with free format in APEX.exe
    # Write line 1:
    
    for opsidx in xrange(len(opsf_list)): 
        outfid_opsccom.writelines("%4i\t%s\n" %(1, opsf_list[opsidx]))
    outfid_opsccom.close()
    
    
    
####
## Call functions
inf_usrjson = read_json(jsonrun_mgtops)
#
## Write sol file
opsf_list = []
write_ops(usr_runfd, inf_usrjson)
write_opsccom(inf_usrjson)
