<?php
$ajaxform = array();
$ajaxform['size'] = "am-text-default";
$ajaxform['url'] = "admin/ajax.php";
$ajaxform['form_name'] = "ajax";
if (substr($_SERVER['PHP_SELF'],strlen($_SERVER['PHP_SELF'])-16)=="problem_list.php") {
    $ajaxform['size'] = "am-text-sm";
    $ajaxform['url'] = "./ajax.php";
    $ajaxform['form_name'] = "form1";
}
?>

<script type="text/javascript">
var color_theme=["primary","secondary","success","warning","danger"];
function problem_add_source(sp,pid){
  //console.log("pid:"+pid);
  let p=$(sp).parent();
  p.html("<form id='ajax' onsubmit='return false;'><input type='hidden' name='m' value='problem_add_source'><input type='hidden' name='ppid' value='"+pid+"'><input type='text' name='ns' maxlength='20'><input type='button' value='<?php echo $MSG_ADD ?>'></form>");
  p.find("input[name=ns]").focus();
  p.find("input[name=ns]").change(function(){
    //console.log($("#<?php echo $ajaxform['form_name'] ?>").serialize());
    let ns=p.find("input[name=ns]").val();
    //console.log("new source:"+ns);
    $.post("<?php echo $ajaxform['url'] ?>",$("#<?php echo $ajaxform['form_name'] ?>").serialize(),function(data,textStatus) {
      if(textStatus=="success") {
        if(data!=0) {
          p.parent().append("<a title='"+ns+"' class='am-badge am-badge-"+color_theme[Math.floor(Math.random()*5)]+" <?php echo $ajaxform['size'] ?> am-radius' href='problemset.php?search=" +ns+ "'>"+ns+"</a>&nbsp;");
        } else alert("‘"+ns+"’已存在！");
        p.parent().append("<span><span class='am-icon-plus' pid='"+pid+"' style='cursor: pointer;' onclick='problem_add_source(this,"+pid+");'></span></span>");
        p.remove();
      }
    });
  });
}
</script>