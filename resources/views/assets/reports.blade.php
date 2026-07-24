@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12 mb-3">
        <h5><i class="fas fa-file-alt"></i> Asset Reports</h5>
    </div>

    <!-- Report Cards -->
    <div class="col-md-3 mb-3">
        <a href="{{ route('assets.report.all') }}" class="text-decoration-none">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">All Assets</h5>
                    <h2 class="card-text">{{ $allAssets }}</h2>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-3 mb-3">
        <a href="{{ route('assets.report.issued') }}" class="text-decoration-none">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Issued Assets</h5>
                    <h2 class="card-text">{{ $issuedAssets }}</h2>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-3 mb-3">
        <a href="{{ route('assets.report.returned') }}" class="text-decoration-none">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Returned Assets</h5>
                    <h2 class="card-text">{{ $returnedAssets }}</h2>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-3 mb-3">
        <a href="{{ route('assets.report.pending') }}" class="text-decoration-none">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <h5 class="card-title">Pending Returns</h5>
                    <h2 class="card-text">{{ $pendingReturns }}</h2>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Quick Links -->
<div class="card mt-3">
    <div class="card-header">
        <h5>Quick Report Links</h5>
    </div>
    <div class="card-body">
        <a href="{{ route('assets.report.all') }}" class="btn btn-primary me-2">
            <i class="fas fa-list"></i> All Assets
        </a>
        <a href="{{ route('assets.report.issued') }}" class="btn btn-warning me-2">
            <i class="fas fa-clock"></i> Issued Assets
        </a>
        <a href="{{ route('assets.report.returned') }}" class="btn btn-success me-2">
            <i class="fas fa-check"></i> Returned Assets
        </a>
        <a href="{{ route('assets.report.pending') }}" class="btn btn-danger">
            <i class="fas fa-exclamation-triangle"></i> Pending Returns
        </a>
    </div>
</div>
@endsection