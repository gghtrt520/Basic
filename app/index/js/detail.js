let id = localStorage.detailId
let type = localStorage.detailType// 1,2,3    文章，更多，菜单
let page = {
    current:1,
    pages:0,
    pageSize:10,
}

if(type == 1){
    getContent(id)
}else{
    getMenu(id)
}
function getContent(id){
    $(".content-list-wrap").hide()
    $(".article-wrap").show()
    $.ajax({
        type: "post",
        data: {id: id},
        url: "http://localhost/v1/api/frontend/content",
        success: function(result) {
            if(result.code == 0){
                getMenu(result.data.menu_id)
                $(".article-wrap").html(result.data.content)
                // $(".breadcrumb .active").text(esult.data.label)
            }
        },
        error: function(e){
            console.log(e.status)
        }
    });
}

function getMenu(menuid){
    $.ajax({
        type: "post",
        data: {menu_id: menuid,page:page.current},
        url: "http://localhost/v1/api/frontend/detail",
        success: function(result) {
            if(result.code == 0){
                if(type != 1){
                    $(".content-list-wrap").show()
                    $(".article-wrap").hide()
                }
                $(".parent-name").text(result.data.menu.label)
                $(".breadcrumb-2 a").text(result.data.menu.label)
                data = result.data
                page.pages = result.extend.pages
                appendPageList(menuid)
                initSpiritPagination(result.extend.pages,Math.ceil(+result.extend.pages/+page.pageSize),page.pageSize)
            }
        },
        error: function(e){
            console.log(e.status)
        }
    });
}

function updateViewInfo(current){
    page.current = current
    getMenu(id)
}

function appendPageList(id){
    let secondSelect = '';
    let secondList = '';
    let contentList = '';
    let menuCildren = data.menu.children;
    for(let i = 0;i<menuCildren.length;i++){
        secondSelect+=`<option value="${menuCildren[i].id}">${menuCildren[i].label}</option>`
        secondList+=`<li class="list-group-item${menuCildren[i].id == id || i == 0 ? ' active' : ''}" data-id="${menuCildren[i].id}"><i class="iconfont icon-double-arrow"></i><span>${menuCildren[i].label}</span</li>`
        if((id && id == menuCildren[i].id)){
            for(let j = 0;j<data.content.length;j++){
                contentList+=`<li class="list-group-item" data-id="${menuCildren[i].content[j].id}">
                    <i class="iconfont icon-playcircle-fill"></i>
                    <span>${menuCildren[i].content[j].title}</span>
                    <span class="float-right text-secondary">${menuCildren[i].content[j].create_time}</span>
                </li>`
            }
        }
    }
    $("#menu-second-select").html(secondSelect)
    $("#menu-second-list").html(secondList)
    $("#content-list").html(contentList)
    $(".breadcrumb-3,.content-title>.title-name").text($("#menu-second-list .list-group-item.active span").text())
}

$("#menu-second-list").on("click",".list-group-item",function(){
    type = 3
    localStorage.detailType = 3
    id = $(this).attr("data-id")
    localStorage.detailId = $(this).attr("data-id")
    getMenu($(this).attr("data-id"))

})


$("#content-list").on("click",".list-group-item",function(){
    type = 1
    localStorage.detailType = 1
    id = $(this).attr("data-id")
    localStorage.detailId = $(this).attr("data-id")
    getContent($(this).attr("data-id"))
})