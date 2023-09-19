@extends('layout')

@section('content')
{{--  <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 style="text-align: center">Order Details</h4>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    You are Logged In
                </div>
            </div>
        </div>
    </div>
</div>  --}}

<div class="container">
    <div class="row">
        <div class="col-12 my-5">
            <h3>Products Lists</h3>
        </div>
        <div class="col-12">
            <table id="datatable" class="table" style="width:100%">
                <thead class="table-dark">
                    <tr>
                        <td>Order ID</td>
                        <td>Order Date</td>
                        <td>Customer Name & Contact</td>
                        <td>Supplier Name</td>
                        <td>Category Name</td>
                        <td>Product Name</td>
                        <td>Employee name</td>
                        <td>Total quantity</td>
                        <td>Total amount</td>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
    integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous">
</script>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>


<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).ready(function() {
        $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('get-product') }}",
                type: "POST",

                data: function (data) {
                    data.search = $('input[type="search"]').val();
                }
            },
           // order: ['1', 'DESC'],
            pageLength: 20,
            searching: true,
            columns: [
                {data: 'OrderID', name:'OrderID'},
                {data: 'OrderDate', name:'OrderDate'},
                {data: 'CustomerNameContact', name:'CustomerNameContact'},
                //{data: 'ContactName', name:'ContactName'},
                {data: 'SupplierName', name:'SupplierName'},
                {data: 'CategoryName', name:'CategoryName'},
                {data: 'ProductName', name:'ProductName'},
                {data: 'FullName', name:'FullName'},
                {data: 'total_quantity', name:'total_quantity'},
                {data: 'total_amount', name:'total_amount'},
                //{data: 'id', width: "20%",}
            ]
        });
    });


</script>
@endsection
