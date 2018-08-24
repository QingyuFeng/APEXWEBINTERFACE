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



class managementfuns
{
    
    public function gettemplatevalue($manid, $db)
    {
        $stmt = $db->prepare("SELECT * FROM "
                . "apexmgtdetailsdefault "
                . "WHERE "
                . "operationid= "
                . ":operationid");
        $stmt->execute(array(
            "operationid"=>$manid
        ));
        $row = $stmt->fetchall(PDO::FETCH_ASSOC);
        return $row;
    }
    
    public function getmgttmpnm($tmpmid, $db)
    {
        $stmt=$db->prepare("SELECT opsschdlname FROM "
                . "apexmgtlistdefault "
                . "WHERE "
                . "opsschdlseriesid= "
                . ":opsschdlseriesid");
        $stmt->execute(array(
            ":opsschdlseriesid" => $tmpmid
        ));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    }
    
    public function listUserMan($usrmanlist, $default)
    {
        $usrmanlst_key = array_keys($usrmanlist);
        for ($rid=0; $rid<count($usrmanlist); $rid++)
        {
            $mankey = $usrmanlst_key[$rid];
            $manname = $usrmanlist[$mankey];
           
            if ($default==$mankey)
            {print("<option value=".$mankey." selected>" . $manname . "</option>");}
            else
            {print("<option value=".$mankey.">" . $manname . "</option>");}
        }
    }
    
    public function updateProjectMan($mankey, $manarray)
    {
        $linekey = array_keys($manarray);
        for ($rowid=0; $rowid<count($manarray); $rowid++)
        {
            $rowkeys = array_keys($manarray[$linekey[$rowid]]);
            foreach ($rowkeys as $rk)
            {
                $_SESSION["PROJDATA"]["management"][$mankey]["mgtarray"][$rowid][$rk] = $manarray[$linekey[$rowid]][$rk];
            }
        }
    }
  
    public function getdftmgtlist($db)
    {
        $stmt=$db->prepare("SELECT * FROM "
                . "apexmgtlistdefault "
                . "ORDER BY "
                . "opsschdlname"
                );
        $stmt->execute();
        $row = $stmt->fetchall();
        return $row;
    }
    
    public function listdftmgtlist($db, $default)
    {
        $dftmgtlist=$this->getdftmgtlist($db);
        for ($rid=0; $rid<count($dftmgtlist);$rid++)
        {
            if ($dftmgtlist[$rid]["opsschdlseriesid"] == $default)
            {print("<option  value = ". $dftmgtlist[$rid]["opsschdlseriesid"] ." selected>" . $dftmgtlist[$rid]["opsschdlname"] . "</option>");}
            else
            {print("<option  value = ". $dftmgtlist[$rid]["opsschdlseriesid"] .">" . $dftmgtlist[$rid]["opsschdlname"] . "</option>");}
        }
    }
    
