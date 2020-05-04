<?php 
require_once("../include/db_info.inc.php");
if (!HAS_PRI("inner_function")) {
  echo "Permission denied!";
  exit(1);
}
?>


<div id="chart" style="width: 100%; height: 800px;"></div>
<div id="user_chart" style="width: 100%; height: 800px;"></div>
<div id="sol_tot_chart" style="width: 100%; height: 800px;"></div>

<script src="../plugins/jquery/jquery-3.1.1.min.js"></script>
<script src="../plugins/echarts/echarts/echarts.min.js"></script>
<?php 
$sql="SELECT DATE_FORMAT(in_date,'%Y/%m/%d') days, count(*) FROM solution GROUP BY days";
$res=$mysqli->query($sql);
$data="";
$date="";
while($row=$res->fetch_array()){
    $date.=("'{$row[0]}'".",");
    $data.=($row[1].",");
}
//echo "<pre>$date</pre>";
?>

<script>
var submission_chart = echarts.init(document.getElementById('chart'));
$(document).ready(function(){
    option = {
        tooltip: {
            trigger: 'axis',
            position: function (pt) {
                return [pt[0], '10%'];
            }
        },
        title: {
            left: 'center',
            text: 'Solution Statics',
        },
        legend: {
            top: 'bottom',
            data:['意向']
        },
        toolbox: {
            feature: {
                dataZoom: {
                    yAxisIndex: 'none'
                },
                restore: {},
                saveAsImage: {}
            }
        },
        xAxis: {
            type: 'category',
            boundaryGap: false,
            data: [<?php echo $date ?>],
        },
        yAxis: {
            type: 'value',
        },
        dataZoom: [{
            type: 'inside',
            start: 0,
            end: 10
        }, {
            start: 0,
            end: 10,
            handleIcon: 'M10.7,11.9v-1.3H9.3v1.3c-4.9,0.3-8.8,4.4-8.8,9.4c0,5,3.9,9.1,8.8,9.4v1.3h1.3v-1.3c4.9-0.3,8.8-4.4,8.8-9.4C19.5,16.3,15.6,12.2,10.7,11.9z M13.3,24.4H6.7V23h6.6V24.4z M13.3,19.6H6.7v-1.4h6.6V19.6z',
            handleSize: '80%',
            handleStyle: {
                color: '#fff',
                shadowBlur: 3,
                shadowColor: 'rgba(0, 0, 0, 0.6)',
                shadowOffsetX: 2,
                shadowOffsetY: 2
            }
        }],
        series: [
            {
                name:'模拟数据',
                type:'line',
                smooth:true,
                symbol: 'none',
                sampling: 'average',
                itemStyle: {
                    normal: {
                        color: 'rgb(255, 70, 131)'
                    }
                },
                areaStyle: {
                    normal: {
                        color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
                            offset: 0,
                            color: 'rgb(255, 158, 68)'
                        }, {
                            offset: 1,
                            color: 'rgb(255, 70, 131)'
                        }])
                    }
                },
                data: [<?php echo $data ?>],
            }
        ]
    };
    submission_chart.setOption(option);
});
$(window).resize(function(){
  submission_chart.resize();
});
</script>
<?php
$data="[";
for($t=1406488901 ; $t<=1479919869 ; $t+=60*60*24){
    $sql="SELECT count(1) FROM users WHERE UNIX_TIMESTAMP(reg_time) <= $t";
    $res=$mysqli->query($sql);
    $cnt=$res->fetch_array()[0];
    $date=time("Y/m/d",$t);
    $data.="{"."name:'$date',value:[$t,$cnt]"."},";
}
$data.="]";
?>
<script>
var user_chart = echarts.init(document.getElementById('user_chart'));
option = {
    title: {
        text: '动态数据 + 时间坐标轴'
    },
    tooltip: {
        trigger: 'axis',
        formatter: function (params) {
            params = params[0];
            var date = new Date(params.name);
            return date.getDate() + '/' + (date.getMonth() + 1) + '/' + date.getFullYear() + ' : ' + params.value[1];
        },
        axisPointer: {
            animation: false
        }
    },
    xAxis: {
        type: 'time',
        splitLine: {
            show: false
        }
    },
    yAxis: {
        type: 'value',  
        splitLine: {
            show: false
        }
    },
    series: [{
        name: '模拟数据',
        type: 'line',
        showSymbol: false,
        hoverAnimation: false,
        data: <?php echo $data ?>
    }]
};
user_chart.setOption(option);
</script>
<?php
$data="[";
for($t=1406488901 ; $t<=1479919869 ; $t+=60*60*24){
    $date=date('Y-m-d H:i:s',$t);
    $sql="SELECT count(1) FROM solution WHERE in_date <= '$date'";
    $res=$mysqli->query($sql);
    $cnt=$res->fetch_array()[0];
    $data.="{"."name:'$date',value:[$t,$cnt]"."},";
}
$data.="]";
?>
<script>
var sol_tot_chart = echarts.init(document.getElementById('sol_tot_chart'));
option = {
    title: {
        text: '动态数据 + 时间坐标轴'
    },
    tooltip: {
        trigger: 'axis',
        formatter: function (params) {
            params = params[0];
            var date = new Date(params.name);
            return date.getDate() + '/' + (date.getMonth() + 1) + '/' + date.getFullYear() + ' : ' + params.value[1];
        },
        axisPointer: {
            animation: false
        }
    },
    xAxis: {
        type: 'time',
        splitLine: {
            show: false
        }
    },
    yAxis: {
        type: 'value',  
        splitLine: {
            show: false
        }
    },
    series: [{
        name: '模拟数据',
        type: 'line',
        showSymbol: false,
        hoverAnimation: false,
        data: <?php echo $data ?>
    }]
};
sol_tot_chart.setOption(option);
</script>