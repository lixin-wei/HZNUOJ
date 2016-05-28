This file contains some importent information due to the
new access permissions implemented in version 2.4.0 of
Quixplorer.

Those changes have been made:

1. The Admin User

The user named "admin" always has admin access. Even removing
the admin access right from that user does not change that.

You still may give other users admin rights for changing users, etc.

Being an admin user does not automatically means that you have
full access rights to the file directories. The permissions are checked
as on any other user. That means that you can make an admin user that
has now rights to create, change or delete files but may create, change and
delete users.

2. New Access Permissions

For each user you may define the following access permissions:

(a)	Read	- 	The user may read and download all files
			in his home directory

(b)	Write	-	The user may upload new files to his home
			directory, even if he has no rights to
			download files.

(c)	Change	-	The user may change, move and rename
			existing files it his home directory, even
			if he has now rights to upload new files.

(d)	Delete	-	The user may delete existing files from
			his home directory

(e)	Change Password
		-	The user may change is own password

(f)	Administrator
		-	The user may add, change and remove users
			and their access permissions.

