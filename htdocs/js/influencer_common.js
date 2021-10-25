
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
    formSubmit: function(url, obj) {
        if(typeof obj !== 'object' || obj.length <= 0) return false;
        var frm = document.createElement("form");

        frm.setAttribute("method", "post");
        frm.setAttribute("action", url);
        frm.setAttribute("target", "formSubmitIframe");

        var isSubmit = true;

        $.each(obj, function(k, o) {
            if(typeof o.validation === 'string' && _.isEmpty(o.value)) {
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
     * URL 형식 체크 함수
     * @param string
     * @returns {boolean}
     */
    isValidURL: function(url) {
        return (url.match(/(http(s)?:\/\/.)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/g) !== null);
    },
    /**
     * 클래스 제거
     * @param list
     * @param className
     */
    removeClass: function(list,className) {
        for(let item of list) {
            item.classList.remove(className);
        }
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
     * 모달창
     * @param type
     * @param msg
     * @param icon
     */
    showModal: function(type,msg,icon) {
        const div = document.createElement('div');
        div.classList.add(type);
        div.classList.add('modal');
        div.innerHTML = msg;
        document.body.append(div);
    }
};

/**
 * 데이터 관련 오브젝트
 */
var data = {
    /**
     * ajax의 콜하는 로직
     * php에서 데이터 가져오는게 성공하면 {"status":"OK","data":data} 형식으로 내려옴
     * 실패하면 {"status":"OOPS","msg":msg} 형식으로 내려옴
     * 성공한 data를 콜백함수로 보내줌
     * @param {*} method t1
     * @param {*} url t2
     * @param {*} data t3
     * @param {*} CB t4
     */
    ajaxCall: function(method,url,data,CB) {
        $.ajax({
            type:method,
            url:url,
            data:data
        })
        .done(function(res){
            console.log(res);
            try {
                res = JSON.parse(res);
                if(res.status === 'OOPS') {
                    alert(res.msg);
                    return;
                }
                CB(res.data);
            }
            catch {
                alert('ajax 에러가 등장했습니다.');
            }
        })
        .fail(function(){
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
        if(_.get(obj, 'm', '') === '') {
            return false;
        }
        return g5_url + '/tracking/click.php?' + this.httpBuildQuery(obj);
    },
    httpBuildQuery(jsonObj) {
        var keys = Object.keys(jsonObj);
        var values = keys.map(key => jsonObj[key]);

        return keys.filter((key, index) => {
            return !_.isEmpty(values[index]);
        }).map((key, index) => {
            return `${key}=${values[index]}`;
        }).join("&");
    },
};