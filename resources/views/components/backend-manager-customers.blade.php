<div>    
    <table id="backend_manager_customers" class="text-center datatable-dark dataTable backend_datatables">
        <thead class="text-capitalize">
            <tr>
                <th width="10%">
                    Customer Number
                    <span data-col='person_number' data-table='managers' data-ordertype='asc' class="asc">&#x2191;</span>
                    <span data-col='person_number' data-table='managers' data-ordertype='desc' class="desc">&#x2193;</span>
                </th>
                <th width="10%">
                    Company Name
                    <span data-col='name' data-table='managers' data-ordertype='asc' class="asc">&#x2191;</span>
                    <span data-col='name' data-table='managers' data-ordertype='desc' class="desc">&#x2193;</span>
                </th>
                <th width="10%">
                    Company Email
                    <span data-col='email' data-table='managers' data-ordertype='asc' class="asc">&#x2191;</span>
                    <span data-col='email' data-table='managers' data-ordertype='desc' class="desc">&#x2193;</span>
                </th>                                   
                <th width="10%">
                    Action
                </th>                                            
            </tr>
        </thead>
        <tbody>
        @foreach ($manager_customers as $customer)
        <tr>
                <td> <a class="" href="">{{ $customer['customerno'] }}</a></td>
                <td>{{ $customer['customername'] }}</td>
                <td>{{ $customer['emailaddress'] }}</td>                                                                
                <td>
                    <div class="status-btns">
                        @if($customer['is_exits'])
                        <a class="btn btn-rounded btn-medium btn-primary text-capitalize d-block" target="_blank" href="{{ route('admin.users.login', ['id' => $customer['user_detail']['user_id'],'user_detail_id' =>$customer['user_detail']['id']]) }}" style="padding:0.5rem !important;">Login As</a>
                        @else 
                        <a class="btn btn-rounded text-capitalize btn-danger bm-btn-danger text-white" target="_blank" href="{{ route('admin.users.login', ['id' => $customer['customerno'],'user_detail_id' => $customer['customerno']]) }}" style="padding:0.5rem !important;">View As</a>
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

