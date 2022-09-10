document.addEventListener('DOMContentLoaded', function () {
    let table = document.querySelector('.algorithm')

    if (!table) {
        return;
    }
    
    table.onmousemove = function (e) {
        if (e.ctrlKey && e.altKey) {
            let td = e.target.closest('td');
            if (!td) return;
            if (!table.contains(td)) return;
            if (td.classList.contains('visited')) return;
            
            let visited = document.querySelector('.visited');
            
            if (visited) {
                visited.classList.remove('visited');
            }
            
            td.classList.add('visited');
            td.style.background = null;
            
            if (td.classList.contains('obstacle')) {
                td.classList.remove('obstacle');
            } else {
                td.classList.add('obstacle');
                td.style.background = 'black';
            }
        }
    }
    
    table.onclick = function(e) {
        let td = e.target.closest('td');

        if (!td) return;
        if (!table.contains(td)) return;
        if (!e.ctrlKey) {
            td.classList.remove('obstacle');
        }

        td.classList.remove('start-point');
        td.classList.remove('end-point');
        td.style.background = null;

        if (e.ctrlKey && !e.altKey) {
            if (td.classList.contains('obstacle')) {
                td.classList.remove('obstacle');
            } else {
                td.classList.add('obstacle');
                td.style.background = 'black';
            }
        } else if (e.altKey && !e.ctrlKey) {
            let startPoint = document.querySelector('.start-point');

            if (startPoint) {
                startPoint.classList.remove('start-point');
                startPoint.style.background = null;
            }

            td.style.background = 'green'
            td.classList.add('start-point');
        } else {
            let endPoint = document.querySelector('.end-point');

            if (endPoint) {
                endPoint.classList.remove('end-point');
                endPoint.style.background = null;
            }

            td.style.background = 'blue';
            td.classList.add('end-point');

            let startPoint = document.querySelector('.start-point');

            if (startPoint) {
                let pathPoints = document.querySelectorAll('.path');

                for (let i = 0; i < pathPoints.length; i++) {
                    pathPoints[i].classList.remove('path');
                    pathPoints[i].style.background = null;
                }

                let csrf = document.querySelector('.csrf').innerHTML;
                let xhr = new XMLHttpRequest();
                let url = document.querySelector('.ajax-url').innerHTML;
                
                xhr.open('POST', url)
                xhr.onreadystatechange = function () {
                    if(this.readyState==4 && this.status==200) {
                        let data = JSON.parse(this.response);
                        let liPoints = {};
                        for (let i = 1; i < data.li.path.length - 1; i++) {
                            let cell = document.querySelector('[data-x="' + data.li.path[i].x + '"][data-y="' + data.li.path[i].y + '"]');
                            cell.style.background = 'yellow';
                            cell.classList.add('path');
                            
                            if (liPoints[data.li.path[i].x] === undefined) {
                                liPoints[data.li.path[i].x] = {};
                            }

                            liPoints[data.li.path[i].x][data.li.path[i].y] = 1;
                        }

                        for (let i = 1; i < data.astar.path.length - 1; i++) {
                            let cell = document.querySelector(
                                '[data-x="' + data.astar.path[i].x + '"][data-y="' + data.astar.path[i].y + '"]'
                            );
                            
                            cell.classList.add('path');
                            
                            if (
                                liPoints[data.astar.path[i].x] === undefined
                                || liPoints[data.astar.path[i].x][data.astar.path[i].y] === undefined
                                || liPoints[data.astar.path[i].x][data.astar.path[i].y] !== 1
                            ) {
                                cell.style.background = 'red';
                            } else {
                                cell.style.background = 'grey';
                            }
                        }
                        
                        let sastar = document.querySelector('.time.astar .s');
                        let msastar = document.querySelector('.time.astar .ms');
                        let mcsastar = document.querySelector('.time.astar .mcs');

                        sastar.innerHTML = data.astar.time.s + 's ';
                        msastar.innerHTML = data.astar.time.ms + 'ms ';
                        mcsastar.innerHTML = data.astar.time.mcs + 'mcs';

                        let sli = document.querySelector('.time.li .s');
                        let msli = document.querySelector('.time.li .ms');
                        let mcsli = document.querySelector('.time.li .mcs');

                        sli.innerHTML = data.li.time.s + 's ';
                        msli.innerHTML = data.li.time.ms + 'ms ';
                        mcsli.innerHTML = data.li.time.mcs + 'mcs';
                    }
                }

                xhr.setRequestHeader("Content-type", "application/json");
                xhr.setRequestHeader("X-CSRF-Token", csrf);

                let obstacles = document.querySelectorAll('.obstacle');
                let obstaclesData = {};

                for (let i = 0; i < obstacles.length; i++) {
                    if (!obstaclesData[Number(obstacles[i].dataset.x)]) {
                        obstaclesData[Number(obstacles[i].dataset.x)] = {};
                    }

                    obstaclesData[Number(obstacles[i].dataset.x)][Number(obstacles[i].dataset.y)] = -1;
                }
                
                xhr.send(JSON.stringify({
                    start: {
                        x: startPoint.dataset.x,
                        y: startPoint.dataset.y
                    },
                    end: {
                        x: td.dataset.x,
                        y: td.dataset.y
                    },
                    obstacles: obstaclesData
                }));
            }
        }
    };
})