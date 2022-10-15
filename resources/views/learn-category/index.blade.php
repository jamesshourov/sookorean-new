@extends('layouts.app')
@section('title')
    Learn Category List
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
                            <h4 class="mb-sm-0">Learn Category List</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Learn Category List</a></li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- end page title -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
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
                            <div class="card-header align-items-center d-flex">
                                <div class="mb-0 flex-grow-1">
                                    <a href="{{ route('learn-category.add') }}" class="btn btn-success">Add New Category</a>
                                </div>
                                <div class="flex-shrink-0">
                                    <form action="{{ route('learn-category.all') }}" method="get">
                                        <div class="row">
                                            <div class="col">
                                                <div class="search-box ms-2">

                                                    <input type="text" class="form-control search"
                                                           placeholder="Search..."
                                                           name="keyword">
                                                    <i class="ri-search-line search-icon"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="card-body">
                                <!-- Bordered Tables -->
                                <table class="table table-bordered table-nowrap">
                                    <thead>
                                    <tr>
                                        <th scope="col" width="5%">SL</th>
                                        <th scope="col">Image</th>
                                        <th scope="col">Title</th>
                                        <th scope="col" width="15%">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $sl = 0;
                                    @endphp
                                    @foreach($rows as $row)
                                        @php
                                            $sl++;
                                        @endphp
                                        <tr valign="middle">
                                            <th scope="row">
                                                {{ $sl }}
                                            </th>
                                            <td>
                                                <img style="width: 150px" src="{{ asset($row->image) }}"  alt="Image"/>
                                            </td>
                                            <td>
                                                {{ $row->title_english }}
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    @if($row->has_sub_category == 'yes' || empty($row->has_sub_category))
                                                    <div class="edit">
                                                        <a class="btn btn-sm btn-info edit-item-btn"
                                                           href="{{ route('learn-subcategory.all', $row->id) }}">Sub Categories</a>
                                                    </div>
                                                    @endif
                                                    <div class="edit">
                                                        <a class="btn btn-sm btn-success edit-item-btn"
                                                           href="{{ route('learn-category.edit', $row->id) }}">Edit</a>
                                                    </div>
                                                    <div class="remove">
                                                        <a class="btn btn-sm btn-danger remove-item-btn"
                                                           href="{{ route('learn-category.delete', $row->id) }}"
                                                           onclick="return confirm('Are you sure?')">Remove</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-end">
                                    {!! $rows->links() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->

    </div>
    <!-- end main content-->
@endsection

