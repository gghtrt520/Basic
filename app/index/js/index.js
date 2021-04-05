var upColor = '#00da3c';
var downColor = '#ec0000';

var chartDom = document.getElementById('echarts');
var myChart = chartDom?echarts.init(chartDom):false;
var option;
function splitData(rawData) {
    var categoryData = [];
    var values = [];
    var volumes = [];
    for (var i = 0; i < rawData.length; i++) {
        categoryData.push(rawData[i].splice(0, 1)[0]);
        values.push(rawData[i]);
        volumes.push([i, rawData[i][4], rawData[i][0] > rawData[i][1] ? 1 : -1]);
    }

    return {
        categoryData: categoryData,
        values: values,
        volumes: volumes
    };
}

function calculateMA(dayCount, data) {
    var result = [];
    for (var i = 0, len = data.values.length; i < len; i++) {
        if (i < dayCount) {
            result.push('-');
            continue;
        }
        var sum = 0;
        for (var j = 0; j < dayCount; j++) {
            sum += data.values[i - j][1];
        }
        result.push(+(sum / dayCount).toFixed(3));
    }
    return result;
}

$.ajax({
    url: "js/stock-DJI.json",
    type: "GET",
    dataType: "json",
    success: function(rawData) {
        var data = splitData(rawData);
        myChart.setOption(option = {
            animation: false,
            legend: {
                bottom: 10,
                left: 'center',
                data: ['Dow-Jones index', 'MA5', 'MA10', 'MA20', 'MA30']
            },
            tooltip: {
                show:false,
                // trigger: 'axis',
                // axisPointer: {
                //     type: 'cross'
                // },
                // borderWidth: 1,
                // borderColor: '#ccc',
                // padding: 10,
                // textStyle: {
                //     color: '#000'
                // },
                // position: function (pos, params, el, elRect, size) {
                //     var obj = {top: 10};
                //     obj[['left', 'right'][+(pos[0] < size.viewSize[0] / 2)]] = 30;
                //     return obj;
                // }
                // extraCssText: 'width: 170px'
            },
            axisPointer: {
                link: {xAxisIndex: 'all'},
                label: {
                    backgroundColor: '#777'
                }
            },
            toolbox: {
                show:false,
            },
            brush: {
                xAxisIndex: 'all',
                brushLink: 'all',
                outOfBrush: {
                    colorAlpha: 0.1
                }
            },
            visualMap: {
                show: false,
                seriesIndex: 5,
                dimension: 2,
                pieces: [{
                    value: 1,
                    color: downColor
                }, {
                    value: -1,
                    color: upColor
                }]
            },
            grid: [
                {
                    left: '10%',
                    right: '8%',
                    height: '50%'
                },
                {
                    left: '10%',
                    right: '8%',
                    top: '63%',
                    height: '16%'
                }
            ],
            xAxis: [
                {
                    type: 'category',
                    data: data.categoryData,
                    scale: true,
                    boundaryGap: false,
                    axisLine: {onZero: false},
                    splitLine: {show: false},
                    splitNumber: 20,
                    min: 'dataMin',
                    max: 'dataMax',
                    axisPointer: {
                        z: 100
                    }
                },
                {
                    type: 'category',
                    gridIndex: 1,
                    data: data.categoryData,
                    scale: true,
                    boundaryGap: false,
                    axisLine: {onZero: false},
                    axisTick: {show: false},
                    splitLine: {show: false},
                    axisLabel: {show: false},
                    splitNumber: 20,
                    min: 'dataMin',
                    max: 'dataMax'
                }
            ],
            yAxis: [
                {
                    scale: true,
                    splitArea: {
                        show: true
                    }
                },
                {
                    scale: true,
                    gridIndex: 1,
                    splitNumber: 2,
                    axisLabel: {show: false},
                    axisLine: {show: false},
                    axisTick: {show: false},
                    splitLine: {show: false}
                }
            ],
            dataZoom: [
                {
                    type: 'inside',
                    xAxisIndex: [0, 1],
                    start: 98,
                    end: 100
                },
                {
                    show: true,
                    xAxisIndex: [0, 1],
                    type: 'slider',
                    top: '85%',
                    start: 98,
                    end: 100
                }
            ],
            series: [
                {
                    name: 'Dow-Jones index',
                    type: 'candlestick',
                    data: data.values,
                    itemStyle: {
                        color: upColor,
                        color0: downColor,
                        borderColor: null,
                        borderColor0: null
                    },
                    tooltip: {
                        formatter: function (param) {
                            param = param[0];
                            return [
                                'Date: ' + param.name + '<hr size=1 style="margin: 3px 0">',
                                'Open: ' + param.data[0] + '<br/>',
                                'Close: ' + param.data[1] + '<br/>',
                                'Lowest: ' + param.data[2] + '<br/>',
                                'Highest: ' + param.data[3] + '<br/>'
                            ].join('');
                        }
                    }
                },
                {
                    name: 'MA5',
                    type: 'line',
                    data: calculateMA(5, data),
                    smooth: true,
                    lineStyle: {
                        opacity: 0.5
                    }
                },
                {
                    name: 'MA10',
                    type: 'line',
                    data: calculateMA(10, data),
                    smooth: true,
                    lineStyle: {
                        opacity: 0.5
                    }
                },
                {
                    name: 'MA20',
                    type: 'line',
                    data: calculateMA(20, data),
                    smooth: true,
                    lineStyle: {
                        opacity: 0.5
                    }
                },
                {
                    name: 'MA30',
                    type: 'line',
                    data: calculateMA(30, data),
                    smooth: true,
                    lineStyle: {
                        opacity: 0.5
                    }
                },
                {
                    name: 'Volume',
                    type: 'bar',
                    xAxisIndex: 1,
                    yAxisIndex: 1,
                    data: data.volumes
                }
            ]
        }, true);

        myChart.dispatchAction({
            type: 'brush',
            areas: [
                {
                    brushType: 'lineX',
                    coordRange: ['2016-06-02', '2016-06-20'],
                    xAxisIndex: 0
                }
            ]
        });
    }
 })