    public function listtillagecate($db, $default)
    {
        try
        {
            $stmt=$db->prepare("SELECT DISTINCT ON "
                    . "(tillcategories)"
                    . "  tillcategories, tillcategoryid "
                    . " FROM "
                    . "apexdbtillage "
                    . "ORDER BY "
                    . "tillcategories");
            $stmt->execute();
            $rows = $stmt->fetchall();
        }catch(PDOException $e) {$error[] = $e->getMessage();}
        
        if (!isset($error))
        {
            for ($rid=0; $rid<count($rows); $rid++)
            {
                $tillcateg = $rows[$rid]["tillcategories"];
                $tillcategid = $rows[$rid]["tillcategoryid"];
                if ($default != null) 
                {
                if (strcmp(trim($default),trim($tillcateg)) == 0){
                    print("<option  value = ". $tillcategid ."  selected>" . $tillcateg . "</option>");}
                else {print("<option  value = ". $tillcategid ."  >" . $tillcateg . "</option>");}
                }
                else 
                {
                if ($Row == 0){print("<option  value = ". $tillcategid ."  selected>" . $tillcateg . "</option>");}
                else {print("<option  value = ". $tillcategid ."  >" . $tillcateg . "</option>");}
                }
            }
        }
        else
        {
            foreach($error as $error){
                echo '<option>'.$error.'</option>';}
        }        
    }
    
    
     public function listtillagename($db, $tillcategory, $default)
    {
        try
        {
            $stmt=$db->prepare("SELECT "
                    . "tilldbnumber, tillshowname "
                    . "FROM "
                    . "apexdbtillage "
                    . "WHERE "
                    . "tillcategories= "
                    . ":tillcategories "
                    . "ORDER BY "
                    . "tillshowname");
            $stmt->execute(array(
                "tillcategories"=> $tillcategory
            ));
            $rows = $stmt->fetchall();
        }catch(PDOException $e) {$error[] = $e->getMessage();}
        
        if (!isset($error))
        {
            for ($rid=0; $rid<count($rows); $rid++)
            {
                $tillname = $rows[$rid]["tillshowname"];
                $tillid = $rows[$rid]["tilldbnumber"];
                if ($default != null) 
                {
                    if (strcmp(trim($default),trim($tillid)) == 0){
                        print("<option  value = ". $tillid ."  selected>" . $tillname . "</option>");}
                    else {print("<option  value = ". $tillid ."  >" . $tillname . "</option>");}
                }
                else 
                {
                    if ($Row == 0){print("<option  value = ". $tillid ."  selected>" . $tillname . "</option>");}
                    else {print("<option  value = ". $tillid ."  >" . $tillname . "</option>");}
                }
            }
        }
        else
        {
            foreach($error as $error){
                echo '<option>'.$error.'</option>';}
        }
        
    }
    
    public function listcropnm($db, $default)
    {
        try
        {
            $stmt=$db->prepare("SELECT "
                    . "cropid, cropshowname "
                    . "FROM "
                    . "apexdbcrop");
            $stmt->execute();
            $rows = $stmt->fetchall();
        }
        catch(PDOException $e) {$error[] = $e->getMessage();}
        
        if (!isset($error))
        {
            for ($rid=0; $rid<count($rows); $rid++)
            {
                $cropname = $rows[$rid]["cropshowname"];
                $cropid = $rows[$rid]["cropid"];
                if ($default != null) 
                {
                    if (strcmp(trim($default),trim($cropid)) == 0){
                        print("<option  value = ". $cropid ."  selected>" . $cropname . "</option>");}
                    else {print("<option  value = ". $cropid ."  >" . $cropname . "</option>");}
                }
                else 
                {
                    if ($Row == 0){print("<option  value = ". $cropid ."  selected>" . $cropname . "</option>");}
                    else {print("<option  value = ". $cropid ."  >" . $cropname . "</option>");}
                }
            }
        }
        else
        {
            foreach($error as $error){
                echo '<option>'.$error.'</option>';}
        }
        
    }

    public function listfertnm($db, $default)
    {
        try
        {
            $stmt=$db->prepare("SELECT "
                    . "fertilizerid, fertdbname "
                    . "FROM "
                    . "apexdbfert");
            $stmt->execute();
            $rows = $stmt->fetchall();
        }
        catch(PDOException $e) {$error[] = $e->getMessage();}
        
        if (!isset($error))
        {
            for ($rid=0; $rid<count($rows); $rid++)
            {
                $fertname = $rows[$rid]["fertdbname"];
                $fertid = $rows[$rid]["fertilizerid"];
                if ($default != null) 
                {
                    if (strcmp(trim($default),trim($fertid)) == 0){
                        print("<option  value = ". $fertid ."  selected>" . $fertname . "</option>");}
                    else {print("<option  value = ". $fertid ."  >" . $fertname . "</option>");}
                }
                else 
                {
                    if ($Row == 0){print("<option  value = ". $fertid ."  selected>" . $fertname . "</option>");}
                    else {print("<option  value = ". $fertid ."  >" . $fertname . "</option>");}
                }
            }
        }
        else
        {
            foreach($error as $error){
                echo '<option>'.$error.'</option>';}
        }
        
    }
    
