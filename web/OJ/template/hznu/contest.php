<?php
  /**
   * This file is created
   * by yybird
   * @2016.03.22
   * last modified
   * by yybird
   * @2016.03.24
  **/
?>

<?php $title="Contest";?>
<?php include "template/hznu/contest_header.php"; ?>
<style type="text/css" media="screen">
 .well{
    display: block;
    padding: 1rem;
    margin: 1rem 0;
    /*font-size: 1.3rem;*/
    line-height: 1.6;
    word-break: break-all;
    word-wrap: break-word;
    color: #555;
    background-color: #f8f8f8;
    border: 1px solid #dedede;
    border-radius: 0;
 }
</style>
<div class="am-container" style="margin-top: 20px;">
  <h1>Announcement</h1><hr/>
  <div class="well" style="font-size: 1.3rem;"><?php echo $view_description?></div>
  <style type="text/css">
    td {
      text-overflow: ellipsis;
      overflow: hidden;
      white-space: nowrap;
    }
    .table-problem {
      table-layout: fixed;
    }
  </style>
  <h1>Problems</h1><hr/>
  <div class="well" style="font-size: normal;">
    <table class="am-table am-table-striped table-problem">
      <thead>
        <th style='width: 3%'></th>
        <th style='width: 15%'>Problem ID</th>
        <th style='width: 50%'>Title</th>
        <th style='width: 15%'>Author</th>
        <th style='width: 8%'>AC</th>
        <th style='width: 9%'>Submssion</th>
      </thead>
      <tbody>
        <?php
          foreach($view_problemset as $row){
            echo "<tr'>";
            foreach($row as $table_cell){
              echo "<td>";
              echo $table_cell;
              echo "</td>";
            }
            echo "</tr>";
          }
        ?>
      </tbody>
    </table>
  </div>
</div>
<?php include "footer.php" ?>
