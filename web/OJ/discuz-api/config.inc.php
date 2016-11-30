<?php

define('UC_CONNECT', 'mysql');				// ���� UCenter �ķ�ʽ: mysql/NULL, Ĭ��Ϊ��ʱΪ fscoketopen()
							// mysql ��ֱ�����ӵ����ݿ�, Ϊ��Ч��, ������� mysql

//���ݿ���� (mysql ����ʱ, ����û������ UC_DBLINK ʱ, ��Ҫ�������±���)
define('UC_DBHOST', '172.17.151.2');			// UCenter ���ݿ�����
define('UC_DBUSER', 'root');				// UCenter ���ݿ��û���
define('UC_DBPW', 'hznujudge');					// UCenter ���ݿ�����
define('UC_DBNAME', 'bbs');				// UCenter ���ݿ�����
define('UC_DBCHARSET', 'utf8');				// UCenter ���ݿ��ַ���
define('UC_DBTABLEPRE', 'bbs.ucenter_');			// UCenter ���ݿ��ǰ׺

//ͨ�����
define('UC_KEY', '123456789');				// �� UCenter ��ͨ����Կ, Ҫ�� UCenter ����һ��
define('UC_API', 'http://localhost/bbs/uc_server');	// UCenter �� URL ��ַ, �ڵ���ͷ��ʱ�����˳���
define('UC_CHARSET', 'utf8');				// UCenter ���ַ���
define('UC_IP', '');					// UCenter �� IP, �� UC_CONNECT Ϊ�� mysql ��ʽʱ, ���ҵ�ǰӦ�÷�������������������ʱ, �����ô�ֵ
define('UC_APPID', 2);					// ��ǰӦ�õ� ID

/*
//ucexample_2.php �õ���Ӧ�ó������ݿ����Ӳ���
$dbhost = '172.17.65.2';			// ���ݿ������
$dbuser = 'root';			// ���ݿ��û���
$dbpw = 'root';				// ���ݿ�����
$dbname = 'jol';			// ���ݿ���
$pconnect = 0;				// ���ݿ�־����� 0=�ر�, 1=��
$tablepre = '';   		// ����ǰ׺, ͬһ���ݿⰲװ�����̳���޸Ĵ˴�
$dbcharset = 'utf8';			// MySQL �ַ���, ��ѡ 'gbk', 'big5', 'utf8', 'latin1', ����Ϊ������̳�ַ����趨
*/
//ͬ����¼ Cookie ����
$cookiedomain = ''; 			// cookie ������
$cookiepath = '/';			// cookie ����·��
