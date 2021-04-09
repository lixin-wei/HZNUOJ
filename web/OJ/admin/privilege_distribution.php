
<?php
require_once("admin-header.php");
// if(!HAS_PRI("edit_privilege_distribution")){
//   require_once("error.php");
//   exit(0);
// }
$can_edit=HAS_PRI("edit_privilege_distribution");

if($_POST['data']){
  require "../include/check_post_key.php";
  if($can_edit){
    $data=$_POST['data'];
    $sql="";
    foreach ($data as $group_name => $arr) {
      foreach ($arr as $key => $value) {
        $sql = "UPDATE privilege_distribution SET $key='$value' WHERE group_name='$group_name';";
        $mysqli->query($sql);
      }
    }
    // echo "<pre>";
    // echo $sql;
    // echo "</pre>";
  }
  else
  {
    require_once("error.php");
    exit(0);
  }
}
?>
<title><?php echo $html_title.$MSG_Distribution ?></title>
<h1><?php echo $MSG_Distribution ?></h1>
<hr/>
<form method="post">
  <?php require "../include/set_post_key.php"?>
  <div class="row">
    <div class="col-sm-2">
      <!-- Nav tabs -->
      <ul id='pri_tag' class="nav nav-pills nav-stacked" role="tablist">
        <?php
        $html="";
        $res=$mysqli->query("SELECT group_name FROM privilege_groups ORDER BY group_order");
        while($group_name=$res->fetch_array()[0]){
          $html .= "<li role='presentation'><a href='#$group_name' role='tab' data-toggle='pill'>$group_name</a></li>";
        }
        echo $html;
        ?>
      </ul>
      <hr/>
      <?php if ($can_edit): ?>
        <center><button type="submit" class="btn btn-default"><?php echo $MSG_SUBMIT ?></button></center>
      <?php endif ?>
    </div><!--col-2-->
    <div class="col-sm-3">
      <!-- Tab panes -->
      <div class="tab-content">
        <?php
        $html="";
        $res=$mysqli->query("SELECT * FROM privilege_distribution");
        while($row=$res->fetch_assoc()){
          $html .= "<div role='tabpanel' class='tab-pane' id='{$row['group_name']}'>";
          foreach ($row as $key => $value) if($key!="group_name"){
            $html .= "<div class='checkbox'><label>";
            $html .= "<input type='hidden' name='data[{$row['group_name']}][$key]' value=0>";
            $html .= "<input type='checkbox' name='data[{$row['group_name']}][$key]' value='1'";
            if($value)$html .= " checked ";
            if(!$can_edit) $html.="disabled";
            $html .= ">";
            if(isset(${"MSG_".$key})){
            $html .= ${"MSG_".$key};
            } else {
              $html .= $key;
            }            
            $html .= "</label></div>";
          }
          $html .= "</div>";
        }
        echo $html;
        ?>
      </div>
    </div><!--col-3-->
    <div class="col-sm-7" >
      <div class="well am-scrollable-vertical" style="height:580px">
      <ul>
        <li>
          <?php echo $MSG_enter_admin_page ?>
        </li>
        <li>
          <?php echo $MSG_rejudge ?>
          <ul><li>
              <?php echo $MSG_HELP_REJUDGE ?>
            </li>
      </ul>
       </li>
        <li>
        <?php echo $MSG_edit_xx_problem ?>:        
          <ul>
            <li>
              <?php echo $MSG_HELP_edit_xx_problem1 ?>
            </li>
            <li>
              note that you can edit hidden problems by typing url yourself even if you don't have privilege,
            </li>
            <li>
              so is recommended to add both "edit"&amp;"see_hidden" privilege. (not finished)
            </li>
          </ul>
        </li>
        <li>
          <?php echo $MSG_see_hidden_xx_problem ?>:
          <ul>
            <li>
            <?php echo $MSG_HELP_see_hidden_xx_problem1 ?>
            </li>
            <li>
            <?php echo $MSG_HELP_see_hidden_xx_problem2 ?>
            </li>
          </ul>
        </li>
        <li>
          <?php echo $MSG_edit_news ?>
          <ul><li>
              <?php echo $MSG_HELP_edit_news ?>
            </li>
      </ul></li>
        <li>
          <?php echo $MSG_edit_contest ?>:
          <ul>
            <li>
            <?php echo $MSG_HELP_edit_contest1 ?>
            </li>
            <li>
            <?php echo $MSG_HELP_edit_contest2 ?>
            </li>
            <li>
            <?php echo $MSG_HELP_edit_contest3 ?>
            </li>
            <li>
            <?php echo $MSG_HELP_edit_contest4 ?>
            </li>
          </ul>
        </li>
        <li>
          <?php echo $MSG_generate_team ?>
          <ul><li>
              <?php echo $MSG_HELP_generate_team ?>
            </li>
      </ul>
        </li>
        <li>
          <?php echo $MSG_edit_user_profile ?>:
          <ul>
            <li>
            <?php echo $MSG_HELP_edit_user_profile ?>
            </li>
          </ul>
        </li>
        <li>
          <?php echo $MSG_edit_privilege_group ?>
          <ul><li>
              <?php echo $MSG_HELP_edit_privilege_group ?>
            </li>
      </ul></li>
        </li>
        <li>
          <?php echo $MSG_edit_privilege_distribution ?>
        </li>
        <li>
        <?php echo $MSG_inner_function ?>
          <ul><li>
              <?php echo $MSG_HELP_inner_function ?>
            </li>
      </ul></li>
        
        <li>
          <?php echo $MSG_see_hidden_user_info ?>:
          <ul>
            <li>
            <?php echo $MSG_HELP_see_hidden_user_info ?>
            </li>
          </ul>
        </li>
        <li>
          <?php echo $MSG_see_wa_info_out_of_contest ?>:
          <ul>
            <li>
            <?php echo $MSG_HELP_see_wa_info_out_of_contest ?>
            </li>
          </ul>
        </li>
        <li>
          <?php echo $MSG_see_wa_info_in_contest ?>
          <ul>
            <li>
            <?php echo $MSG_HELP_see_wa_info_in_contest ?>
            </li>
          </ul>
        </li>
        <li>
          <?php echo $MSG_see_source_out_of_contest ?>
          <ul>
            <li>
            <?php echo $MSG_HELP_see_source_out_of_contest1 ?>
            </li>
            <li>
            <?php echo $MSG_HELP_see_source_out_of_contest2 ?>
            </li>
          </ul>
        </li>
        <li>
          <?php echo $MSG_see_source_in_contest ?>
          <ul>
            <li>
            <?php echo $MSG_HELP_see_source_in_contest1 ?>
            </li>
            <li>
            <?php echo $MSG_HELP_see_source_in_contest2 ?>
            </li>
          </ul>
        </li>
        <li>
          <?php echo $MSG_see_compare ?>
          <ul>
            <li>
            <?php echo $MSG_HELP_see_compare ?>
            </li>
          </ul>
        </li>
        <li>
          <?php echo $MSG_upload_files ?>
          <ul>
            <li>
            <?php echo $MSG_HELP_upload_files1 ?>
            </li>
            <li>
            <?php echo $MSG_HELP_upload_files2 ?>
            </li>
          </ul>
        </li>
      </ul>
      </div><!--well-->
    </div><!--col-7-->
  </div>
</form>

<?php
require_once("admin-footer.php");
?>
<script type="text/javascript">
  $('#pri_tag a:first').tab('show'); // Select first tab
</script>