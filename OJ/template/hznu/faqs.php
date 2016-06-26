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

<div class="am-container"> 
  <hr>
  <center>
    <font size="+3">HZNU Online Judge FAQ</font>
  </center>
  <hr>

  <p>
    <font color=green>Q</font>：这个在线裁判系统使用什么样的编译器和编译选项?<br>
    <font color=red>A</font>：系统运行于<a href="http://www.ubuntu.com">Ubuntu 14.04</a>
    <br><br>
    对应的编译器和编译选项如下:<br>
  </p>
      
  <!-- 编译器选项表格 start -->
  <table border="1">
    <tr>
      <td><center>语言</center></td>
      <td><center>编译器版本</center></td>
      <td><center>编译选项</center></td>
    </tr>
    <tr>
      <td><center>C</center></td>
      <td><center>gcc 4.8.2</center></td>
      <td><center><font color=blue>gcc Main.c -o Main  -fno-asm -O2 -Wall -lm --static -std=c99 -DONLINE_JUDGE</font></center></td>
    </tr>
    <tr>
      <td><center>C++</center></td>
      <td><center>g++ 4.8.2</center></td>
      <td><center><font color=blue>g++ Main.cc -o Main  -fno-asm -O2 -Wall -lm --static -DONLINE_JUDGE</font></center></td>
    </tr>
    <tr>
      <td><center>Pascal</center></td>
      <td><center>Free Pascal 2.6.2</center></td>
      <td><center><font color=blue>fpc Main.pas -oMain -O1 -Co -Cr -Ct -Ci </font></center></td>
    </tr>
    <tr>
      <td><center>Java</td>
      <td><center>openjdk 1.7.0_79</center></td>
      <td><center><font color="blue">javac -J-Xms32m -J-Xmx256m Main.java</font>
      <br>
      <font size="-1" color="red">*Java has 2 more seconds and 512M more memory when running and judging.</font></center>
      </td>
    </tr>
    <tr>
      <td><center>Ruby</center></td>
      <td><center>1.9.3</center></td>
      <td><center></center></td>
    </tr>
    <tr>
      <td><center>Bash</center></td>
      <td><center>4.3.11</center></td>
      <td><center></center></td>
    </tr>
    <tr>
      <td><center>Python</center></td>
      <td><center>2.7.6</center></td>
      <td><center></center></td>
    </tr>
    <tr>
      <td><center>PHP</center></td>
      <td><center>5.5.9</center></td>
      <td><center></center></td>
    </tr>
    <tr>
      <td><center>Perl</center></td>
      <td><center>perl 5 version 18</center></td>
      <td><center></center></td>
    </tr>
    <tr>
      <td><center>C#</center></td>
      <td><center>mono 3.2.8</center></td>
      <td><center></center></td>
    </tr>
    <tr>
      <td><center>Lua</center></td>
      <td><center>5.2.3</center></td>
      <td><center></center></td>
    </tr>
  </table>
  <!-- 编译器选项表格 end -->
  <hr>

  <p>
    <font color=green>Q</font>：程序怎样取得输入、进行输出?<br>
      <font color=red>A</font>：你的程序应该从标准输入 stdin('Standard Input')获取输出 并将结果输出到标准输出 stdout('Standard Output').例如,在C语言可以使用 'scanf' ，在C++可以使用'cin' 进行输入；在C使用 'printf' ，在C++使用'cout'进行输出.
    用户程序不允许直接读写文件, 如果这样做可能会判为运行时错误 "<font color=green>Runtime Error</font>"。<br>
      <br>
    下面是 1000题的参考答案
  </p>

C++:<br>
<pre><font color="blue">
#include &lt;iostream&gt;
using namespace std;
int main(){
  int a,b;
  while(cin >> a >> b)
      cout << a+b << endl;
return 0;
}
</font></pre>
C:<br>
<pre><font color="blue">
#include &lt;stdio.h&gt;
int main(){
  int a,b;
  while(scanf("%d %d",&amp;a, &amp;b) != EOF)
      printf("%d\n",a+b);
return 0;
}
</font></pre>
PASCAL:<br>
<pre><font color="blue">
program p1001(Input,Output); 
var 
a,b:Integer; 
begin 
 while not eof(Input) do 
   begin 
     Readln(a,b); 
     Writeln(a+b); 
   end; 
end.
</font></pre>

