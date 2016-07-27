<?php
  /**
   * This file is created
   * by yybird
   * @2016.01.06
   * last modified
   * by yybird
   * @2016.04.06
  **/
?>


<?php
  require_once("calChart.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>学生个人成绩</title>
    <script src="echarts.min.js"></script>
    <style type="text/css">
      body{
        margin: 0 35px;
      }
      .chart {
        height:201px;
        width:330px;
        border:1px solid  #000;
        padding-left: 2px;
        padding-right: 2px;
        padding-top: 15px;
        padding-bottom: 0px;
        margin: 13px 6px 14px 40px;
        float:left;
        // display: none;
      }
      #main {
        height:210px;
        width:650px;
        border:1px solid #000 ;
        margin-left:auto;
        margin-right:auto; 
        /* margin-left:27px; */
        margin-top: 23px;
        margin-bottom: 15px;
        //float: left;
       // display: none;
      }
    </style>
  </head>
  <body>
    <div id="main"></div>
    <div id="solved" class="chart"></div>
    <div id="dif" class="chart"></div>
    <div id="eff" class="chart"></div>
    <div id="insist" class="chart"></div>
    <div id="activity" class="chart"></div>
    <div id="copy" class="chart"></div>

    <!-- <div style="margin-top:20px" >
      <input type="button" value="导 出" onclick="exportImg()" />  
    </div> -->
    <form id="exportForm" action="get.php" method="post">
      <input type="hidden" name="main_img" id="main_img" />
      <input type="hidden" name="sol_img" id="sol_img" />
      <input type="hidden" name="act_img" id="act_img" />
      <input type="hidden" name="copy_img" id="copy_img" />
      <input type="hidden" name="ins_img" id="ins_img" />
      <input type="hidden" name="eff_img" id="eff_img" />
      <input type="hidden" name="dif_img" id="dif_img" />
    </form>
  </body>
  <script type="text/javascript">

    // main
    var main = echarts.init(document.getElementById('main'));
    var option = {
      tooltip: { trigger: 'item' },
      title : {
        text: '姓名：<?php echo $stu->real_name ?>\n\n学号：<?php echo $stu->user_id ?>\n\n班级：<?php echo classToCN($stu->class) ?>\n\n总分：<?php echo round($stu->total_score) ?>',
        x : 17,
        y : 'center' ,
        textStyle:{ fontSize: 22 }
      }, 
      radar : [
        {
          indicator : [
            {text : '[1]解题量', max  : 100},
            {text : '[2]挑战性', max  : 100},
            {text : '[3]高效性', max  : 100},
            {text : '[4]坚持性', max  : 100},
            {text : '[5]积极性', max  : 100},
            {text : '[6]独立性', max  : 100}
          ],
          center: ['75%', '50%'],
          radius : 62,
          splitArea : {
            show : true,
            areaStyle : { /*color: ['#b1e2ff','#ffa3e4']*/ },
          },
          axisLine: {            // 坐标轴线
            show: true,        // 默认显示，属性show控制显示与否
            lineStyle: {       // 属性lineStyle控制线条样式
              color: '#1bcbc9',
              width: 3,
              type: 'solid',
            }
          }, 
        }
      ],
      series : [{
        name: '学生个人成绩',
        type: 'radar',
        data : [{
            value : [
              <?php echo round($stu->solved_score, 2) ?>, 
              <?php echo round($stu->dif_score, 2) ?>, 
              <?php echo round($stu->eff_score, 2) ?>, 
              <?php echo round($stu->insist_score, 2) ?>, 
              <?php echo round($stu->act_score, 2) ?>, 
              <?php echo round($stu->idp_score, 2) ?>
            ]
        }]
      }]
    };
    main.setOption(option);


    // solved
    var solved = echarts.init(document.getElementById('solved'));
    option = {
      title : {
        text: '本周<?php echo round($stu->solved_score_week) ?>分',
        x : 'right',
        y : 'top' ,
        textStyle:{ 
          fontSize: 14,
          fontWeight: 'lighter' 
        },
        borderColor: 'black',
        borderWidth: 1
      }, 
      tooltip : {
          trigger: 'axis',
          backgroundColor:'rgba(0,0,0,0.5)',
      },
      calculable : true,
      legend: { data:['题数','排名'] },
      xAxis : [
        {
          type : 'category',
          data : ['第1周','2周','3周','4周','5周','6周','7周','8周','9周','10周','11周','12周','13周','14周','15周','16周','17周','18周','19周','20周'],
          axisLabel : { textStyle:{ fontSize:13 } }
        }
      ],
      yAxis : [
        {
          type : 'value',
          name : '题数',
          axisLabel : {
            formatter: '{value} ',
            textStyle:{ fontSize:13 }
          }
        },
        {
          type : 'value',
          name : '排名',
          axisLabel : {
            formatter: '{value} ',
            textStyle: { fontSize:13 }
          },
          inverse: true,
          nameGap: 25
        }
      ],
      series : [
        {
          name:'题数',
          type:'bar',
          data:[
            <?php
              $sz = count($stu->solved_weekly);
              for ($i=1; $i<=$sz; ++$i) {
                echo $stu->solved_weekly[$i].",";
              }
            ?>
          ]
        },
        {
          name:'排名',
          type:'line',
          yAxisIndex: 1,
          data:[
            <?php
              $sz = count($stu->solved_rank_weekly);
              for ($i=1; $i<=$sz; ++$i) {
                echo $stu->solved_rank_weekly[$i].",";
              }
            ?>
          ]
        }
      ]
    };                  
    solved.setOption(option);


    // difficult
    var dif = echarts.init(document.getElementById('dif'));
    option = {
      title : {
        text: '本周<?php echo round($stu->dif_score_week) ?>分',
        x : 'right',
        y : 'top' ,
        textStyle:{ 
          fontSize: 14,
          fontWeight: 'lighter' 
        },
        borderColor: 'black',
        borderWidth: 1
      }, 
      tooltip: { trigger: 'axis' },
      legend: { data:['难度'] },
      calculable : true,
      xAxis: [
        {
          type : 'category',
          boundaryGap : false,
          data : ['第1周','2周','3周','4周','5周','6周','7周','8周','9周','10周','11周','12周','13周','14周','15周','16周','17周','18周','19周','20周']
        }
      ],
      yAxis : [ { type : 'value' } ],
      series : [
        {
          name:'难度',
          type:'line',
          stack: '程度',
          data:[
            <?php
              $sz = count($stu->dif_weekly);
              for ($i=1; $i<=$sz; ++$i) {
                echo $stu->dif_weekly[$i].",";
              }
            ?>
          ]
        }
      ]
    };
    dif.setOption(option);


    // efficient
    var eff = echarts.init(document.getElementById('eff'));
    option = {
      title : {
        text: '本周<?php echo round($stu->eff_score_week) ?>分',
        x : 'right',
        y : 'top' ,
        textStyle:{ 
          fontSize: 14,
          fontWeight: 'lighter' 
        },
        borderColor: 'black',
        borderWidth: 1
      }, 
      tooltip : { trigger: 'axis' },
      legend: { data:['时间效率','空间效率'] },
      calculable: true,
      xAxis: [
        {
          type : 'category',
          boundaryGap : false,
          data : ['第1周','2周','3周','4周','5周','6周','7周','8周','9周','10周','11周','12周','13周','14周','15周','16周','17周','18周','19周','20周'],
          axisLabel : { textStyle:{ fontSize:13 } }
        }
      ],
      yAxis : [
        {
          type: 'value',
          name:'时间效率(ms)',
          axisLabel: {
            formatter: '{value} ',
            textStyle:{ fontSize:13 }
          },
          inverse: true,
          nameGap: 25
        },
        {
          type: 'value',
          name: '空间效率(MB)',
          axisLabel: {
            formatter: '{value} ',
            textStyle:{ fontSize:13 }
          },
          inverse: true,
          nameGap: 25
        }
      ],
      series : [
        {
          name:'时间效率',
          type:'line',
          data:[
            <?php
              $sz = count($stu->time_weekly);
              for ($i=1; $i<=$sz; ++$i) {
                echo round($stu->time_weekly[$i],1).",";
              }
            ?>
          ],
          markPoint : {
            data : [
              {type: 'max', name: '最低时间效率'},
              {type: 'min', name: '最高时间效率'}
            ],
          },
          markLine : { data : [{ type: 'average', name: '平均时间效率' }] }
        },
        {
          name:'空间效率',
          type:'line',
          yAxisIndex: 1,
          data:[
            <?php
              $sz = count($stu->mem_weekly);
              for ($i=1; $i<=$sz; ++$i) {
                echo round($stu->mem_weekly[$i]/1024,1).",";
              }
            ?>
          ],
          markPoint : {
            data : [
              {type:'max', name: '最低空间效率'},
              {type:'min', name: '最高空间效率'}
            ]
          },
          markLine : { data: [{type : 'average', name : '平均空间效率'}] }
        }
      ]
    };
    eff.setOption(option);


    // insist
    var insist = echarts.init(document.getElementById('insist'));
    option = {
      title : {
        text: '本周<?php echo round($stu->insist_score_week) ?>分',
        x : 'right',
        y : 'top' ,
        textStyle:{ 
          fontSize: 14,
          fontWeight: 'lighter' 
        },
        borderColor: 'black',
        borderWidth: 1
      }, 
      tooltip : { trigger: 'axis' },
      legend: { data:['天数'] },
      calculable : true,
      xAxis : [
        {
          type : 'category',
          data : ['第1周','2周','3周','4周','5周','6周','7周','8周','9周','10周','11周','12周','13周','14周','15周','16周','17周','18周','19周','20周']
        }
      ],
      yAxis: [ { type: 'value' } ],
      series : [
        {
          name:'天数',
          itemStyle: {
            normal: {
                color: '#00abea',
                barBorderColor: '#00abea',
                barBorderWidth: 6,
                barBorderRadius:0,
                label : {
                  show: true, 
                  position: 'insideTop'
                }
            }
          },
          type:'bar',
          data:[
            <?php
              $sz = count($stu->insist_day_weekly);
              for ($i=1; $i<=$sz; ++$i) {
                echo $stu->insist_day_weekly[$i].",";
              }
            ?>
          ],
        },            
      ]
    };                 
    insist.setOption(option);


    // activity
    var activity = echarts.init(document.getElementById('activity'));
    option = {
      title : {
        text: '本周<?php echo round($stu->act_score_week) ?>分',
        x : 'right',
        y : 'top' ,
        textStyle:{ 
          fontSize: 14,
          fontWeight: 'lighter' 
        },
        borderColor: 'black',
        borderWidth: 1
      }, 
      tooltip : { trigger: 'axis' },
      legend: { data:['积极性排名'] },
      calculable : true,
      xAxis : [
        {
          type : 'category',
          boundaryGap : false,
          data : ['第1周','2周','3周','4周','5周','6周','7周','8周','9周','10周','11周','12周','13周','14周','15周','16周','17周','18周','19周','20周'],
          axisLabel : { textStyle:{ fontSize:13 } }
        }
      ],
      yAxis : [
        {
          name: '积极性排名',
          type : 'value',
          axisLabel : {
            formatter: '{value}',
            textStyle:{ fontSize:13 }
          },
          inverse: true,
          nameGap: 25
        }
      ],
      series : [
        {
          name:'积极性排名',
          type:'line',
          data:[
            <?php
              $sz = count($stu->act_rank_weekly);
              for ($i=1; $i<=$sz; ++$i) {
                echo $stu->act_rank_weekly[$i].",";
              }
            ?>
          ],
          markPoint : {
            data : [
              {type : 'max', name: '最大值'},
              {type : 'min', name: '最小值'}
            ]
          },
          markLine : { data : [ {type : 'average', name: '平均值'} ] }
        }
      ]
    };
    activity.setOption(option);


    // copy
    var copy = echarts.init(document.getElementById('copy'));
    option = {
      title : {
        text: '本周<?php echo round($stu->idp_score_week) ?>分',
        x : 'right',
        y : 'top' ,
        textStyle:{ 
          fontSize: 14,
          fontWeight: 'lighter' 
        },
        borderColor: 'black',
        borderWidth: 1
      }, 
      tooltip : { trigger: 'axis' },
      calculable : true,
      legend: { data:['抄袭','被抄袭'] },
      xAxis : [
        {
          type : 'category',
          data : ['第1周','2周','3周','4周','5周','6周','7周','8周','9周','10周','11周','12周','13周','14周','15周','16周','17周','18周','19周','20周'],
          axisLabel: { textStyle:{ fontSize:13 } }
        }
      ],
      yAxis : [
        {
          type : 'value',
          name : '次数',
          axisLabel : {
              formatter: '{value} ',
              textStyle:{ fontSize:13 }
          }
        },
      ],
      series : [
        {
          name:'抄袭',
          type:'bar',
          data: [
            <?php
              $sz = count($stu->copy1_weekly);
              for ($i=1; $i<=$sz; ++$i) {
                echo $stu->copy1_weekly[$i].",";
              }
            ?>
          ]
        },
        {
          name: '被抄袭',
          type: 'bar',
          data: [
            <?php
              $sz = count($stu->copy2_weekly);
              for ($i=1; $i<=$sz; ++$i) {
                echo $stu->copy2_weekly[$i].",";
              }
            ?>
          ]
        },
      ]
    };
    copy.setOption(option);


    function exportImg(){
      var main_data = main.getDataURL("png");
      var sol_data = solved.getDataURL("png");
      var act_data = activity.getDataURL("png");
      var copy_data = copy.getDataURL("png");
      var ins_data = insist.getDataURL("png");
      var eff_data = eff.getDataURL("png");
      var dif_data = dif.getDataURL("png");
      $("#main_img").val(main_data);
      $("#sol_img").val(sol_data);
      $("#act_img").val(act_data);
      $("#copy_img").val(copy_data);
      $("#ins_img").val(ins_data);
      $("#eff_img").val(eff_data);
      $("#dif_img").val(dif_data);
      $("#exportForm").submit();  
    }
  </script>
</html>