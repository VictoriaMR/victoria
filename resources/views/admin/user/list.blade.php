@include('admin/common/baseHeader')
<body>
    <div class="x-nav">
      <span class="layui-breadcrumb">
        <a href="">首页</a>
        <a href="">管理员管理</a>
        <a>
          <cite>管理员列表</cite></a>
      </span>
      <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
        <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
    </div>
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body ">
                        <form class="layui-form layui-col-space5">
                            <!-- <div class="layui-inline layui-show-xs-block">
                                <input class="layui-input"  autocomplete="off" placeholder="开始日" name="start" id="start">
                            </div>
                            <div class="layui-inline layui-show-xs-block">
                                <input class="layui-input"  autocomplete="off" placeholder="截止日" name="end" id="end">
                            </div> -->
                            <div class="layui-inline layui-show-xs-block">
                                <input type="text" name="name"  placeholder="用户名搜索" autocomplete="off" class="layui-input" value="{{$name ?? ''}}">
                            </div>

                            <div class="layui-inline layui-show-xs-block">
                                <input type="text" name="mobile"  placeholder="手机搜索" autocomplete="off" class="layui-input" value="{{$mobile ?? ''}}">
                            </div>
                            <div class="layui-inline layui-show-xs-block">
                                <button class="layui-btn"  lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
                            </div>
                        </form>
                    </div>
                    @if(empty($info['is_super']))
                    <div class="layui-card-header">
                        <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
                        <button class="layui-btn" onclick="xadmin.open('添加用户', ADMIN_URL+'user/addPage', 700, 500)"><i class="layui-icon"></i>添加</button>
                    </div>
                    @endif
                    <div class="layui-card-body ">
                        <table class="layui-table layui-form">
                          <thead>
                            <tr>
                              <th width="10">
                                <input type="checkbox" name=""  lay-skin="primary">
                              </th>
                              <th>ID</th>
                              <th>名称</th>
                              <th>昵称</th>
                              <th>手机</th>
                              <th>属性</th>
                              <th>加入时间</th>
                              <th>状态</th>
                              <th>操作</th>
                          </thead>
                          <tbody>
                            @foreach($list as $info)
                            <tr>
                              <td width="10">
                                <input type="checkbox" name=""  lay-skin="primary">
                              </td>
                              <td>{{$info['user_id'] ?? ''}}</td>
                              <td>{{$info['name'] ?? ''}}</td>
                              <td>{{$info['nickname'] ?? ''}}</td>
                              <td>{{$info['mobile'] ?? ''}}</td>
                              <td>{{$info['is_super'] ? '超级管理员' : ''}}</td>
                              <td>{{$info['created_at']}}</td>
                              <td class="td-status">
                                <span class="layui-btn layui-btn-normal layui-btn-mini">已启用</span></td>
                              <td class="td-manage">
                                @if(empty($info['is_super']))
                                <a onclick="member_stop(this,'10001')" href="javascript:;"  title="启用">
                                  <i class="layui-icon">&#xe601;</i>
                                </a>
                                <a title="编辑"  onclick="xadmin.open('编辑','admin-edit.html')" href="javascript:;">
                                  <i class="layui-icon">&#xe642;</i>
                                </a>
                                <a title="删除" onclick="member_del(this,'要删除的id')" href="javascript:;">
                                  <i class="layui-icon">&#xe640;</i>
                                </a>
                                @endif
                              </td>
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                    </div>
                    <div class="layui-card-body ">
                        <div id="page">
                            <div>
                              <a class="prev" href="">&lt;&lt;</a>
                              <a class="num" href="">1</a>
                              <span class="current">2</span>
                              <a class="num" href="">3</a>
                              <a class="num" href="">489</a>
                              <a class="next disable" href="" >&gt;&gt;</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</body>
<script>
  layui.use(['laydate','form'], function(){
    var laydate = layui.laydate;
    var form = layui.form;
    
    //执行一个laydate实例
    laydate.render({
      elem: '#start' //指定元素
    });

    //执行一个laydate实例
    laydate.render({
      elem: '#end' //指定元素
    });
  });

  layui.use('laypage', function(){
  var laypage = layui.laypage;
  
  //执行一个laypage实例
  laypage.render({
    elem: 'page', //注意，这里的 test1 是 ID，不用加 # 号
    count: <?php echo $pagination['total'] ?? 0;?>, //数据总数，从服务端得到
    limit: <?php echo $pagination['pagesize'];?>,
    curr: <?php echo $pagination['page'] ?? 1;?>, //当前页
    hash: 'fenye',
    jump: function(obj, first){        
        //首次不执行
        if(!first){
            var parmaString = replacrQueryParma({page: obj.curr, 'pagesize': obj.limit});

            var url = 'http://'+ window.location.host + window.location.pathname + parmaString

            window.location.href = url
        }
    }
  });
});

   /*用户-停用*/
  function member_stop(obj,id){
      layer.confirm('确认要停用吗？',function(index){

          if($(obj).attr('title')=='启用'){

            //发异步把用户状态进行更改
            $(obj).attr('title','停用')
            $(obj).find('i').html('&#xe62f;');

            $(obj).parents("tr").find(".td-status").find('span').addClass('layui-btn-disabled').html('已停用');
            layer.msg('已停用!',{icon: 5,time:1000});

          }else{
            $(obj).attr('title','启用')
            $(obj).find('i').html('&#xe601;');

            $(obj).parents("tr").find(".td-status").find('span').removeClass('layui-btn-disabled').html('已启用');
            layer.msg('已启用!',{icon: 5,time:1000});
          }
          
      });
  }

  /*用户-删除*/
  function member_del(obj,id){
      layer.confirm('确认要删除吗？',function(index){
          //发异步删除数据
          $(obj).parents("tr").remove();
          layer.msg('已删除!',{icon:1,time:1000});
      });
  }



  function delAll (argument) {

    var data = tableCheck.getData();

    layer.confirm('确认要删除吗？'+data,function(index){
        //捉到所有被选中的，发异步进行删除
        layer.msg('删除成功', {icon: 1});
        $(".layui-form-checked").not('.header').parents('tr').remove();
    });
  }
</script>
@include('admin/common/baseFooter')