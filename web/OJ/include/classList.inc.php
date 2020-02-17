<?php
  /**
   * This file is created
   * by yybird
   * @2016.03.22
   * last modified
   * by d-star
   * @2017.09.20
  **/
?>

<?php
  require_once("my_func.inc.php");
  if(!class_is_exist('其它')){
    $sqlclass = "INSERT INTO `class_list` VALUES ('其它', '0');";
    $mysqli->query($sqlclass);
  }
  $base_list = array(
        "其它",
      );
  $sqlclass = "SELECT `class_name` FROM `class_list` WHERE `class_name`<>'其它' ORDER BY `enrollment_year` DESC, `class_name`";
  $classList = array_merge( $base_list, array_column($mysqli->query($sqlclass)->fetch_all(MYSQLI_ASSOC), 'class_name') );

  // /* 请将班级加在其它的下一行 */
  // $classList = array(
  //   "其它",
  //   /* 请从该行下方开始插入 */
  //   "信息与服务工程类181","信息与服务工程类182","信息与服务工程类183","信息与服务工程类184",
  //   "信息与服务工程类185","信息与服务工程类186","信息与服务工程类187","信息与服务工程类188",
  //   "数据科学与大数据技术181",
    
  //   "信息与服务工程类171","信息与服务工程类172","信息与服务工程类173","信息与服务工程类174",
  //   "信息与服务工程类175","信息与服务工程类176","信息与服务工程类177","信息与服务工程类178",
  //   "理科实验班171",
  //   "社工171",

  //   "计算机161","计算机162","计算机163","计算机164",
  //   "软工161","软工162","软工163",
  //   "物联网161","物联网162",
  //   "理科实验班161",
  //   "日语161","心理161",
    
  //   "计算机151","计算机152","计算机153",
  //   "软工151","软工152",
  //   "物联网151","物联网152",
  //   "理综（数学）151",
  //   "日语152",
    
  //   "计算机141","计算机142",
  //   "计算机143","计算机144",
  //   "软工141","软工142",
  //   "物联网141",
  //   "计算机132",
    
  //   );


?>
