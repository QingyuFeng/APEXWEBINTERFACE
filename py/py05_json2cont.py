

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

#jsonrun_cont = "%s/run_sub.json" %(usr_runfd)
jsonrun_cont = sys.argv[2]
if not os.path.isfile(jsonrun_cont):
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


def write_apexcont(inf_usrjson):
    
    # I will use a distionary for cont lines
    # It will be initiated first as the template for stop.

    # Write the APEXCONT file
    outfid_cont = 0
    outfid_cont = open(r"%s/APEXCONT.DAT" %(usr_runfd), "w")
    # APEXCONT is read with free format in APEX.exe
    # Line 1
    outfid_cont.writelines(u"%5s%5s%5s%5s%5s%5s%5s%5s%5s%5s%5s%5s%5s%5s%5s%5s%5s%5s%5s%5s\n" %(\
            inf_usrjson["model_setup"]["yearstorun_nbyr"],\
            inf_usrjson["model_setup"]["begin_year_iyr"],\
            inf_usrjson["model_setup"]["begin_month_imo"],\
            inf_usrjson["model_setup"]["begin_day_ida"],\
            inf_usrjson["model_setup"]["output_freq_ipd"],\
            inf_usrjson["weather"]["weather_in_var_ngn"],\
            inf_usrjson["weather"]["random_seeds_ign"],\
            inf_usrjson["weather"]["date_weather_duplicate_igsd"],\
            inf_usrjson["model_setup"]["leap_year_lpyr"],\
            inf_usrjson["weather"]["pet_method_iet"],\
            inf_usrjson["runoff_sim"]["stochastic_cn_code_iscn"],\
            inf_usrjson["runoff_sim"]["peak_rate_method_ityp"],\
            inf_usrjson["water_erosion"]["static_soil_code_ista"],\
            inf_usrjson["management"]["automatic_hu_schedule_ihus"],\
            inf_usrjson["runoff_sim"]["non_varying_cn_nvcn"],\
            inf_usrjson["runoff_sim"]["runoff_method_infl"],\
            inf_usrjson["nutrient_loss"]["pesticide_mass_conc_masp"],\
            inf_usrjson["nutrient_loss"]["enrichment_ratio_iert"],\
            inf_usrjson["nutrient_loss"]["soluble_p_estimate_lbp"],\
            inf_usrjson["nutrient_loss"]["n_p_uptake_curve_nupc"]\
            ))
    # Line 2
    outfid_cont.writelines(u"%5s%5s%5s%5s%5s%5s%5s%5s%5s%5s%5s%5s%5s%5s%5s%5s%5s%5s%5s%5s%5s%5s%5s\n" %(\
            inf_usrjson["management"]["manure_application_mnul"],\
            inf_usrjson["management"]["lagoon_pumping_lpd"],\
            inf_usrjson["management"]["solid_manure_mscp"],\
            inf_usrjson["water_erosion"]["slope_length_steep_islf"],\
            inf_usrjson["air_quality"]["air_quality_code_naq"],\
            inf_usrjson["flood_routing"]["flood_routing_ihy"],\
            inf_usrjson["air_quality"]["co2_ico2"],\
            inf_usrjson["runoff_sim"]["field_capacity_wilting_isw"],\
            inf_usrjson["weather"]["number_generator_seeds_igmx"],\
            inf_usrjson["model_setup"]["data_dir_idir"],\
            inf_usrjson["management"]["minimum_interval_automow_imw"],\
            inf_usrjson["air_quality"]["o2_function_iox"],\
            inf_usrjson["nutrient_loss"]["denitrification_idnt"],\
            inf_usrjson["geography"]["latitude_source_iazm"],\
            inf_usrjson["management"]["auto_p_ipat"],\
            inf_usrjson["management"]["grazing_mode_ihrd"],\
            inf_usrjson["runoff_sim"]["atecedent_period_iwtb"],\
            inf_usrjson["model_setup"]["real_time_nstp"],\
            inf_usrjson["model_setup"]["output_subareanum_isap"],\
            "0", "0", "0", "0"\
            ))                      
    # Line 3                    
    outfid_cont.writelines(u"%8s%8s%8s%8s%8s%8s%8s%8s%8s%8s\n" %(\
            inf_usrjson["nutrient_loss"]["avg_conc_n_rainfall_rfn"],\
            inf_usrjson["air_quality"]["co2_conc_atom_co2"],\
            inf_usrjson["nutrient_loss"]["no3n_conc_irrig_cqn"],\
            inf_usrjson["management"]["pest_damage_scaling_pstx"],\
            inf_usrjson["weather"]["yrs_max_mon_rainfall_ywi"],\
            inf_usrjson["weather"]["wetdry_prob_bta"],\
            inf_usrjson["weather"]["param_exp_rainfall_dist_expk"],\
            inf_usrjson["runoff_sim"]["channel_capacity_flow_qg"],\
            inf_usrjson["runoff_sim"]["exp_watershed_area_flowrate_qcf"],\
            inf_usrjson["geography"]["average_upland_slope_chso"]\
            ))                                    
    # Line 4                   
    outfid_cont.writelines(u"%8s%8s%8s%8s%8s%8s%8s%8s%8s%8s\n" %(\
            inf_usrjson["geography"]["channel_bottom_woverd_bwd"],\
            inf_usrjson["flood_routing"]["floodplain_over_channel_fcw"],\
            inf_usrjson["flood_routing"]["floodplain_ksat_fpsc"],\
            inf_usrjson["geography"]["max_groundwater_storage_gwso"],\
            inf_usrjson["geography"]["groundwater_resident_rfto"],\
            inf_usrjson["runoff_sim"]["returnflow_ratio_rfpo"],\
            inf_usrjson["runoff_sim"]["ksat_adj_sato"],\
            inf_usrjson["wind_erosion"]["field_length_fl"],\
            inf_usrjson["wind_erosion"]["field_width_fw"],\
            inf_usrjson["wind_erosion"]["field_length_angle_ang"]\
            ))
    # Line 5        
    outfid_cont.writelines(u"%8s%8s%8s%8s%8s%8s%8s%8s%8s%8s\n" %(\
            inf_usrjson["wind_erosion"]["windspeed_distribution_uxp"],\
            inf_usrjson["wind_erosion"]["soil_partical_diameter_diam"],\
            inf_usrjson["wind_erosion"]["wind_erosion_adj_acw"],\
            inf_usrjson["management"]["grazing_limit_gzl0"],\
            inf_usrjson["management"]["cultivation_start_year_rtn0"],\
            inf_usrjson["weather"]["coef_rainfalldiretow_bxct"],\
            inf_usrjson["weather"]["coef_rainfalldirston_byct"],\
            inf_usrjson["flood_routing"]["interval_floodrouting_dthy"],\
            inf_usrjson["flood_routing"]["routing_threshold_vsc_qth"],\
            inf_usrjson["flood_routing"]["vsc_threshold_stnd"]\
            ))      
    # Line 6
    outfid_cont.writelines(u"%8s%8s%8s%8s%8s%8s%8s%8s\n" %(\
            inf_usrjson["water_erosion"]["water_erosion_equation_drv"],\
            inf_usrjson["geography"]["fraction_ponds_pco0"],\
            inf_usrjson["water_erosion"]["usle_c_channel_rcc0"],\
            inf_usrjson["nutrient_loss"]["salt_conc_irrig_cslt"],\
            inf_usrjson["water_erosion"]["msi_input_1"],\
            inf_usrjson["water_erosion"]["msi_input_2"],\
            inf_usrjson["water_erosion"]["msi_input_3"],\
            inf_usrjson["water_erosion"]["msi_input_4"]\
            ))                                                 
    outfid_cont.close()

inf_usrjson = read_json(jsonrun_cont)
write_apexcont(inf_usrjson)
