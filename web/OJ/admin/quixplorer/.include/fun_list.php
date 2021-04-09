<?php
/*------------------------------------------------------------------------------
     The contents of this file are subject to the Mozilla Public License
     Version 1.1 (the "License"); you may not use this file except in
     compliance with the License. You may obtain a copy of the License at
     http://www.mozilla.org/MPL/

     Software distributed under the License is distributed on an "AS IS"
     basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
     License for the specific language governing rights and limitations
     under the License.

     The Original Code is fun_list.php, released on 2003-03-31.

     The Initial Developer of the Original Code is The QuiX project.

     Alternatively, the contents of this file may be used under the terms
     of the GNU General Public License Version 2 or later (the "GPL"), in
     which case the provisions of the GPL are applicable instead of
     those above. If you wish to allow use of your version of this file only
     under the terms of the GPL and not to allow others to use
     your version of this file under the MPL, indicate your decision by
     deleting  the provisions above and replace  them with the notice and
     other provisions required by the GPL.  If you do not delete
     the provisions above, a recipient may use your version of this file
     under either the MPL or the GPL."
------------------------------------------------------------------------------*/
/*------------------------------------------------------------------------------
Author: The QuiX project
	quix@free.fr
	http://www.quix.tk
	http://quixplorer.sourceforge.net

Comment:
	QuiXplorer Version 2.3
	Directory-Listing Functions
	
	Have Fun...
------------------------------------------------------------------------------*/
require_once "./.include/permissions.php";
require_once "./.include/login.php";

