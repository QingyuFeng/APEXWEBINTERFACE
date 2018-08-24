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


 //     const UPLOADEDFILES =  "c:/sw/temp/";
      define("UPLOADEDFILES", "usrrun/");

         function save_document_info($fileInfo){

       $doc = new DOMDocument('1.0');

   $xmlfile = UPLOADEDFILES."docinfo.xml";



   if(is_file($xmlfile)){
      $doc->load($xmlfile);
      $workflowElements = $doc->getElementsByTagName("workflow");
      $root = $workflowElements->item(0);

            $statistics = $root->getElementsByTagName("statistics")->item(0);
      $total =  $root->getElementsByTagName("fileInfo")->length;
      $statistics->setAttribute("total",$total);

   } else{


          $root = $doc->createElement('workflow');
   $doc->appendChild($root);

   $statistics = $doc->createElement("statistics");
   $statistics->setAttribute("total", "1");
   $statistics->setAttribute("approved", "0");
   $root->appendChild($statistics);

   }

      $filename = $fileInfo['name'];
   $filetype = $fileInfo['type'];
   $filesize = $fileInfo['size'];

   $fileInfo = $doc->createElement("fileInfo");

   $fileInfo->setAttribute("status", "pending");
   $fileInfo->setAttribute("submittedBy", $_SESSION["username"]);

   $approvedBy = $doc->createElement("approvedBy");

   $fileName = $doc->createElement("fileName");
   $fileNameText = $doc->createTextNode($filename);
   $fileName->appendChild($fileNameText);

   $location = $doc->createElement("location");
   $locationText = $doc->createTextNode(UPLOADEDFILES);
   $location->appendChild($locationText);

   $type = $doc->createElement("fileType");
   $typeText = $doc->createTextNode($filetype);
   $type->appendChild($typeText);

   $size = $doc->createElement("size");
   $sizeText = $doc->createTextNode($filesize);
   $size->appendChild($sizeText);

   $fileInfo->appendChild($approvedBy);
   $fileInfo->appendChild($fileName);
   $fileInfo->appendChild($location);
   $fileInfo->appendChild($type);
   $fileInfo->appendChild($size);

   $root->appendChild($fileInfo);

  $doc->save($xmlfile);

   }

   function save_document_info_json_single($file){

   $jsonFile = UPLOADEDFILES."docinfo.json";

   $workflow["fileInfo"] = array();
   $workflow["statistics"]["total"] = 1;
   $workflow["statistics"]["approved"] = 0;

   $filename = $file['name'];
   $filetype = $file['type'];
   $filesize = $file['size'];

   $fileInfo["status"] = "pending";
   $fileInfo["submittedBy"] = $_SESSION["username"];
   $fileInfo["approvedBy"] = "";

   $fileInfo["fileName"] = $filename;
   $fileInfo["location"] = UPLOADEDFILES;
   $fileInfo["fileType"] = $filetype;
   $fileInfo["size"] = $filesize;


   array_push($workflow["fileInfo"], $fileInfo);

   $jsonText = json_encode($workflow);
   file_put_contents($jsonFile, $jsonText);
}


function save_document_info_json($file){

   $jsonfile = UPLOADEDFILES."docinfo.json";

   if(is_file($jsonfile)){
      $jsonText = file_get_contents($jsonfile);
      $workflow = json_decode($jsonText, true);
      $statistics = $workflow["statistics"];
   } else{
      $jsonText = '{"statistics": {"total": 0, "approved": 0}, "fileInfo":[]}';
      $workflow = json_decode($jsonText, true);
      $workflow["statistics"]["approved"] = 0;
   }

   $filename = $file['name'];
   $filetype = $file['type'];
   $filesize = $file['size'];

   $fileInfo["status"] = "pending";
   $fileInfo["submittedBy"] = "username";
   $fileInfo["approvedBy"] = "";

   $fileInfo["fileName"] = $filename;
   $fileInfo["location"] = UPLOADEDFILES;
   $fileInfo["fileType"] = $filetype;
   $fileInfo["size"] = $filesize;

   array_push($workflow["fileInfo"], $fileInfo);

   $total =  count($workflow["fileInfo"]);
   $workflow["statistics"]["total"] = $total;

   file_put_contents($jsonfile, json_encode($workflow));

}

   function display_files(){

     $workflow = json_decode(file_get_contents(UPLOADEDFILES."docinfo.json"), true);

     echo "<table width='100%'>";

     $files = $workflow["fileInfo"];

             echo "<tr><th>File Name</th>";
        echo "<th>Submitted By</th><th>Size</th>";
        echo "<th>Status</th></tr>";

     for ($i = 0; $i < count($workflow["fileInfo"]); $i++){
     //foreach($files as $thisFile){
                        $thisFile = $workflow["fileInfo"][$i];
        echo "<tr>";
        echo "<td>".$thisFile["fileName"]."</td>";
        echo "<td>".$thisFile["submittedBy"]."</td>";
        echo "<td>".$thisFile["size"]."</td>";
        echo "<td>".$thisFile["status"]."<td>";
        echo "</tr>";

     }

   }

?>