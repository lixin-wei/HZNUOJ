<?php $title="Programming Fundamentals"; ?>
<?php
require_once('./include/db_info.inc.php');
require_once('./include/setlang.php'); 
?>
<?php require_once "template/".$OJ_TEMPLATE."/header.php"; ?>
  <style>
    .box{
      border: 1px solid #eee;
      padding: 30px;
      margin: 25px 0 15px 0;
      box-shadow: 2px 2px 10px 0 #ccc;
    }
    .class-name-ch{
      font-size: xx-large;
    }
    .class-name-en{
      
    }
    .class-title{
      padding-bottom: 15px;
    }
    .class-description{
      color: #515151;
    }
    .content-block{
      margin-bottom: 50px;
    }
    .content-block:last-child{
      margin-bottom: 15px;
    }
    .content-block-title{
      font-size: large;
      font-weight: bold;
      border-bottom: 1px solid #eee;
      margin-bottom: 10px;
    }
    .content-block-body{
      padding-left: 20px;
    }
    .detail-table{
      width: 100%;
      -ms-word-break: break-all;
      word-break: break-all;
    }
    .detail-table>tbody>tr>td{
      border-left: 1px solid #eee;
      border-bottom: 1px solid #eee;
      padding: 10px;
    }
    .detail-table tr td:first-child{
      border-left: 0;
    }
    .detail-table tbody tr:last-child td{
      border-bottom: 0;
    }
    .class-score{
      float: right;
    }
  </style>
  <div class="am-container" style="padding-top: 30px;">
    <div class="box">
      <div class="class-title">
        <div class="class-name-ch">
          <span>程序设计基础</span>
          <div class="class-score">
            <span class="am-badge am-badge-success am-text-xl">学分：3+2</span>
          </div>
        </div>
        <div class="class-name-en">
          Programming Fundamentals
        </div>
      </div>
      <div class="class-description">
        《程序设计基础》是高等学校计算机科学与技术、软件工程等专业的一门重要的基础课程。是后续课程的先修课。<br>
          <a href="./upload/file/20180916/20180916214410_30851.doc">程序设计基础教学大纲</a>&nbsp;
          <a href="./upload/file/20180916/20180916214446_68972.doc">程序设计基础实践教学大纲</a>
      </div>
    </div>
    <div class="am-g">
      <div class="am-u-md-8">
        <div class="box">
          <div class="content-block">
            <div class="content-block-title">详细信息</div>
            <div class="content-block-body">
              <table width="841" class="detail-table">
                <tbody>
                <tr>
                  <td colspan="2" >
                    <p>开课对象</p>
                  </td>
                  <td colspan="5" >
                    <p>计算机系</p>
                  </td>
                  <td >
                    <p>授课人数</p>
                  </td>
                  <td colspan="6" >
                    <p>50 </p>
                  </td>
                </tr>
                <tr>
                  <td colspan="2" >
                    <p>课程类别</p>
                  </td>
                  <td colspan="5" >
                    <p>大类基础课程</p>
                  </td>
                  <td >
                    <p>课程性质</p>
                  </td>
                  <td colspan="6" >
                    <p>必修</p>
                  </td>
                </tr>
                <tr>
                  <td colspan="2" >
                    <p>课程类型</p>
                  </td>
                  <td colspan="5" >
                    <p>理论课（含实践）</p>
                  </td>
                  <td >
                    <p>教学周起止</p>
                  </td>
                  <td colspan="6" >
                    <p>2&mdash;&mdash;17</p>
                  </td>
                </tr>
                <tr>
                  <td colspan="2" rowspan="2" >
                    <p>总学时</p>
                    <p>（周学时）</p>
                  </td>
                  <td colspan="2" rowspan="2" >
                    <p>80</p>
                  </td>
                  <td >
                    <p>理论讲授</p>
                  </td>
                  <td colspan="2" >
                    <p>48</p>
                  </td>
                  <td >
                    <p>授课时间</p>
                  </td>
                  <td colspan="2" >
                    <p> </p>
                  </td>
                  <td colspan="2" >
                    <p>授课地点</p>
                  </td>
                  <td colspan="2" >
                    <p>勤园</p>
                  </td>
                </tr>
                <tr>
                  <td style="border-left: 1px solid #eee;">
                    <p>实验（践）</p>
                  </td>
                  <td colspan="2" >
                    <p>32</p>
                  </td>
                  <td >
                    <p>实验项目数</p>
                  </td>
                  <td colspan="6" >
                    <p>每章安排实验（均为综合性、设计性实验）</p>
                  </td>
                </tr>
                <tr>
                  <td colspan="2" >
                    <p>授课教师</p>
                  </td>
                  <td colspan="3" >
                    <p>单振宇</p>
                  </td>
                  <td colspan="2" >
                    <p>联系方式</p>
                  </td>
                  <td colspan="7" >
                    <p>电话：13666644516</p>
                    <p>e-mail：shanzhenyu@zju.edu.cn</p>
                    <p>周三下午答疑 地点：勤园13号楼623/404</p>
                  </td>
                </tr>
                <tr>
                    <td colspan="2" >
                        <p>授课教师</p>
                    </td>
                    <td colspan="3" >
                        <p>胡斌 </p>
                    </td>
                    <td colspan="2" >
                        <p>联系方式</p>
                    </td>
                    <td colspan="7" >
                        <p>电话：13857178115</p>
                        <p>e-mail：bin@hznu.edu.cn</p>
                        <p>周三下午答疑 地点：勤园11号楼402</p>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" >
                        <p>授课教师</p>
                    </td>
                    <td colspan="3" >
                        <p>周炯</p>
                    </td>
                    <td colspan="2" >
                        <p>联系方式</p>
                    </td>
                    <td colspan="7" >
                        <p>电话：13958112405</p>
                        <p>e-mail：181199085@qq.com</p>
                        <p>周三下午答疑 地点：勤园13号楼627</p>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" >
                        <p>授课教师</p>
                    </td>
                    <td colspan="3" >
                        <p>孙军梅</p>
                    </td>
                    <td colspan="2" >
                        <p>联系方式</p>
                    </td>
                    <td colspan="7" >
                        <p>电话：18958190051</p>
                        <p>e-mail：184036895@qq.com</p>
                        <p>周三下午答疑 地点：勤园12号楼415</p>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" >
                        <p>授课教师</p>
                    </td>
                    <td colspan="3" >
                        <p>陶利民</p>
                    </td>
                    <td colspan="2" >
                        <p>联系方式</p>
                    </td>
                    <td colspan="7" >
                        <p>电话：13750836501</p>
                        <p>e-mail：tlm5460@163.com</p>
                        <p>周三下午答疑 地点：勤园11号楼503</p>
                    </td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="content-block">
            <div class="content-block-title">
              课程描述及与其他课程关系
            </div>
            <div class="content-block-body">
              <p>《程序设计基础》是高等学校计算机科学与技术、软件工程等专业的一门重要的基础课程。是后续课程的先修课。</p>
            </div>
          </div>
          <div class="content-block">
            <div class="content-block-title">
              使用教材与参考书目
            </div>
            <div class="content-block-body">
              <p>《程序设计基础&mdash;&mdash;以C为例》，虞歌，清华大学出版社，2012.10</p>
            </div>
          </div>
          <div class="content-block">
            <div class="content-block-title">
              课程考核
            </div>
            <div class="content-block-body">
              <p>1. 成绩构成和比例</p>
              <p>期末20%，平时80%，平时成绩由刷题成绩（40%）、上机考试成绩（30%）和平时表现（10%）组成</p>
              <p>2. 期末成绩</p>
              <p>全部为客观题，包括判断题、选择题、概念填空题、程序填空题和程序改错题，总分55分，换算成百分制。</p>
              <p>3. 刷题成绩</p>
              <p>体现学生平时作业刷题数和实验刷题数。</p>
              <p>a.作业刷题数目为hsacm.cn上统计的题目总数，截止2016年1月13日早6点；</p>
              <p>b.OJ刷题数目为各大OJ上统计的题目总数，截止2016年1月13日早6点；</p>
              <p>c.得分=min(120,作业刷题数目)*0.2+min(80,作业刷题数目-120+OJ刷题数目)*0.2；</p>
              <p>4. 上机成绩</p>
              <p>为四次上机考试的平均分，每次上机考试的评分标准如下：</p>
              <p>a. 第一次：前四题每题20分，后两题每题10分，附加题不算分。</p>
              <p>b. 第二次：按班级排名给分，第一名100分，后面递减。</p>
              <p>c. 第三次：全部完成100分，少一题减10分。</p>
              <p>d. 第四次：每题16.6分，附加题不算分。</p>
              <p>注：四种方式混合使用，防止学生作弊。</p>
              <p>5. 平时表现</p>
              <p>机器自动打分考虑5大指标：</p>
              <p>a.学生完成程序所占内存和运行速度；</p>
              <p>b.代码抄袭情况（重复情况）；</p>
              <p>c.刷题时间分布；</p>
              <p>d.完成题目的难度；</p>
              <p>完成积极性；</p>
            </div>
          </div>
          <div class="content-block">
            <div class="content-block-title">
              教学方法与手段及相关要求
            </div>
            <div class="content-block-body">
              <p>课堂教学与上机实验相结合，课堂教学约占总学时的3/5，上机实验约占总学时的2/5，并尽力创造条件鼓励学生利用业余时间多上机实践。使学生理论知识和实践技能得到共同发展，提高分析问题、解决问题的能力。</p>
              <p>在条件具备的前提下，充分利用多媒体手段、利用在线程序自动评判系统来改进教学效果、提高教学效率。</p>
            </div>
          </div>
          <div class="content-block">
            <div class="content-block-title">
              教学网站
            </div>
            <div class="content-block-body">
              <p>在线实验和考试平台： <a href="http://acm.hznu.edu.cn" title="">http://acm.hznu.edu.cn</a>， <a href="http://cai.hznu.edu.cn/pe">http://cai.hznu.edu.cn/pe</a></p>
            </div>
          </div>
          <div class="content-block">
            <div class="content-block-title">
              本学期教学目的与要求
            </div>
            <div class="content-block-body">
              <p>学习本课程旨在使学生掌握C语言的基本语法、基本语句、基本控制结构以及自顶向下的结构化程序设计方法，培养学生良好的程序设计风格和熟练使用C语言解决实际问题的能力，为学生进一步学习其他专业课程和今后从事软件开发工作打下坚实的基础。</p>
              <p>《程序设计基础》是一门实践性很强的课程。本课程每章安排了作业和实验，实验进度与教学进度同步，使学生经过一定数量的上机训练，加深对课堂教学内容的理解。</p>
            </div>
          </div>
          <div class="content-block">
            <div class="content-block-title">
              其他需说明事项
            </div>
            <div class="content-block-body">
              <p>学习本课程旨在使学生掌握C语言的基本语法、基本语句、基本控制结构以及自顶向下的结构化程序设计方法，培养学生良好的程序设计风格和熟练使用C语言解决实际问题的能力，为学生进一步学习其他专业课程和今后从事软件开发工作打下坚实的基础。</p>
              <p>《程序设计基础》是一门实践性很强的课程。本课程每章安排了作业和实验，实验进度与教学进度同步，使学生经过一定数量的上机训练，加深对课堂教学内容的理解。</p>
            </div>
          </div>
          <div class="content-block">
            <div class="content-block-title">
              章节分布及课程资料
            </div>
            <div class="content-block-body">
              <table class="detail-table">
                <tr>
                  <td >
                    <p>教学周<a href="#_edn4" name="_ednref4">[iv]</a></p>
                  </td>
                  <td colspan="2" >
                    <p>课时</p>
                  </td>
                  <td colspan="3" >
                    <p>主要教学内容</p>
                    <p>（实验项目与要求）</p>
                  </td>
                  <td colspan="3" >
                    <p>&nbsp;教学形式、方法、修读书目及篇章</p>
                  </td>
                  <td colspan="4" >
                    <p>作业布置与辅导安排</p>
                  </td>
                  <td >
                    <p>资源下载</p>
                  </td>
                </tr>
                <tr>
                  <td >
                    <p>第 2 周</p>
                  </td>
                  <td colspan="2" >
                    <p>5</p>
                  </td>
                  <td colspan="3" >
                    <p>第1章</p>
                    <p>程序设计概述</p>
                  </td>
                  <td colspan="3" >
                    <p>讲授（3）</p>
                    <p>实验（2）</p>
                  </td>
                  <td colspan="4" >
                    <p>作业一（题库第1套）</p>
                    <p>实验一（题库第1套）</p>
                  </td>
                  <td >
                    <p><a href="upload/course_doc/自学内容_程序设计概述.pdf">自学内容_程序设计概述</a></p>
                    <p><a href="upload/course_doc/习题解答1.pdf">习题解答1</a></p>
                    <p><a href="upload/course_doc/PPT-C01.pdf">PPT-C01</a></p>
                    <p><a href="https://space.bilibili.com/6259531/#/channel/detail?cid=51097">视频地址</a></p>
                  </td>
                </tr>
                <tr>
                  <td >
                    <p>第 3 周</p>
                    <p>国庆</p>
                  </td>
                  <td colspan="2" >
                    <p>3</p>
                  </td>
                  <td colspan="3" >
                    <p>第2章</p>
                    <p>C语言程序设计入门（1）</p>
                  </td>
                  <td colspan="3" >
                    <p>讲授（3）</p>
                  </td>
                  <td colspan="4" >
                    <p>无</p>
                  </td>
                  <td rowspan="3" >
                    <p><a href="upload/course_doc/自学内容_C语言程序设计入门.pdf">自学内容_C语言程序设计入门</a></p>
                    <p><a href="upload/course_doc/习题解答2.pdf">习题解答2</a></p>
                    <p><a href="upload/course_doc/PPT-C02.pdf">PPT-C02</a></p>
                    <p><a href="https://www.bilibili.com/video/av31127977">视频地址</a></p>
                  </td>
                </tr>
                <tr>
                  <td >
                    <p>第 4 周</p>
                    <p>国庆</p>
                  </td>
                  <td colspan="2" >
                    <p>2</p>
                  </td>
                  <td colspan="3" >
                    <p>第2章</p>
                    <p>C语言程序设计入门（1）</p>
                  </td>
                  <td colspan="3" >
                    <p>实验（2）</p>
                  </td>
                  <td colspan="4" >
                    <p>实验二（1）（题库第2套）</p>
                  </td>
                </tr>
                <tr>
                  <td >
                    <p>第 5 周</p>
                  </td>
                  <td colspan="2" >
                    <p>5</p>
                  </td>
                  <td colspan="3" >
                    <p>第2章</p>
                    <p>C语言程序设计入门（2）</p>
                  </td>
                  <td colspan="3" >
                    <p>讲授（3）</p>
                    <p>实验（2）</p>
                  </td>
                  <td colspan="4" >
                    <p>作业二（题库第2套）</p>
                    <p>实验二（2）（题库第2套）</p>
                  </td>
                </tr>
                <tr>
                  <td >
                    <p>第 6 周</p>
                    <p>运动会</p>
                  </td>
                  <td colspan="2" >
                    <p>3</p>
                  </td>
                  <td colspan="3" >
                    <p>第3章</p>
                    <p>语句和基本控制结构（1）</p>
                  </td>
                  <td colspan="3" >
                    <p>讲授（3）</p>
                  </td>
                  <td colspan="4" >
                    <p>实验三（1）分支（题库第3套）</p>
                    <p>(课后完成)</p>
                    <p>完成第一次上机测试</p>
                  </td>
                  <td rowspan="2" >
                    <p><a href="upload/course_doc/自学内容_分支结构.pdf">自学内容_分支结构</a></p>
                    <p><a href="upload/course_doc/自学内容_循环结构.pdf">自学内容_循环结构</a></p>
                    <p><a href="upload/course_doc/习题解答3.pdf">习题解答3</a></p>
                    <p><a href="upload/course_doc/PPT-C03.pdf">PPT-C03</a></p>
                    <p><a href="https://www.bilibili.com/video/av31132112">视频地址</a></p>
                  </td>
                </tr>
                <tr>
                  <td >
                    <p>第 7 周</p>
                  </td>
                  <td colspan="2" >
                    <p>5</p>
                  </td>
                  <td colspan="3" >
                    <p>第3章</p>
                    <p>语句和基本控制结构（2）</p>
                  </td>
                  <td colspan="3" >
                    <p>讲授（3）</p>
                    <p>实验（2）</p>
                  </td>
                  <td colspan="4" >
                    <p>作业三（题库第3套）</p>
                    <p>实验三（2）循环（题库第4套）</p>
                  </td>
                </tr>
                <tr>
                  <td >
                    <p>第 8 周</p>
                  </td>
                  <td colspan="2" >
                    <p>5</p>
                  </td>
                  <td colspan="3" >
                    <p>第4章函数（1）</p>
                  </td>
                  <td colspan="3" >
                    <p>讲授（3）</p>
                    <p>实验（2）</p>
                  </td>
                  <td colspan="4" >
                    <p>补充实验：输入输出训练（题库第11套）</p>
                  </td>
                  <td rowspan="2" >
                    <p><a href="upload/course_doc/自学内容_函数.pdf">自学内容_函数</a></p>
                    <p><a href="upload/course_doc/习题解答4.pdf">习题解答4</a></p>
                    <p><a href="upload/course_doc/PPT-C04.pdf">PPT-C04</a></p>
                    <p><a href="https://www.bilibili.com/video/av31132779">视频地址</a></p>
                  </td>
                </tr>
                <tr>
                  <td >
                    <p>第 9 周</p>
                  </td>
                  <td colspan="2" >
                    <p>5</p>
                  </td>
                  <td colspan="3" >
                    <p>第4章函数（2）</p>
                  </td>
                  <td colspan="3" >
                    <p>讲授（3）</p>
                    <p>实验（2）</p>
                  </td>
                  <td colspan="4" >
                    <p>作业四（题库第4套）</p>
                    <p>实验四（题库第5套）</p>
                  </td>
                </tr>
                <tr>
                  <td >
                    <p>第 10 周</p>
                  </td>
                  <td colspan="2" >
                    <p>5</p>
                  </td>
                  <td colspan="3" >
                    <p>第5章指针（1）</p>
                  </td>
                  <td colspan="3" >
                    <p>讲授（3）</p>
                    <p>实验（2）</p>
                  </td>
                  <td colspan="4" >
                    <p>期中上机考试（安排实验课时间）</p>
                    <p>完成第二次上机测试</p>
                  </td>
                  <td rowspan="2" >
                    <p><a href="upload/course_doc/自学内容_指针.pdf">自学内容_指针</a></p>
                    <p><a href="upload/course_doc/习题解答5.pdf">习题解答5</a></p>
                    <p><a href="upload/course_doc/PPT-C05.pdf">PPT-C05</a></p>
                    <p><a href="https://www.bilibili.com/video/av31132948">视频地址</a></p>
                  </td>
                </tr>
                <tr>
                  <td >
                    <p>第 11 周</p>
                  </td>
                  <td colspan="2" >
                    <p>5</p>
                  </td>
                  <td colspan="3" >
                    <p>第5章指针（2）</p>
                  </td>
                  <td colspan="3" >
                    <p>讲授（3）</p>
                    <p>实验（2）</p>
                  </td>
                  <td colspan="4" >
                    <p>作业五（题库5套）</p>
                    <p>实验五（题库第6套）</p>
                  </td>
                </tr>
                <tr>
                  <td >
                    <p>第 12 周</p>
                  </td>
                  <td colspan="2" >
                    <p>5</p>
                  </td>
                  <td colspan="3" >
                    <p>第6章数组（1）</p>
                  </td>
                  <td colspan="3" >
                    <p>讲授（3）</p>
                    <p>实验（2）</p>
                  </td>
                  <td colspan="4" >
                    <p>实验六（1）数组（题库第7套）</p>
                  </td>
                  <td rowspan="2" >
                    <p><a href="upload/course_doc/自学内容_数组.pdf">自学内容_数组</a></p>
                    <p><a href="upload/course_doc/习题解答6.pdf">习题解答6</a></p>
                    <p><a href="upload/course_doc/PPT-C06.pdf">PPT-C06</a></p>
                    <p><a href="https://www.bilibili.com/video/av31133040">视频地址</a></p>
                  </td>
                </tr>
                <tr>
                  <td >
                    <p>第 13 周</p>
                  </td>
                  <td colspan="2" >
                    <p>5</p>
                  </td>
                  <td colspan="3" >
                    <p>第6章数组（2）</p>
                  </td>
                  <td colspan="3" >
                    <p>讲授（3）</p>
                    <p>实验（2）</p>
                  </td>
                  <td colspan="4" >
                    <p>作业六（题库第6套）</p>
                    <p>实验六（2）字符串（题库第8套）</p>
                    <p>完成第三次上机测试</p>
                  </td>
                </tr>
                <tr>
                  <td >
                    <p>第 14 周</p>
                  </td>
                  <td colspan="2" >
                    <p>5</p>
                  </td>
                  <td colspan="3" >
                    <p>第7章结构（1）</p>
                  </td>
                  <td colspan="3" >
                    <p>讲授（3）</p>
                    <p>实验（2）</p>
                  </td>
                  <td colspan="4" >
                    <p>实验七（1）（题库第9套）</p>
                  </td>
                  <td rowspan="2" >
                    <p><a href="upload/course_doc/自学内容_结构.pdf">自学内容_结构</a></p>
                    <p><a href="upload/course_doc/习题解答7.pdf">习题解答7</a></p>
                    <p><a href="upload/course_doc/PPT-C07.pdf">PPT-C07</a></p>
                    <p><a href="https://www.bilibili.com/video/av31133112">视频地址</a></p>
                  </td>
                </tr>
                <tr>
                  <td >
                    <p>第15 周</p>
                  </td>
                  <td colspan="2" >
                    <p>5</p>
                  </td>
                  <td colspan="3" >
                    <p>第7章结构（2）</p>
                  </td>
                  <td colspan="3" >
                    <p>讲授（3）</p>
                    <p>实验（2）</p>
                  </td>
                  <td colspan="4" >
                    <p>作业七（题库第7套）</p>
                    <p>实验七（2）（题库第9套）</p>
                  </td>
                </tr>
                <tr>
                  <td >
                    <p>第 16 周</p>
                  </td>
                  <td colspan="2" >
                    <p>5</p>
                  </td>
                  <td colspan="3" >
                    <p>第8章文件</p>
                  </td>
                  <td colspan="3" >
                    <p>讲授（3）</p>
                    <p>实验（2）</p>
                  </td>
                  <td colspan="4" >
                    <p>作业八（题库第8套）</p>
                    <p>实验八（题库第10套）</p>
                  </td>
                  <td >
                    <p><a href="upload/course_doc/自学内容_文件.pdf">自学内容_文件</a></p>
                    <p><a href="upload/course_doc/习题解答8.pdf">习题解答8</a></p>
                    <p><a href="upload/course_doc/PPT-C08.pdf">PPT-C08</a></p>
                    <p><a href="https://www.bilibili.com/video/av31133216">视频地址</a></p>
                  </td>
                </tr>
                <tr>
                  <td >
                    <p>第 17 周</p>
                  </td>
                  <td colspan="2" >
                    <p>3</p>
                  </td>
                  <td colspan="3" >
                    <p>第9章编写多文件程序</p>
                  </td>
                  <td colspan="3" >
                    <p>讲授（3）</p>
                    <p>实验（2）</p>
                  </td>
                  <td colspan="4" >
                    <p>实验九</p>
                  </td>
                  <td >
                    <p><a href="upload/course_doc/PPT-C09.pdf">PPT-C09</a></p>
                  </td>
                </tr>
                <tr>
                  <td >
                    <p>第 18周</p>
                  </td>
                  <td colspan="2" >
                    <p>2</p>
                  </td>
                  <td colspan="3" >
                    <p>期末考试</p>
                  </td>
                  <td colspan="3" >
                    <p>&nbsp;</p>
                  </td>
                  <td colspan="4" >
                    <p>上机考试</p>
                  </td>
                  <td >
                    <p>&nbsp;</p>
                  </td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="am-u-md-4">
        <div class="box" data-am-sticky="{top:60}">
          <div class="content-block">
            <div class="content-block-title">
              授课班级
            </div>
            <div class="content-block-body">
                <?php
                $sql="SELECT DISTINCT class FROM users WHERE class LIKE '软工%' OR class LIKE '计算机%' OR class LIKE'物联网%' OR class LIKE '信息%' ORDER BY class DESC";
                $res=$mysqli->query($sql);
                while($row=$res->fetch_array()) {
                    $text=htmlentities($row[0]);
                    echo<<<HTML
                                    <span class="am-badge am-badge-primary">$text</span>
HTML;
                    
                }
                ?>
<!--                <span class="am-badge am-badge-primary">信息与服务工程类171-178</span>-->
<!--                <span class="am-badge am-badge-primary">信息与服务工程类181-188</span>-->
            </div>
          </div>
          <div class="content-block">
            <div class="content-block-title">
              题集
            </div>
            <div class="content-block-body">
              <a href="./problemset.php?OJ=c">ProblemSet - C Course</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div><!-- container -->

<?php require_once "template/".$OJ_TEMPLATE."/footer.php" ?>