//------------------------------------------------------------------------------
// HELPER FUNCTIONS (USED BY MAIN FUNCTION 'list_dir', SEE BOTTOM)
function make_list($_list1, $_list2) {		// make list of files
	$list = array();

	if($GLOBALS["srt"]=="yes") {
		$list1 = $_list1;
		$list2 = $_list2;
	} else {
		$list1 = $_list2;
		$list2 = $_list1;
	}
	
	if(is_array($list1)) {
		foreach ($list1 as $key => $val) {
			$list[$key] = $val;
		}
	}
	
	if(is_array($list2)) {
		foreach ($list2 as $key => $val){
			$list[$key] = $val;
		}
	}
	
	return $list;
}
//------------------------------------------------------------------------------
function make_tables($dir, &$dir_list, &$file_list, &$tot_file_size, &$num_items)
{						// make table of files in dir
	// make tables & place results in reference-variables passed to function
	// also 'return' total filesize & total number of items
	
	$tot_file_size = $num_items = 0;
	
	// Open directory
	$handle = @opendir(get_abs_dir($dir));
	if($handle===false) show_error($dir.": ".$GLOBALS["error_msg"]["opendir"]);
	
	// Read directory
	while(($new_item = readdir($handle))!==false) {
		$abs_new_item = get_abs_item($dir, $new_item);
		
		if(!get_show_item($dir, $new_item)) continue;
		if(!@file_exists($abs_new_item)) show_error($dir.": ".$GLOBALS["error_msg"]["readdir"]);
		
		$new_file_size = filesize($abs_new_item);
		$tot_file_size += $new_file_size;
		$num_items++;
		
		if(get_is_dir($dir, $new_item)) {
			if($GLOBALS["order"]=="mod") {
				$dir_list[$new_item] =
					@filemtime($abs_new_item);
			} else {	// order == "size", "type" or "name"
				$dir_list[$new_item] = $new_item;
			}
		} else {
			if($GLOBALS["order"]=="size") {
				$file_list[$new_item] = $new_file_size;
			} elseif($GLOBALS["order"]=="mod") {
				$file_list[$new_item] =
					@filemtime($abs_new_item);
			} elseif($GLOBALS["order"]=="type") {
				$file_list[$new_item] =
					get_mime_type($dir, $new_item, "type");
			} else {	// order == "name"
				$file_list[$new_item] = $new_item;
			}
		}
	}
	closedir($handle);
	
	
	// sort
	if(is_array($dir_list)) {
		if($GLOBALS["order"]=="mod") {
			if($GLOBALS["srt"]=="yes") arsort($dir_list);
			else asort($dir_list);
		} else {	// order == "size", "type" or "name"
			if($GLOBALS["srt"]=="yes") ksort($dir_list);
			else krsort($dir_list);
		}
	}
	
	// sort
	if(is_array($file_list)) {
		if($GLOBALS["order"]=="mod") {
			if($GLOBALS["srt"]=="yes") arsort($file_list);
			else asort($file_list);
		} elseif($GLOBALS["order"]=="size" || $GLOBALS["order"]=="type") {
			if($GLOBALS["srt"]=="yes") asort($file_list);
			else arsort($file_list);
		} else {	// order == "name"
			if($GLOBALS["srt"]=="yes") ksort($file_list);
			else krsort($file_list);
		}
	}
}
//------------------------------------------------------------------------------
// print table of files
function print_table($dir, $list)
{
	if(!is_array($list)) return;
	foreach ($list as $item => $val) { //while(list($item,) = each($list)){ // php7.2 error:each()
		// link to dir / file
		$abs_item=get_abs_item($dir,$item);
		$target="";
		//$extra="";
		//if(is_link($abs_item)) $extra=" -> ".@readlink($abs_item);
		if(is_dir($abs_item)) {
			$link = make_link("list",get_rel_item($dir, $item),NULL);
		} else { //if(get_is_editable($dir,$item) || get_is_image($dir,$item)) {
//?? CK Hier wird kuenftig immer mit dem download-Link gearbeitet, damit
//?? CK die Leute links klicken koennen
//?? CK			$link = $GLOBALS["home_url"]."/".get_rel_item($dir, $item);
			$link = make_link("download", $dir, $item);
			$target = "_blank";
		} //else $link = "";
		
		echo "<TR class=\"rowdata\"><TD><INPUT TYPE=\"checkbox\" name=\"selitems[]\" value=\"";
		echo htmlspecialchars($item)."\" onclick=\"javascript:Toggle(this);\"></TD>\n";
	// Icon + Link
		echo "<TD nowrap>";
		if (permissions_grant($dir, $item, "read"))
			echo"<A HREF=\"" . $link . "\">";
		//else echo "<A>";
		echo "<span class='".get_mime_type($dir, $item, "img")."'>&nbsp;";
		$s_item=$item;	if(strlen($s_item)>50) $s_item=substr($s_item,0,47)."...";
		echo htmlspecialchars($s_item);
		if (permissions_grant($dir, $item, "read"))
			echo "</A>";
		echo "</TD>\n";	// ...$extra...
	// Size
		echo "<TD>".parse_file_size(get_file_size($dir,$item))."</TD>\n";
	// Type
		echo "<TD>".get_mime_type($dir, $item, "type")."</TD>\n";
	// Modified
		echo "<TD>".parse_file_date(get_file_date($dir,$item))."</TD>\n";
	// Permissions
		echo "<TD>";
		if (permissions_grant($dir, NULL, "change"))
		{
			echo "<A HREF=\"".make_link("chmod",$dir,$item)."\" TITLE=\"";
			echo $GLOBALS["messages"]["permlink"]."\">";
		}
		echo parse_file_type($dir,$item).parse_file_perms(get_file_perms($dir,$item));
		if (permissions_grant($dir, NULL, "change"))
			echo "</A>";
		echo "</TD>\n";
	// Actions
		echo "<TD>\n";
		// EDIT
		if(get_is_editable($dir, $item))
		{
			_print_link("edit", permissions_grant($dir, $item, "change"), $dir, $item);
		}
		// DOWNLOAD
		if(get_is_file($dir,$item))
		{
			_print_link("download", permissions_grant($dir, $item, "read"), $dir, $item);
		}
		echo "</TD></TR>\n";
	}
}
//------------------------------------------------------------------------------
// MAIN FUNCTION
function list_dir($dir)
{
	$dir_up = dirname($dir);
	if($dir_up==".") $dir_up = "";
	
	if(!get_show_item($dir_up,basename($dir))) show_error($dir." : ".$GLOBALS["error_msg"]["accessdir"]);
	
	// make file & dir tables, & get total filesize & number of items
	make_tables($dir, $dir_list, $file_list, $tot_file_size, $num_items);
	
	$s_dir=$dir;		if(strlen($s_dir)>50) $s_dir="...".substr($s_dir,-47);
	show_header(": ".get_rel_item("",$s_dir));
	
	// Javascript functions:
	include "./.include/javascript.php";
	
	// Sorting of items
	$_img = "&nbsp;<span class=\"";
	if($GLOBALS["srt"]=="yes") {
		$_srt = "no";	$_img .= "glyphicon glyphicon-chevron-up\"></span>";
	} else {
		$_srt = "yes";	$_img .= "glyphicon glyphicon-chevron-down\"></span>";
	}
	
	// Toolbar
	echo "<BR>";
	
	// PARENT DIR
	echo "<A style='margin:2px' HREF=\"".make_link("list",$dir_up,NULL)."\" title=\"".$GLOBALS["messages"]["uplink"]."\">";
	echo "<span class='glyphicon glyphicon-arrow-up' style='font-size: 18px;'></span>";
	// echo "</A>";
	// HOME DIR
	// echo "<A style='margin:2px' HREF=\"".make_link("list",NULL,NULL)."\" title=\"".$GLOBALS["messages"]["homelink"]."\">";
	// echo "<span class='glyphicon glyphicon-home' style='font-size: 18px;'></span>";
	// echo "</A>";
	// RELOAD
	echo "<A style='margin:2px;' HREF=\"javascript:location.reload();\" title=\"".$GLOBALS["messages"]["reloadlink"]."\">";
	echo "<span class='glyphicon glyphicon-refresh' style='font-size: 18px;'></span>";
	echo "</A>";
	// SEARCH
	echo "<A style='margin:2px' HREF=\"".make_link("search",$dir,NULL)."\" title=\"".$GLOBALS["messages"]["searchlink"]."\">";
	echo "<span class='glyphicon glyphicon-search' style='font-size: 18px;'></span>";
	echo "</A>";
	
	//echo "::";

	// print the edit buttons
	_print_edit_buttons($dir);
	
	// ADMIN & LOGOUT
	if(login_ok())
	{
		echo "<TD>::</TD>";
		// ADMIN
		_print_link("admin", 
				permissions_grant(NULL, NULL, "admin")
				|| permissions_grant(NULL, NULL, "password"),
				$dir, NULL);
		// LOGOUT
		_print_link("logout", true, $dir, NULL);
	}
	
	// Create File / Dir
	if (permissions_grant($dir, NULL, "create"))
	{
		echo "<FORM class='form-inline' style='float:right;' action=\"".make_link("mkitem",$dir,NULL)."\" method=\"post\">\n";
		echo "<label><span class='glyphicon glyphicon-file'></span>".$GLOBALS["mimes"]["file"].":&nbsp;</label>";
		echo "<input type='hidden' name=\"mktype\" value=\"file\">\n";
		//echo "<SELECT class='selectpicker' data-width='100' name=\"mktype\"><option value=\"file\">".$GLOBALS["mimes"]["file"]."</option>";
		//echo "<option value=\"dir\">".$GLOBALS["mimes"]["dir"]."</option></SELECT>\n";
		echo "<INPUT class='form-control' name=\"mkname\" type=\"text\" size=\"15\">&nbsp;";
		echo "<INPUT class='btn btn-default' type=\"submit\" value=\"".$GLOBALS["messages"]["btncreate"];
		echo "\"></FORM>\n";
	}
		
	// End Toolbar
	
	
	// Begin Table + Form for checkboxes
	echo"<TABLE WIDTH=\"95%\" class='table table-hover' style='white-space: nowrap;word-wrap: break-word; margin-top: 20px;'><FORM name=\"selform\" method=\"POST\" action=\"".make_link("post",$dir,NULL)."\">\n";
	echo "<INPUT type=\"hidden\" name=\"do_action\"><INPUT type=\"hidden\" name=\"first\" value=\"y\">\n";
	
	// Table Header
	echo "<TR><TD WIDTH=\"2%\" class=\"header\">\n";
	echo "<INPUT TYPE=\"checkbox\" name=\"toggleAllC\" onclick=\"javascript:ToggleAll(this);\"></TD>\n";
	echo "<TD WIDTH=\"44%\" class=\"header\"><B>\n";
	if($GLOBALS["order"]=="name") $new_srt = $_srt;	else $new_srt = "yes";
	echo "<A href=\"".make_link("list",$dir,NULL,"name",$new_srt)."\">".$GLOBALS["messages"]["nameheader"];
	if($GLOBALS["order"]=="name") echo $_img;
	echo "</A></B></TD>\n<TD WIDTH=\"10%\" class=\"header\"><B>";
	if($GLOBALS["order"]=="size") $new_srt = $_srt;	else $new_srt = "yes";
	echo "<A href=\"".make_link("list",$dir,NULL,"size",$new_srt)."\">".$GLOBALS["messages"]["sizeheader"];
	if($GLOBALS["order"]=="size") echo $_img;
	echo "</A></B></TD>\n<TD WIDTH=\"16%\" class=\"header\"><B>";
	if($GLOBALS["order"]=="type") $new_srt = $_srt;	else $new_srt = "yes";
	echo "<A href=\"".make_link("list",$dir,NULL,"type",$new_srt)."\">".$GLOBALS["messages"]["typeheader"];
	if($GLOBALS["order"]=="type") echo $_img;
	echo "</A></B></TD>\n<TD WIDTH=\"14%\" class=\"header\"><B>";
	if($GLOBALS["order"]=="mod") $new_srt = $_srt;	else $new_srt = "yes";
	echo "<A href=\"".make_link("list",$dir,NULL,"mod",$new_srt)."\">".$GLOBALS["messages"]["modifheader"];
	if($GLOBALS["order"]=="mod") echo $_img;
	echo "</A></B></TD><TD WIDTH=\"8%\" class=\"header\"><B>".$GLOBALS["messages"]["permheader"]."</B>\n";
	echo "</TD><TD WIDTH=\"6%\" class=\"header\"><B>".$GLOBALS["messages"]["actionheader"]."</B></TD></TR>\n";
		
	// make & print Table using lists
	print_table($dir, make_list($dir_list, $file_list));

	// print number of items & total filesize
	echo "<TR>\n<TD class=\"header\"></TD>";
	echo "<TD class=\"header\">".$num_items." ".$GLOBALS["messages"]["miscitems"]." (";
	if(function_exists("disk_free_space")) {
		$free=parse_file_size(disk_free_space(get_abs_dir($dir)));
	} elseif(function_exists("diskfreespace")) {
		$free=parse_file_size(diskfreespace(get_abs_dir($dir)));
	} else $free="?";
	// echo "Total: ".parse_file_size(disk_total_space(get_abs_dir($dir))).", ";
	echo $GLOBALS["messages"]["miscfree"].": ".$free.")</TD>\n";
	echo "<TD class=\"header\">".parse_file_size($tot_file_size)."</TD>\n";
	for($i=0;$i<4;++$i) echo"<TD class=\"header\"></TD>";
	echo "</TR>\n</FORM></TABLE>\n";
	
?><script language="JavaScript1.2" type="text/javascript">
//<!--
	// Uncheck all items (to avoid problems with new items)
	var ml = document.selform;
	var len = ml.elements.length;
	for(var i=0; i<len; ++i) {
		var e = ml.elements[i];
		if(e.name == "selitems[]" && e.checked == true) {
			e.checked=false;
		}
	}
// -->
</script><?php
}
//------------------------------------------------------------------------------
function _print_edit_buttons ($dir)
{
	// for the copy button the user must have create and read rights
	_print_link("copy", permissions_grant_all($dir, NULL, array("create", "read")), $dir, NULL);
	//_print_link("move", permissions_grant($dir, NULL, "change"), $dir, NULL);
	_print_link("delete", permissions_grant($dir, NULL, "delete"), $dir, NULL);
	_print_link("upload", permissions_grant($dir, NULL, "create") && get_cfg_var("file_uploads"), $dir, NULL);
	_print_link("archive", 
		permissions_grant_all($dir, NULL, array("create", "read"))
			&& ($GLOBALS["zip"] || $GLOBALS["tar"] || $GLOBALS["tgz"]),
		$dir, NULL);
	_print_link("Random-data", true, $dir, NULL);
}

