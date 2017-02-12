<?php
  /**
   * This file is created
   * by yybird
   * @2016.03.23
   * last modified
   * by yybird
   * @2016.03.23
  **/
?>
<?php $title="F.A.Qs";?>
<?php require_once("header.php"); ?>
<style>
  .red {
    color: red;
  }
  .green {
    color: green;
  }
  .box{
    border: 1px solid #eee;
    padding: 30px;
    margin: 25px 0 15px 0;
    box-shadow: 2px 2px 10px 0 #ccc;
  }
  .qa {
    padding-top: 10px;
    padding-bottom: 10px;
    margin-bottom: 20px;
    border-bottom: 1px solid #eee;
  }
  #title-index {
    font-size: 95%;
  }
</style>
<div class="am-container">
  <h1 style="margin-top: 50px;">HZNU Online Judge FAQ</h1>
  <hr>
  <div class="am-g">
    <div class="am-u-md-3">
      <div class="box" data-am-sticky="{top:60}">
        <ul id="title-index" class="am-list">
          <h2>Content</h2>
        </ul>
      </div>
    </div>
  
    <div class="am-u-md-9">
      <div class="box">
        <div class="qa" id="p-1">
          <div class="question"><span class="red">Q: </span><span class="title">这个在线裁判系统使用什么样的编译器和编译选项?</span></div>
          <div class="answer"><span class="green">A: </span>系统运行于<a href="http://www.ubuntu.com">Ubuntu 14.04</a><br>
            <br>
            对应的编译器和编译选项如下:<br>
          </div>
    
          <!-- 编译器选项表格 start -->
          <table class="am-table am-table-bordered am-table-striped am-table-compact am-text-center">
            <tr>
              <td>语言</td>
              <td>编译器版本</td>
              <td>编译选项</td>
            </tr>
            <tr>
              <td>C</td>
              <td>gcc 4.8.2</td>
              <td>gcc Main.c -o Main  -fno-asm -O2 -Wall -lm --static -std=c99 -DONLINE_JUDGE</td>
            </tr>
            <tr>
              <td>C++</td>
              <td>g++ 4.8.2</td>
              <td>g++ Main.cc -o Main  -fno-asm -O2 -Wall -lm --static  -std=c++11 -DONLINE_JUDGE</td>
            </tr>
            <tr>
              <td>Pascal</td>
              <td>Free Pascal 2.6.2</td>
              <td>fpc Main.pas -oMain -O1 -Co -Cr -Ct -Ci</td>
            </tr>
            <tr>
              <td>Java</td>
              <td>openjdk 1.7.0_79</td>
              <td>javac -J-Xms32m -J-Xmx256m Main.java
                <br>
                <span style="color: grey;">
              Languages except C/C++ has 2 more seconds and 128M more memory when running and judging.
            </span>
              </td>
            </tr>
            <tr>
              <td>Ruby</td>
              <td>1.9.3</td>
              <td></td>
            </tr>
            <tr>
              <td>Bash</td>
              <td>4.3.11</td>
              <td></td>
            </tr>
            <tr>
              <td>Python</td>
              <td>2.7.6</td>
              <td></td>
            </tr>
            <tr>
              <td>PHP</td>
              <td>5.5.9</td>
              <td></td>
            </tr>
            <tr>
              <td>Perl</td>
              <td>perl 5 version 18</td>
              <td></td>
            </tr>
            <tr>
              <td>C#</td>
              <td>mono 3.2.8</td>
              <td></td>
            </tr>
            <tr>
              <td>Lua</td>
              <td>5.2.3</td>
              <td></td>
            </tr>
          </table>
          <!-- 编译器选项表格 end -->
        </div>
  
        <div class="qa" id="p-2">
          <div class="question"><span class="red">Q: </span><span class="title">程序怎样取得输入、进行输出?</span></div>
          <div class="answer"><span class="green">A: </span>你的程序应该从标准输入 stdin('Standard Input')获取输出 并将结果输出到标准输出 stdout('Standard Output').例如,在C语言可以使用 'scanf' ，在C++可以使用'cin' 进行输入；在C使用 'printf' ，在C++使用'cout'进行输出.
            用户程序不允许直接读写文件, 如果这样做可能会判为运行时错误 "<font color=green>Runtime Error</font>"。<br>
            <br>
            下面是 1000题的参考答案
          </div>
    
          <style type="text/css">
            .code-bk-tsp {
              background-color: transparent;
            }
          </style>
            <?php
            $sql="SELECT * FROM faq_codes";
            $result=$mysqli->query($sql);
            echo "<div class='am-tabs' data-am-tabs='{noSwipe: 1}' id='doc-tab-demo-2'>";
            echo " <ul class='am-tabs-nav am-nav am-nav-tabs'>";
            while($row=$result->fetch_array()){
                if($row['language']=="c")
                    echo "<li class='am-active'><a href='javascript: void(0)'>".$row['language_show']."</a></li>";
                else echo "<li><a href='javascript: void(0)'>".$row['language_show']."</a></li>";
            }
            echo "</ul>";
            $result=$mysqli->query($sql);
            echo "<div class='am-tabs-bd'>";
            while($row=$result->fetch_array()){
                if($row['language']=="c") echo "<div class='am-tab-panel am-active'>";
                else echo "<div class='am-tab-panel'>";
                echo "<pre class='code-bk-tsp'><code class='code-bk-tsp'>";
                echo htmlentities(str_replace("\r\n", "\n", $row['code'])) ;
                echo "</code></pre>";
                echo "</div>";
            }
            echo "</div>";
            echo "</div>"
            ?>
        </div>
  
        <div class="qa" id="p-3">
          <div class="question"><span class="red">Q: </span><span class="title">为什么我的程序在自己的电脑上正常编译，而系统告诉我编译错误!</span></div>
          <div class="answer"><span class="green">A: </span>GCC的编译标准与VC6有些不同，更加符合c/c++标准:<br>
            <ul>
              <li>main 函数必须返回int, void main 的函数声明会报编译错误。<br>
              <li>i 在循环外失去定义 "for(int i=0...){...}"<br>
              <li>itoa 不是ansi标准函数.<br>
              <li>__int64 不是ANSI标准定义，只能在VC使用, 但是可以使用long long声明64位整数。<br>如果用了__int64,试试提交前加一句#define __int64 long long
            </ul>
          </div>
        </div>
  
        <div class="qa" id="p-4">
          <div class="question"><span class="red">Q: </span><span class="title">系统返回信息都是什么意思?</span></div>
          <div class="answer"><span class="green">A: </span>详见下述：<br></div>
          <table class="am-table am-table-bordered am-table-striped am-table-compact am-text-center">
            <tr>
              <td width="255">评测结果 </td>
              <td width="87">缩写</td>
              <td width="678">含义</td>
            </tr>
            <tr>
              <td class="ltd">Pending </td>
              <td class="mtd">PD</td>
              <td class="rtd">您的提交正排队等待评测。</td>
            </tr>
            <tr>
              <td class="ltd">Pending Rejudge</td>
              <td class="mtd">PR</td>
              <td class="rtd">因为数据更新或其他原因，系统将重新判你的答案。</td>
            </tr>
            <tr>
              <td class="ltd">Compiling</td>
              <td class="mtd">CP</td>
              <td class="rtd">您提交的代码正在被编译。</td>
            </tr>
            <tr>
              <td class="ltd">Running & Judging</td>
              <td class="mtd">RN</td>
              <td class="rtd">您的程序正在运行。 </td>
            </tr>
            <tr>
              <td class="ltd">Judging</td>
              <td class="mtd">JG</td>
              <td class="rtd">我们 正在检查您程序的输出是否正确。 </td>
            </tr>
            <tr >
              <td class="ltd">Accepted</td>
              <td class="mtd">AC</td>
              <td class="rtd">您的程序是正确的!。</td>
            </tr>
            <tr>
              <td class="ltd">Presentation Error</td>
              <td class="mtd">PE</td>
              <td class="rtd">您的程序输出有格式问题，请检查是否多了或者少了空格                                                                                                                                                                                           （' '）、制表符（'\t'）或者换行符（'\n'） </td>
            </tr>
            <tr >
              <td class="ltd">Wrong Answer</td>
              <td class="mtd">WA</td>
              <td class="rtd">您的程序输出结果错误。</td>
            </tr>
            <tr>
              <td class="ltd">Runtime Error</td>
              <td class="mtd">RE</td>
              <td class="rtd">您的程序在运行时发生错误。 </td>
            </tr>
            <tr >
              <td class="ltd">Time Limit Exceeded</td>
              <td class="mtd">TLE</td>
              <td class="rtd">您的程序运行的时间已经超出了题目的时间限制。 </td>
            </tr>
            <tr>
              <td class="ltd">Memory Limit Exceeded</td>
              <td class="mtd">MLE</td>
              <td class="rtd">您的程序运行的内存已经超出了题目的内存限制。 </td>
            </tr>
            <tr >
              <td class="ltd">Output Limit Exceeded</td>
              <td class="mtd">OLE</td>
              <td class="rtd">您的程序输出内容太多，超过了这个题目的输出限制。</td>
            </tr>
            <tr>
              <td class="ltd">Compile Error</td>
              <td class="mtd">CE</td>
              <td class="rtd">您的程序语法出现问题，编译器无法编译。 </td>
            </tr>
            <tr >
              <td class="ltd">System Error</td>
              <td class="mtd">SE</td>
              <td class="rtd">评判系统内部出现错误 ，我们会尽快处理。 </td>
            </tr>
            <tr>
              <td class="ltd">Out Of Contest Time</td>
              <td class="mtd">OCT</td>
              <td class="rtd">考试已经结束，不再评测提交。</td>
            </tr>
          </table>
        </div>
        
        <div class="qa" id="p-5">
          <div class="question"><span class="red">Q: </span><span class="title">如何参加在线比赛？</span></div>
          <div class="answer"><span class="green">A: </span><a href=registerpage.php>注册</a> 一个帐号，然后就可以练习，点击比赛列表Contests可以看到正在进行的比赛并参加。<br>
          </div>
        </div>
  
        <div class="qa" id="p-6">
          <div class="question"><span class="red">Q: </span><span class="title">比赛的排名规则？</span></div>
          <div class="answer"><span class="green">A: </span>
            HZNUOJ比赛的排名规则与ACM/ICPC的排名规则相同。<br/>
            每题耗时：Accepted的那一刻距离比赛开始的时间。<br/>
            总罚时：所有AC了的题的（耗时+错误次数*20min）的和。<br/>
            排名时，AC题数优先，题数相同时按罚时排序。 <br/>
          </div>
        </div>
        
        <div class="qa" id="p-7">
          <div class="question"><span class="red">Q: </span><span class="title">为什么有些题目突然不见了？</span></div>
          <div class="answer"><span class="green">A: </span>    一般是因为这道题被放进了一个正在进行的比赛中了，等到比赛结束就好了。<br/>
            当然也有可能是题目出问题了，被管理员暂时隐藏了。<br/>
          </div>
        </div>
  
        <div class="qa" id="p-8">
          <div class="question"><span class="red">Q: </span><span class="title">等级是如何划分的？</span></div>
          <div class="answer"><span class="green">A: </span>等级划分与小说《斗破苍穹》一致，自低向高分别为斗之气、斗者、斗师、大斗师、斗灵、斗王、斗皇、斗宗、斗尊、斗圣、斗帝，除斗帝外，每一阶又分不同等级，阶数越高，升级越困难。除此之外，每一阶还有不同的代表颜色，该阶等级越高，颜色越深。<br>
          </div>
        </div>
  
        <div class="qa" id="p-9">
          <div class="question"><span class="red">Q: </span><span class="title">积分规则？</span></div>
          <div class="answer"><span class="green">A: </span>等级由实力（Strength）决定，当实力达到一定值后自然会升级，而实力又从刷题中来，每道题后面均标有分数（Scores），代表AC这道题之后能提升多少实力。一般来说，越少人做的题目，分数越高，一起刷题的人越多，每道题的分数也越高。需要说明的是，用户的实力值是会根据大环境动态变化的（其实是因为分数在动态变化），如果你AC的题目被更多人AC出来了，你的实力值会下降，另外一方面，OJ内有更多强者涌入的时候，你的实力值也会提升。所以，想要快速升级，那就多刷题，刷难题！<br>
          </div>
        </div>
        
      </div>
    </div>
  </div>