Java:<br>
<pre><font color="blue">
import java.util.*;
public class Main{
public static void main(String args[]){
	Scanner cin = new Scanner(System.in);
	int a, b;
	while (cin.hasNext()){
		a = cin.nextInt(); b = cin.nextInt();
		System.out.println(a + b);
	}
}
}</font></pre>

  <hr>

  <p>
    <font color=green>Q</font>：为什么我的程序在自己的电脑上正常编译，而系统告诉我编译错误!<br>
    <font color=red>A</font>：GCC的编译标准与VC6有些不同，更加符合c/c++标准:<br>
    <ul>
      <li><font color=blue>main</font> 函数必须返回<font color=blue>int</font>, <font color=blue>void main</font> 的函数声明会报编译错误。<br> 
      <li><font color=green>i</font> 在循环外失去定义 "<font color=blue>for</font>(<font color=blue>int</font> <font color=green>i</font>=0...){...}"<br>
      <li><font color=green>itoa</font> 不是ansi标准函数.<br>
      <li><font color=green>__int64</font> 不是ANSI标准定义，只能在VC使用, 但是可以使用<font color=blue>long long</font>声明64位整数。<br>如果用了__int64,试试提交前加一句#define __int64 long long
    </ul>
  </p>

  <hr>

  <p>
    <font color=green>Q</font>：系统返回信息都是什么意思?<br>
    <font color=red>A</font>：详见下述：<br>
    <p><font color=blue>Pending</font> : 系统忙，你的答案在排队等待. </p>
    <p><font color=blue>Pending Rejudge</font>: 因为数据更新或其他原因，系统将重新判你的答案.</p>
    <p><font color=blue>Compiling</font> : 正在编译.<br>
  </p>
  <p><font color="blue">Running &amp; Judging</font>: 正在运行和判断.<br></p>
  <p><font color=blue>Accepted</font> : 程序通过!<br>
    <br>
    <font color=blue>Presentation Error</font> : 答案基本正确，但是格式不对。<br>
    <br>
    <font color=blue>Wrong Answer</font> : 答案不对，仅仅通过样例数据的测试并不一定是正确答案，一定还有你没想到的地方.<br>
    <br>
    <font color=blue>Time Limit Exceeded</font> : 运行超出时间限制，检查下是否有死循环，或者应该有更快的计算方法。<br>
    <br>
    <font color=blue>Memory Limit Exceeded</font> : 超出内存限制，数据可能需要压缩，检查内存是否有泄露。<br>
    <br>
    <font color=blue>Output Limit Exceeded</font>: 输出超过限制，你的输出比正确答案长了两倍.<br>
    <br>
    <font color=blue>Runtime Error</font> : 运行时错误，非法的内存访问，数组越界，指针漂移，调用禁用的系统函数。请点击后获得详细输出。<br>
  </p>
  <p><font color=blue>Compile Error</font> : 编译错误，请点击后获得编译器的详细输出。<br><br></p>
  <hr>

  <p>
    <font color=green>Q</font>：如何参加在线比赛？<br>
    <font color=red>A</font>：<a href=registerpage.php>注册</a> 一个帐号，然后就可以练习，点击比赛列表Contests可以看到正在进行的比赛并参加。<br>
  </p><br>
  <hr>

  <p>
    <font color=green>Q</font>：等级是如何划分的？<br>
    <font color=red>A</font>：等级划分与小说《斗破苍穹》一致，自低向高分别为斗之气、斗者、斗师、大斗师、斗灵、斗王、斗皇、斗宗、斗尊、斗圣、斗帝，除斗帝外，每一阶又分不同等级，阶数越高，升级越困难。除此之外，每一阶还有不同的代表颜色，该阶等级越高，颜色越深。<br>
  </p><br>
  <hr>

  <p>
    <font color=green>Q</font>：如何升级？<br>
    <font color=red>A</font>：等级由实力（Strength）决定，当实力达到一定值后自然会升级，而实力又从刷题中来，每道题后面均标有分数（Scores），代表AC这道题之后能提升多少实力。一般来说，越少人做的题目，分数越高，一起刷题的人越多，每道题的分数也越高。需要说明的是，用户的实力值是会根据大环境动态变化的（其实是因为分数在动态变化），如果你AC的题目被更多人AC出来了，你的实力值会下降，另外一方面，OJ内有更多强者涌入的时候，你的实力值也会提升。所以，想要快速升级，那就多刷题，刷难题！<br>
  </p><br>
  <hr>

  <center>
    <font color=green size="+2">其他问题请访问<a href="..\bbs"><?php echo $OJ_NAME?>论坛系统</a></font>
  </center>

	 <?php require_once("footer.php");?>
     
</div><!--end wrapper-->
