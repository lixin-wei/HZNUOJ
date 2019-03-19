FROM ubuntu:16.04

USER root
WORKDIR /root/
COPY ./ ./HZNUOJ

ENV MYSQL_PWD root
# change apt-source to aliyun
RUN sed -i s@/archive.ubuntu.com/@/mirrors.aliyun.com/@g /etc/apt/sources.list\
    && apt-get clean\
# set mysql password before installation in case of stucking in asking for password
    && echo "mysql-server mysql-server/root_password password $MYSQL_PWD" | debconf-set-selections\
    && echo "mysql-server mysql-server/root_password_again password $MYSQL_PWD" | debconf-set-selections\
# run HZNUOJ install script
    && cd /root/HZNUOJ/judger/install\
    && bash install.sh

EXPOSE 80 3306
ENTRYPOINT ["/root/HZNUOJ/docker/docker_entry.sh"]

CMD ["/bin/bash"]

