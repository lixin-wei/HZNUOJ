# HZNUOJ 安装说明 

0. HZNUOJ目前只支持Ubuntu14.04，在更高版本下判题机可能无法正常运行。

1. 下载源码
   `git clone https://github.com/wlx65003/HZNUOJ.git`
   或者直接访问`https://github.com/wlx65003/HZNUOJ` 下载zip包

2. 若已安装mysql，请修改`intall.sh` `judge.conf` `/web/OJ/include/db_info.inc.php` 中的相应账户密码信息(默认为root/root)。若还未安装，请确保接下来安装mysql的过程中将用户名和密码都设成root。

3. 以root权限运行`judger/install/install.sh`

4. 打开`/etc/apache2/sites-available/000-default.conf` ，将`DocumentRoot /var/www/html` 更改为 `DocumentRoot /var/www/web` ，即Apache的站点根目录设置为`/var/www/web` 

5. 重启Apache服务器`sudo service apache2 restart`
