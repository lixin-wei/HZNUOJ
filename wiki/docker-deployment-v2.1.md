# Docker部署HZNUOJ教程

Docker是目前很流行的容器引擎，你可以理解为和虚拟机类似的东西，不过无论在体积和速度上都比虚拟机快很多。

我已经将整个HZNUOJ包括其运行环境打包成了Docker镜像，可以很方便的直接部署，不需要折腾烦人的环境。

整个教程分为Docker安装与镜像导入、运行镜像和WEB端配置三部分。如果你对Docker部署过程十分熟悉，可以直接下载镜像部署，然后看一下WEB端配置部分即可。

如果您觉得这篇教程对您多少有点帮助，还请动动鼠标，给我一个Star。

就是最顶上，右上角的那颗小星星（Star），看到了没，注册一个账号，点一下~

这个星星对我挺重要，如果能给我颗星星将是对我莫大的支持，鞠躬！

## Docker安装与镜像导入

### 下载所需文件

部署步骤里所有用到的文件我都传到我的网盘上了，包括镜像和windows下docker的安装包，各位老师们可以从[这里](http://pan.baidu.com/s/1jHMzsHo)下载。

镜像挺大的，有3个G，百度网盘的限速又比较厉害，请耐心等待。

### 该选择Linux系统还是windows系统

如果您的电脑配置较低，尤其是如果内存小于4G，请务必选择Linux系统。Docker可能无法在内存小于4G的windows机器上运行。

否则可随性选择，不过建议用Linux，安装方便，且原生支持Docker，出现莫名其妙错误的概率比windows低很多，如果您在windows下安装遇到了解决不了的问题，不妨换Linux试试。

### Linux-Ubuntu

Ubuntu是Linux系统最有名，生态最好的发行版系统之一。

目前部署过程我只在Ubuntu16.04 64bit上测试过，所以强烈建议你和我一样选择Ubuntu16.04 64bit系统。具体系统安装教程请自行百度。

另外注意安装的时候如果选择手动分区，请多分点空间给主分区'/'，起码50G，越多越好，因为docker的文件是存在主分区里的。

#### 安装Docker

打开命令行（如果是桌面环境，右键桌面，点击打开终端），依次输入以下五条命令。

```bash
sudo apt-get -y install \
  apt-transport-https \
  ca-certificates \
  curl
```

```shell
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -
```

```shell
sudo add-apt-repository \
       "deb [arch=amd64] https://download.docker.com/linux/ubuntu \
       $(lsb_release -cs) \
       stable"
```

```shell
sudo apt-get update
```

```shell
sudo apt-get -y install docker-ce
```

安装好后执行`docker version`，如果有输出版本号，说明安装成功。

如果update时网速奇慢无比，很可能是中国的防火长城的原因，阻拦了外国网站的流量，这时候请换成国内的源，具体换源教程请百度关键词“ubuntu 16.04 源替换”。

如果install的时候提示失败，试着多运行几次，很可能也是网络问题。

#### 导入镜像

**特别提示：在Linux下运行所有docker命令时，请使用root权限操作，否则会提示权限不足。**

**为了方便，可以输入`sudo su`命令，输入密码后回车，这样接下来就能以超级管理员权限运行所有命令。或者你不嫌麻烦的话，可以在所有命令最前面加一个`sudo`，效果是一样的。**

然后在命令行里使用`cd`命令进入到你下载来的镜像所放的目录，例如你放在`/home/my-user`，就`cd /home/my-user`

然后运行`docker load -i hznuoj2.1.tar`导入镜像，`hznuoj2.1.tar`是文件名，耐心等待导入完成。

至此镜像导入就完成了，请跳至运行镜像部分阅读。

----

### Windows

首先，确保你的系统是 **Windows10专业版** ，必须是专业版，因为docker容器引擎需要用到win10专业版才有的hyper-v虚拟机功能。

接着，请你关闭所有杀毒软件，包括xx卫士、xx管家等，以免造成不必要的麻烦。

#### 安装Docker

运行下载来的 `InstallDocker.msi` ，安装完后点ok，然后**不要动**，还有些功能在后台部署，大概过3分钟左右会自动重启，重启后过程中会部署一些功能，大约要花1~20分钟。耐心等待，直到再次看到桌面。

然后任务栏右下角会出现一只小鲸鱼，鼠标移上去显示docker is starting...，耐心等待它加载完成，大概也需要1~10分钟不等，加载完成后会跳出一个消息框，显示docker is running，就是加载完毕了。

#### 配置Docker

然后右击小鲸鱼图标，选择settings，在弹出的窗口里，点击shared drives，在里面勾上你允许docker使用的盘符，可以尽量选一个空闲空间大一点的盘。然后在Advanced里可以配置你允许docker使用的CPU核心数和内存，可以全部拉满，内存可以少给一点，以免本身内存不够用。设置好后点击Apply生效，可能会让你输入windows账户密码，如果没有密码请去设置一个，docker不接受空密码（ghost安装的系统一般会没有密码）。

#### 导入镜像

然后进入你放下载来的镜像的文件夹，shift+右键点击空白处，选择在此处打开命令窗口，然后输入`docker load -i hznuoj2.1.tar`，耐心等待导入完成。

至此镜像导入就完成了，请跳至运行镜像部分阅读。

## 运行镜像

镜像导入完成后，运行镜像，输入命令：

`docker run -it --name hznuoj2.1 -p 80:80 hznuoj:2.1 /bin/bash`

成功后你将进入一个完整的ubuntu命令行界面，里面已经部署好了一切。但是需要你重新启动三个服务，运行下面三个命令

`service apache2 restart`

`service mysql restart`

`sudo judged`

**以后每次重启也请重打一遍这三个命令。**

现在你可以打开浏览器，输入`localhost/OJ`，即可访问系统了。和你同一个内网的人，也可以通过输入地址 `(你的内网IP)/OJ` 访问。

服务器重启等运维相关的操作请参考文末的HZNUOJ使用手册。

## WEB端配置

服务器内置了一个管理员账户，账号密码均为admin，可以直接像普通账号一样登录，登录后为了安全请**立即修改密码**。此管理员拥有最高权限，点击右上角的`用户名->admin`可以进入后台，可以在后台进行题目、比赛、公告、用户的管理。

至此，整个部署过程就结束了。

然而HZNUOJ还有很多不足的地方，也有很多已知的小BUG，因为学业繁忙来实在来不及修复和完善，如果对WEB开发比较熟悉的老师，也可以自己修改系统，所有的源码都存在`/var/www` 目录下，使用PHP编写。如果有心的话，还可以在GitHub上fork一下我的项目进行修改，然后向我提交pull request，从而一起参与进HZNUOJ的开发。

----

使用过程中更多细节的问题请参考[HZNUOJ使用手册](maintainer-manual.md)
