@extends('layouts/main')

@section('content')
    <div class="row column_title">
        <div class="col-md-12">
            <div class="page_title">
                <h2>Transactions</h2>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4"></div>
        <div class="col-lg-4 text-right">
        </div>
        <div class="col-lg-4 text-right">
            <button onclick="confirmDelete()" class="btn btn-danger">Clear Transaction</button>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#transactionModal">
                Add New Transaction
            </button>
            <div class="modal fade" id="transactionModal" tabindex="-1" role="dialog" aria-labelledby="transactionModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="transactionModalLabel">Add New add_transaction</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" action="{{url('/add_transaction')}}">
                        <div class="modal-body">
                            @csrf
                            <div class="form-group row">
                                <label for="tsc_type" class="col-sm-4 col-form-label font-weight-bold">Transaction Type</label>
                                <div class="col-sm-8 col-form-label">
                                    <div class="row pl-3">
                                        <div class="form-group">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="tsc_type" id="tsc_type" value="in" checked>
                                                <label class="form-check-label" for="tsc_type">In</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="tsc_type" id="tsc_type" value="out">
                                                <label class="form-check-label" for="tsc_type">Out</label>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                            <div class="form-group row">
                                <label for="tsc_category" class="col-sm-4 col-form-label font-weight-bold">Transaction Category</label>
                                <div class="col-sm-8 col-form-label text-left">
                                    <select class="form-control" name="tsc_category" id="tsc_category">
                                        <option value="Expenses">Expenses</option>
                                        {{-- <option value="Equity">Equity</option> --}}
                                        {{-- <option value="Assets">Assets</option> --}}
                                        <option value="Liability">Liability (Bank's Loan)</option>
                                        <option value="Income">Income</option>
                                    </select>
                                    {{-- <input type="text" class="form-control" id="tsc_category" name="tsc_category" required placeholder="Transaction Category"> --}}
                                    {{-- <small>eg : Hospital Expenses, Rent Car, etc.</small> --}}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="tsc_detail1" class="col-sm-4 col-form-label font-weight-bold">Transaction Title</label>
                                <div class="col-sm-8 col-form-label text-left">
                                    <input type="text" class="form-control" id="tsc_title" name="tsc_title" required placeholder="Transaction Title">
                                    <small>eg : Hospital Expenses, Rent Car, etc.</small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="tsc_detail1" class="col-sm-4 col-form-label font-weight-bold">Transaction Detail 2</label>
                                <div class="col-sm-8 col-form-label text-left">
                                    <input type="text" class="form-control" id="tsc_detail1" name="tsc_detail1" required placeholder="Transaction Note">
                                    <small>Little description about this transaction</small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="tsc_detail1" class="col-sm-4 col-form-label font-weight-bold">Transaction Detail 3</label>
                                <div class="col-sm-8 col-form-label text-left">
                                    <input type="text" class="form-control" id="tsc_detail2" name="tsc_detail2" required placeholder="Transaction Types">
                                    <small>eg : Cash, Expenses, Bank Loan, Income, etc</small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="tsc_amount" class="col-sm-4 col-form-label font-weight-bold">Transaction Amount</label>
                                <div class="col-sm-8 col-form-label">
                                    <input type="text" class="form-control" id="tsc_amount" name="tsc_amount" required placeholder="Transaction Amount">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="tsc_target" class="col-sm-4 col-form-label font-weight-bold">Transaction Target</label>
                                <div class="col-sm-8 col-form-label">
                                    <input type="text" class="form-control" id="tsc_target" name="tsc_target" required placeholder="Transaction Target">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row column1 social_media_section">
        <div class="col-lg-12 col-md-12">
            <div class="white_shd full margin_bottom_30">
                <div class="full graph_head">
                    <div class="heading1 margin_0">
                       <h2>Transactions List</h2>
                    </div>
                </div>
                @if(count($list_tsc) > 0)
                <div class="table_section padding_infor_info">
                    <div class="table-responsive-sm">
                        <table class="table table-bordered">
                            <thead class="dark_bg text-white">
                                <tr>
                                    <th class="font-weight-bold">#</th>
                                    <th class="font-weight-bold">Transaction Type</th>
                                    <th class="font-weight-bold">Transaction Details</th>
                                    <th class="font-weight-bold">Money In</th>
                                    <th class="font-weight-bold">Money Out</th>
                                    <th class="font-weight-bold">Target</th>
                                    <th class="font-weight-bold">Balance Before</th>
                                    <th class="font-weight-bold">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1;?>
                                @foreach ($list_tsc as $tsc)
                                    <tr class="@if($tsc->tsc_type == 'in') green_bg @else red_bg @endif text-white ">
                                        <td>{{$i}}</td>
                                        <td>{{$tsc->tsc_type}}</td>
                                        <td>{{$tsc->tsc_detail}}</td>
                                        <td>$ @if($tsc->tsc_type == 'in') {{number_format($tsc->tsc_amount)}} @else 0 @endif</td>
                                        <td>$ @if($tsc->tsc_type == 'out') {{number_format($tsc->tsc_amount)}} @else 0 @endif</td>
                                        <td>{{$tsc->tsc_target}}</td>
                                        <td>$ {{number_format($tsc->balance)}}</td>
                                        <td>
                                            <button class="btn btn-warning" onclick="getModalInfo({{$tsc->id}})" data-toggle="modal" data-target="#editModal">Edit</button>
                                            <a class="btn btn-danger" href="{{url('delete/'.$tsc->id)}}">Delete</a>
                                        </td>
                                    </tr>
                                    <?php $i++; ?>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editTransactionModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editTransactionModalLabel">Edit Transaction</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form method="POST" action="{{url('/submit_edit_transaction')}}">
                            <div class="modal-body">
                                @csrf
                                <input type="hidden" name="tsc_id" id="tsc_id">
                                <div class="form-group row">
                                    <label for="tsc_type_ed" class="col-sm-4 col-form-label font-weight-bold">Transaction Category</label>
                                    <div class="col-sm-8 col-form-label text-left">
                                        <select class="form-control" name="tsc_type_ed" id="tsc_type_ed">
                                            <option value="in">In</option>
                                            {{-- <option value="Equity">Equity</option> --}}
                                            {{-- <option value="Assets">Assets</option> --}}
                                            <option value="out">Out</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="tsc_category_ed" class="col-sm-4 col-form-label font-weight-bold">Transaction Category</label>
                                    <div class="col-sm-8 col-form-label text-left">
                                        <select class="form-control" name="tsc_category_ed" id="tsc_category_ed">
                                            <option value="Expenses">Expenses</option>
                                            {{-- <option value="Equity">Equity</option> --}}
                                            {{-- <option value="Assets">Assets</option> --}}
                                            <option value="Liability">Liability (Bank's Loan)</option>
                                            <option value="Income">Income</option>
                                        </select>
                                        {{-- <input type="text" class="form-control" id="tsc_category" name="tsc_category" required placeholder="Transaction Category"> --}}
                                        {{-- <small>eg : Hospital Expenses, Rent Car, etc.</small> --}}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="tsc_title_ed" class="col-sm-4 col-form-label font-weight-bold">Transaction Title</label>
                                    <div class="col-sm-8 col-form-label text-left">
                                        <input type="text" class="form-control" id="tsc_title_ed" name="tsc_title_ed" required placeholder="Transaction Title">
                                        <small>eg : Hospital Expenses, Rent Car, etc.</small>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="tsc_detail1_ed" class="col-sm-4 col-form-label font-weight-bold">Transaction Detail 2</label>
                                    <div class="col-sm-8 col-form-label text-left">
                                        <input type="text" class="form-control" id="tsc_detail1_ed" name="tsc_detail1_ed" required placeholder="Transaction Note">
                                        <small>Little description about this transaction</small>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="tsc_detail2_ed" class="col-sm-4 col-form-label font-weight-bold">Transaction Detail 3</label>
                                    <div class="col-sm-8 col-form-label text-left">
                                        <input type="text" class="form-control" id="tsc_detail2_ed" name="tsc_detail2_ed" required placeholder="Transaction Types">
                                        <small>eg : Cash, Expenses, Bank Loan, Income, etc</small>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="tsc_amount_ed" class="col-sm-4 col-form-label font-weight-bold">Transaction Amount</label>
                                    <div class="col-sm-8 col-form-label">
                                        <input type="text" class="form-control" id="tsc_amount_ed" name="tsc_amount_ed" required placeholder="Transaction Amount">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="tsc_target_ed" class="col-sm-4 col-form-label font-weight-bold">Transaction Target</label>
                                    <div class="col-sm-8 col-form-label">
                                        <input type="text" class="form-control" id="tsc_target_ed" name="tsc_target_ed" required placeholder="Transaction Target">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
                @else
                    <div class="row">
                        <div class="col-lg-12">
                            <h3 class="text-center">There are no transaction. Please make a new one</h3>
                        </div>
                    </div>
                @endif
             </div>
        </div>
    </div>
    <div class="row column1">
        <div class="col-md-6">
            <div class="white_shd full margin_bottom_30">
               <div class="full graph_head">
                  <div class="heading1 margin_0">
                     <h2>Assets List</h2>
                  </div>
               </div>
               @if(count($list_tsc) > 0)
               <div class="table_section padding_infor_info">
                  <div class="table-responsive-sm">
                     <table class="table table-hover">
                        <thead class="thead-dark">
                            <tr>
                            <th>#</th>
                            <th>City</th>
                            <th>Land</th>
                            <th>Building</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $cnt = 1;?>
                            @foreach ($list_asset as $asset)
                                <tr>
                                    <td>{{$cnt}}</td>
                                    <td>{{$asset->asset_loc}}</td>
                                    <td>$ {{$asset->asset_land_price}}</td>
                                    <td>$ {{$asset->asset_building_price}}</td>
                                </tr>
                                <?php $cnt++; ?>
                            @endforeach
                        </tbody>
                     </table>
                  </div>
               </div>
               @else
                <div class="row">
                    <div class="col-lg-12">
                        <h3 class="text-center">There are no assets. Please make a new one</h3>
                    </div>
                </div>
               @endif
            </div>
         </div>
    </div>
    <script>
        function confirmDelete(){
            var ask = confirm("Are you sure to clear ?");
            if(ask){
                window.location = "{{url('clear_transaction')}}";
            }
        }
        function getModalInfo(tsc_id){
            var tsc_id = tsc_id;
            console.log(tsc_id);
            $.ajax({
            url: `/tsc_info/${tsc_id}`,
            type: "GET",
            cache: false,
                success:function(response){
                    console.log(response);
                    //fill data to form
                    $('#tsc_id').val(response.id);
                    $('#tsc_type_ed').val(response.tsc_type);
                    $('#tsc_category_ed').val(response.tsc_category);
                    $('#tsc_title_ed').val(response.tsc_detail);
                    $('#tsc_detail1_ed').val(response.tsc_detail1);
                    $('#tsc_detail2_ed').val(response.tsc_detail2);
                    $('#tsc_amount_ed').val(response.tsc_amount);
                    $('#tsc_target_ed').val(response.tsc_target);

                    //open modal
                    $('#modal-edit').modal('show');
                }
            });
        }
    </script>
@endsection