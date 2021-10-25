// 해당 노드들의 class를 지워줌
function removeClass(list,className) {
    for(let item of list) {
        item.classList.remove(className);
    }
}

// ajax의 콜하는 로직
// php에서 데이터 가져오는게 성공하면 {"status":"OK","data":data} 형식으로 내려옴
// 실패하면 {"status":"OOPS","msg":msg} 형식으로 내려옴
// 성공한 data를 콜백함수로 보내줌


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
function ajaxCall(method,url,data,CB) {
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
}



// 클립보드로 카피하는 함수
function clipboardCopy(msg) {
    var tempElem = document.createElement('textarea');
    tempElem.value = msg;  
    document.body.appendChild(tempElem);

    tempElem.select();
    document.execCommand("copy");
    document.body.removeChild(tempElem);
}

// jelly/css/mobild_shop에서 수정
function showModal(type,msg,icon) {
    const div = document.createElement('div');
    div.classList.add(type);
    div.classList.add('modal');
    div.innerHTML = msg;
    document.body.append(div);
}