<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Penjualan</title>
    <style type="text/css">
		
        * {
			font-size: 12px;
		}
        .ui-pg-input {
            width: 40px !important;
        }
        
	</style>
</head>

<body>
    <script>
        let pager = 1
        let activeGrid = '#grid_id'
        let triggerClick = true
        let sortname = 'invoice'
        let sortorder = 'asc'
        let rownum = 10
        let limit

        $(document).ready(function() 
        {
            $("#grid_id").jqGrid(
            {
                caption: 'Penjualan',
                url: '{{url('customers/index')}}',
                datatype: 'json',
                styleUI: 'jQueryUI',

                width: 850,
                height: '250',
                pageable: true,
                viewrecords: true,
                ignoreCase: true,
                gridview: true,
                page: 1,
                sortname: sortname,
                rownumbers: true,

                rowNum: 10,
                rowList : [10,15,20],
                pager: '#jqGridPager',

                colNames: ['Id', 'No. Invoice', 'Name', 'Date', 'Gender', 'Saldo'],
                colModel: [
                    {
                        name:'id',
                        sortable: true,
                        hidden: true,
                        key: true,
                    },
                    {
                        name:'invoice',
                        index: 'invoice',
                        sortable: true,
                        editable: true,
                        editoptions:
                        {
                            dataInit: function(element) 
                                {
                                    $(element).attr('autocomplete', 'off'),
                                    $(element).css('text-transform', 'uppercase')
                                }
                        },
                        searchoptions: 
                        {
                            dataInit: function(element) 
                            {
                                $(element).attr('autocomplete', 'off')
                            },
                            sopt: ["in","ge","le"] 
                        }
                    },
                    {
                        name:'nama',
                        index: 'nama',
                        sortable: true,
                        editable: true,
                        editoptions:
                        {
                            dataInit: function(element) 
                                {
                                    $(element).attr('autocomplete', 'off'),
                                    $(element).css('text-transform', 'uppercase')
                                }
                        },
                    },
                    {
                        name:'tanggal',
                        index: 'tanggal',
                        sortable: true,
                        editable: true,
                        formatter: 'date',
                        formatoptions: 
                        { 
                            newformat: 'd-m-Y'
                        },
                        sorttype:'date',
                        searchoptions: 
                        {
                            dataInit: function(element)
                            {
                                $(element).attr('autocomplete', 'off'),
                                $(element).css('text-transform', 'uppercase')
                            }
                        }
                    },
                    {
                        name:'jeniskelamin',
                        index: 'jeniskelamin',
                        edittype:
                        {
                            value: ':LAKI-LAKI;2:PEREMPUAN',
                        },
                    },
                    {
                        name:'saldo',
                        index:'saldo',
                        sortable: true,
                        align: 'right',
                        editable: true,
                        formatter:'currency',
                        formatoptions:
                        {
                            thousandsSeparator: ".",
                            decimalSeparator: ",",
                            decimalPlaces : 2,
                            prefix : 'Rp ',  
                            deaultValue: "Rp 0.00",
                        },
                        searchoptions: 
                        {
                            dataInit: function(element)
                            {
                                $(element).attr('autocomplete', 'off'),
                                $(element).css('text-transform', 'uppercase')
                            }
                        }
                    },
                ],
                jsonReader: {
                    root: 'data',
                    id: 'Id',
                    repeatitems: false
                },
                onPaging: function(pgButton) 
                {
                    // Get the new page and row count
                    var newPage = $(this).getGridParam('page');
                    var newRowNum = $(this).getGridParam('rowNum');
                    
                    // Update rowList to match the new value
                    $(this).setGridParam({ rowList: [newRowNum, newRowNum + 5, newRowNum + 10] });
                },
                loadComplete: function () 
                {
                    var grid = $("#grid_id");
                    var rowHeight = grid.find("tbody tr:first-child").height();
                    var numVisibleRows = grid.parent().height() / rowHeight;
                    var totalRecords = grid.getGridParam("records");
                    var totalPages = Math.ceil(totalRecords / grid.getGridParam("rowNum"));
                    var rowNum = grid.getGridParam('rowNum');
                    var rowList = grid.getGridParam('rowList').sort(function(a, b) { return a - b; });
                    var totalRecords = grid.getGridParam('records');

                    if (rowList.indexOf(rowNum) === -1) 
                    {
                        rowList.push(rowNum);
                    }
                    var maxVisibleRows = rowList[0];
                    for (var i = 0; i < rowList.length; i++) 
                    {
                        if (rowNum <= rowList[i]) 
                        {
                            maxVisibleRows = rowList[i];
                            break;
                        }
                    }
                    if (rowNum > rowList[rowList.length - 1]) 
                    {
                        var remainder = totalRecords % rowNum;
                        maxVisibleRows = (remainder === 0) ? rowNum : remainder;
                    }
                    if (rowNum < rowList[0]) 
                    {
                        maxVisibleRows = rowList[0];
                    }
                    if (rowList.indexOf(rowNum) !== -1 && rowNum < maxVisibleRows) 
                    {
                        maxVisibleRows = rowNum;
                    }
                    if (rowNum > rowList[rowList.length - 1]) 
                    {
                        var remainder = totalRecords % rowNum;
                        maxVisibleRows = (remainder === 0) ? rowNum : remainder;
                    }

                    $("#grid_id").swipe(
                    {
                        swipe: function (event, direction) 
                        {
                            var currentPage = grid.getGridParam("page");
                            var nextPage = currentPage + 1;
                            var prevPage = currentPage - 1;

                            if (direction === "down") {
                                if (currentPage > 1) {
                                    grid.setGridParam({page: currentPage - 1}).trigger("reloadGrid");
                                } else {
                                    var firstVisibleRow = parseInt(grid.find("tbody tr:first-child").attr("id").split("_")[1], 10);
                                    if (firstVisibleRow > 1) {
                                        var newRowNum = Math.min(firstVisibleRow - 1, grid.getGridParam("rowNum"));
                                        grid.setGridParam({rowNum: newRowNum}).trigger("reloadGrid");
                                    }
                                }
                            } else if (direction === "up") 
                             {
                                var lastVisibleRow = parseInt(grid.find("tbody tr:last-child").attr("id").split("_")[1], 10);
                                 if (lastVisibleRow === totalRecords && currentPage === totalPages) 
                                 {
                                     if (nextPage <= totalPages) 
                                     {
                                         firstRow = (nextPage - 1) * maxVisibleRows;
                                         grid.setGridParam({page: nextPage, rowNum: maxVisibleRows, firstrow: firstRow}).trigger("reloadGrid");
                                     }
                                 } else {
                                     if (lastVisibleRow < totalRecords) 
                                     {
                                         var nextPageRows = Math.min(maxVisibleRows, totalRecords - lastVisibleRow);
                                         grid.setGridParam({ page: currentPage, rowNum: maxVisibleRows + nextPageRows }).trigger("reloadGrid");
                                     } else if (nextPage <= totalPages) 
                                     {
                                         firstRow = (nextPage - 1) * maxVisibleRows;
                                        grid.setGridParam({page: nextPage, rowNum: maxVisibleRows, firstrow: firstRow}).trigger("reloadGrid");
                                    }
                                }
                                
                            }
                        },
                        allowPageScroll: "vertical"
                    });
                } 
            }); 
        });
    </script>
</body>
</html>