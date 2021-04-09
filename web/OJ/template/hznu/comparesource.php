<?php
  /**
   * This file is created
   * by yybird
   * @2016.03.26
   * last modified
   * by yybird
   * @2016.05.26
  **/
?>

<?php 
  if (is_numeric($cid)) {
      $_GET['cid']=$cid;
      require_once("contest_header.php");
  }else require_once("header.php");
  $title="Compare Source Code";
?>
  <link type="text/css" rel="stylesheet" href="plugins/mergely/mergely.css" />
  <link type="text/css" rel="stylesheet" href="plugins/mergely/codemirror.css" />

<div class="am-container" style="padding-top: 40px;">
     <input type="checkbox" id="ignorews">&nbsp;<?php echo $MSG_IgnoreWS ?>
      <table  style="width: 100%;"><tr>
        <td style="width: 50%;"><tt id="path-lhs"></tt> &nbsp; <a id="save-lhs" class="save-link" href="#"><?php echo $MSG_Download ?></a></td>
        <td style="width: 50%;"><tt id="path-rhs"></tt> &nbsp; <a id="save-rhs" class="save-link" href="#"><?php echo $MSG_Download ?></a></td>
      </tr></table>

      <div id="mergely-resizer" style="height: 450px;">
        <div id="compare">
        </div>
      </div>
  </div>

<?php include "footer.php" ?>
  <!-- Requires CodeMirror 2.16 -->
  <script type="text/javascript" src="plugins/mergely/codemirror.js"></script>
  <!-- Requires Mergely -->
  <script type="text/javascript" src="plugins/mergely/mergely.js"></script>

   <script type="text/javascript">
  $(document).ready(function () {
        $('#compare').mergely({
            width: 'auto',
            height: 'auto', // containing div must be given a height
            cmsettings: { readOnly: false },
        });
        var lhs_url = 'getsource.php?id=<?php echo intval($_GET['left'])?>';
        var rhs_url = 'getsource.php?id=<?php echo intval($_GET['right'])?>';
        $.ajax({
            type: 'GET', async: true, dataType: 'text', url: lhs_url,
            success: function (response) {
                $('#path-lhs').text(lhs_url);
                $('#compare').mergely('lhs', response);
            }
        });
        $.ajax({
            type: 'GET', async: true, dataType: 'text', url: rhs_url,
            success: function (response) {
                $('#path-rhs').text(rhs_url);
                $('#compare').mergely('rhs', response);
            }
        });
  
    function checkFileList(files) {
      if (typeof window.FileReader !== 'function')
        error_msg("The file API isn't supported on this browser yet.");
      if (files.length>0) readFile(files[0], "lhs");
      if (files.length>1) readFile(files[1], "rhs");
    }
    function readFile(file, side) {
      var reader = new FileReader();
      reader.onload = function file_onload() {
        $('#path-'+side).text(file.name);
        $('#compare').mergely(side, reader.result);
      }
      reader.readAsBinaryString(file);
    }
    function handleDragOver(evt) {
      evt.stopPropagation();
      evt.preventDefault();
      evt.dataTransfer.dropEffect = 'copy'; // Explicitly show this is a copy.
    }
    function handleFileSelect(evt) {
      document.getElementById('drop_zone').visibility = "collapse";
      evt.stopPropagation();
      evt.preventDefault();
      var files = evt.dataTransfer.files; // FileList object.
      checkFileList(files);
    }
    var dropZone = document.getElementById('drop_zone');
    document.body.addEventListener('dragover', handleDragOver, false);
    document.body.addEventListener('drop', handleFileSelect, false);
    function download_content(a, side) {
      //a.innerHTML = "preparing content..";
      var txt = $('#compare').mergely('get', side);
      var datauri = "data:plain/text;charset=UTF-8," + encodeURIComponent(txt);
      a.setAttribute('download', side+".txt");
      a.setAttribute('href', datauri);
      //a.innerHTML = "content ready.";
    }
    document.getElementById('save-lhs').addEventListener('mouseover', function() { download_content(this, "lhs"); }, false);
    document.getElementById('save-rhs').addEventListener('mouseover', function() { download_content(this, "lhs"); }, false);
    document.getElementById('ignorews').addEventListener('change', function() {
        $('#compare').mergely('options', { ignorews: this.checked });
        }, false);
  });
  </script>

