$(function() {
    layui.use('form', function() {
        var form = layui.form;
        //监听提交
        form.on('submit(login)', function(data) {
            $.post(ADMIN_URI + 'login', data.field, function(res) {
                layer.msg(res.message);
                if (res.code == 200) {
                    location.href = ADMIN_URL
                }
            })
            return false;
        });
    });
})

