@include('admin/common/baseHeader')

<body>
	<div class="x-nav">
      <span class="layui-breadcrumb">
        <a href="">首页</a>
        <a href="">商品管理</a>
        <a>
          <cite>@if(!empty($_GET['product_id'])) 编辑商品 @else 新增商品 @endif</cite></a>
      </span>
    </div>
    <div style="padding-top: 10px;"></div>
	<div class="layui-card">
		<div class="layui-fluid">
			<form id="addForm" class="layui-form" action="" style="width: 800px;">
				<div class="layui-form-item">
					<label class="layui-form-label">商品名称</label>
					<div class="layui-input-block">
						<input type="text" name="goodsName" required lay-verify="required" autocomplete="off" class="layui-input">
					</div>
				</div>
				<div class="layui-form-item">
					<label class="layui-form-label">商品图片</label>
					<div class="layui-upload-drag" id="goodsPic">
					  <i class="layui-icon"></i>
					  <p>点击上传，或将文件拖拽到此处</p>
					</div>
				</div>
				<div class="layui-form-item">
					<label class="layui-form-label">单位</label>
					<div class="layui-input-block">
						<input type="text" name="company" required lay-verify="required" autocomplete="off" class="layui-input">
					</div>
				</div>
				<div class="layui-form-item">
					<label class="layui-form-label">参考价格</label>
					<div class="layui-input-block">
						<input type="text" name="price" required lay-verify="required|number" autocomplete="off" class="layui-input">
					</div>
				</div>
				<div class="layui-form-item">
					<label class="layui-form-label">型号</label>
					<div class="layui-input-block">
						<input type="password" name="password" autocomplete="off" class="layui-input">
					</div>
				</div>
				<div class="layui-form-item">
					<label class="layui-form-label">规格</label>
					<div class="layui-input-block">
						<input type="password" name="password2" autocomplete="off" class="layui-input">
					</div>
				</div>
				<div class="layui-form-item">
					<label class="layui-form-label">描述</label>
					<div class="layui-input-block">
						<textarea name="desc" class="layui-textarea"></textarea>
					</div>
				</div>
				<div class="layui-form-item">
					<label class="layui-form-label">是否是批发商品</label>
					<div class="layui-input-block">
						<input type="radio" name="sfpfsp" value="nan" title="是">
						<input type="radio" name="sfpfsp" value="nv" title="否" checked>
					</div>
				</div>
				<div class="layui-form-item">
					<label class="layui-form-label">分类</label>
	                <div class="layui-input-inline">
	                    <select name="provid" id="provid" lay-filter="provid">
	                        <option value="">一级分类</option>
					        <option value="0">北京</option>
					        <option value="1">上海</option>
					        <option value="2">广州</option>
					        <option value="3">深圳</option>
					        <option value="4">杭州</option>
	                    </select>
	                </div>
	                <div class="layui-input-inline">
	                    <select name="cityid" id="cityid" lay-filter="cityid">
	                        <option value="">二级分类</option>
					        <option value="0">北京</option>
					        <option value="1">上海</option>
					        <option value="2">广州</option>
					        <option value="3">深圳</option>
					        <option value="4">杭州</option>
	                    </select>
	                </div>
				</div>
				<div class="layui-form-item">
					<label class="layui-form-label">状态</label>
					<div class="layui-input-block">
						<input type="radio" name="sex" value="nan" title="启用">
						<input type="radio" name="sex" value="nv" title="禁用" checked>
					</div>
				</div>
				
				<div class="layui-form-item">
					<div class="layui-input-block">
						<button class="layui-btn" lay-submit lay-filter="submitBut">立即提交</button>
						<button type="reset" class="layui-btn layui-btn-primary">重置</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</body>

<script>
	layui.use(['upload','form'], function() {
		var form = layui.form;
		var upload = layui.upload;
		var layer = layui.layer;
		//监听提交
		form.on('submit(submitBut)', function(data) {
			return false;
		});
		form.verify({
			//数组的两个值分别代表：[正则匹配、匹配不符时的提示文字]
		  	ZHCheck: [
			    /^[\u0391-\uFFE5]+$/
			    ,'只允许输入中文'
		  	] 
		});
		//拖拽上传
		upload.render({
			elem: '#goodsPic',
			url: '/upload/',
			done: function(res) {
			  	console.log(res)
			}
		});
	});
</script>

@include('admin/common/baseFooter')