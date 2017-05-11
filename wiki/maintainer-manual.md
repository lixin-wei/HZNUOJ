# HZNUOJ使用手册

本文档包括了一系列使用过程中经常遇到的问题。

### 班级列表修改

班级列表因为以前都是自己学校在用，所以没有做特别友好的修改接口。是写死在代码里的(下一个版本会改进)。

如果想修改班级列表，请打开`/var/www/html/web/OJ/include/classList.inc.php`

在里面按格式修改即可。

### 题集编辑

题集编辑的界面目前也还没写好，下个版本会改进。

你可以手动修改，存在mysql的jol数据库的problemset表里，自行添加即可。

### 重启服务器

如果服务器意外关机，可以通过如下步骤重新运行（不要按照上面的教程再run一遍）：

`docker start hznuoj2.1`

`docker attach hznuoj2.1`

然后重启三个服务即可

`service apache2 restart`

`service mysql restart`

`sudo judged`

### 一个小BUG

现在已知一个小BUG，就是服务器意外关机后，有概率出现Python所有的提交都是Wrong Answer。这时候请在容器内部重启下判题守护进程：

`pidof judged`

会跳出一个id，kill掉它

`kill -9 刚刚的id`

然后重启程序

`sudo judged`