option && myChart.setOption(option);

window.onresize = function () {
    myChart?myChart.resize():'';
}

$.ajax({
    type: "get",
    jsonp: "callback",
    url: "http://localhost/v1/api/frontend/list",
    success: function(result) {
        let html = '';
        if(result.code == 0 && result.data.length>0){
            for(let i = 0;i<result.data.length;i++){
                if(result.data[i].children && result.data[i].children.length>0){
                    html+=`<li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="menu-parent${i}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">${result.data[i].label}</a>
                        <div class="dropdown-menu" aria-labelledby="menu-parent${i}">`;
                    appendsCenter(result.data[i]);
                    for(let j = 0;j<result.data[i].children.length;j++){
                        html+=`<a class="dropdown-item" href="#">${result.data[i].children[j].label}</a>`
                        appendsCenter(result.data[i].children[j]);
                    }
                    html+=`</div></li>`
                }else{
                    html += `<li class="nav-item"><a class="nav-link" href="#">${result.data[i].label}</a></li>`
                }
            }
            $("#indexNavbars>.navbar-nav").html(html);
        }
    },
    error: function(e){
        console.log(e.status);
        console.log(e.responseText);
    }
});

$.ajax({
    type: "post",
    data: {loop_banner_id: 1},
    url: "http://localhost/v1/setting/banner-list/list",
    success: function(result) {
        if(result.code == 0 && result.data.length>0){
            let html = '';
            let html2 = '';
            let length = result.data.length>4?4:result.data.length;
            for(let i = 0;i<length;i++){
                html+=`<li data-target="#myCarousel" data-slide-to="${i}" class="${i === 0 ? 'active' : ''}"></li>`;
                html2+=`<div class="carousel-item a-href${i === 0 ? ' active' : ''}" data-id="${result.data[i].id}">
                    <img src="${result.data[i].url}" class="d-block w-100">
                </div>`
            }
            $("#myCarousel .carousel-indicators").html(html);
            $("#myCarousel .carousel-inner").html(html2)
        }
    },
    error: function(e){
        console.log(e.status);
        console.log(e.responseText);
    }
});

