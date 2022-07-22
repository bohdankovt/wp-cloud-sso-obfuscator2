## Setup:
    Note: This setup is also valid for Ubuntu 18...  
    1. Prerequisites: git and php-cli (command line interface) packages. 
       on ubuntu: (adapt according your linux distribution)
       # apt install php-cli
       do not forget to install all other php modules that you are using within your software:
            for example: apt install php-mysql if you are using mysql... 
    2. Navigate to the directory where you want to install yakpro-po (/usr/local is a good idea): 
       # cd /usr/local 
    3. Then retrieve from GitHub: 
       # git clone https://github.com/bohdankovt/wp-cloud-sso-obfuscator.git
    4. Go to the wp-cloud-sso-obfuscator directory: 
       # cd wp-cloud-sso-obfuscator
    6. Check that obfuscator.php has execute rights, otherwise:
                                            # chmod a+x obfuscator.php 
    7. Create a symbolic link in the /usr/local/bin directory
       # cd /usr/local/bin 
       # ln -s /usr/local/wp-cloud-sso-obfuscator/obfuscator.php wp-obfuscator 
    8. You can now run wp-obfuscator 
       # wp-obfuscator <directory>

    Modify a copy of the yakpro-po/yakpro-po.cnf to fit your needs...
    Read the "Configuration file loading algorithm" section of this document
    to choose the best location suiting your needs!

    That's it! You're done!

####