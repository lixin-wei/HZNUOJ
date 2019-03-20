**HZNUOJ 是基于 [HUSTOJ](https://github.com/zhblue/hustoj) 改造而来的，遵循GPL协议开源**

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

在仓库根目录下：

```bash
docker build -t hznuoj:latest -f docker/Dockerfile ./
```
 
等待build完成即可。

完成后`docker image ls`，若有看到hznuoj的镜像即为成功。

#### 从Docker Hub下载

如果你实在懒得build，也可以从Docker Hub下载。

HZNUOJ的Docker Hub主页：https://hub.docker.com/r/wlx65003/hznuoj ，会持续从master分支构建最新的镜像。

使用docker pull下载即可，由于HZNUOJ的Judger依赖较多，所以镜像很大，约1.2G，请耐心等待，或者自行寻找科学上网方式。最好还是自己build。

```bash
docker pull wlx65003/hznuoj
```

### 启动容器

```
docker run -it --rm -p 90:80 hznuoj:latest
```

其中`-p 90:80`表示把容器的80端口映射到宿主机的90端口，可自行修改，可以直接改成http默认的80端口以省去网址里的端口号。

`--rm` 表示运行一次就删除容器，如果你想长期运行，当虚拟机用，需要去掉。

然后访问localhost:90即可。


## 使用源码

0. HZNUOJ目前只在Ubuntu16.04上跑过，在更高版本下判题机可能无法正常运行。

1. 下载源码
   `git clone https://github.com/wlx65003/HZNUOJ.git`
   或者直接访问`https://github.com/wlx65003/HZNUOJ` 下载zip包

2. 若已安装mysql，请修改`intall.sh` `judge.conf` `/web/OJ/include/db_info.inc.php` 中的相应账户密码信息(默认为root/root)。若还未安装，请确保接下来安装mysql的过程中将用户名和密码都设成root。

3. 以root权限运行`judger/install/install.sh` , 请确保在目录 `judger/install/` 下

4. 安装完成后访问localhost即可。

# 使用教程

默认管理员账号为admin/123456。

出题手册见https://www.yuque.com/weilixinlianxin/zcf10d/yfk05w

# 求赞

HZNUOJ目前主要只有我一个人在开发，还有许多不友好的功能和小BUG，只能抽时间慢慢改了，欢迎大家提交pull request帮忙一起完善HZNUOJ。

最后，如果您觉得HZNUOJ好用，请给我一个Star，这将是对我莫大的帮助与鼓励，十分感谢！