function appendsCenter(data){
    switch(data.label){
        case '今日导读':appendToday(data.content);break;
        case '新闻中心':newsCenter(flatRandom(data));break;
        case '公司新闻':newsCenterMenu(data.content,'公司新闻');break;
        case '基层动态':newsCenterMenu(data.content,'基层动态');break;
        case '通知公告':newsCenterMenu(data.content,'通知公告');break;
        case '招标公告':newsCenterMenu(data.content,'招标公告');break;
        case '安全生产':safetyProduction(flatRandom(data));break;
        case '社会责任':social(flatRandom(data));break;
    }
}

//今日导读
function appendToday(data){
    let html = '';
    let length = data.length>4?4:data.length;
    for(let i = 0;i<length;i++){
        html+=`<div class="col-md-6 d-flex a-href" data-id=${data[i].id}>
            <div class="guide-title"></div>
            <div class="text-truncate text-nowrap">${data[i].title}</div>
            <div class="text-nowrap">${data[i].create_time}</div>
        </div>`
    }
    $(".today-wrap").html(html)
}

//新闻中心
function newsCenter(data){
    let html = '';
    let html2 = '';
    let length = data.length>2?2:data.length;
    for(let i = 0;i<length;i++){
        html+=`<li data-target="#carouselExampleCaptions" data-slide-to="${i}" class="${i === 0 ? 'active' : ''}"></li>`;
        html2+=`<div class="carousel-item a-href${i === 0 ? ' active' : ''}" data-id="${data[i].id}">
            <img src="${data[i].image}" class="d-block w-100">
            <div class="carousel-caption d-md-block">
                <h5>${data[i].title}</h5>
                <hr class="news-hr"/>
                <p>${data[i].resume}</p>
            </div>
        </div>`
    }
    $("#carouselExampleCaptions .carousel-indicators").html(html)
    $("#carouselExampleCaptions .carousel-inner").html(html2)
}

//新闻中心下的四个菜单
function newsCenterMenu(data,type){
    let html = '';
    let wraps = {
        '公司新闻':'pills-home',
        '基层动态':'pills-profile',
        '通知公告':'pills-contact',
        '招标公告':'pills-tenders',
    }
    let length = data.length>3?3:data.length;
    for(let i = 0;i<length;i++){
        html+=`<li class="media mb-4 border a-href bg-light shadow-sm" data-id="${data[i].id}">
            <img src="${data[i].image}">
            <div class="media-body p-3 overflow-hidden">
                <h5 class="mt-0 mb-1 text-truncate text-nowrap">
                    <div class="news-name">
                    <div class="text-truncate">
                    ${data[i].title}
                    </div>
                    <div class="news-time">
                    ${data[i].create_time}
                    </div>
                    </div>
                </h5>
                <hr class="news-hr"/>
                <p>${data[i].resume}</p>
            </div>
        </li>`;
    }
    $("#"+ wraps[type] +" .list-unstyled").html(html)
}

//安全生产
function safetyProduction(data){
    let html = '';
    let length = data.length>2?2:data.length;
    for(let i = 0;i<length;i++){
        html+=`<div class="col-md-6">
            <div class="card mb-4 shadow-sm">
                <img src="${data[i].image}" alt="">
                <div class="card-body">
                    <h6 class="card-title">${data[i].title}</h6>
                    <p class="card-text">${data[i].resume}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">${data[i].create_time}</small>
                    </div>
                </div>
            </div>
        </div>`;
    }
    $(".safety-production .row").html(html)
}

//社会责任
function social(data){
    let html = '';
    let length = data.length>3?3:data.length;
    for(let i = 0;i<length;i++){
        html+=`<div class="col-md-4">
            <div class="card text-center mb-4 shadow-sm">
            <img src="${data[i].image}" alt="">
            <div class="card-body">
                <h7 class="text-danger">${data[i].title}</h7>
                <p class="card-text">${data[i].resume}</p>
            </div>
            </div>
        </div>`;
    }
    $(".social-wrap").html(html)
}

function flatRandom(parentData){
    let datas;
    if(parentData.content.length){
        datas = parentData.content;
    } else {
        datas = shuffle([parentData.children[0].content,parentData.children[1].content].flat(Infinity));
    }
    return datas;
}
function shuffle(arr) {
    let i = arr.length;
    while (i) {
        let j = Math.floor(Math.random() * i--);
        [arr[j], arr[i]] = [arr[i], arr[j]];
    }
    return arr;
}
