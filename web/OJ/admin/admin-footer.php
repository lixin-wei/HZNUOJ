
<!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
<script src="/OJ/plugins/jquery/jquery-3.1.1.min.js"></script>

<!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
<script src="/OJ/plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="/OJ/plugins/bootstrap/js/bootstrap-filestyle.min.js"></script>
<script src="/OJ/plugins/bootstrap/js/bootstrap-select.min.js"></script>
</div><!-- container -->

</body>
</html>

<script>
  $("document").ready(function (){
    $("form").append("<div id='csrf' />");
    $("#csrf").load("../csrf.php");
  });
</script>