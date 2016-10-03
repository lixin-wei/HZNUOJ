
<!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>

<!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
<script src="//cdn.bootcss.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
</div><!-- container -->

</body>
</html>

<script>
  $("document").ready(function (){
    $("form").append("<div id='csrf' />");
    $("#csrf").load("../csrf.php");
  });
</script>