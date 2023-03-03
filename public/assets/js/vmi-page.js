console.log('vmi page.js')
if ($('#dataTable').length) {
    $('#dataTable').DataTable({
        responsive: true
    });
}
// let pagecount = parseInt($("#vmi-page-filter-count option:selected").val());
// const vmi_page_table = $('#vmi-page-table').DataTable( {
//     searching: true,
//     lengthChange: true,
//     pageLength:pagecount,
//     paging: true,
//     ordering: false,
//     info: false,
// });

// // open orders table search
// $('#vmi-page-search').keyup(function(){
//     vmi_page_table.search($(this).val()).draw() ;
// })

// //open orders table filter
// $(document).on('change','#vmi-page-filter-count',function(){
//     let val = parseInt($("#vmi-page-filter-count option:selected").val());
//     vmi_page_table.page.len(val).draw();
// })

getVmiData();

function getVmiData(){
   $.ajax({
        type: 'GET',
        url: '/getVmiData',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend:function(){
            // $('#dashboard-open-orders-chart .chart-loader-div').removeClass('d-none');
        },
        success: function (res) {  
            console.log(res,'___respose');
            res = JSON.parse(res);
            $('#vmi_table_disp').html(res.table_code);
            $('#pagination_disp').html(res.pagination_code);
            let pagecount = parseInt($("#vmi-page-filter-count option:selected").val());
            const vmi_page_table = $('#vmi-page-table').DataTable( {
                searching: true,
                lengthChange: true,
                pageLength:pagecount,
                paging: true,
                ordering: false,
                info: false,
            });

            // open orders table search
            $('#vmi-page-search').keyup(function(){
                vmi_page_table.search($(this).val()).draw() ;
            })

            //open orders table filter
            $(document).on('change','#vmi-page-filter-count',function(){
                let val = parseInt($("#vmi-page-filter-count option:selected").val());
                vmi_page_table.page.len(val).draw();
            })
            return false;
            // customerSalesChartDisplays(res)
            // res = JSON.parse(res);
            // if(res.success){
            //     customerOpenOrders(res.data.data)
            //     // let count = res.data.count.toFixed(2)
            //     // open order total display work start
            //     console.log(res.data.count.toFixed(2));
            //     $('#open-orders-total-amount').text(`$ ${res.data.count.toFixed(2)}`)
            //     // open order total display work end
            // }
            // console.log(res,'___get customer open orders');
        },
        complete:function(){
            // $('#dashboard-open-orders-chart .chart-loader-div').addClass('d-none');
        }
    }); 
}