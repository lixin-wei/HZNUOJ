<?php 

require_once "../include/db_info.inc.php";

?>


<div id="chart" style="width: 100%; height: 800px;"></div>

<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
<script src="//cdn.bootcss.com/echarts/3.2.3/echarts.min.js"></script>
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