    public function listpestnm($db, $default)
    {
        try
        {
            $stmt=$db->prepare("SELECT "
                    . "pestid, pestname "
                    . "FROM "
                    . "apexdbpesticide");
            $stmt->execute();
            $rows = $stmt->fetchall();
        }
        catch(PDOException $e) {$error[] = $e->getMessage();}
        
        if (!isset($error))
        {
            for ($rid=0; $rid<count($rows); $rid++)
            {
                $pestname = $rows[$rid]["pestname"];
                $pestid = $rows[$rid]["pestid"];
                if ($default != null) 
                {
                    if (strcmp(trim($default),trim($pestid)) == 0){
                        print("<option  value = ". $pestid ."  selected>" . $pestname . "</option>");}
                    else {print("<option  value = ". $pestid ."  >" . $pestname . "</option>");}
                }
                else 
                {
                    if ($Row == 0){print("<option  value = ". $pestid ."  selected>" . $pestname . "</option>");}
                    else {print("<option  value = ". $pestid ."  >" . $pestname . "</option>");}
                }
            }
        }
        else
        {
            foreach($error as $error){
                echo '<option>'.$error.'</option>';}
        }
        
    }    
    
    public function gettillcatnm($db, $tillcatid)
    {
        $stmt=$db->prepare("SELECT DISTINCT ON "
                . "(tillcategories)"
                . "  tillcategories "
                . " FROM "
                . "apexdbtillage "
                . "WHERE "
                . "tillcategoryid= "
                . ":tillcategoryid");
        $stmt->execute(array(
            ":tillcategoryid" => $tillcatid
        ));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
        
        
    }
    
    public function sortMgtbyDate($mgtarrayusr, $edtrowid)
    {
        echo "Sorting mgt by date<br>";
        // To sort by date, I need two arrays
        // 1. to store the new order with date
        // 2. to store the sorted array.
        // The sorted array will be updated 
        // based on the new order array.
        $sortingDate = array();
        
        for ($rowid=0; $rowid<count($mgtarrayusr); $rowid++)
        {
            $sortingDate[$rowid]["jx1_year"] = $mgtarrayusr[$rowid]["jx1_year"];
            $sortingDate[$rowid]["jx2_month"] = $mgtarrayusr[$rowid]["jx2_month"];
            $sortingDate[$rowid]["jx3_day"] = $mgtarrayusr[$rowid]["jx3_day"];
            $sortingDate[$rowid]["oldorder"]=$rowid;
            $year = 2000+intval($mgtarrayusr[$rowid]["jx1_year"]);
            $sortingDate[$rowid]["date"]=date("Y-m-d", strtotime(
                    $year."-"
                    . "".$mgtarrayusr[$rowid]["jx2_month"]."-"
                    . "".$mgtarrayusr[$rowid]["jx3_day"]));
            if($rowid ==$edtrowid)
            {$sortingDate[$rowid]["editrowid"] = "yes";}
            else
            {$sortingDate[$rowid]["editrowid"] = "no";}
        }
        // Calling a sorting function to sort the ordering date
        $this->array_sort_by_column($sortingDate, 'date');
        
        // Update mgt array based on this order
        for ($rowid2=0; $rowid2<count($sortingDate); $rowid2++)
        {
            $oldid = $sortingDate[$rowid2]["oldorder"];
            $newmgtarray[$rowid2] = $mgtarrayusr[$oldid];
            if(strcmp($sortingDate[$rowid2]["editrowid"], "yes")==0)
            {$newrowid = $rowid2;}
        }
        
        $result["newarray"] = $newmgtarray;
        $result["newrowid"] = $newrowid;
 
        return $result;
        
    } 
    
    function array_sort_by_column(&$array, $column, $direction = SORT_ASC) 
    {
        $reference_array = array();

        foreach($array as $key => $row) {
            $reference_array[$key] = $row[$column];
        }

        array_multisort($reference_array, $direction, $array);
    }
   
    
    
}













?>