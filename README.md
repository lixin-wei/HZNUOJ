**HZNUOJ is based on [HUSTOJ](https://github.com/zhblue/hustoj)**

Site address: [acm.hznu.edu.cn](http://acm.hznu.edu.cn)(isn't working temporarily because of the G20)

Spare address [www.hsacm.cn](http://www.hsacm.cn)(isn't working now, either.)

# Features
HZNUOJ deeply modified the web client and add more features.

1. Brand new UI and more JS effect, use the amazeUI framework.
2. Tag system, help users more easily to find the problem they want.
3. ...

# Plans
1. Perfect the tag system, make it more friendly to use.
    * A more friendly tag-add interface.
    * Search by tag.
    * Tag can only use particular words.
    * ...
2. Reconstruct the problemset system.
3. Reconstruct the privilege system. 


# Installation
Ubuntu 14.04 is recommended, an installer may not work well in the later version.

Simply clone the repository, then run judger/install/install.sh.

Then you need to modify apache settings, change the default server dir to /var/www/web/OJ.

You may modify web/OJ/include/db_info.inc.php with your own database information.
