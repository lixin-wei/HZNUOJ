<?php

// English Language Module for v2.3 (translated by the QuiX project)

$GLOBALS["charset"] = "utf-8";
$GLOBALS["text_dir"] = "ltr"; // ('ltr' for left to right, 'rtl' for right to left)
$GLOBALS["date_fmt"] = "Y/m/d H:i";
$GLOBALS["error_msg"] = array(
	// error
	"error"			=> "错误信息",
	"back"			=> "返回",
	
	// root
	"home"			=> "主目录不存在,请检查你的设置。",
	"abovehome"		=> "不允许访问超出主目录以外的内容。",
	"targetabovehome"	=> "不允许访问超出主目录以外的内容。",
	
	// exist
	"direxist"		=> "目录不存在。",
	//"filedoesexist"	=> "文件已存在。",
	"fileexist"		=> "文件不存在。",
	"itemdoesexist"		=> "该项已存在。",
	"itemexist"		=> "该项不存在。",
	"targetexist"		=> "目标文件夹内不存在。",
	"targetdoesexist"	=> "目标文件夹内已存在。",
	
	// open
	"opendir"		=> "文件夹打开失败。",
	"readdir"		=> "文件夹读取失败。",
	
	// access
	"accessdir"		=> "你没有权限进入此文件夹。",
	"accessfile"		=> "你没有权限进入这个文件。",
	"accessitem"		=> "你没有权限进入此项。",
	"accessfunc"		=> "你没有权限使用这个功能。",
	"accesstarget"		=> "你没有权限进入这个文件夹。",
	
	// actions
	"permread"		=> "获取权限失败",
	"permchange"		=> "权限更改失败。",
	"openfile"		=> "打开文件失败。.",
	"savefile"		=> "保存文件失败。",
	"createfile"		=> "新建文件失败。",
	"createdir"		=> "新建文件夹失败。",
	"uploadfile"		=> "文件上传失败",
	"copyitem"		=> "复制失败。",
	"moveitem"		=> "移动失败。",
	"delitem"		=> "删除失败。",
	"chpass"		=> "修改密码失败。",
	"deluser"		=> "删除用户失败。",
	"adduser"		=> "增加用户失败。",
	"saveuser"		=> "保存用户失败。",
	"searchnothing"		=> "请输入搜索内容以便查找。",
	
	// misc
	"miscnofunc"		=> "功能无法使用。",
	"miscfilesize"		=> "文件大小超过最大限制。",
	"miscfilepart"		=> "文件没有完整上传。",
	"miscnoname"		=> "请输入文件名。",
	"miscselitems"		=> "你没有选择任何项目。",
	"miscdelitems"		=> "确认删除以下\"+num+\"个项目吗?",
	"miscdeluser"		=> "确认删除用户'\"+user+\"'吗?",
	"miscnopassdiff"	=> "新密码和旧密码相同",
	"miscnopassmatch"	=> "密码不匹配。",
	"miscfieldmissed"	=> "还有一个重要的项目没有填写。",
	"miscnouserpass"	=> "用户名或者密码错误。",
	"miscselfremove"	=> "你不能删除自己的账号。",
	"miscuserexist"		=> "用户已存在。",
	"miscnofinduser"	=> "找不到该用户。",
);
$GLOBALS["messages"] = array(
	// links
	"permlink"		=> "修改权限",
	"editlink"		=> "编辑",
	"downlink"		=> "下载",
	"uplink"		=> "回到上级",
	"homelink"		=> "主目录",
	"reloadlink"	=> "刷新",
	"copylink"		=> "复制",
	"movelink"		=> "移动",
	"dellink"		=> "删除",
	"comprlink"		=> "打包压缩",
	"adminlink"		=> "管理员",
	"logoutlink"	=> "注销",
	"uploadlink"	=> "上传文件",
	"searchlink"	=> "搜索",
	
	// list
	"nameheader"	=> "文件名",
	"sizeheader"	=> "大小",
	"typeheader"	=> "类型",
	"modifheader"	=> "修改时间",
	"permheader"	=> "读写权限",
	"actionheader"	=> "操作",
	"pathheader"	=> "路径",
	
	// buttons
	"btncancel"		=> "取消",
	"btnsave"		=> "保存",
	"btnchange"		=> "修改",
	"btnreset"		=> "重置",
	"btnclose"		=> "关闭",
	"btncreate"		=> "新建",
	"btnsearch"		=> "搜索",
	"btnupload"		=> "上传",
	"btncopy"		=> "复制",
	"btnmove"		=> "移动",
	"btnlogin"		=> "登录",
	"btnlogout"		=> "注销",
	"btnadd"		=> "增加",
	"btnedit"		=> "编辑",
	"btnremove"		=> "删除",
	"Random-data"	=> "随机测试数据生成器",
	
	// actions
	"actdir"		=> "文件夹",
	"actperms"		=> "更改权限",
	"actedit"		=> "编辑文件",
	"actsearchresults"	=> "搜索结果",
	"actcopyitems"		=> "复制项目",
	"actcopyfrom"		=> "从 /%s 复制到 /%s ",
	"actmoveitems"		=> "移动项目",
	"actmovefrom"		=> "从 /%s 移动到 /%s ",
	"actlogin"		=> "登录",
	"actloginheader"	=> "登录网盘",
	"actadmin"		=> "超级管理员",
	"actchpwd"		=> "修改密码",
	"actusers"		=> "用户",
	"actarchive"	=> "压缩",
	"actupload"		=> "上传文件",
	
	// misc
	"miscitems"		=> "项",
	"miscfree"		=> "可用空间",
	"miscusername"		=> "用户名",
	"miscpassword"		=> "密码",
	"miscoldpass"		=> "旧密码",
	"miscnewpass"		=> "新密码",
	"miscconfpass"		=> "确认密码",
	"miscconfnewpass"	=> "确认新密码",
	"miscchpass"		=> "修改密码",
	"mischomedir"		=> "主目录",
	"mischomeurl"		=> "主目录地址",
	"miscshowhidden"	=> "显示隐藏项目",
	"mischidepattern"	=> "Hide pattern",
	"miscperms"			=> "权限",
	"miscuseritems"		=> "(用户名, 主目录, 显示隐藏项目, 权限, 操作)",
	"miscadduser"		=> "增加用户",
	"miscedituser"		=> "编辑用户 '%s'",
	"miscactive"		=> "操作",
	"misclang"			=> "语言",
	"miscnoresult"		=> "找不到搜索内容.",
	"miscsubdirs"		=> "搜索子文件夹",
	"miscpermissions"	=> array(
					"read"		=> array("读取", "用户可以读取和下载文件"),
					"create" 	=> array("写入", "用户可以新建文件"),
					"change"	=> array("修改", "用户可以修改(上传, 编辑) 文件"),
					"delete"	=> array("删除", "用户可以删除文件"),
					"password"	=> array("修改密码", "用户可以修改密码"),
					"admin"		=> array("管理员", "所有权限"),
			),
	"miscyesno"		=> array("是","否","Y","N"),
	"miscchmod"		=> array("拥有者", "用户组", "其他"),
);
?>
