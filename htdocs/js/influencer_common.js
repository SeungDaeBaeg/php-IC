
/**
 * 자주쓰는 함수를 모아놓은 오브젝트
 */
var util = {
    /**
     * form submit 헬퍼 함수
     *
     util.formSubmit("php", [
     {name: "zip_code", value:$("#zip_code").val(),  validation: function() { alert("주소를 입력해주세요"); }}
     ]);
     * @param url
     * @param obj
     * @returns {boolean}
     */
    formSubmit: function(url, obj, opt) {
        if(typeof obj !== 'object' || obj.length <= 0) return false;
        var frm = document.createElement("form");

        if(_.isEmpty(opt.method)) opt.method = 'post';

        frm.setAttribute("method", opt.method);
        frm.setAttribute("action", url);

        if(!opt.isNotIframe) {
            frm.setAttribute("target", "formSubmitIframe");
        }

        var isSubmit = true;

        $.each(obj, function(k, o) {
            if(typeof o.validation === 'string' && _.isEmpty(o.value)) {
                alert(o.validation);
                isSubmit = false;
                return false;
            } else if(typeof o.validation === 'function') {
                if(!o.validation(o.value)) {
                    isSubmit = false;
                    return false;
                }
            }

            var i = document.createElement("input");
            i.setAttribute("type", "hidden");
            i.setAttribute("name", o.name);
            i.setAttribute("value", o.value);
            frm.appendChild(i);
        });

        if(isSubmit) {
            document.body.appendChild(frm);
            frm.submit();
        }
    },
    /**
     * 리스트 클래스 제거
     * @param list
     * @param className
     */
    removeListClass: function(list,className) {
        for(var i = 0; i < list.length; i ++) {
            var item = list[i];
            item.classList.remove(className);
        };
    },
    /**
     * 클립보드 복사
     * @param msg
     */
    clipboardCopy: function(msg) {
        var tempElem = document.createElement('textarea');
        tempElem.value = msg;
        document.body.appendChild(tempElem);

        tempElem.select();
        document.execCommand("copy");
        document.body.removeChild(tempElem);
    },
    /**
     * alert창
     * @param msg 
     * @param icon /img에서 시작하는 파일
     * @param type info error로 구분하는 타입값
     */
    alert: function(msg, icon, type) {
        if(_.isEmpty(type)) {
            type = 'info';
        }

        var div = document.createElement('div');
        div.classList.add(type);
        div.classList.add('modal_box');
        var id = this.getRandom();
        div.id = id; 
        div.innerHTML = msg;
        if(icon) {
            var img = document.createElement('img');
            img.src = '../../img/'+icon;
            div.prepend(img);
        }
        document.body.append(div);
        setTimeout(function(){
            document.getElementById(id).remove();
        },1000);
    },
    /**
     * 랜덤값 뽑아내는 함수
     * @param {*} len 랜덤길이 기본값 32자리
     * @param {*} type number | string 기본값 number+string
     * @returns 
     */
    getRandom: function(len,type) {
        if(_.isEmpty(len)) {
            len = 32;
        }
        var data = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz';
        var res = '';
        if(type === 'number') {
            data = '0123456789';
        }
        else if(type === 'string') {
            data = 'ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz';
        }
        data = data.split('');
        for(var i = 0; i < len; i++) {
            res += data[Math.floor(Math.random() * data.length)];
        }
        return res;
    }
    
};

/**
 * 데이터 관련 오브젝트
 */
var data = {
    /**
     * ajax 로드 함수
     * php에서 데이터 가져오는게 성공하면 {"status":"OK","data":data} 형식으로 내려옴
     * 실패하면 {"status":"OOPS","msg":msg} 형식으로 내려옴
     * 성공한 data를 콜백함수로 보내줌
     * @param {*} method post/get, default post
     * @param {*} url 데이터를 받아올 URL
     * @param {*} data 받아온 데이터
     * @param {*} CB 데이터 가공할 콜백
     */
    ajax: function(url, data, callback, method) {
        if(_.isEmpty(method)) {
            method = 'post';
        }

        $.ajax({
            type:method,
            url:url,
            data:data
        }).done(function(res) {
            try {
                res = JSON.parse(res);
                if(res.status === 'OOPS') {
                    alert(res.msg);
                    return;
                }

                if(typeof callback === 'function') {
                    callback(res.data);
                }
            } catch(e) {
                alert('ajax 에러가 등장했습니다.');
            }
        }).fail(function(){
            alert('데이터를 가져오는데 실패하였습니다.');
        })
    },
    getLoginId: function() {
        return g5_logined_id;
    },
    isLogin: function() {
        return this.getLoginId() !== '';
    }
};

var url = {
    getClickUrl: function(obj) {
        console.log(obj);
        if(_.isEmpty(obj.m)) {
            return false;
        }
        return g5_url + '/tracking/click.php?' + this.httpBuildQuery(obj);
    },
    httpBuildQuery: function(jsonObj) {
        var keys = Object.keys(jsonObj);
        var values = keys.map(function(key) {
            return jsonObj[key];
        });

        return keys.filter(function(key, index) {
            return !_.isEmpty(values[index].toString());
        }).map(function(key, index) {
            return key + '=' + values[index];
        }).join("&");
    },
    /**
     * url get 파라미터 리턴하는 함수
     * @param {string} param 가져올 키 파라미터 
     * @returns 키에 대응되는 밸류값 리턴
     */
    getUrlParam: function(param) {
        var getParam = location.search.substr(location.search.indexOf("?") + 1);
        var res = "";
        var getParams = getParam.split("&");

        for(var i = 0; i < getParams.length; i++) {
            var temp = getParams[i].split("=");
            if ([temp[0]] == param) res = temp[1];
        }
        return res;    
    },
    /**
     * url get 파라미터 리턴하는 함수
     * @param {array} param 가져올 키 파라미터 배열
     * @returns 키에 대응되는 밸류값 오브젝트 리턴
     */
    getUrlListParam: function(params) {
        var res = {};
        for(var i = 0; i < params.length; i++) {
            var param = params[i];
            res[param] = this.getUrlParam(param);
        }
        return res;
    },
    /**
     * GET QueryString을 Object로 변환
     * @param queryString
     * @returns {{}|*}
     */
    getUrlParams(queryString) {
        let query = (queryString || window.location.search).substring(1);
        if (!query) {
            return {};
        }

        return _.chain(query.split('&'))
            .map(function(params) {
                var p = params.split('=');
                return [p[0], decodeURIComponent(p[1])];
            })
            .fromPairs()
            .value();
    },
    /**
     * URL 형식 체크 함수
     * @param string
     * @returns {boolean}
     */
    isValidURL: function(url) {
        return (url.match(/(http(s)?:\/\/.)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/g) !== null);
    }
};