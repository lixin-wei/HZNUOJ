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

运行完上面的命令后，Python的实际版本已经是3了，但是web的提交界面里显示的还是Python2，看着难受的话可以改一下。

但是命令行界面下编辑文件得用vim，这个对新手很不友好，如果不会用可以百度下教程。

`vim /var/www/web/OJ/include/const.inc.php`

在里面找到language_name数组，把里面的Python2改成Python3就可以了，或者干脆直接改成Python，以免后面切换又得改。

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
