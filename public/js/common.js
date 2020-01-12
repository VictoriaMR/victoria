//获取参数值
function getQueryVariable(variable)
{
   var query = window.location.search.substring(1);
   var vars = query.split("&");
   for (var i=0;i<vars.length;i++) {
           var pair = vars[i].split("=");
           if(pair[0] == variable){return pair[1];}
   }
   return(false);
}

function replacrQueryParma(obj)
{
	var query = window.location.search.substring(1);
	var vars = query.split("&");

	for (var i in obj) {
		var inArr = false
		for (var j=0 ;j < vars.length; j++) {
		   	var pair = vars[j].split("=");
		   	if(pair[0] == obj[i]) {
		   		vars[j] = i + '=' + obj[i]
		   		inArr = true
		   	}
		}

		if (!inArr) {
			vars.push(i + '=' + obj[i])
		}
	}
	var parmaString = '?'
	for (var i in vars) {
		if (vars[i] != '') {
			parmaString += vars[i]
			if (i < vars.length - 1)
				parmaString += '&'
		}
	}

	return parmaString
}

function logout()
{
    $.post(ADMIN_URI + 'logout', {}, function(res) {
        layer.alert(res.message , {time: 2000}, function(){
	        if (res.code == 200) {
	            location.href = ADMIN_URL + 'login'
	        }
        });
    })
}