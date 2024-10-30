@extends('layouts.admin') <!-- Extend your admin layout -->

@section('content') <!-- Define the content section -->
<div class="content">
    <!-- Logo Section -->
    <div class="text-center my-4">
        <img src="https://www.d2codelab.com/assets/images/logo.png" alt="Company Logo" class="img-fluid" style="max-width: 200px;">
    </div>
    <!-- End of Logo Section -->

    <div class="row">
        <div class="col-lg-6">
            <!-- Start of Card for Total Admins -->
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Admins</h5>
                    <p class="card-text">{{1}}</p>
                </div>
            </div>
            <!-- End of Card for Total Admins -->
        </div>
        <div class="col-lg-6">
            <!-- Start of Card for Total Users -->
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Users</h5>
                    <p class="card-text">1000</p>
                </div>
            </div>
            <!-- End of Card for Total Users -->
        </div>
        <div class="col-lg-6">
            <!-- Start of Card for Total Products -->
            <div class="card text-white bg-danger mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Products</h5>
                    <p class="card-text">{{ $productCount }}</p>
                </div>
            </div>
            <!-- End of Card for Total Products -->
        </div>
        <div class="col-lg-6">
            <!-- Start of Card for Total Vendors -->
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Vendors</h5>
                    <p class="card-text">{{$vendorCount}}</p>
                </div>
            </div>
            <!-- End of Card for Total Vendors -->
        </div>
    </div>
    <!-- Rest of your content goes here -->
</div>
@endsection <!-- End of content section -->

@section('scripts') <!-- Define the scripts section -->
    @parent <!-- Include any parent scripts if needed -->
    <!-- Additional scripts if needed -->
@endsection <!-- End of scripts section -->
