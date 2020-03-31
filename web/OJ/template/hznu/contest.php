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

<?php $title=$MSG_PROBLEM;?>
<?php include "contest_header.php"; ?>
<div class="am-container" style="margin-top: 20px;">
  <?php if($view_description):?>
  <h1><?php echo $MSG_Announcement?></h1><hr/>
  <div class="well" style="font-size: 1.3rem;"><?php echo $view_description?></div>
  <?php endif ?>
  <?php if($can_enter_contest):?>
  <h1><?php echo $MSG_PROBLEM ?></h1><hr/>
  <div class="well" style="font-size: normal;">
    <table class="am-table am-table-striped am-table-hover">
      <thead>
      <tr>
        <th style='width: 3%'></th>
        <th><?php echo $MSG_SCORE ?></th>
        <th><?php echo $MSG_PROBLEM_ID ?></th>
        <th><?php echo $MSG_TITLE ?></th>
        <th><?php echo $MSG_AUTHOR ?></th>
        <th title="in this contest"><?php echo $MSG_AC ?></th>
        <th title="in this contest"><?php echo $MSG_SUBMISSION ?></th>
        <?php if($practice): ?>
        <th title="include submissions out of this contest">总共<?php echo $MSG_AC ?></th>
        <th title="include submissions out of this contest">总共<?php echo $MSG_SUBMISSIONS ?></th>
        <?php endif ?>
      </tr>
      </thead>
      <tbody>
        <?php
          foreach($view_problemset as $row){
            echo "<tr>";
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
<?php endif?>
<?php include "footer.php" ?>
