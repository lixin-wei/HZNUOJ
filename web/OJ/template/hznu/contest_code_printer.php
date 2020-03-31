<?php
  /**
   * by jnxxhzz
   * @2019.03.15
  **/
?>
<?php include "contest_header.php"; ?>
<div class="am-container">
  <hr/>
  <h2>Code printing service</h2>
    <form class="am-form">
      <div class="am-form-group">
        <textarea id="code_to_be_print" id="" rows="13" class="kindeditor"></textarea>
      </div>
      <button id="submit" value="submit" class="am-btn am-btn-lg am-btn-primary am-btn-block">Submit</button>
    </form>
    <h2>History</h2>
    <table class="am-table">
      <thead>
        <tr>
          <th>code</th>
          <th>length</th>
          <th>date</th>
          <th>status</th>
        </tr>
      </thead>
    <?php
    foreach ($printed_codes as $row) {
      $code_write = $row->code;
      $code_len = strlen($code_write)."B";
      if(strlen($code_write) >= 30) $code_write = substr($code_write,0,30)."...";
      $code_write = htmlentities($code_write,ENT_QUOTES,"UTF-8");
      $status = $row->status == 0 ? "pending" : "printed";
      echo <<<HTML
      <tr>
        <td>$code_write</td>
        <td>$code_len</td>
        <td>$row->in_date</td>
        <td>$status</td>
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
    var code = $("#code_to_be_print").val();
    if (code.length <= 3) {
      alert("code is too short to be printed.");
      return;
    }
    $.post("ajax/printer_code/add.php", {
      cid: <?php echo $contest_id ?>,
      code: $("#code_to_be_print").val()
    }, function(data) {
      alert(data['msg']);
      if(data['result']) {
        location.reload();
      }
    }, "json");
  });
</script>