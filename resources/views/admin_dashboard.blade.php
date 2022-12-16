@extends('layouts/main')

@section('content')
    <div class="row column_title">
        <div class="col-md-12">
            <div class="page_title">
                <h2>Admin Dashboard</h2>
            </div>
        </div>
    </div>
    <div class="row column1">
        <div class="col-md-6 col-lg-3">
        <div class="full counter_section margin_bottom_30">
            <div class="couter_icon">
                <div> 
                    <i class="fa fa-users yellow_color"></i>
                </div>
            </div>
            <div class="counter_no">
                <div>
                    <p class="total_no">{{number_format($list_user->count())}}</p>
                    <p class="head_couter">Total User</p>
                </div>
            </div>
        </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="full counter_section margin_bottom_30">
                <div class="couter_icon">
                    <div> 
                        <i class="fa fa-landmark blue1_color"></i>
                    </div>
                </div>
                <div class="counter_no">
                    <div>
                        <p class="total_no">{{$list_tsc->count()}}</p>
                        <p class="head_couter">Transactions</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection