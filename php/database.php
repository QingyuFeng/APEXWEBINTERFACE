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

    //database credentials
    define('DBHOST','localhost');
    define('DBUSER','postgres');
    define('DBPASS','nserl');
    define('DBNAME','apexwebinput');

    // Get the database connection
    try {

        //create PDO connection
        $db = new PDO("pgsql:dbname=".DBNAME.";host=".DBHOST, DBUSER, DBPASS);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    } catch(PDOException $e) {
            //show error
        echo '<p class="bg-danger">'.$e->getMessage().'</p>';
        exit;
    }

    if (!($Connection = pg_connect("host=localhost dbname=apexwebinput user=postgres")))
    {
        print("Could not establish connection.<BR>\n");
        exit;
    }   
    else
    {$Connection = pg_connect("host=localhost dbname=apexwebinput user=postgres");}
    
?>