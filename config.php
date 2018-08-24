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





    // Start session
    session_start();

    //set timezone
    date_default_timezone_set('America/Indiana/Indianapolis');

    // PhpFunctions folder
    defined("FD_PHPFUN")
    or define("FD_PHPFUN", realpath(dirname(__FILE__) . '/php'));

    // User Run folder
    defined("FD_USRRUN")
    or define("FD_USRRUN", realpath(dirname(__FILE__) . '/usrrun'));
       
    // Json file folder
    defined("FD_JSON")
    or define("FD_JSON", realpath(dirname(__FILE__) . '/json'));
        
    // Climate station searcher folder
    defined("FD_CLINEAR")
    or define("FD_CLINEAR", realpath(dirname(__FILE__) . '/clinear'));

    // Define apexdatabase default folder
    defined("FD_APEXDB")
    or define("FD_APEXDB", realpath(dirname(__FILE__) . '/apexdb'));

    // Python scripts folder
    defined("FD_PY")
    or define("FD_PY", realpath(dirname(__FILE__) . '/py'));



    // Define apex exe default folder
    defined("APEXEXE")
    or define("APEXEXE", 'APEX1501_64R.exe');

    // Define default json project file
    defined("JS_DFTPROJ")
    or define("JS_DFTPROJ", realpath(dirname(__FILE__) . '/json/default_projin.json'));
        
    
    // Get database information
    require(FD_PHPFUN."/database.php");
    
    // Include files
    require(FD_PHPFUN.'/classuilist.php');
    require(FD_PHPFUN.'/constants.php');
    require(FD_PHPFUN.'/classmanfuns.php');
    require(FD_PHPFUN.'/listoptions.php');
    require(FD_PHPFUN.'/classrunapex.php');
    require(FD_PHPFUN.'/updatejson.php');

    
    // Get common classes
    $uilist = new ui_list();
    $managefuns = new managementfuns();
    $runapex = new runapex();
    $jsonupdate = new jsonupdater();





?>

