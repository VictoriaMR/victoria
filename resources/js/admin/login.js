$(function  () {
    layui.use('form', function(){
      var form = layui.form;
      //监听提交
      form.on('submit(login)', function(data){
        // alert(888)
        layer.msg(JSON.stringify(data.field),function(){
            location.href='index.html'
        });
        return false;
      });
    });
})