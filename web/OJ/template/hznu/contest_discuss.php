<?php
include "contest_header.php";
require_once('./include/const.inc.php');
?>
<div class="am-container">
  <hr/>
  <h2>Submit question</h2>
    <form class="am-form">
      <select id="problem_id">
        <?php
        foreach ($problem_list as $problem) {
          $pid_show = PID($problem->num);
          echo "<option value=\"{$problem->num}\">$pid_show</option>";
        }
        ?>
      </select>
      <div class="am-form-group">
        <textarea id="content" id="" rows="5" class="kindeditor"></textarea>
      </div>
      <button id="submit" value="submit" class="am-btn am-btn-lg am-btn-primary am-btn-block">Submit</button>
    </form>
    <h2>History</h2>
    <table class="am-table">
      <thead>
        <tr>
          <th>id</th>
          <th>problem_id</th>
          <th></th>
          <th>in_date</th>
          <th>reply</th>
          <th>reply_date</th>
        </tr>
      </thead>
    <?php
    foreach($discuss_list as $discuss) {
      $pid_show = PID($discuss->problem_id);
      $content = htmlentities($discuss->content);
      $reply = htmlentities($discuss->reply);
      echo <<<HTML
      <tr>
        <td>$discuss->id</td>
        <td>$pid_show</td>
        <td><pre>$content</pre></td>
        <td>$discuss->in_date</td>
        <td><pre>$reply</pre></td>
        <td>$discuss->reply_date</td>
      </tr>
HTML;
    }
    ?>
    </table>
  
</div>

<?php include "footer.php" ?>


<script type="text/javascript">
  $("#submit").click(function(e) {
    e.preventDefault();
    var content = $("#content").val();
    if (content.length <= 3) {
      alert("question is too short to be submited.");
      return;
    }
    $.post("ajax/contest_discuss/ask.php", {
      cid: <?php echo $contest_id ?>,
      problem_id: $("#problem_id").val(),
      content: $("#content").val()
    }, function(data) {
      alert(data['msg']);
      if(data['result']) {
        location.reload();
      }
    }, "json");
  });
</script>