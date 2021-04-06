var total = 0;
var pages = 0;
var size = 0;
var curr = 1;
var htmlFirstSeparator = '<li class="page-item page-first-separator disabled"><a class="page-link" href="JavaScript:void(0);">...</a></li>';
var htmlLastSeparator = '<li class="page-item page-last-separator disabled"><a class="page-link" href="JavaScript:void(0);">...</a></li>';

function generatePageItem(pages){
    return '<li class="page-item"><a class="page-link" href="JavaScript:void(0);">'+pages+'</a></li>';
}

$(document).on("click",'.page-item',function () {
    var pageList = $(".spiritPagination .page-item");
    var click = $(this);
    var active = $(".spiritPagination .active");
    var pagePre = $(".spiritPagination .page-pre")
    var pageNext = $(".spiritPagination .page-next");
    var page = +click.text();
    if(click.hasClass("page-first")){
        if(active.text() == 1){
            return false
        }
        page = 1
        active.removeClass("active")
        $(".spiritPagination .first-item").addClass("active")
        pagePre.attr("disabled",true)
    }else if(click.hasClass("page-pre")){
        if(active.text() == 1){
            return false
        }
        page = +active.text()-1
        active.removeClass("active");
        active.prev().addClass("active");
    }else if(click.hasClass("page-next")){
        if(active.text() == pages){
            return false
        }
        page = +active.text()+1
        active.removeClass("active");
        active.next().addClass("active");
    }else if(click.hasClass("page-last")){
        if(active.text() == pages){
            return false
        }
        page = pages
        active.removeClass("active")
        $(".spiritPagination .last-item").addClass("active")
        pageNext.attr("disabled",true)
    }else{
        if(page == 1){
            $(".spiritPagination .first-item").attr("disabled",true)
            pagePre.attr("disabled",true)
        }
        if(page == this.total){
            $(".spiritPagination .last-item").attr("disabled",true)
            pageNext.attr("disabled",true)
        }
        pageList.removeClass("active");
        click.addClass("active");
    }
    updatePaginationDetail(page);
});

function initSpiritPagination(total, pages, size, curr){
    this.total = total;
    this.pages = pages;
    this.size = size;
    this.curr = curr;
    var html = '';
    // var from = curr*size;
    // var to = pages * this.size;
    // if(pages === this.pages){
    //     to = this.total;
    // }
    var commonHead = '<div class="float-left pagination-detail">' +
        // '<span class="pagination-info">' +
        // '显示第 '+ from +' 到第 '+to+' 条记录，总共 '+this.total+' 条记录' +
        // '</span>' +
        '</div>' +
        '<div class="float-right pagination">' +
        '<ul class="pagination">' +
        '<li class="page-item page-first"><a class="page-link" href="JavaScript:void(0);">首页</a></li>'+
        '<li class="page-item page-pre"><a class="page-link" href="JavaScript:void(0);">上一页</a></li>'
    if(pages === 0){
        // html =  '<div class="float-left pagination-detail">' +
        //     '<span class="pagination-info">' +
        //     '显示第 0 到第 0 条记录，总共 0 条记录' +
        //     '</span>' +
        //     '</div>';
    }else if(pages === 1){//只有一页数据
        // html =  '<div class="float-left pagination-detail">' +
        //     '<span class="pagination-info">' +
        //     '显示第 1 到第 '+total+' 条记录，总共 '+total+' 条记录' +
        //     '</span>' +
        //     '</div>';
    }else{
        html =  commonHead;
        for(var i=0; i< pages; i++){
            html += `<li class="page-item${this.curr == i+1 ? ' active':''}${i == 0 ? ' first-item':''}${i == pages-1 ? ' last-item':''}"><a class="page-link" href="JavaScript:void(0);">${i+1}</a></li>`
        }
        html += `<li class="page-item page-next"><a class="page-link" href="JavaScript:void(0);">下一页</a></li>
        <li class="page-item page-last"><a class="page-link" href="JavaScript:void(0);">尾页</a></li>
        </ul></div>`
    }
    // }else if(pages <= 7){
    //     html =  commonHead;
    //     for(var i=0; i< pages; i++){
    //         html += `<li class="page-item ${this.curr == i+1 ? 'active':''}"><a class="page-link" href="JavaScript:void(0);">${i+1}</a></li>`
    //     }
    //     console.log(html)
    //     html += '<li class="page-item page-next"><a class="page-link" href="JavaScript:void(0);">›</a></li>'+
    //         '</ul></div>';
    // }else if(pages > 7){
    //     html =  commonHead;
    //     for(var j=0; j< 4; j++){
    //         html += `<li class="page-item ${this.curr == j+1 ? 'active':''}"><a class="page-link" href="JavaScript:void(0);">${j+1}</a></li>`
    //     }
    //     html += '<li class="page-item page-last-separator disabled"><a class="page-link" href="JavaScript:void(0);">...</a></li>'+
    //         '<li class="page-item"><a class="page-link" href="JavaScript:void(0);">'+pages+'</a></li>'+
    //         '<li class="page-item page-next"><a class="page-link" href="JavaScript:void(0);">›</a></li>'+
    //         '</ul></div>';
    // }
    $(".spiritPagination").html(html);
}

function updatePaginationDetail(pages){
    var from = (pages-1) * this.size + 1;
    var to = pages * this.size;
    if(pages === this.pages){
        to = this.total;
    }
    var html = '显示第 '+from+' 到第 '+to+' 条记录，总共 '+this.total+' 条记录';
    $(".pagination-info").html(html);
    updateViewInfo(pages, this.size);
}