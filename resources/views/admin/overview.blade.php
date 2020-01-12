@include('admin/common/baseHeader')
<body>
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body ">
                        <blockquote class="layui-elem-quote">欢迎管理员：
                            <span class="x-red">{{$name ?? ''}}</span>！
                        </blockquote>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">数据统计</div>
                    <div class="layui-card-body ">
                        <ul class="layui-row layui-col-space10 layui-this x-admin-carousel x-admin-backlog">
                            <li class="layui-col-md2 layui-col-xs6">
                                <a href="javascript:;" class="x-admin-backlog-body">
                                    <h3>文章数</h3>
                                    <p>
                                        <cite>66</cite></p>
                                </a>
                            </li>
                            <li class="layui-col-md2 layui-col-xs6">
                                <a href="javascript:;" class="x-admin-backlog-body">
                                    <h3>会员数</h3>
                                    <p>
                                        <cite>12</cite></p>
                                </a>
                            </li>
                            <li class="layui-col-md2 layui-col-xs6">
                                <a href="javascript:;" class="x-admin-backlog-body">
                                    <h3>回复数</h3>
                                    <p>
                                        <cite>99</cite></p>
                                </a>
                            </li>
                            <li class="layui-col-md2 layui-col-xs6">
                                <a href="javascript:;" class="x-admin-backlog-body">
                                    <h3>商品数</h3>
                                    <p>
                                        <cite>67</cite></p>
                                </a>
                            </li>
                            <li class="layui-col-md2 layui-col-xs6">
                                <a href="javascript:;" class="x-admin-backlog-body">
                                    <h3>文章数</h3>
                                    <p>
                                        <cite>67</cite></p>
                                </a>
                            </li>
                            <li class="layui-col-md2 layui-col-xs6 ">
                                <a href="javascript:;" class="x-admin-backlog-body">
                                    <h3>文章数</h3>
                                    <p>
                                        <cite>6766</cite></p>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="layui-col-sm6 layui-col-md3">
                <div class="layui-card">
                    <div class="layui-card-header">下载
                        <span class="layui-badge layui-bg-cyan layuiadmin-badge">月</span></div>
                    <div class="layui-card-body  ">
                        <p class="layuiadmin-big-font">33,555</p>
                        <p>新下载
                            <span class="layuiadmin-span-color">10%
                                <i class="layui-inline layui-icon layui-icon-face-smile-b"></i></span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="layui-col-sm6 layui-col-md3">
                <div class="layui-card">
                    <div class="layui-card-header">下载
                        <span class="layui-badge layui-bg-cyan layuiadmin-badge">月</span></div>
                    <div class="layui-card-body ">
                        <p class="layuiadmin-big-font">33,555</p>
                        <p>新下载
                            <span class="layuiadmin-span-color">10%
                                <i class="layui-inline layui-icon layui-icon-face-smile-b"></i></span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="layui-col-sm6 layui-col-md3">
                <div class="layui-card">
                    <div class="layui-card-header">下载
                        <span class="layui-badge layui-bg-cyan layuiadmin-badge">月</span></div>
                    <div class="layui-card-body ">
                        <p class="layuiadmin-big-font">33,555</p>
                        <p>新下载
                            <span class="layuiadmin-span-color">10%
                                <i class="layui-inline layui-icon layui-icon-face-smile-b"></i></span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="layui-col-sm6 layui-col-md3">
                <div class="layui-card">
                    <div class="layui-card-header">下载
                        <span class="layui-badge layui-bg-cyan layuiadmin-badge">月</span></div>
                    <div class="layui-card-body ">
                        <p class="layuiadmin-big-font">33,555</p>
                        <p>新下载
                            <span class="layuiadmin-span-color">10%
                                <i class="layui-inline layui-icon layui-icon-face-smile-b"></i></span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">系统信息</div>
                    <div class="layui-card-body ">
                        <table class="layui-table">
                            <tbody>
                                <tr>
                                    <th>xxx版本</th>
                                    <td>1.0.180420</td></tr>
                                <tr>
                                    <th>服务器地址</th>
                                    <td>{{$server_addr}}</td></tr>
                                <tr>
                                    <th>操作系统</th>
                                    <td>{{$system_os}}</td></tr>
                                <tr>
                                    <th>服务器版本</th>
                                    <td>{{$server_software}}</td></tr>
                                <tr>
                                    <th>最大内存限制</th>
                                    <td>{{$memory_limit}}</td></tr>
                                <tr>
                                    <th>处理器</th>
                                    <td>{{$processor_identifier}}</td></tr>
                                <tr>
                                    <th>PHP版本</th>
                                    <td>{{$php_version}}</td></tr>
                                <tr>
                                    <th>PHP运行方式</th>
                                    <td>{{$php_sapi_name}}</td></tr>
                                <tr>
                                    <th>MySQL版本</th>
                                    <td>{{$mysql_version}}</td></tr>
                                <tr>
                                    <th>Laravel</th>
                                    <td>{{$laravel_version}}</td></tr>
                                <tr>
                                    <th>上传附件限制</th>
                                    <td>{{$upload_max_filesize}}</td></tr>
                                <tr>
                                    <th>执行时间限制</th>
                                    <td>{{$max_execution_time}}</td></tr>
                                <tr>
                                    <th>剩余空间</th>
                                    <td>{{$disk_free_space}}</td></tr>
                                <tr>
                                    <th>已使用磁盘比</th>
                                    <td>{{$disk_used_rate}}</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</body>
@include('admin/common/baseFooter')