/**
  print out an button link in the toolbar.

  if $allow is set, make this button active and work, otherwise print
  an inactive button.
*/
function _print_link ($function, $allow, $dir, $item)
{
	// the list of all available button and the coresponding data
	$functions = array(
			"copy" => array("jfunction" => "javascript:Copy();",
					"icon" => "glyphicon glyphicon-tags",
					"imagedisabled" => "_img/_copy_.gif",
					"message" => $GLOBALS["messages"]["copylink"],
					"target" => ""),
			"move" => array("jfunction" => "javascript:Move();",
					"icon" => "glyphicon glyphicon-move",
					"imagedisabled" => "_img/_move_.gif",
					"message" => $GLOBALS["messages"]["movelink"],
					"target" => ""),
			"delete" => array("jfunction" => "javascript:Delete();",
					"icon" => "glyphicon glyphicon-trash",
					"imagedisabled" => "_img/_delete_.gif",
					"message" => $GLOBALS["messages"]["dellink"],
					"target" => ""),
			"upload" => array("jfunction" => make_link("upload", $dir, NULL),
					"icon" => "glyphicon glyphicon-upload",
					"imagedisabled" => "_img/_upload_.gif",
					"message" => $GLOBALS["messages"]["uploadlink"],
					"target" => ""),
			"archive" => array("jfunction" => "javascript:Archive();",
					"icon" => "glyphicon glyphicon-compressed",
					"message" => $GLOBALS["messages"]["comprlink"],
					"target" => ""),
			"admin" => array("jfunction" => make_link("admin", $dir, NULL),
					"icon" => "glyphicon glyphicon-cog",
					"message" => $GLOBALS["messages"]["adminlink"]),
			"logout" => array("jfunction" => make_link("logout", NULL, NULL),
					"icon" => "glyphicon glyphicon-log-out",
					"imagedisabled" => "_img/_logout_.gif",
					"message" => $GLOBALS["messages"]["logoutlink"]),
			"edit" => array("jfunction" => make_link("edit", $dir, $item),
					"icon" => "glyphicon glyphicon-edit",
					"imagedisabled" => "_img/_edit_.gif",
					"message" => $GLOBALS["messages"]["editlink"],
					"target" => ""),
			"download" => array("jfunction" => make_link("download", $dir, $item),
					"icon" => "glyphicon glyphicon-download",
					"imagedisabled" => "_img/_download_.gif",
					"message" => $GLOBALS["messages"]["downlink"],
					"target" => ""),
			"Random-data" => array("jfunction" => "https://muzea-demo.github.io/random-data/",
					"icon" => "glyphicon glyphicon-random",
					"message" => $GLOBALS["messages"]["Random-data"],
					"target" => "_blank"),
			);
	
	// determine the functio nof this button and it's data
	$values = $functions[$function];	
	// make an active link if the access is allowed
	if ($allow)
	{
		echo "<A style='margin:5px;' HREF=\"" . $values["jfunction"] . "\" title='".$values["message"]."' target='".$values["target"]."'>";
		echo "<span class='".$values['icon']."' style='font-size: 18px;'></span>";
		echo "</A>";
		return;
	}

	if (!isset($values["imagedisabled"]))
		return;

	// make an inactive link if the access is forbidden
		echo "<span class='".$values['icon']."'></span>";
		echo "\n";
}

?>
