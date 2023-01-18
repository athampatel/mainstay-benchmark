// cutomersaleshistory
$(function(){
    // customer sales history
    AjaxRequestCom('/customersales','GET','','chartDisplay');
    // customer invoice orders
    $.ajax({
        type: 'GET',
        url: '/customer-invoice-orders',
        dataType: "JSON",
        success: function (res) {
            // chartDisplay(res.data);
            console.log(res,'___customer invoice response')
            customerInvoiceOrderDisplay(res);
        }
    });
    // AjaxRequest('/admin/user/cancel','POST',{ "_token": "{{ csrf_token() }}",'user_id':$user_id},'userActivateResponseAction')
});


function customerInvoiceOrderDisplay(res){
    $html = '';
    for(let i = 1; i<=5; i++){
        $html += `
        <tr>
            <td>
                <a href="javascript:void(0)" class="item-number font-12 btn btn-primary btn-rounded">#89742</a>
            </td>
            <td>
                <a href="javascript:void(0)" class="customer-name">Adams Baker</a>
            </td>
            <td>
                <a href="mailto:adamsbaker@mail.com" class="customer-name">adamsbaker@mail.com</a>
            </td>
            <td>
                2
            </td>
            <td>
                $245
            </td>
            <td>
                Apr 08, 2021
            </td>
            <td>
                <span class="svg-icon location-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="8.542" height="11.46" viewBox="0 0 8.542 11.46"><path class="location-svg" d="M260.411,154a4.266,4.266,0,0,0-4.266,4.266c0,2.494,2.336,5.48,3.551,6.872a.952.952,0,0,0,1.428,0c1.217-1.385,3.563-4.37,3.563-6.872A4.266,4.266,0,0,0,260.411,154Zm0,6.7a2.439,2.439,0,1,1,1.724-.714A2.438,2.438,0,0,1,260.411,160.7Z" transform="translate(-256.145 -154)" fill="#9fcc47"/></svg>
                </span> 
                London
            </td>
            <td>
                Completed
            </td>
        </tr>`;

    }
    $('#invoice-orders-table-body').html($html);

// <tr>
/* <td><a href="javascript:void(0)" class="item-number font-12 btn btn-primary btn-rounded">#89742</a></td>
<td><a href="javascript:void(0)" class="customer-name">Adams Baker</a></td>
<td><a href="mailto:adamsbaker@mail.com" class="customer-name">adamsbaker@mail.com</a></td>
<td>2</td>
<td>$245</td>
<td>Apr 08, 2021</td>
<td><span class="svg-icon location-icon">
<svg xmlns="http://www.w3.org/2000/svg" width="8.542" height="11.46" viewBox="0 0 8.542 11.46"><path class="location-svg" d="M260.411,154a4.266,4.266,0,0,0-4.266,4.266c0,2.494,2.336,5.48,3.551,6.872a.952.952,0,0,0,1.428,0c1.217-1.385,3.563-4.37,3.563-6.872A4.266,4.266,0,0,0,260.411,154Zm0,6.7a2.439,2.439,0,1,1,1.724-.714A2.438,2.438,0,0,1,260.411,160.7Z" transform="translate(-256.145 -154)" fill="#9fcc47"/></svg>
</span>London
</td>
<td>Completed</td>
</tr> */ 
console.log(res,'__response');
}


function chartDisplay($res){
    $counts = [];
    $categories = [];
    $customer_data = $res.data.data.customersaleshistory;
    for(let i = 1; i <= 12 ; i++ ){
        let is_add = true;
        $customer_data.forEach(da => {
            if(da.fiscalperiod == i){
                is_add = false;
                $counts.push(da.dollarssold)
            }
        })
        if(is_add){
            $counts.push(0);
        }
    }
    
    var options = {
        series: [{
                name: 'sales',
                data: $counts
            },
        ],
        chart: {
            foreColor: '#9ba7b2',
            type: 'line',
            height: 360,
            zoom:{
                enabled:false
            },
            toolbar : {
                show:true
            },
            dropShadow:{
                enabled:true,
                top:3,
                left:14,
                blur:4,
                opacity:0.10,
            }
        },
        stroke: {
            width: 5,
            curve:'straight'
        },

        xaxis: {
            type:'month',
            categories: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
            title:{
                text:'Year '+$res.data.year,
            }
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'light',
                gradientToColors: ['#A4CD3C'],
                shadeIntensity: 1,
                type: 'horizontal',
                opacityFrom: 1,
                opacityTo: 1,
                stops: [0, 100, 100, 100]
            },
        },
        markers: {
        	size: 4,
        	colors: ["#A4CD3C"],
        	strokeColors: "#fff",
        	strokeWidth: 2,
        	hover: {
        		size: 7,
        	}
        },
        colors: ["#A4CD3C"],
        
        yaxis: {
            title: {
                text: ''
            }
        },
    };

    var chart = new ApexCharts(document.querySelector("#customer_sales_history"), options);
    chart.render();
}

// customer recent invoice orders
