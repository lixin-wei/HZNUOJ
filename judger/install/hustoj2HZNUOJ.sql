set names utf8;
alter database jol character set utf8; 
use jol;

CREATE TABLE `class_list` (
  `class_name` varchar(100) NOT NULL,
  `enrollment_year` smallint(4) NOT NULL,
  PRIMARY KEY (`class_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO `class_list` VALUES ('其它', '0');

CREATE TABLE `contest_discuss` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(255) NOT NULL DEFAULT '',
  `contest_id` int(11) NOT NULL,
  `problem_id` int(11) DEFAULT NULL,
  `content` text,
  `reply` text,
  `in_date` datetime DEFAULT NULL,
  `reply_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `contest_excluded_user` (
  `index` int(11) NOT NULL AUTO_INCREMENT,
  `contest_id` int(11) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`index`),
  KEY `contest_id` (`contest_id`,`user_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

CREATE TABLE `faq_codes` (
  `language` varchar(255) CHARACTER SET utf8 NOT NULL,
  `language_show` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `code` varchar(10000) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`language`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;
INSERT INTO `faq_codes` VALUES ('bash','Bash','#!/bin/bash\r\n  \r\nread -a arr\r\n#echo ${#arr[@]}\r\nwhile [ ${#arr[@]} -eq 2 ]\r\ndo\r\nsum=$((${arr[0]}+${arr[1]}))\r\necho \"$sum\"\r\nread -a arr\r\ndone'),('c','C','#include <stdio.h>\r\nint main()\r\n{\r\n   int a, b;\r\n   while(scanf(\"%d%d\", &a, &b) != EOF)\r\n      printf(\"%d\\n\", a + b);\r\n}'),('cpp','C++','#include <iostream>\r\nusing namespace std;\r\nint main()\r\n{\r\n    int a, b;\r\n    while(cin>> a >> b)\r\n    	cout << a + b << endl;\r\n    return 0;\r\n}'),('csharp','C#','using System;\r\nusing System.Linq;\r\n \r\nnamespace ConsoleApplication\r\n{\r\n    public class Program\r\n    {\r\n        private static void Main()\r\n        {\r\n            string line;\r\n            while((line = Console.ReadLine()) != null)\r\n            {\r\n                Console.WriteLine(line.Split().Select(int.Parse).Sum());\r\n            }\r\n        }\r\n    }\r\n}'),('java','Java','//package main\r\n//注意不要添加包名称，否则会报错。\r\n \r\nimport java.io.*;\r\nimport java.util.*;\r\nclass Test {\r\n}\r\npublic class Main\r\n{\r\n    public static void main(String args[])\r\n    {\r\n        Scanner cin = new Scanner(System.in);\r\n        int a, b;\r\n        while(cin.hasNextInt())\r\n        {\r\n            a = cin.nextInt();\r\n            b = cin.nextInt();\r\n            System.out.println(a + b);\r\n        }\r\n    }\r\n}'),('lua','Lua','local count = 0\r\nfunction string.split(str, delimiter)\r\n  if str==nil or str==\'\' or delimiter==nil then\r\n    return nil\r\n  end\r\n   \r\n    local result = {}\r\n    for match in (str..delimiter):gmatch(\"(.-)\"..delimiter) do\r\n        table.insert(result, match)\r\n    end\r\n    return result\r\nend\r\nwhile true do\r\n  local line = io.read()\r\n  if line == nil or line == \"\" then break end\r\n  local tb = string.split(line, \" \")\r\n  local sum = 0\r\n  for i=1, #tb do\r\n    local a = tonumber(tb[i])\r\n    sum = sum+a\r\n  end\r\n  if count>0 then\r\n    io.write(\"\\n\")\r\n  end\r\n  io.write(string.format(\"%d\", sum))\r\n  count = count+1\r\nend'),('pascal','Pascal','program p1001(Input,Output);\r\nvar\r\na,b:Integer;\r\nbegin\r\n while not eof(Input) do\r\n   begin\r\n     Readln(a,b);\r\n     Writeln(a+b);\r\n   end;\r\nend.'),('perl','Perl','while (defined(my $line = <STDIN>)) {\r\n    $line =~ s/\\s+$//;\r\n    my @tokens = split(/ +/, $line);\r\n    my $a = $tokens[0];\r\n    my $b = $tokens[1];\r\n    printf(\"%d\\n\", $a + $b);\r\n}'),('php','PHP','< ?php\r\nfunction solveMeFirst($a,$b){\r\n    return $a + $b;\r\n}\r\n$handle = fopen (\"php://stdin\",\"r\");\r\n$s = fgets($handle);\r\nwhile ($s != \"\") {\r\n  $a = explode(\" \", $s);\r\n  $sum = solveMeFirst((int)$a[0],(int)$a[1]);\r\n  print ($sum);\r\n  print (\"\\n\");\r\n  $s = fgets($handle);\r\n}\r\nfclose($handle);\r\n?>'),('python','Python2','#!/usr/bin/env python \r\n# coding=utf-8 \r\n# Python使用的是2.7，缩进可以使用tab、4个空格或2个空格，但是只能任选其中一种，不能多种混用\r\nwhile 1:\r\n  a=[] \r\n  s = raw_input()\r\n  # raw_input()里面不要有任何提示信息\r\n  if s != \"\":\r\n    for x in s.split(): \r\n        a.append(int(x)) \r\n        \r\n    print sum(a)\r\n  else:\r\n    break'),('ruby','Ruby','a=gets\r\nwhile a != nil && a != \"\" && a != \"\\r\" && a != \"\\n\" do\r\n    arr = a.split(\" \")\r\n    sum = 0\r\n    arr.each_with_index do |value, index|\r\n        sum = sum + value.to_i\r\n    end\r\n    puts sum.to_s\r\n    a=gets\r\nend');

CREATE TABLE `faqs` (
  `index` int(11) NOT NULL AUTO_INCREMENT,
  `content` text,
  PRIMARY KEY (`index`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
INSERT INTO `faqs` VALUES (1,'## 环境参数\r\n\r\n系统运行于[Ubuntu 14.04](http://www.ubuntu.com/)\r\n对应的编译器和编译选项如下:\r\n\r\n|   语言   |       编译器版本       |                   编译选项                   |\r\n| :----: | :---------------: | :--------------------------------------: |\r\n|   C    |     gcc 4.8.4     | gcc Main.c -o Main -fno-asm -O2 -Wall -lm --static -std=c99 -DONLINE_JUDGE |\r\n|  C++   |     g++ 4.8.4     | g++ Main.cc -o Main -fno-asm -O2 -Wall -lm --static -std=c++11 -DONLINE_JUDGE |\r\n| Pascal | Free Pascal 2.6.2 | fpc Main.pas -oMain -O1 -Co -Cr -Ct -Ci  |\r\n|  Java  | openjdk 1.7.0_79  | javac -J-Xms32m -J-Xmx256m Main.java (Languages except C/C++ has 2 more seconds and 128M more memory when running and judging.) |\r\n|  Ruby  |       1.9.3       |                                          |\r\n|  Bash  |      4.3.11       |                                          |\r\n| Python2 |       2.7.6       |                                          |\r\n| Python3 |       3.4.3       |                                          |\r\n|  PHP   |       7.0      |                                          |\r\n|  Perl  | perl 5 version 18 |                                          |\r\n|   C#   |    mono 3.2.8     |                                          |\r\n|  Lua   |       5.2.3       |                                          |\r\n\r\n## 例题示范\r\n\r\n你的程序应该从标准输入 `stdin(\'Standard Input\')`获取输出 并将结果输出到标准输出 `stdout(\'Standard Output\')`.例如,在C语言可以使用 `scanf` ，在C++可以使用`cin` 进行输入；在C使用`printf` ，在C++使用`cout`进行输出. 用户程序不允许直接读写文件, 如果这样做可能会判为运行时错误 \"Runtime Error\"。\r\n详见[1000](/OJ/problem.php?id=1000)题hint中各种语言的参考答案。\r\n\r\n## 测评结果释义\r\n\r\n| 评测结果                  | 缩写   | 含义                                       |\r\n| --------------------- | ---- | :--------------------------------------- |\r\n| Pending               | PD   | 您的提交正排队等待评测。                             |\r\n| Pending Rejudge       | PR   | 因为数据更新或其他原因，系统将重新判你的答案。                  |\r\n| Compiling             | CP   | 您提交的代码正在被编译。                             |\r\n| Running & Judging     | RN   | 您的程序正在运行。                                |\r\n| Judging               | JG   | 我们 正在检查您程序的输出是否正确。                       |\r\n| Accepted              | AC   | 恭喜！您的程序通过了所有数据！                          |\r\n| Presentation Error    | PE   | 您的程序输出有格式问题，请检查是否多了或者少了空格 （\' \'）、制表符（\'\\t\'）或者换行符（\'\\n\'） |\r\n| Wrong Answer          | WA   | 您的程序输出结果错误。                              |\r\n| Runtime Error         | RE   | 您的程序在运行时发生错误。                            |\r\n| Time Limit Exceeded   | TLE  | 您的程序运行的时间已经超出了题目的时间限制。                   |\r\n| Memory Limit Exceeded | MLE  | 您的程序运行的内存已经超出了题目的内存限制。                   |\r\n| Output Limit Exceeded | OLE  | 您的程序输出内容太多，超过了这个题目的输出限制。（一般输出超过答案2倍时会触发，强制终止程序，防止恶意输出对硬盘造成压力） |\r\n| Compile Error         | CE   | 您的程序语法出现问题，编译器无法编译。                      |\r\n| System Error          | SE   | 评判系统内部出现错误 ，我们会尽快处理。                     |\r\n| Out Of Contest Time   | OCT  | 考试已经结束，不再评测提交。                           |\r\n\r\n## 常见编译问题\r\n\r\n有的时候你的程序在本地能编译通过，但提交OJ后却显示编译错误。\r\n\r\n这多见于C/C++，一般是因为你本地用的是VS，VS的编译器是MS-VC++，而OJ用的是G++，这两个编译器的标准略有不同，G++更符合标准，下面列出一些常见的导致CE原因：\r\n\r\n* `main` 函数必须返回`int`, ` void main()` 的函数声明会报编译错误。\r\n* `itoa` 不是ansi标准函数.\r\n* `__int64` 不是ANSI标准定义，只能在VC使用, 但是可以使用`long long`声明64位整数。\r\n\r\n如果你使用JAVA语言，请注意类名一定要是`Main`， 否则也会返回CE。\r\n\r\n## 比赛相关\r\n\r\n### 比赛的类型\r\n\r\n目前HZNUOJ有四种类型的比赛：\r\n\r\n* practice，练习赛，只是简单的把题目归个类做做练习，相应题目不会从problemset中隐藏，且通过后可以立即进入题目的status里查看别人的代码。\r\n* public，公开的比赛，任何人均可进入参加。\r\n* password，设有密码保护的比赛，只有输入正确密码才能进入。\r\n* special，特殊比赛，只有使用专门发放的账号才能进入。\r\n\r\n### 比赛赛制\r\n\r\nHZNUOJ所有类型的比赛均为ACM/ICPC赛制。\r\n\r\n每场比赛设有若干道题目，比赛开始后，参赛者需在时限内去解决这些题目。\r\n\r\n每场比赛都设有实时榜单，榜单排名规则也与ACM/ICPC相同。\r\n\r\n### ACM/ICPC排名规则\r\n\r\n每题耗时：Accepted的那一刻距离比赛开始的时间。\r\n\r\n总罚时：所有AC了的题的（耗时+错误次数*20min）的和。\r\n\r\n排名时，AC题数优先，题数相同时按罚时排序。 \r\n\r\n有些比较正式的比赛设有封榜机制，即比赛最后一段时间内的提交结果将隐藏（除了自己都不可见），榜单也会停止更新，新的提交会显示为灰色，留作最后滚榜用。\r\n\r\n#### 滚榜机制介绍\r\n\r\n滚榜是ACM/ICPC系列比赛中一个十分具有特色的机制。\r\n\r\n在正规ACM/ICPC系列比赛中，比赛最后一个小时的提交结果是隐藏的，只有选手本人能看到，在榜单上会显示成代表未知的灰色，以增加比赛紧张气氛。\r\n\r\n然后在颁奖会上，将从榜单最后一名开始，一个个揭晓灰色的未知提交，一旦揭晓的结果为通过，这个人的排名就会上升，否则这个人的排名确定，开始揭晓下一个人，以此类推。这样一来，可以从后往前一个个确定最终排名，一旦名次达到获奖名次内，可以直接进行颁奖。整个过程惊险刺激，是整个比赛的亮点所在。\r\n\r\n## 题目相关\r\n\r\nHZNUOJ的所有题目均在ProblemSet 中，每个题目都有一个唯一的数字编号，称为Problem ID。\r\n\r\n每当你AC了一道题，你就有权限查看这题所有的提交代码，借鉴参考大神们的写法，从而更上一层楼。\r\n\r\n比赛的所有题目，都是从ProblemSet中选出来的，是它的子集。\r\n\r\n当一道题被选入某个非practice模式的比赛中之后，为公平起见，它会在ProblemSet中被隐藏掉，在比赛结束后恢复。\r\n\r\n一般如果题目突然不见了，可能就是这个原因，当然也有可能是因为其他原因而被管理员手动隐藏了。\r\n\r\n当然，一般比赛的题都是新出的，比赛结束后才第一次在ProblemSet中露面。\r\n\r\n选入比赛中的题目，在比赛界面中，会隐藏掉原来的Problem ID，取而代之的是A, B, C...的代号。在比赛结束后，会在标题旁边显示真正的Problem ID，可以点击前往ProblemSet补题。\r\n\r\n## 积分规则\r\n\r\nHZNUOJ的ProblemSet中设有一个榜单，积分和等级的计算规则如下。\r\n\r\n等级由实力（Strength）决定，当实力达到一定值后自然会升级，而实力又从刷题中来，每道题后面均标有分数（Scores），代表AC这道题之后能提升多少实力。一般来说，越少人做的题目，分数越高，一起刷题的人越多，每道题的分数也越高。需要说明的是，用户的实力值是会根据大环境动态变化的（其实是因为分数在动态变化），如果你AC的题目被更多人AC出来了，你的实力值会下降，另外一方面，OJ内有更多强者涌入的时候，你的实力值也会提升。所以，想要快速升级，那就多刷题，刷难题！\r\n\r\n等级划分与小说《斗破苍穹》一致，自低向高分别为斗之气、斗者、斗师、大斗师、斗灵、斗王、斗皇、斗宗、斗尊、斗圣、斗帝，除斗帝外，每一阶又分不同等级，阶数越高，升级越困难。除此之外，每一阶还有不同的代表颜色，该阶等级越高，颜色越深。\r\n\r\n');

CREATE TABLE `hit_log` (
  `index` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(20) DEFAULT NULL,
  `path` text,
  `time` datetime DEFAULT NULL,
  `user_id` text,
  PRIMARY KEY (`index`),
  KEY `time` (`time`),
  KEY `ip` (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `printer_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` char(20) NOT NULL,
  `contest_id` int(11) NOT NULL,
  `code` text NOT NULL,
  `in_date` datetime DEFAULT NULL,
  `status` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `privilege_distribution` (
  `group_name` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `enter_admin_page` tinyint(4) DEFAULT NULL,
  `edit_default_problem` tinyint(4) DEFAULT NULL,
  `rejudge` tinyint(4) DEFAULT NULL,
  `edit_news` tinyint(4) DEFAULT NULL,
  `edit_contest` tinyint(4) DEFAULT NULL,
  `download_ranklist` tinyint(4) DEFAULT NULL,
  `generate_team` tinyint(4) DEFAULT NULL,
  `edit_user_profile` tinyint(4) DEFAULT NULL,
  `edit_privilege_group` tinyint(4) DEFAULT NULL,
  `edit_privilege_distribution` tinyint(4) DEFAULT NULL,
  `inner_function` tinyint(4) DEFAULT NULL,
  `see_hidden_default_problem` tinyint(4) DEFAULT NULL,
  `see_hidden_user_info` tinyint(4) DEFAULT NULL,
  `see_wa_info_out_of_contest` tinyint(4) DEFAULT NULL,
  `see_wa_info_in_contest` tinyint(4) DEFAULT NULL,
  `see_source_out_of_contest` tinyint(4) DEFAULT NULL,
  `see_source_in_contest` tinyint(4) DEFAULT NULL,
  `see_compare` tinyint(4) DEFAULT NULL,
  `upload_files` tinyint(4) DEFAULT NULL,
  `watch_solution_video` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`group_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;
INSERT INTO `privilege_distribution` VALUES ('administrator',1,1,1,1,1,1,1,1,1,0,0,1,1,1,1,1,1,1,1,1),('exam_user',1,0,1,0,1,0,0,0,0,0,0,0,1,1,1,1,1,1,0,0),('hznu_viewer',1,1,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,1,0),('root',1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1),('source_browser',1,0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,1,1,0,0),('teacher',1,1,1,0,1,1,0,1,0,0,0,1,1,1,1,1,1,1,1,1),('teacher_assistant',1,1,1,0,1,0,0,0,0,0,0,1,1,1,1,1,1,0,1,0);

CREATE TABLE `privilege_groups` (
  `group_order` int(11) NOT NULL DEFAULT '0',
  `group_name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`group_order`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;
INSERT INTO `privilege_groups` VALUES (0,'root'),(1,'administrator'),(2,'teacher'),(3,'teacher_assistant'),(4,'source_browser'),(5,'hznu_viewer'),(6,'exam_user');
-- DROP TABLE IF EXISTS `problem_samples`;
CREATE TABLE `problem_samples` (
  `problem_id` int(11) NOT NULL,
  `sample_id` int(11) NOT NULL DEFAULT '0',
  `input` text CHARACTER SET utf8,
  `output` text CHARACTER SET utf8,
  `show_after` int(11) DEFAULT '0',
  PRIMARY KEY (`problem_id`,`sample_id`),
  KEY `problem_id` (`problem_id`,`sample_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

CREATE TABLE `problemset` (
  `index` int(11) NOT NULL AUTO_INCREMENT,
  `set_name` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `set_name_show` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`index`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT AUTO_INCREMENT=2;
INSERT INTO `problemset` VALUES (1,'default','DEFAULT');

CREATE TABLE `reg_code` (
  `class_name` varchar(100) NOT NULL,
  `reg_code` varchar(100) NOT NULL,
  `remain_num` smallint(4) NOT NULL,
  PRIMARY KEY (`class_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO `reg_code` VALUES ('其它', '', '0');

CREATE TABLE `slide` (
  `img_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(200) NOT NULL,
  `defunct` char(1) DEFAULT NULL,
  PRIMARY KEY (`img_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `solution_video_watch_log` (
  `index` int(11) NOT NULL AUTO_INCREMENT,
  `video_id` int(11) NOT NULL,
  `user_id` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  PRIMARY KEY (`index`),
  KEY `video_id` (`video_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

CREATE TABLE `tag` (
  `index` int(11) NOT NULL AUTO_INCREMENT,
  `problem_id` int(10) unsigned zerofill NOT NULL,
  `user_id` varchar(100) CHARACTER SET utf8 NOT NULL,
  `tag` varchar(100) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`index`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `team` (
  `user_id` varchar(30) NOT NULL,
  `prefix` varchar(30) DEFAULT NULL,
  `NO` int(10) DEFAULT NULL,
  `password` varchar(32) NOT NULL,
  `nick` varchar(100) NOT NULL,
  `contest_id` int(13) NOT NULL,
  `stu_id` varchar(255) DEFAULT NULL,
  `institute` varchar(255) DEFAULT NULL,
  `class` varchar(30) DEFAULT NULL,
  `real_name` varchar(255) DEFAULT NULL,
  `seat` varchar(255) DEFAULT NULL,
  `school` varchar(100) DEFAULT NULL,
  `accesstime` datetime DEFAULT NULL,
  `reg_time` datetime DEFAULT NULL,
  `ip` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`contest_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `users_cache` (
  `user_id` varchar(48) NOT NULL,
  `class` varchar(15) CHARACTER SET utf8 NOT NULL,
  `AC_day` int(10) unsigned zerofill DEFAULT NULL,
  `sub_day` int(10) unsigned zerofill DEFAULT NULL,
  `activity` int(10) unsigned zerofill DEFAULT NULL,
  `total_score` decimal(10,2) unsigned zerofill DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
INSERT INTO `users_cache` VALUES ('admin','',0000000001,0000000001,NULL,NULL);

CREATE TABLE `users_cache_array` (
  `index` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(48) NOT NULL,
  `type` varchar(15) NOT NULL,
  `week` int(9) unsigned zerofill DEFAULT NULL,
  `value_int` int(10) unsigned zerofill NOT NULL,
  `value_double` decimal(10,2) unsigned zerofill DEFAULT NULL,
  PRIMARY KEY (`index`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `contest` ADD COLUMN `user_limit` char(1) NOT NULL DEFAULT 'N';
ALTER TABLE `contest` ADD COLUMN `defunct_TA` char(1) NOT NULL DEFAULT 'N';
ALTER TABLE `contest` ADD COLUMN `open_source` char(1) NOT NULL DEFAULT 'N';
ALTER TABLE `contest` ADD COLUMN `lock_time` int(11) DEFAULT NULL;
ALTER TABLE `contest` ADD COLUMN `unlock` tinyint(4) DEFAULT '1';
ALTER TABLE `contest` ADD COLUMN `first_prize` int(11) DEFAULT '0';
ALTER TABLE `contest` ADD COLUMN `second_prize` int(11) DEFAULT '0';
ALTER TABLE `contest` ADD COLUMN `third_prize` int(11) DEFAULT '0';
ALTER TABLE `contest` ADD COLUMN `practice` tinyint(4) DEFAULT NULL;
ALTER TABLE `contest` ADD INDEX `contest_id` (`contest_id`,`defunct`,`private`,`defunct_TA`,`open_source`) USING BTREE;
ALTER TABLE `contest` ADD INDEX `running_contest` (`start_time`,`end_time`,`practice`);
UPDATE `contest` SET `first_prize`=1,`second_prize`=3,`third_prize`=5;
-- 老版的hustoj没有user_id字段，新版有
DROP PROCEDURE IF EXISTS AddUser_id;
delimiter //
create procedure AddUser_id()
begin
    SELECT count(*) INTO @cnt FROM information_schema.columns WHERE `TABLE_SCHEMA` ='jol' AND `TABLE_NAME` = 'contest' AND `COLUMN_NAME` = 'user_id';
    IF @cnt=0 THEN
        ALTER TABLE `contest` ADD COLUMN `user_id` VARCHAR(48) NOT NULL DEFAULT 'admin' AFTER `password`;
    END IF;
end; //
delimiter ;
call AddUser_id();
drop procedure AddUser_id;
-- 写入比赛创建人 start 针对老版的hustoj
DROP PROCEDURE IF EXISTS updateContestCreator;
delimiter //
create procedure updateContestCreator()
begin  
  DECLARE cid int(11);
  DECLARE userid char(48);
  DECLARE done INT DEFAULT 0;
  DECLARE cur CURSOR FOR SELECT contest_id,p.user_id FROM contest LEFT JOIN (SELECT * FROM privilege WHERE rightstr LIKE 'm%') p ON CONCAT('m',contest_id)=rightstr ORDER BY contest_id;
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET done=1;
  IF @cnt=0 THEN
	OPEN cur;
    FETCH NEXT FROM cur INTO cid, userid;
    while(done<>1) do
      UPDATE contest SET user_id=userid WHERE contest_id=cid;
      FETCH NEXT FROM cur INTO cid, userid;
    end while;
    close cur;
  END IF;
end;//
delimiter ;
call updateContestCreator();
DROP PROCEDURE updateContestCreator;
-- 写入比赛创建人 end
-- 更新比赛的语言掩码，和hustoj的掩码每位都相反 start
DROP PROCEDURE IF EXISTS transLangmask;
delimiter //
create procedure transLangmask()
begin
  DECLARE cid int(11);
  DECLARE mask int(10);
  DECLARE masklen int(10);
  DECLARE done int DEFAULT 0;
  DECLARE i int;
  DECLARE cur CURSOR FOR SELECT contest_id,langmask FROM contest WHERE langmask<>262143 ORDER BY contest_id;
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET done=1;  
  UPDATE contest SET langmask=262143 WHERE langmask=0;
  OPEN cur;
  FETCH NEXT FROM cur INTO cid, mask;
  while(done<>1) do
	  SET masklen=length(bin(mask));
	  SET i=0;
    while(i<masklen) do
      SET mask=mask^(1<<i);
      SET i=i+1;
    end while;
    UPDATE contest SET langmask=mask WHERE contest_id=cid;
    FETCH NEXT FROM cur INTO cid, mask;
  end while;
  close cur;
end;//
delimiter ;
call transLangmask();
DROP PROCEDURE transLangmask;
-- 更新比赛的语言掩码 end

-- 新版hustoj新增了几个字段和索引，老版没有
DROP PROCEDURE IF EXISTS AddCOLUMN;
delimiter //
create procedure AddCOLUMN()
begin
    SELECT count(*) INTO @cnt FROM information_schema.columns WHERE `TABLE_SCHEMA` ='jol' AND `TABLE_NAME` = 'contest_problem' AND `COLUMN_NAME` = 'c_accepted';
    IF @cnt=0 THEN
        ALTER TABLE `contest_problem` ADD COLUMN `c_accepted` int(11) NOT NULL DEFAULT '0' AFTER `num`;
    END IF;
    SELECT count(*) INTO @cnt FROM information_schema.columns WHERE `TABLE_SCHEMA` ='jol' AND `TABLE_NAME` = 'contest_problem' AND `COLUMN_NAME` = 'c_submit';
    IF @cnt=0 THEN
        ALTER TABLE `contest_problem` ADD COLUMN `c_submit` int(11) NOT NULL DEFAULT '0' AFTER `c_accepted`;
    END IF;
    SELECT count(*) INTO @cnt FROM information_schema.statistics WHERE `TABLE_SCHEMA` ='jol' AND table_name='contest_problem' AND index_name='Index_contest_id' ;
    IF @cnt=0 THEN
	    ALTER TABLE `contest_problem` ADD INDEX `Index_contest_id` (`contest_id`);
    END IF;
end; //
delimiter ;
call AddCOLUMN();
drop procedure AddCOLUMN;
ALTER TABLE `contest_problem` ADD COLUMN `index` int(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`index`);
ALTER TABLE `contest_problem` ADD COLUMN `score` int(11) NOT NULL DEFAULT '100';
ALTER TABLE `contest_problem` ADD INDEX `problem_id` (`problem_id`);

ALTER TABLE `loginlog` ADD COLUMN `index` int(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`index`);

ALTER TABLE `privilege` ADD COLUMN `index` int(11) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`index`);
INSERT INTO `privilege`(`user_id`,`rightstr`) VALUES ('admin','root');

ALTER TABLE `problem` ADD  COLUMN `author` varchar(30) DEFAULT NULL AFTER `hint`;
ALTER TABLE `problem` ADD COLUMN `solved_user` int(9) DEFAULT '0';
ALTER TABLE `problem` ADD COLUMN `submit_user` int(9) DEFAULT NULL;
ALTER TABLE `problem` ADD COLUMN `score` decimal(6,2) unsigned DEFAULT '100.00';
ALTER TABLE `problem` ADD COLUMN `tag1` varchar(250) DEFAULT NULL;
ALTER TABLE `problem` ADD COLUMN `tag2` varchar(250) DEFAULT NULL;
ALTER TABLE `problem` ADD COLUMN `tag3` varchar(250) DEFAULT NULL;
ALTER TABLE `problem` ADD COLUMN `problemset` varchar(255) DEFAULT NULL;
ALTER TABLE `problem` ADD UNIQUE `problem_id` (`problem_id`) USING BTREE;
ALTER TABLE `problem` ADD INDEX `score` (`score`,`accepted`);
UPDATE `problem` SET `problemset`='default';
UPDATE `problem` SET `source`=REPLACE(`source`,'&nbsp ','_');
UPDATE `problem` SET `source`=REPLACE(`source`,'&nbsp;','_');
UPDATE `problem` SET `source`='' WHERE `source`='\r\n';
UPDATE `problem` SET `source`='' WHERE `source`='\n';
UPDATE `problem` SET `source`=REPLACE(`source`,'\r\n',' ');
UPDATE `problem` SET `source`=REPLACE(`source`,'\n',' ');
-- 命题人更新， hustoj是从privilege中查如p1000/p1001，查不到就是外部导入 start
DROP PROCEDURE IF EXISTS updateAuthor;
delimiter //
create procedure updateAuthor()
begin
  DECLARE pid int(11);    
  DECLARE userid char(48);
  DECLARE done INT DEFAULT 0;
  DECLARE cur CURSOR FOR SELECT problem_id,p.user_id FROM problem ,(SELECT * FROM privilege WHERE rightstr LIKE 'p%') p WHERE CONCAT('p',problem_id)=rightstr ORDER BY problem_id;
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET done=1;
  OPEN cur;
  FETCH NEXT FROM cur INTO pid, userid;
  while(done<>1) do
    UPDATE problem SET author=userid WHERE problem_id=pid;
    FETCH NEXT FROM cur INTO pid, userid;
  end while;
  close cur;
end;//
delimiter ;
call updateAuthor();
DROP PROCEDURE updateAuthor;
-- 命题人更新 end

-- 转移题目中样例数据到表`problem_samples` start
DROP PROCEDURE IF EXISTS updateSamples;
delimiter //
create procedure updateSamples()
begin
  DECLARE pid int(11);
  DECLARE s_input text;
  DECLARE s_output text;
  DECLARE done INT DEFAULT 0;
  DECLARE cur CURSOR FOR SELECT problem_id,sample_input,sample_output FROM problem ORDER BY problem_id;
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET done=1;
  OPEN cur;
  FETCH NEXT FROM cur INTO pid, s_input, s_output;  
  while(done<>1) do
    INSERT INTO problem_samples(problem_id,`input`,`output`) VALUES(pid, s_input, s_output);
    FETCH NEXT FROM cur INTO pid, s_input, s_output;
  end while;
  close cur;
end;//
delimiter ;
call updateSamples();
DROP PROCEDURE updateSamples;
-- 转移题目中样例数据到表`problem_samples` end

DROP PROCEDURE IF EXISTS Add_sim_idx;
delimiter //
create procedure Add_sim_idx()
begin
  SELECT count(*) INTO @cnt FROM information_schema.statistics WHERE `TABLE_SCHEMA` ='jol' AND table_name='sim' AND index_name='Index_sim_id' ;
  IF @cnt=0 THEN
	ALTER TABLE `sim` ADD INDEX `Index_sim_id` (`sim_s_id`);
  END IF;
end;//
delimiter ;
call Add_sim_idx();
DROP PROCEDURE Add_sim_idx;

-- 老版的hustoj没有nick字段，新版有
DROP PROCEDURE IF EXISTS AddNick;
delimiter //
create procedure AddNick()
begin
    SELECT count(*) INTO @cnt FROM information_schema.columns WHERE `TABLE_SCHEMA` ='jol' AND `TABLE_NAME` = 'solution' AND `COLUMN_NAME` = 'nick';
    IF @cnt=0 THEN
        ALTER TABLE `solution` ADD COLUMN `nick` char(20) NOT NULL DEFAULT '' AFTER `user_id`;
    END IF;
end; //
delimiter ;
call AddNick();
drop procedure AddNick;

ALTER TABLE `solution` ADD INDEX `in_date` (`in_date`) USING BTREE;

ALTER TABLE `users` ADD COLUMN `stu_id` varchar(20) DEFAULT NULL AFTER `user_id`;
ALTER TABLE `users` ADD COLUMN `volume_c` int(11) DEFAULT NULL AFTER `volume`;
ALTER TABLE `users` ADD COLUMN `real_name` varchar(100) DEFAULT NULL AFTER `reg_time`;
ALTER TABLE `users` ADD COLUMN `class` varchar(127) DEFAULT NULL;
ALTER TABLE `users` ADD COLUMN `level` varchar(20) DEFAULT NULL;
ALTER TABLE `users` ADD COLUMN `strength` double(10,2) DEFAULT NULL;
ALTER TABLE `users` ADD COLUMN `color` varchar(20) DEFAULT NULL;
ALTER TABLE `users` ADD COLUMN `ZJU` int(9) unsigned DEFAULT NULL;
ALTER TABLE `users` ADD COLUMN `HDU` int(9) unsigned DEFAULT NULL;
ALTER TABLE `users` ADD COLUMN `PKU` int(9) unsigned DEFAULT NULL;
ALTER TABLE `users` ADD COLUMN `UVA` int(9) unsigned DEFAULT NULL;
ALTER TABLE `users` ADD COLUMN `CF` int(9) DEFAULT NULL;
ALTER TABLE `users` ADD COLUMN `like` int(9) DEFAULT '0';
ALTER TABLE `users` ADD COLUMN `dislike` int(9) DEFAULT '0';
ALTER TABLE `users` ADD COLUMN `tag` varchar(250) DEFAULT NULL;
UPDATE `users` SET `class`='其它';

-- 老版的hustoj没有表`share_code`，新版有
CREATE TABLE IF NOT EXISTS `share_code` (
  `share_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(48) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `share_code` text COLLATE utf8mb4_unicode_ci,
  `language` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `share_time` datetime DEFAULT NULL,
  PRIMARY KEY (`share_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8;