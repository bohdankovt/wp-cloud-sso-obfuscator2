## Requires:
    - php-cli    
####

## Setup:
    1. Navigate to the directory where you want to install yakpro-po (/usr/local is a good idea): 
       # cd /usr/local 
    2. Then retrieve from GitHub: 
       # git clone https://github.com/bohdankovt/wp-cloud-sso-obfuscator.git
    3. Go to the wp-cloud-sso-obfuscator directory: 
       # cd wp-cloud-sso-obfuscator
    4. Change permissions: 
        # chmod a+x obfuscator.sh
    5. Create a symbolic link in the /usr/local/bin directory
       # cd /usr/local/bin 
       # ln -s /usr/local/wp-cloud-sso-obfuscator/obfuscator.php wp-obfuscator 
    6. You can now run wp-obfuscator 
       # wp-obfuscator <directory>

    Modify a copy of the yakpro-po/yakpro-po.cnf to fit your needs...
    Read the "Configuration file loading algorithm" section of this document
    to choose the best location suiting your needs!

    That's it! You're done!
####

## Usage:
    For run script use command: 
        # wp-obfuscator <directory>

    This command will be generated output ZIP file in output folder
####