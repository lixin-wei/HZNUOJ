----------------------------------------------------------------------------------------------------
QuiXplorer 2.4.1 - README
----------------------------------------------------------------------------------------------------
----------------------------------------------------------------------------------------------------
Usage:
	1. Download the latest version of QuiXplorer.
	2. Unzip it to a folder on your website. (e.g. /home/you/htdocs/quixplorer)
	   (you may want to protect this folder using .htaccess)
	3. Open the file ".config/conf.php",
	4. set "home_dir" to your desired home folder (e.g. /home/you/htdocs)
	5. and set "home_url" to the corresponding URL. (e.g. http://yoursite)
	6. Have Fun...
----------------------------------------------------------------------------------------------------
Troubleshooting:
	* Some browsers (e.g. Konqueror) may want to save a download as index.php.
	  To solve this, just supply the correct name when saving.
	* Internet Explorer may behave strangely when downloading files.
	  If you open the php-file download, the real download window should open.
	* Mozilla may add the extension 'php' to a file being downloaded.
	  Save as 'any file (*.*)' and remove the 'php' extension to get the proper name.
	  (NOTE: for php-files, this extension is correct)
	* If you are unable to perform certain operations,
	  try using an FTP-chmod to set the directories to 755 and the files to 644.
	* If you don't know the full name of a directory on your website,
	  you can use a php-script containing '<?php echo getcwd(); ?>' to get it.
	* The Search Function uses PCRE regex syntax to search; though wildcards like * and ?
	  should work (like with 'ls' on Linux), it may show unexpected behaviour.
	* User-management may logout unexpectedly or show other strange behaviour.
	  This is due to a bug in PHP 4.1.2; we would advise you to upgrade to a higher version.
----------------------------------------------------------------------------------------------------
Users:
	* user-authentication is activated by default, set "require_login" to false to
	  disable user-authentication in ".config/conf.php";
	  you should also set the path for the admin user in ".config/.htusers".
	* You can easily manage users using the "admin" section of QuiXplorer.
	* Standard, there is only one user, "admin", with password "pwd_admin";
	  you should change this password immediately.
----------------------------------------------------------------------------------------------------
Languages:
	* You can choose a default language by changing "language" in ".config/conf.php"
	  (to "en", "de", "nl", "fr", "es", "ptbr", "it", "pl", "ro" or "ru").
	* When using user-authentication, users can select a language on login.
----------------------------------------------------------------------------------------------------
----------------------------------------------------------------------------------------------------
author:		the QuiX project
www:		http://www.quix.tk, http://quixplorer.sourceforge.net
----------------------------------------------------------------------------------------------------
