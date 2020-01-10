$(function  () {
    layui.use('form', function(){
      var form = layui.form;
      //监听提交
      form.on('submit(login)', function(data){
        $.post(ADMIN_URI + 'login', data.field, function(res){
            if (res.data) {
                // location.href='index.html'
            } else {
                layer.msg(res.message);
            }
        })
        return false;
        // alert(888)
        layer.msg(JSON.stringify(data.field),function(){
            location.href='index.html'
        });
        return false;
      });
    });
})