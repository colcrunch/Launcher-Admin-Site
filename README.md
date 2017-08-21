# Launcher-Admin-Site
An admin panel to manage news for the SKCraft Minecraft launcher.

# Demo
For a demo of this, please visit http://admin.colsfiles.us

For demo of Admin Features:
Username: demoAdmin / Password: admin123

For demo of user features:
Username: demoUser / Password: username123

For a demo of a public facing site to be used with this go to  http://launcher.colsfiles.us

## Note
You will probably notice that the user features and the admin features are nearly identical, this tool is meant to be accessed by trusted people only. An admin is allowed to answer help questions, and manage other accounts.

The site is set up specifically so that admin accounts have to be manually added to the admin database.

# Set Up
To set up the site, make sure to fill out /includes/psl-config.php with your SQL information, the name of the admin for contact, and the name of yor group.

You can use the included SQL file to set up the database. The default admin account will be the same as the demo, so remember to either change the password when you first login, or to make a new account (and add it to the admins database) and delete the demo account.

## Credit
This tool uses the following:

  * CKEditor - CKSoft Sp
  * Bootstrap
  * sha512.js from http://pajhome.org.uk/crypt/md5/
