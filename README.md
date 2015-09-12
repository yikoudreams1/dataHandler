# dataHandler
scripts and classes for handling business information data.

usage:
*********************************************************************************************************
usage : php handleGongShang.php -F filename [ other actions]
example:
php handleGongShang.php -F fname -i 13 -t 15 //get first colomn of website from tagtitle on 15th colomn of fname;
                                     //get second colomn of ip from url on 13rd colomn of fname;
                                     //get other colomns from the same colomns of fname.
actions:
    -n get the name of website from tagtitle
    -l get the license information from copyright
    -L get the level of domain from url
    -i get the ip from url
    -a get the address from url
    -d get the domain from url
    -D get the diyu from zhuti
*********************************************************************************************************
