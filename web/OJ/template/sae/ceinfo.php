<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?php echo $view_title?></title>
	<link rel=stylesheet href='./template/<?php echo $OJ_TEMPLATE?>/<?php echo isset($OJ_CSS)?$OJ_CSS:"hoj.css" ?>' type='text/css'>
</head>
<body>
<div id="wrapper">
	<?php require_once("oj-header.php");?>
<div id=main>
	
<pre id='errtxt' class="alert alert-error"><?php echo $view_reinfo?></pre>
<div id='errexp'>Explain:</div>

<script>
   var i=0;
   var pats=new Array();
   var exps=new Array();


   pats[i]=/not a statement/;
   exps[i++]="检查大括号{}匹配情况，eclipse整理代码快捷键Ctrl+Shift+F";
   pats[i]=/class, interface, or enum expected/;
   exps[i++]="请不要将java函数（方法）放置在类声明外部，注意大括号的结束位置}";
   pats[i]=/asm.*java/;
   exps[i++]="请不要将java程序提交为C语言";
   pats[i]=/package .* does not exist/;
   exps[i++]="检测拼写，如：系统对象System为大写S开头";
   pats[i]=/possible loss of precision/;
   exps[i++]="赋值将会失去精度，检测数据类型，如确定无误可以使用强制类型转换";
   pats[i]=/incompatible types/;
   exps[i++]="Java中不同类型的数据不能互相赋值，整数不能用作布尔值";
   pats[i]=/illegal start of expression/;
   exps[i++]="字符串应用英文双引号(\")引起";
   pats[i]=/cannot find symbol/;
   exps[i++]="拼写错误或者缺少调用函数所需的对象如println()需对System.out调用";
   pats[i]=/';' expected/;
   exps[i++]="缺少分号。";
   pats[i]=/should be declared in a file named/;
   exps[i++]="Java必须使用public class Main。";

   pats[i]=/expected ‘.*’ at end of input/;
   exps[i++]="代码没有结束，缺少匹配的括号或分号，检查复制时是否选中了全部代码。";
   pats[i]=/invalid conversion from ‘.*’ to ‘.*’/;
   exps[i++]="隐含的类型转换无效，尝试用显示的强制类型转换如(int *)malloc(....)";
   pats[i]=/warning.*declaration of 'main' with no type/;
   exps[i++]="C++标准中，main函数必须有返回值";
   pats[i]=/'.*' was not declared in this scope/;
   exps[i++]="变量没有声明过，检查下是否拼写错误！";
   pats[i]=/main’ must return ‘int’/;
   exps[i++]="在标准C语言中，main函数返回值类型必须是int，教材和VC中使用void是非标准的用法";
   pats[i]=/ .* was not declared in this scope/;
   exps[i++]="函数或变量没有声明过就进行调用，检查下是否导入了正确的头文件";
   pats[i]=/printf.*was not declared in this scope/;
   exps[i++]="printf函数没有声明过就进行调用，检查下是否导入了stdio.h或cstdio头文件";
   pats[i]=/ warning: ignoring return value of/;
   exps[i++]="警告：忽略了函数的返回值，可能是函数用错或者没有考虑到返回值异常的情况";
   pats[i]=/:.*__int64’ undeclared/;
   exps[i++]="__int64没有声明，在标准C/C++中不支持微软VC中的__int64,请使用long long来声明64位变量";
   pats[i]=/:.*expected ‘;’ before/;
   exps[i++]="前一行缺少分号";
   pats[i]=/ .* undeclared \(first use in this function\)/;
   exps[i++]="变量使用前必须先进行声明，也有可能是拼写错误，注意大小写区分。";
   pats[i]=/scanf.*was not declared in this scope/;
   exps[i++]="scanf函数没有声明过就进行调用，检查下是否导入了stdio.h或cstdio头文件";
   pats[i]=/memset.*was not declared in this scope/;
   exps[i++]="memset函数没有声明过就进行调用，检查下是否导入了stdlib.h或cstdlib头文件";
   pats[i]=/malloc.*was not declared in this scope/;
   exps[i++]="malloc函数没有声明过就进行调用，检查下是否导入了stdlib.h或cstdlib头文件";
   pats[i]=/puts.*was not declared in this scope/;
   exps[i++]="puts函数没有声明过就进行调用，检查下是否导入了stdio.h或cstdio头文件";
   pats[i]=/gets.*was not declared in this scope/;
   exps[i++]="gets函数没有声明过就进行调用，检查下是否导入了stdio.h或cstdio头文件";
   pats[i]=/str.*was not declared in this scope/;
   exps[i++]="string类函数没有声明过就进行调用，检查下是否导入了string.h或cstring头文件";
   pats[i]=/‘import’ does not name a type/;
   exps[i++]="不要将Java语言程序提交为C/C++,提交前注意选择语言类型。";
   pats[i]=/asm’ undeclared/;
   exps[i++]="不允许在C/C++中嵌入汇编语言代码。";
   pats[i]=/redefinition of/;
   exps[i++]="函数或变量重复定义，看看是否多次粘贴代码。";
   pats[i]=/expected declaration or statement at end of input/;
   exps[i++]="程序好像没写完，看看是否复制粘贴时漏掉代码。";
   pats[i]=/warning: unused variable/;
   exps[i++]="警告：变量声明后没有使用，检查下是否拼写错误，误用了名称相似的变量。";
   pats[i]=/implicit declaration of function/;
   exps[i++]="函数隐性声明，检查下是否导入了正确的头文件。";
   pats[i]=/too .* arguments to function/;
   exps[i++]="函数调用时提供的参数数量不对，检查下是否用错参数。";
   pats[i]=/expected ‘=’, ‘,’, ‘;’, ‘asm’ or ‘__attribute__’ before ‘namespace’/;
   exps[i++]="不要将C++语言程序提交为C,提交前注意选择语言类型。";
   pats[i]=/stray ‘\\[0123456789]*’ in program/;
   exps[i++]="中文空格、标点等不能出现在程序中注释和字符串以外的部分。";
   pats[i]=/division by zero/;
   exps[i++]="除以零将导致浮点溢出。";
   pats[i]=/cannot be used as a function/;
   exps[i++]="变量不能当成函数用，检查变量名和函数名重复的情况，也可能是拼写错误。";
   pats[i]=/format .* expects type .* but argument .* has type .*/;
   exps[i++]="scanf/printf的格式描述和后面的参数表不一致，检查是否多了或少了取址符“&”，也可能是拼写错误。";
   pats[i]=/类.*是公共的，应在名为 .*java 的文件中声明/;
   exps[i++]="Java语言提交只能有一个public类，并且类名必须是Main，其他类请不要用public关键词";
   function explain(){
     //alert("asdf");
       var errmsg=document.getElementById("errtxt").innerHTML;
	   var expmsg="辅助解释：<br>";
	   for(var i=0;i<pats.length;i++){
		   var pat=pats[i];
		   var exp=exps[i];
		   var ret=pat.exec(errmsg);
		   if(ret){
		      expmsg+=ret+":"+exp+"<br>";
		   }
	   }
	   document.getElementById("errexp").innerHTML=expmsg;
     //alert(expmsg);
   }
   explain();
 
 </script>
<div id=foot>
	<?php require_once("oj-footer.php");?>

</div><!--end foot-->
</div><!--end main-->
</div><!--end wrapper-->
</body>
</html>
