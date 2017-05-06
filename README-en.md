**HZNUOJ is based on [HUSTOJ](https://github.com/zhblue/hustoj)**

Site address: [acm.hznu.edu.cn](http://acm.hznu.edu.cn)

# Features
HZNUOJ deeply modified the web client and add more features.

1. Brand new UI and more JS effect, use the amazeUI framework.
2. A more flexible privilege system.
3. Tag system, help users more easily to find the problem they want.
4. ...

# Plans
1. Perfect the tag system, make it more friendly to use.
    * A more friendly tag-add interface.
    * Search by tag.
    * Tag can only use particular words.
    * ...
2. Board freeze function.
3. Board rolling function.

# Installation
Ubuntu 14.04 is recommended, the installer may not work well in the later version.

Simply clone the repository, then run `judger/install/install.sh`.

Then you need to modify apache settings, change the default server dir to `/var/www/web`.

At last, `sudo chown www-data -R /var/www`.

[中文版安装步骤](README.md)
