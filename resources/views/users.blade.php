@extends('layouts/main')

@section('content')
    <div class="row column_title">
        <div class="col-md-12">
            <div class="page_title">
                <h2>Users</h2>
            </div>
        </div>
    </div>
    <div class="row column1 social_media_section">
        <div class="col-lg-12 col-md-12">
            <div class="white_shd full margin_bottom_30">
                <div class="full graph_head">
                    <div class="heading1 margin_0">
                       <h2>Users List</h2>
                    </div>
                </div>
                @if(count($list_users) > 0)
                <div class="table_section padding_infor_info">
                    <div class="table-responsive-sm">
                        <table class="table table-bordered">
                            <thead class="dark_bg text-white">
                                <tr>
                                    <th class="font-weight-bold">#</th>
                                    <th class="font-weight-bold">Name</th>
                                    <th class="font-weight-bold">Equity</th>
                                    <th class="font-weight-bold">Last Login</th>
                                    <th class="font-weight-bold">Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1;?>
                                @foreach ($list_users as $user)
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td>{{$user->name}}</td>
                                        <td>{{$user->equity}}</td>
                                        <td>{{$user->last_login}}</td>
                                        <td>{{$user->created_at}}</td>
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
@endsection