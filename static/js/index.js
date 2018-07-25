$('body').on('click', '#connect-btn', function (e) {
    var data = $("#connect").serialize();
    $.ajax({
        url: "/dataediter.php?a=connect",
        type: "POST",
        data: data,
        success: function (data) {
            var data = JSON.parse(data);
            $('#data,#page').css('display', 'none');
            $('.data-table thead').empty();
            $('.data-table tbody').empty();
            if (data.status == '1') {
                $('#table').empty();
                $('#table').append('<option value="">SELECT</option>');
                $.each(data.table, function (index, table) {
                    $('#table').append('<option value="' + table + '">' + table + '</option>');
                });
                bootoast.toast({
                    message: '连接数据库成功',
                    type: 'success',
                    position: 'top',
                    icon: null,
                    timeout: true,
                    animationDuration: 300,
                    dismissible: true
                });
            } else {
                $('#table').empty();
                $('#table').append('<option value="">TABLE</option>');
                bootoast.toast({
                    message: data.exception,
                    type: 'danger',
                    position: 'top',
                    icon: null,
                    timeout: true,
                    animationDuration: 300,
                    dismissible: true
                });
            }

        }
    });
});

$('body').on('click', '#select-btn', function (e) {
    var data = $("#select").serialize();
    $.ajax({
        url: "/dataediter.php?a=query",
        type: "POST",
        data: data,
        success: function (data) {
            query(data);
        }
    });
});

$('body').on('change', '#perpape', function (e) {
    var perpage = $("#perpape").val();
    $.ajax({
        url: "/dataediter.php?a=perpage",
        type: "POST",
        data: {'perpage': perpage},
        success: function (data) {
            query(data);
        }
    });
});

$('body').on('click', '.page-link', function (e) {
    var page = $(e.target).attr('data-page');
    $.ajax({
        url: "dataediter.php?a=page",
        type: "POST",
        data: {'page': page},
        success: function (data) {
            query(data);
        }
    })
});

function query(data) {
    $('#data,#page').css('display', 'block');
    $('.data-table thead').empty();
    $('.data-table tbody').empty();
    var query_data = JSON.parse(data);
    $.each(query_data.data, function (index, value) {
        if ('0' == index) {
            $('.data-table thead').append('<tr class="text-center"></tr>');
            $.each(value, function (i, v) {
                $('.data-table thead tr').append('<th  scope="col">' + i + '</th>');
            })
        }
        var tr = $('<tr class="text-center"></tr>');
        $.each(value, function (i, v) {
            tr.append('<td>' + v + '</td>');
        });
        $('.data-table tbody').append(tr);
    });
    $('.pagination').empty();
    $('.pagination').append('<div class="perpape clearfix"><div>共' + query_data.nums + '条数据</div><div>每页</div><input id="perpape" class="form-control" name="perpape" type="text" value="' + query_data.perpage + '"><div>条</div></div>');
    $('.pagination').append('<li class="' + query_data.pagination.prev.class + '"><a class="page-link" href="javascript:;" data-page="' + query_data.pagination.prev.num + '">上一页</a></li>');
    var pagenum = query_data.pagination.num;
    $.each(pagenum, function (index, value) {
        $('.pagination').append('<li class="' + value.class + '"><a class="page-link" href="javascript:;" data-page="' + value.num + '">' + value.num + '</a></li>');
    });
    $('.pagination').append('<li class="' + query_data.pagination.next.class + '"><a class="page-link" href="javascript:;" data-page="' + query_data.pagination.next.num + '">下一页</a></li>');
    bootoast.toast({
        message: '查询成功',
        type: 'success',
        position: 'top',
        icon: null,
        timeout: true,
        animationDuration: 300,
        dismissible: true
    });
}