
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
<title>Privilege Distribution</title>
<h1>Privilege Distribution Edit</h1>
<hr/>
<form method="post">
  <?php require $_SERVER['DOCUMENT_ROOT']."/OJ/include/set_post_key.php"?>
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
        <center><button type="submit" class="btn btn-default">Submit</button></center>
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
            $html .= $key;
            $html .= "</label></div>";
          }
          $html .= "</div>";
        }
        echo $html;
        ?>
      </div>
    </div><!--col-3-->
    <div class="col-sm-7">
      <div class="well">
      <ul>
        <li>
          enter_admin_page
        </li>
        <li>
          rejudge
        </li>
        <li>
          edit_news
        </li>
        <li>
          edit_contest:
        </li>
        <li>
          <ul>
            <li>
              edit and add contest.
            </li>
            <li>
              you can enter any contest in passing.
            </li>
            <li>
              you can see problem ID even when contest is running.
            </li>
          </ul>
        </li>
        <li>
          generate_team
        </li>
        <li>
          edit_user_profile:
        </li>
        <li>
          <ul>
            <li>
              change user ID, password.&nbsp;
            </li>
          </ul>
        </li>
        <li>
          edit_privilege_group
        </li>
        <li>
          edit_privilege_distribution
        </li>
        <li>
          inner_function
        </li>
        <li>
          edit_xx_problem:
        </li>
        <li>
          <ul>
            <li>
              edit problems, see problems' data in corresponding problemset.
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
          see_hidden_xx_problem:
        </li>
        <li>
          <ul>
            <li>
              see hidden problems in corresponding problemset at problemset page.
            </li>
            <li>
              including problems in running contest.
            </li>
          </ul>
        </li>
        <li>
          upload_files
        </li>
        <li>
          <ul>
            <li>
              checked when uploading files.
            </li>
            <li>
              problem and contest editor need this privilege.
            </li>
          </ul>
        </li>
        <li>
          see_hidden_user_info:
        </li>
        <li>
          <ul>
            <li>
              see the hidden information in userinfo page, including real name, class and recent login info.
            </li>
          </ul>
        </li>
        <li>
          see_wa_info_out_of_contest:
        </li>
        <li>
          <ul>
            <li>
              see WA,RE,PE,TEST_RUN and CE information of all users&nbsp;only in normal status.
            </li>
          </ul>
        </li>
        <li>
          see_wa_info_in_contest
        </li>
        <li>
          <ul>
            <li>
              see WA,RE,PE,TEST_RUN and CE information of all users in contest status.
            </li>
          </ul>
        </li>
        <li>
          see_source_out_of_contest
        </li>
        <li>
          <ul>
            <li>
              see souce code of all subbmissions out of contest.
            </li>
            <li>
              you will see the hidden time,memory,language info in conteset of all users in passing.
            </li>
          </ul>
        </li>
        <li>
          see_source_in_contest
        </li>
        <li>
          <ul>
            <li>
              see souce code of all subbmissions in contest.
            </li>
            <li>
              you will see the hidden time,memory,language info in conteset of all users in passing.
            </li>
          </ul>
        </li>
        <li>
          see_compare
        </li>
        <li>
          <ul>
            <li>
              view the compare source page.
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