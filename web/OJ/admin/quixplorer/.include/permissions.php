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

     The Original Code is fun_admin.php, released on 2003-03-31.

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
	http://quixplorer.sourceforge.net

Comment:
	Permission administration	
	
------------------------------------------------------------------------------*/

/**
	This functions creates the list of assignments of
	permission values and it's logical description (permission name).

	It returns an array with the permission names and it's values
*/
function permissions_get ()
{
	static $perms = array (
			"read"		=> 0x0001,
			"create"	=> 0x0002,
			"change"	=> 0x0004,
			"delete"	=> 0x0008,
			"password"	=> 0x0040,
			"admin"		=> 0x8000,	// admin
			);
	return $perms;
}


/**
  The permission engine.

  This function decides wether a specific function is allowed or not
  depending the rights of the current user.

  @param $dir	Directory in which  the action should happen. If this parameter is
  		NULL the engine relys on the global permissions of the user.
  		
  @param $file	File on which the action should happen, if this parameter is NULL
  		the permission engine relys on the permission of the directory.

  @param $action
  		One ore more action of the action set (see permissions_get) which sould
  		be exectuted.
		More actions are seperated by a &.
		
		Example:

		"read&write&password" grants only if user has all three permissions

  @return	true if the action is granted, false otherwise

  @remarks	Until now the permission engine does not support directory or
  		file based actions, so only the global actions are treated. The paramers
		$dir and $file are ignored. This is for later use. However, if possible,
		provide the $dir and $file parameters so the code does not have to
		be chaned if the permission engine will support this features in
		the future.
  */
function permissions_grant ($dir, $file, $action)
{
	// determine if a user has logged in
	$user = $GLOBALS['__SESSION']["s_user"];

	// if no user is logged in, use the global permissions
	if (!isset($user))
		return permissions_global($dir, $file, $action);

	// check if the user currently logged in has the given rights
	return permissions_grant_user($user, $dir, $file, $action);
}

/**
  This function return the global permission settings.

  The global permission settings forbid any access as long
  as the require login setting is set to true.

  Otherwise, the global permission settings allow that function
  defined in the configuration variable 'global_permissions'
  in conf.php
  */
function permissions_global ($dir, $file, $action)
{
	// check if login is required
	if ($GLOBALS['require_login'] == true)
		return false;

	// if no login is required, get the global permissions
	$permissions = $GLOBALS['global_permissions'];

	// if the global permissions are undefined, nothing
	// is allowed
	if (! isset($permissions))
		return false;

	$permdefs = permissions_get();

	// check if this action is allowed by the global permissions
	return $permissions & $permdefs[$action];
}

function permissions_grant_all ($dir, $file, $actions)
{
	foreach ($actions as $action)
	{
		if (!permissions_grant($dir, $file, $action))
			return false;
	}

	return true;
}

function permissions_grant_user ($user, $dir, $file, $action)
{
	// determine the user permissions of the given user
	$permissions = user_get_permissions($user);

	// determine the permission definitions
	$permdefs = permissions_get();

	// the user with the name "admin" always has admin rights
	if ($action == "admin" && $user == "admin")
		return true;

	// check if the action is allowed
	return ($permdefs[$action] & $permissions) != 0;
}

?>
