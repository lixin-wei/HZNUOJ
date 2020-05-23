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

上一版本的班级列表是写死在代码里的，本次更新（2020年5月）已经更改由数据库管理，老版本HZNUOJ请运行源码文件夹下 `judger/install/update.sql` 更新数据库。

本次关于班级列表功能的更新包括班级模式开关，以及以班级为单位的注册码、注册名额功能。具体参看源码文件夹下 `web/OJ/include/static.php` 中的 **$OJ_NEED_CLASSMODE** 和 **$OJ_REG_NEED_CONFIRM** 两个参数的注释说明

### 题集编辑

题集编辑的界面（添加、删除、修改）本次更新中（2020年5月）已经添加，请运行源码文件夹下judger/install/update.sql更新数据库（index字段改为自动递增）

管理员需要inner_function权限，当题集下有题目存在时这个题集不能删除。

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

### 将hustoj升级为HZNUOJ（只迁移web部分，后台的判题机不变）

1、下载源码，root权限运行`judger/install/hustoj2HZNUOJ.sh` , 请确保在目录 `judger/install/` 下执行hustoj2HZNUOJ.sh，本地登录或者ssh远程登录Ubuntu系统后，命令行下按顺序执行以下指令，按提示操作即可：

   ```bash
   admin@ubuntu16:~$ git clone https://github.com/wlx65003/HZNUOJ.git
   admin@ubuntu16:~$ cd HZNUOJ/judger/install
   admin@ubuntu16:~/HZNUOJ/judger/install$ sudo bash hustoj2HZNUOJ-ubuntu16+.sh
   ```

2、因HZNUOJ是hustoj的分支，判题机支持的编程语言和hustoj最新版会略有差异，请参照待升级hustoj的**const.inc.php**文件，人工编辑 `OJ/include/const.inc.php` 中$language_name数组（编程语言支持列表），以及$language_order数组（各个编程语言的显示顺序）。

3、根据需要修改`OJ/include/static.php`中的$OJ_LANGMASK，调整开放的编程语言，具体参看[HZNUOJ配置手册](Configuration.md)或者配置文件中的注释。

4、**注意**：Ubuntu14系统中php版本为PHP5，而HZNUOJ的web部分是基于PHP7，因此若待升级hustoj跑在Ubuntu14上，请先将PHP5升级至PHP7（未测试），或将系统更换或升级成Ubuntu16或Ubuntu18后，再升级成HZNUOJ系统。