# HZNUOJ常见问题列表

本文档包括了一系列使用过程中经常遇到的问题。

### 命令行下的编辑

因为命令行界面下没有图形界面，编辑文件只能用一个叫vim的编辑器。

下面所有牵扯到文件修改的步骤都会使用vim，所以这里默认你掌握基础的vim使用，不会的可以去百度一下。

容器中默认还没装vim，运行`apt install vim`安装。

然后你得配置一下vim，不然显示中文会乱码

打开vimrc配置文件`vim /etc/vim/vimrc`

在末尾加入以下指令即可

```
set fileencodings=utf-8,gb2312,gbk,gb18030
set termencoding=utf-8
set encoding=prc
```

### 切换Python版本到Python3

首先attach进入容器

然后执行下面两条命令即可

`sudo update-alternatives --install /usr/bin/python python /usr/bin/python2 100`

`sudo update-alternatives --install /usr/bin/python python /usr/bin/python3 150`

要切换回python2的话，执行`sudo update-alternatives --config python`，按照提示输入选择数字回车即可。

运行完上面的命令后，Python的实际版本已经是3了，提交页面选择Pyhton语言提交Python3代码判题即可。

### 班级列表

上一版本的班级列表是写死在代码里的，本次更新（2020年3月）已经更改由数据库管理，老版本HZNUOJ请运行源码文件夹下judger/install/update.sql更新数据库。

本次关于班级列表功能的更新包括班级模式开关，以及以班级为单位的注册码、注册名额功能。具体参看源码文件夹下 web/OJ/include/static.php 中的 **$OJ_NEED_CLASSMODE** 和 **$OJ_REG_NEED_CONFIRM** 两个参数的注释说明

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

或者试试 `sudo pkill -9 judged && sudo judged`
