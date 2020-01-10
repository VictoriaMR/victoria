
@include('admin/common/baseHeader')
<body class="login-bg">
<div class="login layui-anim layui-anim-up">
    <div class="message">管理员登陆</div>
    <div id="darkbannerwrap"></div>
    
    <form method="post" class="layui-form" >
        <input name="username" placeholder="用户名/手机号"  type="text" lay-verify="required" class="layui-input" >
        <hr class="hr15">
        <input name="password" lay-verify="required" placeholder="密码"  type="password" class="layui-input">
        <hr class="hr15">
        <input value="登录" lay-submit lay-filter="login" style="width:100%;" type="submit">
        <hr class="hr20" >
    </form>
</div>
<script type="text/javascript" src="http://localhost:80/js/admin/login.js"></script>