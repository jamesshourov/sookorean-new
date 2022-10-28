@extends('layouts.app')
@section('title')
    Ticket Details
@endsection
@section('content')
    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Ticket Details</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Ticket Details</a></li>
                                    <li class="breadcrumb-item active">Ticket Details</li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                @if (session('error'))
                                    <div class="alert alert-danger w-100" role="alert">
                                        {{ session('error') }}
                                    </div>
                                @endif
                                @if (session('success'))
                                    <div class="alert alert-success w-100" role="alert">
                                        {{ session('success') }}
                                    </div>
                                @endif
                                <h4 class="card-title mb-0">Ticket Details</h4>
                            </div>
                            <!-- end card header -->
                            <div class="card-body">
                                <form action="{{ route('ticket.update') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $row->id }}">
                                    <p>
                                        <strong>Sender Name : </strong> {{ $row->sender_name }}
                                    </p>
                                    <p>
                                        <strong>Sender Email : </strong> {{ $row->sender_email }}
                                    </p>
                                    <p>
                                        <strong>Receiver Email : </strong> {{ $row->receiver_email }}
                                    </p>
                                    <p>
                                        <strong>Description : </strong> {{ $row->description }}
                                    </p>
                                    <div class="col-md-12">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-select form-control" required>
                                            <option value="opened" @selected($row->status == 'opened')>Opened</option>
                                            <option value="replied" @selected($row->status == 'replied')>Replied</option>
                                            <option value="closed" @selected($row->status == 'closed')>Closed</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6" style="margin-top:20px;">
                                        <button type="submit" class="btn btn-success">Update</button>
                                    </div>
                                    <!--end row-->
                                </form>
                            </div>
                        </div>
                    </div>
                    <!--end col-->
                </div>
                <!--end row-->

            </div> <!-- container-fluid -->
        </div>
        <!-- End Page-content -->

    </div>
    <!-- end main content-->
@endsection
