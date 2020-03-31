<?php
/**
 * Created by PhpStorm.
 * User: ZKin
 * Date: 9/7/2018
 * Time: 4:01 PM
 */
?>

<?php $title="ACM Foundation"; ?>
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
                <span>杭州师范大学ACM大学生竞赛发展基金</span>
                <div class="class-score">
                    <span class="am-badge am-badge-success am-text-xl">Update: 2018/09/06</span>
                </div>
            </div>
            <div class="class-name-en">
                ACM Collegiate Programming Contest Development Foundation - Hangzhou Normal University
            </div>
        </div>
        <div class="class-description">
            作为杭州师范大学最有影响力的学科竞赛基地之一，ACM实验室为培养一代又一代计算机英才做出了辉煌业绩。
            为促进ACM实验室更好的发展和培养计算机英才，特设立“ACM大学生程序设计竞赛发展基金”。
        </div>
    </div>
    <div class="am-g">
        <div class="am-u-md-12">
            <div class="box">
                <div class="content-block">
                    <div class="content-block-title">
                        基金管理章程
                    </div>
                    <div class="content-block-body">
                        <p>为使这一基金得到规范科学的管理，充分发挥其功效，特制定本章程。</p>
                    </div>
                </div>
                <div class="content-block">
                    <div class="content-block-title">
                        第一章 总 则
                    </div>
                    <div class="content-block-body">
                        <p>第一条 本基金定名为: “杭州师范大学ACM大学生程序设计竞赛发展基金” （以下简称“竞赛发展基金”）。</p>
                        <p>第二条 “竞赛发展基金”将重点支持激励学生积极参与程序设计竞赛基地以及相关竞赛的建设和组织工作。</p>
                    </div>
                </div>
                <div class="content-block">
                    <div class="content-block-title">
                        第二章 组 织 机 构
                    </div>
                    <div class="content-block-body">
                        <p>第三条 为保证“竞赛发展基金”的正常运转和有效使用，专门成立“竞赛发展基金理事会”（以下简称“基金理事会”）
                            作为基金使用的评审和管理机构。</p>
                        <p>第四条 基金理事会由已毕业学长、现役队员组成。基金理事会负责制订、审议资助项目并对资助结果进行评估。
                            任何重大事项由理事会投票决定。</p>
                    </div>
                </div>
                <div class="content-block">
                    <div class="content-block-title">
                        第三章 基金管理和使用
                    </div>
                    <div class="content-block-body">
                        <p>第五条 “竞赛发展基金”接受学长们的捐赠。基金捐赠形式可一次性捐赠，也可先认捐，分期赠款。
                            将有专人记录学长的捐赠，基金以项目运作方式管理，专款专用。</p>
                    </div>
                </div>
                <div class="content-block">
                    <div class="content-block-title">
                        第一届杭州师范大学ACM大学生程序设计竞赛发展基金理事会组织机构
                    </div>
                    <div class="content-block-body">
                        <p><b>理事长：</b>张 鹏</p>
                        <p><b>副理事长：</b>陈俊帆、陈嘉驰</p>
                        <p><b>理事：</b>徐韬、马吴渊、林亨泽、余德利、吴正</p>
                        <p>财务（负责入账，记录捐赠）：余赛康</p>
                        <p>会计（负责支出，整理月表）：谭洁</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><!-- container -->

<?php require_once "template/".$OJ_TEMPLATE."/footer.php" ?>