</div><!--end container-->
<?php require_once("footer.php");?>

<!-- highlight.js START-->
<!-- <link href='highlight/styles/github-gist.css' rel='stylesheet' type='text/css'/> -->
<!-- <script src='highlight/highlight.pack.js' type='text/javascript'></script> -->
<!-- <script src='highlight/highlightjs-line-numbers.min.js' type='text/javascript'></script> -->

<link href="/OJ/plugins/highlight/styles/github-gist.css" rel="stylesheet">
<script src="/OJ/plugins/highlight/highlight.pack.js"></script>
<script src="/OJ/plugins/highlight/highlightjs-line-numbers.min.js"></script>
<style type="text/css">
  .hljs-line-numbers {
      text-align: right;
      border-right: 1px solid #ccc;
      color: #999;
      -webkit-touch-callout: none;
      -webkit-user-select: none;
      -khtml-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
      user-select: none;
  }
</style>
<script>
  hljs.initHighlightingOnLoad();
  hljs.initLineNumbersOnLoad();
  
  //auto generate title index
  $ul = $("#title-index");
  var i = 1;
  $("span.title").each(function () {
      $ul.append("<li id=\"index-" + i + "\"><a href=\"#\" class=\"am-text-truncate\">" + $(this).html() + "</a></li>");
      i++;
  });
  $("li[id^=index]").click(function () {
      n = $(this).attr("id").substring(6);
      $('html, body').animate({
          scrollTop: $("#p-"+n).offset().top - 80
      }, 500);
  });
</script>
<!-- highlight.js END-->