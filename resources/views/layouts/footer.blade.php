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
        body {
            overscroll-behavior-y: contain;
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
                loadComplete: function () 
                {
                    let grid = $("#grid_id");
                    let rowNum = grid.getGridParam('rowNum');
                    let rowList = grid.getGridParam('rowList').sort(function(a, b) { return a - b; });
                    let totalRecords = grid.getGridParam("records");
                    let totalPages = Math.ceil(totalRecords / rowNum);
                    let lastVisibleRow = parseInt(grid.find("tbody tr:last-child").attr("id").split("_")[1], 10);

                    //rowList
                    let maxVisibleRows = rowList[0];
                    for (let i = 0; i < rowList.length; i++) 
                    {
                        if (rowNum <= rowList[i]) 
                        {
                            maxVisibleRows = rowList[i];
                            break;
                        }
                    }
                    
                    //swipe
                    $("#grid_id").swipe(
                    {
                        swipe: function (event, direction) 
                        {
                            let currentPage = grid.getGridParam("page");
                            let nextPage = currentPage + 1;
                            let prevPage = currentPage - 1;

                            if (direction === "down") 
                            {
                                if (currentPage > 1) 
                                {
                                    grid.setGridParam({page: prevPage}).trigger("reloadGrid");
                                } else {
                                    let firstVisibleRow = parseInt(grid.find("tbody tr:first-child").attr("id").split("_")[1], 10);
                                    if (firstVisibleRow > 1) {
                                        let newRowNum = Math.min(firstVisibleRow - 1);
                                        grid.setGridParam({rowNum: newRowNum}).trigger("reloadGrid");
                                    } 
                                }
                            } else if (direction === "up") 
                             {
                                if (lastVisibleRow === totalRecords && currentPage === totalPages) 
                                {
                                    grid.setGridParam({page: 1}).trigger("reloadGrid");
                                } else {
                                    if (lastVisibleRow < totalRecords) 
                                    {
                                        let nextPageRows = Math.min(maxVisibleRows, totalRecords - lastVisibleRow);
                                        grid.setGridParam({ page: currentPage, rowNum: maxVisibleRows + nextPageRows}).trigger("reloadGrid");
                                    } 
                                    else if (nextPage <= totalPages) 
                                    {
                                        grid.setGridParam({page: nextPage, rowNum: maxVisibleRows}).trigger("reloadGrid");
                                        grid[0].grid.bDiv.scrollTop = 0;
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