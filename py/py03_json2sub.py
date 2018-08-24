

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


# Input folder containing the prj file from the online JSON
#usr_runfd = "userruns/Alabama36003_Area500_Slope5_sol328057_9et0mm4ooh6rtoq6eklac7b0r4"


usr_runfd = sys.argv[1]
if not os.path.isdir(usr_runfd):
    print("Folder for runs doesn't exist, please check")

#jsonrun_sub = "%s/run_sub.json" %(usr_runfd)
jsonrun_sub = sys.argv[2]
if not os.path.isfile(jsonrun_sub):
    print("Input json file doesn't exist, please check")   

    
# Functions
#####################################################################
def read_json(user_json):
    
    import json
    import pprint
    inf_usrjson = 0
    with open(user_json) as json_file:    
        inf_usrjson = json.loads(json_file.read())
    pprint.pprint(inf_usrjson)
    
    return inf_usrjson


def write_sub_com(inf_usrjson):
    
    
    outfn_sub = "SUB%s.SUB" %(inf_usrjson["model_setup"]["description_title"])
    # Write the Site file
    outfid_sub = open(r"%s/%s" %(usr_runfd, outfn_sub), "w")
    # APEXRUN is read with free format in APEX.exe
    # Write line 1:
    outfid_sub.writelines("%8i%8i\n" %(1, 1))
    # Write line 2:
    outfid_sub.writelines(u"%8s%8s%8s%8s%8s%8s%8s%8s%8s%8s%8s%8s\n" %(\
                        inf_usrjson["soil"]["soilid"],\
                        inf_usrjson["management"]["opeartionid_iops"],\
                        inf_usrjson["model_setup"]["owner_id"],\
                        inf_usrjson["grazing"]["feeding_area_ii"],\
                        inf_usrjson["grazing"]["manure_app_area_iapl"],\
                        0.00,\
                        inf_usrjson["model_setup"]["nvcn"],\
                        inf_usrjson["weather"]["daily_wea_stnid_iwth"],\
                        inf_usrjson["point_source"]["point_source_ipts"],\
                        inf_usrjson["model_setup"]["outflow_release_method_isao"],\
                        inf_usrjson["land_use_type"]["land_useid_luns"],\
                        inf_usrjson["management"]["min_days_automow_imw"]\
                        ))
    # Write line 3:
    outfid_sub.writelines(u"%8s%8s%8.2f%8.2f%8s%8s%8s%8s\n" %(\
                        inf_usrjson["weather"]["begin_water_in_snow_sno"],\
                        inf_usrjson["land_use_type"]["standing_crop_residue_stdo"],\
                        float(inf_usrjson["geographic"]["latitude_xct"]),\
                        float(inf_usrjson["geographic"]["longitude_yct"]),\
                        inf_usrjson["wind_erosion"]["azimuth_land_slope_amz"],\
                        inf_usrjson["wind_erosion"]["field_lenthkm_fl"],\
                        inf_usrjson["wind_erosion"]["field_widthkm"],\
                        inf_usrjson["wind_erosion"]["angel_of_fieldlength_angl"]\
                        ))
    # Write line 4
    outfid_sub.writelines(u"%8.2f%8s%8s%8s%8s%8s%8s%8s%8s%8s\n" %(\
                        float(inf_usrjson["geographic"]["wsa_ha"]),\
                        inf_usrjson["geographic"]["channellength_chl"],\
                        inf_usrjson["geographic"]["channel_depth_chd"],\
                        inf_usrjson["geographic"]["channelslope_chs"],\
                        inf_usrjson["geographic"]["channelmanningn_chn"],\
                        inf_usrjson["geographic"]["avg_upland_slp"],\
                        inf_usrjson["geographic"]["avg_upland_slplen_splg"],\
                        inf_usrjson["geographic"]["uplandmanningn_upn"],\
                        inf_usrjson["flood_plain"]["flood_plain_frac_ffpq"],\
                        inf_usrjson["urban"]["urban_frac_urbf"]\
                        ))
    # Write Line 5
    outfid_sub.writelines(u"%8s%8s%8s%8s%8s%8s%8s%8s%8s%8s%8s%8s\n" %(\
                        inf_usrjson["geographic"]["reach_length_rchl"],\
                        inf_usrjson["geographic"]["reach_depth_rchd"],\
                        inf_usrjson["geographic"]["reach_bottom_width_rcbw"],\
                        inf_usrjson["geographic"]["reach_top_width_rctw"],\
                        inf_usrjson["geographic"]["reach_slope_rchs"],\
                        inf_usrjson["geographic"]["reach_manningsn_rchn"],\
                        inf_usrjson["geographic"]["reach_uslec_rchc"],\
                        inf_usrjson["geographic"]["reach_uslek_rchk"],\
                        inf_usrjson["geographic"]["reach_floodplain_rfpw"],\
                        inf_usrjson["geographic"]["reach_floodplain_length_rfpl"],\
                        inf_usrjson["geographic"]["rch_ksat_adj_factor_sat1"],\
                        inf_usrjson["flood_plain"]["fp_ksat_adj_factor_fps1"]\
                        ))
    # Write Line 6
    outfid_sub.writelines(u"%8s%8s%8s%8s%8s%8s%8s%8s%8s%8s\n"%(\
                        inf_usrjson["reservoir"]["elev_emers_rsee"],\
                        inf_usrjson["reservoir"]["res_area_emers_rsae"],\
                        inf_usrjson["reservoir"]["runoff_emers_rsve"],\
                        inf_usrjson["reservoir"]["elev_prins_rsep"],\
                        inf_usrjson["reservoir"]["res_area_prins_rsap"],\
                        inf_usrjson["reservoir"]["runoff_prins_rsvp"],\
                        inf_usrjson["reservoir"]["ini_res_volume_rsv"],\
                        inf_usrjson["reservoir"]["avg_prins_release_rate_rsrr"],\
                        inf_usrjson["reservoir"]["ini_sed_res_rsys"],\
                        inf_usrjson["reservoir"]["ini_nitro_res_rsyn"]\
                        ))
    # Write Line 7
    outfid_sub.writelines(u"%8s%8s%8s%8s%8s%8s\n" %(\
                        inf_usrjson["reservoir"]["hydro_condt_res_bottom_rshc"],\
                        inf_usrjson["reservoir"]["time_sedconc_tonormal_rsdp"],\
                        inf_usrjson["reservoir"]["bd_sed_res_rsbd"],\
                        inf_usrjson["pond"]["frac_pond_pcof"],\
                        inf_usrjson["buffer"]["frac_buffer_bcof"],\
                        inf_usrjson["buffer"]["buffer_flow_len_bffl"]\
                        ))
    # Write Line 8
    outfid_sub.writelines(u"%8s%8s%8s%8s%8s%8.1f%8s%8s%8s%8s%8s%8s%8s\n" %(\
                        inf_usrjson["irrigation"]["irrigation_irr"],\
                        inf_usrjson["irrigation"]["min_days_btw_autoirr_iri"],\
                        inf_usrjson["management"]["min_days_autonitro_ifa"],\
                        inf_usrjson["management"]["liming_code_lm"],\
                        inf_usrjson["management"]["furrow_dike_code_ifd"],\
                        float(inf_usrjson["drainage"]["drainage_depth_idr"]),\
                        inf_usrjson["management"]["autofert_lagoon_idf1"],\
                        inf_usrjson["management"]["auto_manure_feedarea_idf2"],\
                        inf_usrjson["management"]["auto_commercial_p_idf3"],\
                        inf_usrjson["management"]["auto_commercial_n_idf4"],\
                        inf_usrjson["management"]["auto_solid_manure_idf5"],\
                        inf_usrjson["management"]["auto_commercial_k_idf6"],\
                        inf_usrjson["irrigation"]["subareaid_irrwater_irrs"]\
                        ))
    # Write Line 9
    outfid_sub.writelines(u"%8s%8s%8s%8s%8s%8s%8s%8s%8s%8s\n" %(\
                        inf_usrjson["irrigation"]["waterstress_triger_irr_bir"],\
                        inf_usrjson["irrigation"]["irr_lost_runoff_efi"],\
                        inf_usrjson["irrigation"]["max_annual_irri_vol_vimx"],\
                        inf_usrjson["irrigation"]["min_single_irrvol_armn"],\
                        inf_usrjson["irrigation"]["max_single_irrvol_armx"],\
                        inf_usrjson["management"]["nstress_trigger_auton_bft"],\
                        inf_usrjson["management"]["auton_rate_fnp4"],\
                        inf_usrjson["management"]["max_annual_auton_fmx"],\
                        inf_usrjson["drainage"]["drain_days_end_w_stress_drt"],\
                        inf_usrjson["management"]["fd_water_store_fdsf"]\
                        )) 
    # Write Line 10
    outfid_sub.writelines(u"%8s%8s%8s%8s%8s%8s%8s%8s%8s%8s\n" %(\
                        inf_usrjson["water_erosion"]["usle_p_pec"],\
                        inf_usrjson["pond"]["frac_lagoon_dalg"],\
                        inf_usrjson["pond"]["lagoon_vol_ratio_vlgn"],\
                        inf_usrjson["pond"]["wash_water_to_lagoon_coww"],\
                        inf_usrjson["pond"]["time_reduce_lgstorage_nom_ddlg"],\
                        inf_usrjson["pond"]["ratio_liquid_manure_to_lg_solq"],\
                        inf_usrjson["pond"]["frac_safety_lg_design_sflg"],\
                        inf_usrjson["grazing"]["feedarea_pile_autosolidmanure_rate_fnp2"],\
                        inf_usrjson["management"]["auton_manure_fnp5"],\
                        inf_usrjson["irrigation"]["factor_adj_autoirr_firg"]\
                        ))                        
    # Write Line 11
    outfid_sub.writelines(u"%8s%8s%8s%8s%8s%8s%8s%8s%8s%8s\n" %(\
                        inf_usrjson["grazing"]["herds_eligible_forgrazing_ny1"],\
                        inf_usrjson["grazing"]["herds_eligible_forgrazing_ny2"],\
                        inf_usrjson["grazing"]["herds_eligible_forgrazing_ny3"],\
                        inf_usrjson["grazing"]["herds_eligible_forgrazing_ny4"],\
                        inf_usrjson["grazing"]["herds_eligible_forgrazing_ny5"],\
                        inf_usrjson["grazing"]["herds_eligible_forgrazing_ny6"],\
                        inf_usrjson["grazing"]["herds_eligible_forgrazing_ny7"],\
                        inf_usrjson["grazing"]["herds_eligible_forgrazing_ny8"],\
                        inf_usrjson["grazing"]["herds_eligible_forgrazing_ny9"],\
                        inf_usrjson["grazing"]["herds_eligible_forgrazing_ny10"]\
                        ))                          
    # Write Line 12
    outfid_sub.writelines(u"%8s%8s%8s%8s%8s%8s%8s%8s%8s%8s\n" %(\
                        inf_usrjson["grazing"]["grazing_limit_herd_xtp1"],\
                        inf_usrjson["grazing"]["grazing_limit_herd_xtp2"],\
                        inf_usrjson["grazing"]["grazing_limit_herd_xtp3"],\
                        inf_usrjson["grazing"]["grazing_limit_herd_xtp4"],\
                        inf_usrjson["grazing"]["grazing_limit_herd_xtp5"],\
                        inf_usrjson["grazing"]["grazing_limit_herd_xtp6"],\
                        inf_usrjson["grazing"]["grazing_limit_herd_xtp7"],\
                        inf_usrjson["grazing"]["grazing_limit_herd_xtp8"],\
                        inf_usrjson["grazing"]["grazing_limit_herd_xtp9"],\
                        inf_usrjson["grazing"]["grazing_limit_herd_xtp10"]\
                        ))                         
                        
                        
                        
    outfid_sub.close()
    
    outfn_subcom = "SUBACOM.DAT"
    outfid_subcom = open(r"%s/%s" %(usr_runfd, outfn_subcom), "w")
    # APEXRUN is read with free format in APEX.exe
    # Write line 1:
    outfid_subcom.writelines("%4i\t%s\n" %(1, outfn_sub))
    outfid_subcom.close()


inf_usrjson = read_json(jsonrun_sub)
write_sub_com(inf_usrjson)