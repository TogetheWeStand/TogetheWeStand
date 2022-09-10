document.addEventListener('DOMContentLoaded', function() {
    let colls = document.querySelectorAll('.coll');
    let csrf = document.querySelector('input[name=\'_csrf\']').value;

    for (let i in colls) {
        colls[i].addEventListener('click', function(e) {
            let xhr = new XMLHttpRequest();
            xhr.open('POST', '/game/tic-tac-toe/')
            xhr.onreadystatechange = function (resp) {
                if(this.readyState==4 && this.status==200) {
                    // console.log(this.response);
                    let response = JSON.parse(this.response);
                    let table = document.querySelector('table');
                    for (let y in response.field) {
                        for (let x in response.field[y]) {
                            let coll = document.querySelector('.coll[data-x="' + x + '"][data-y="' + y + '"]')
                            coll.innerHTML = response.field[y][x];
                        }
                    }

                    if (response.msg) {
                        let msgContainer = document.querySelector('.game-result');
                        msgContainer.innerHTML = response.msg;
                    }
                }
            }
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send("_csrf=" + csrf + "&Step[x]=" + e.target.dataset.x + "&Step[y]=" + e.target.dataset.y);
        })
    }
})