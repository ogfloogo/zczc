define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'agent/user_recharge/index' + location.search,
                    add_url: 'agent/user_recharge/add',
                    edit_url: 'agent/user_recharge/edit',
                    del_url: 'agent/user_recharge/del',
                    multi_url: 'agent/user_recharge/multi',
                    import_url: 'agent/user_recharge/import',
                    table: 'user_recharge',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                fixedColumns: true,
                fixedRightNumber: 1,
                columns: [
                    [
                        // { checkbox: true },
                        // {field: 'id', title: __('Id')},
                        // { field: 'sid_name', title: __('上级'), operate: false },
                        { field: 'user.mobile', title: __('Mobile') },
                        { field: 'user.money', title: __('Money'), operate: false },
                        // { field: 'user.loginip', title: __('IP'), operate: false },
                        // {
                        //     field: 'user.jointime', title: __('注册时间'), formatter(value, row, index) {
                        //         return new Date(parseInt(value) * 1000).toLocaleString();
                        //         return new Date(parseInt(value) * 1000).toLocaleString().replace(/:\d{1,2}$/, ' ');
                        //     },
                        // },
                        // {field: 'user.nickname', title: __('Nickname')},
                        { field: 'price', title: __('Price'), operate: false },
                        { field: 'paytime', title: __('Paytime'), operate: 'RANGE', addclass: 'datetimerange', autocomplete: false, formatter: Table.api.formatter.datetime },
                        { field: 'recharge_channel.name', title: __('Channel') },
                        { field: 'order_id', title: __('Order_id'), operate: 'LIKE' },
                        // {field: 'paycode', title: __('Paycode'), operate: 'LIKE'},
                        // { field: 'order_num', title: __('Order_num'), operate: 'LIKE' },
                        { field: 'status', title: __('Status'), searchList: { "0": __('Status 0'), "1": __('Status 1'), "2": __('Status 2') }, formatter: Table.api.formatter.status },
                        { field: 'createtime', title: __('Createtime'), operate: 'RANGE', addclass: 'datetimerange', autocomplete: false, formatter: Table.api.formatter.datetime },
                        // {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        // {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        recyclebin: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    'dragsort_url': ''
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: 'agent/user_recharge/recyclebin' + location.search,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        { checkbox: true },
                        { field: 'id', title: __('Id') },
                        {
                            field: 'deletetime',
                            title: __('Deletetime'),
                            operate: 'RANGE',
                            addclass: 'datetimerange',
                            formatter: Table.api.formatter.datetime
                        },
                        {
                            field: 'operate',
                            width: '130px',
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            buttons: [
                                {
                                    name: 'Restore',
                                    text: __('Restore'),
                                    classname: 'btn btn-xs btn-info btn-ajax btn-restoreit',
                                    icon: 'fa fa-rotate-left',
                                    url: 'agent/user_recharge/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'agent/user_recharge/destroy',
                                    refresh: true
                                }
                            ],
                            formatter: Table.api.formatter.operate
                        }
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },

        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            },
            formatter: {
                user: function (value, row, index) {
                    console.log(row)
                    //这里手动构造URL
                    url = "user/user/edit/ids/" + row.user_id + '&{ids}';

                    //方式一,直接返回class带有addtabsit的链接,这可以方便自定义显示内容
                    //return '<a href="' + url + '" class="label label-success addtabsit" title="' + __("Search %s", value) + '">' + __('Search %s', value) + '</a>';

                    //方式二,直接调用Table.api.formatter.addtabs
                    this.url = url;
                    // return Fast.api.open(url,__('title'),{area:['880px','550px']}) //area:控制弹窗大小

                    return Table.api.formatter.dialog.call(this, value, row, index);
                }
            }
        }
    };
    return Controller;
});
