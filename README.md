## **HZNUOJ 是基于 [HUSTOJ](https://github.com/zhblue/hustoj) 改造而来的，遵循GPL协议开源**

目录
--
[优势](#%E4%BC%98%E5%8A%BF)

[界面截图](#%E7%95%8C%E9%9D%A2%E6%88%AA%E5%9B%BE)

[部署指南](#%E9%83%A8%E7%BD%B2%E6%8C%87%E5%8D%97)

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[基于Docekr部署](#%E4%BD%BF%E7%94%A8docker%E6%8E%A8%E8%8D%90)

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[基于源码部署](#%E4%BD%BF%E7%94%A8%E6%BA%90%E7%A0%81)

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[功能配置](#%E5%8A%9F%E8%83%BD%E9%85%8D%E7%BD%AE)

[使用教程](#%E4%BD%BF%E7%94%A8%E6%95%99%E7%A8%8B)

[贡献代码/Bug反馈](#%E8%B4%A1%E7%8C%AE%E4%BB%A3%E7%A0%81bug%E5%8F%8D%E9%A6%88)



# 优势

* 更华丽的界面
* 更灵活的权限管理
* 支持多组样例
* 有封装好的Docker镜像，一键部署


# 界面截图

## 首页

支持提交量和访问量的统计

![index](images/index.jpg)

## 榜单

重写过的的榜单

![board](images/board.jpg)

能点开查看每题的提交状况

![board2](images/board2.jpg)

## 题目编辑界面

![problem-edit](images/problem-edit.jpg)

多样例支持

![problem-edit](images/problem-edit2.jpg)

## 权限管理界面

细分的权限分配

![privilege](images/privilege.jpg)

# 部署指南

## 使用Docker（推荐）

### 获取镜像

#### 自行build

下载源文件，在仓库根目录下执行build：

```bash
admin@ubuntu16:~$ git clone https://github.com/wlx65003/HZNUOJ.git
admin@ubuntu16:~$ cd HZNUOJ
admin@ubuntu16:~/HZNUOJ$ sudo docker build -t hznuoj:latest -f docker/Dockerfile ./
```
 
等待build完成即可。

完成后`docker image ls`，若有看到hznuoj的镜像即为成功。

#### 从Docker Hub下载

如果你实在懒得build，也可以从Docker Hub下载。

HZNUOJ的Docker Hub主页：https://hub.docker.com/r/wlx65003/hznuoj ，会持续从master分支构建最新的镜像。

使用docker pull下载即可，由于HZNUOJ的Judger依赖较多，所以镜像很大，约1.2G，请耐心等待，或者自行寻找科学上网方式。最好还是自己build。

```bash
admin@ubuntu16:~$ docker pull wlx65003/hznuoj
```

### 启动容器

```bash
admin@ubuntu16:~$ docker run -it --rm -p 90:80 --privileged hznuoj:latest
```

其中`-p 90:80`表示把容器的80端口映射到宿主机的90端口，可自行修改，可以直接改成http默认的80端口以省去网址里的端口号。

`--rm` 表示运行一次就删除容器，如果你想长期运行，当虚拟机用，需要去掉。

`--privileged` 不能省略，否则判题机会权限不足，判题功能无法正常运作。

然后访问localhost:90即可。


## 使用源码

1. HZNUOJ目前只在Ubuntu16.04上跑过，在更高版本下判题机可能无法正常运行。

2. 若OJ用于OI信息学奥赛训练，请下载源码后修改`/judger/install/judge.conf`OJ_OI_MODE=1，默认运行模式为OJ_OI_MODE=0 ACM竞赛模式。

3. 下载源码，root权限运行`judger/install/install.sh` , 请确保在目录 `judger/install/` 下执行install.sh，建议安装前把更新源换成国内的比如阿里云、清华等更新源。
   本地登录或者ssh远程登录Ubuntu系统后，命令行下按顺序执行以下指令：
   ```bash
   admin@ubuntu16:~$ git clone https://github.com/wlx65003/HZNUOJ.git
   admin@ubuntu16:~$ cd HZNUOJ/judger/install
   admin@ubuntu16:~/HZNUOJ/judger/install$ sudo bash install.sh
   ```
   源码或者还可以直接访问`https://github.com/wlx65003/HZNUOJ` 下载zip包

4. 安装完成后访问localhost、服务器IP或相应域名即可。

## 功能配置

### 原理

系统分为后台的Core(判题机)和前台的web(网站)两大部分。两个部分相对独立，通过数据库关联。判题机通过轮询数据库提取判题队列，生成判题信息回写数据库，web部分读取数据库显示在网页上。

若发现提交代码后无法判题，一直显示pending或者等待，请尝试服务器中运行以下命令重启判题机进程
```bash
admin@ubuntu16:~$ sudo pkill -9 judged && sudo judged && ps -A | grep judged
若出现类似"xxxx ?        00:00:00 judged"的字样，说明进程重启成功
```

更多原理和说明可参考[hustoj文档大全.pdf](https://github.com/zhblue/hustoj/wiki/hustoj文档大全.pdf)及[HZNUOJ常见问题列表](wiki/maintainer-manual.md)

### 参数配置

Core判题机部分的配置文件为/home/judge/etc/judge.conf，web网站部分的配置文件为/var/www/web/OJ/include/static.php

详细的配置说明可以参看[HZNUOJ配置手册](wiki/Configuration.md)或者配置文件中的注释。

下面把几个可能会用到的功能配置参数说明一下：

### 比赛模式切换 OJ_OI_MODE

judge.conf中的OJ_OI_MODE负责标记判题机的判题模式是OI信息学奥赛模式还是ACM大学生程序设计竞赛模式。

#### OJ_OI_MODE = 0
判题模式默认为ACM大学生程序设计竞赛模式，一旦有测试实例出错就停止运行，比赛根据 AC 数量排名。

#### OJ_OI_MODE = 1
判题模式为OI信息学奥赛模式，即使有测试实例出错，它依旧会测试下去，比赛根据数据通过率排名，而不只看 AC 数量；另外会在status.php状态页中显示测试实例的通过率和程序最大运行时间。
 
可运行脚本快速修改或手动修改judge.conf文件
```bash
执行源码目录下的 `judger/install/ch_OI_MODE.sh` ，按照提示操作即可
admin@ubuntu16:~$ sudo bash HZNUOJ/judger/install/ch_OI_MODE.sh
```

### 判题WrongAnswer信息相关参数，输出模式切换 OJ_FULL_DIFF 和 对比信息查看开关 $OJ_SHOW_DIFF

judge.conf中的OJ_FULL_DIFF负责标记判题机的错误信息对比说明输出模式，static.php中的$OJ_SHOW_DIFF负责控制用户是否有权限在reinfo.php页看到上述错误信息对比说明。

**OJ_FULL_DIFF的修改不会对老的判题WrongAnswer信息产生影响，除非修改后重新判题。**

#### OJ_FULL_DIFF = 0
部分输出模式，若有测试实例出错，将出错的各组测试实例数据的输出数据xx.out，以及代码运行结果输出到表`runtimeinfo`，供reinfo.php页调用显示。

#### OJ_FULL_DIFF = 1
全输出模式，若有测试实例出错，将出错的各组测试实例数据的输入xx.in、输出数据xx.out，以及代码运行结果全部输出到表`runtimeinfo`，若将此类reinfo开放给选手，很容易因为in、out文件输入输出数据的泄露而导致恶意刷题，请谨慎使用。

#### $OJ_SHOW_DIFF = true
用户可以在reinfo.php页查看判题错误信息的对比说明。

#### $OJ_SHOW_DIFF = false
reinfo.php页会被禁止访问，用户不能查看判题错误信息的对比说明。

### 代码相似度检测开关 OJ_SIM_ENABLE 和 $OJ_SIM

OJ_SIM_ENABLE负责标记判题机是否开启代码相似度检测，$OJ_SIM负责标记web部分的状态页上是否显示代码相似度检测的结果。

通过配置judge.conf中的OJ_SIM_ENABLE = 1 和 static.php中的$OJ_SIM = true 开启代码相似度检测，也就是抄袭检查功能，但只针对AC的代码，不检测没通过的代码。

判题机通过调用第三方应用程序SIM对**AC的提交代码**进行语法分析判读文本相似度，通过检验的代码将由判题机复制进题目数据的 ac 目录成为新的参考样本（基于此，长期开启本功能会占用大量硬盘存储空间）。

# 使用教程

默认管理员账号为admin/123456。

出题手册见https://www.yuque.com/weilixinlianxin/zcf10d/yfk05w

# 贡献代码/Bug反馈

HZNUOJ目前开发人手很有限，只有已经上班的我和训练繁忙的一些学弟，有许多已知的不友好的功能和小BUG，只能抽时间慢慢改了。

同时也欢迎大家反馈[issue](https://github.com/wlx65003/HZNUOJ/issues)/提交[pull request](https://github.com/wlx65003/HZNUOJ/pulls)帮忙一起完善HZNUOJ。

最后，如果您觉得HZNUOJ好用，请给我一个Star，这将是对我莫大的帮助与鼓励，十分感谢！
