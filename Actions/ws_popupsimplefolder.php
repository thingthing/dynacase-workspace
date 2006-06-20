<?php
/**
 * Context menu view in folder list for a document
 *
 * @author Anakeen 2006
 * @version $Id: ws_popupsimplefolder.php,v 1.1 2006/06/15 16:01:42 eric Exp $
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package FREEDOM
 * @subpackage 
 */
 /**
 */


include_once("FDL/popupdoc.php");
include_once("FDL/popupdocdetail.php");
// -----------------------------------
function ws_popupsimplefolder(&$action) {
  // -----------------------------------
  // define accessibility
  $docid = GetHttpVars("id");
  $abstract = (GetHttpVars("abstract",'N') == "Y");
  $zone = GetHttpVars("zone"); // special zone

  $dbaccess = $action->GetParam("FREEDOM_DB");
  $doc = new_Doc($dbaccess, $docid);

  //  if ($doc->doctype=="C") return; // not for familly

  

  $tsubmenu=array();

  // -------------------- Menu menu ------------------

  $surl=$action->getParam("CORE_STANDURL");

  $tlink=array(
	       "latest"=>array("descr"=>_("View latest"),
			       "url"=>"$surl&app=FDL&action=FDL_CARD&latest=Y&id=$docid",
			       "confirm"=>"false",
			       "tconfirm"=>"",
			       "target"=>"latest",
			       "visibility"=>POPUP_INVISIBLE,
			       "submenu"=>"",
			       "barmenu"=>"false"),
	       "editdoc"=>array( "descr"=>_("Edit"),
				 "url"=>"$surl&app=GENERIC&action=GENERIC_EDIT&rzone=$zone&id=$docid",
				 "confirm"=>"false",
				 "tconfirm"=>"",
				 "target"=>"_self",
				 "visibility"=>POPUP_ACTIVE,
				 "submenu"=>"",
				 "barmenu"=>"false"),
	       "delete"=>array( "descr"=>_("Delete the folder and its containt"),
				"url"=>"$surl&app=GENERIC&action=GENERIC_DEL&recursive=yes&id=$docid",
				"confirm"=>"true",
				"tconfirm"=>sprintf(_("Sure delete %s and its containt ?"),$doc->title),
				"target"=>"_self",
				"visibility"=>POPUP_INACTIVE,
				"submenu"=>"",
				"barmenu"=>"false"),
	       "postit"=>array( "descr"=>_("Add a note"),
				"jsfunction"=>"postit('$surl&app=GENERIC&action=GENERIC_EDIT&classid=27&pit_title=&pit_idadoc=$docid',50,50,300,200)",
			       "confirm"=>"false",
			       "tconfirm"=>"",
			       "target"=>"",
			       "visibility"=>POPUP_ACTIVE,
			       "submenu"=>"",
			       "barmenu"=>"false"),
	       "tobasket"=>array( "descr"=>_("Add to basket"),
				  "jsfunction"=>"shortcutToFld(event,$docid,'".$action->getParam("FREEDOM_IDBASKET")."')",
				  "confirm"=>"false",
				  "tconfirm"=>"",
				  "target"=>"",
				  "visibility"=>POPUP_ACTIVE,
				  "submenu"=>"",
				  "barmenu"=>"false"),
	       "restore"=>array( "descr"=>_("restore"),
				 "jsfunction"=>"restoreDoc(event,$docid)",
				 "tconfirm"=>"",
				 "confirm"=>"false",
				 "target"=>"",
				 "visibility"=>POPUP_INVISIBLE,
				 "submenu"=>"",
				 "barmenu"=>"false"));
  changeMenuVisibility($action,$tlink,$doc);


  if ($doc->doctype=='Z') {
    $tlink["restore"]["visibility"]=POPUP_ACTIVE;
    $tlink["duplicate"]["visibility"]=POPUP_INVISIBLE;
  }
  
  
         
  if  (($doc->doctype != 'S') && (preg_match("/doctype='Z'/",$doc->getValue("se_sqlselect")))) {
    $tlink["trash"]=array("descr"=>_("Empty trash"),
			  "jsfunction"=>"emptytrash(event)",
			  "confirm"=>"false",
			  "tconfirm"=>"",
			  "target"=>"nresume",
			  "visibility"=>POPUP_ACTIVE,
			  "submenu"=>"",
			  "barmenu"=>"false");
  }

  //  addFamilyPopup($tlink,$doc);
  popupdoc($action,$tlink,$tsubmenu);
}


?>