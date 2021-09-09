@extends('admin.layouts.master')
@section('title') 
    {{ __('cms.index.title') }} 
@endsection

@push('css')
    
@endpush

@section('content')

    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">

                <div class="card breadcrumb-card">
                    <div class="row justify-content-between align-content-between" style="height: 100%;">
                        <div class="col-md-6">

                            <h3 class="page-title">{{ __('cms.index.title') }} </h3>

                            <ul class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active-breadcrumb">
                                    <a href="{{ route('cms.index') }}">{{ __('cms.index.title') }}</a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-3">
                            @can('cms-create')
                                <div class="create-btn pull-right">
                                    <a href="{{ route('cms.create') }}" class="btn custom-create-btn">{{ __('cms.form.add-button') }}</a>
                                </div>
                            @endcan
                        </div>
                    </div>
                </div><!-- /card finish -->

            </div>
        </div>
    </div><!-- /Page Header -->

    <div class="row">
        <div class="col-sm-12">

            <div class="card">
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="datatable table table-hover table-center mb-0 custom-table">
                            <thead>
                                <tr>
                                    <th>{{ __('default.table.sl') }}</th>
                                    <th>{{ __('default.table.title') }}</th>
                                    <th>{{ __('default.table.category') }}</th>
                                    <th>{{ __('default.table.status') }}</th>
                                    @if(auth()->user()->can('cms-edit') || auth()->user()->can('cms-delete'))
                                        <th>{{ __('default.table.action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cms as $cms)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $cms->title }}</td>
                                        <td>{{ $cms->cmscategory->name }}</td>

                                        <td>
                                            <input type="checkbox" class="cms-status" data-id="{{ $cms->id }}" {{ $cms->status == 1 ? 'checked' : '' }} data-toggle="toggle" data-on="Active" data-off="De-active" data-onstyle="success" data-offstyle="danger" data-style="slow">
                                        </td>

                                        @if(auth()->user()->can('cms-edit') || auth()->user()->can('cms-delete'))
                                            <td>
                                                @can('cms-edit')
                                                    <a href="{{ route('cms.edit', $cms->id) }}" class="custom-edit-btn mr-1">
                                                        <i data-feather="edit"></i>{{ __('default.table.edit') }}
                                                    </a> 
                                                @endcan

                                                @can('cms-delete')
                                                    <a href="{{ route('cms.destroy', $cms->id) }}" class="custom-delete-btn delete-cms">
                                                        <i data-feather="trash"></i>{{ __('default.table.delete') }}
                                                    </a>
                                                @endcan
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div> <!-- end card body -->
            </div> <!-- end card -->

        </div>			
    </div>
@endsection

@push('scripts')
<script>
$(function() {
    $('.cms-status').change(function() {
        var status = $(this).prop('checked') == true ? 1 : 0; 
        var cms_id = $(this).data('id');  
        $.ajax({
            type: "GET",
            dataType: "json",
            url: '{{ route('cms.status_update') }}',
            data: {'status': status, 'cms_id': cms_id},
            success: function(data){
                if(status == 1){
                    toastr.success(data.message);
                }else{
                    toastr.error(data.message);
                } 
            }
        });
    })
  })
</script>

<script type="text/javascript">
    $('.delete-cms').on('click', function (event) {
        event.preventDefault();
        const url = $(this).attr('href');
        swal({
            title: 'Are you sure?',
            text: 'This record and it`s details will be permanantly deleted!',
            icon: 'warning',
            dangerMode: true,
            buttons: true,

        }).then(function(value) {
            if (value) {
                window.location.href = url;
            }
        });
    });
</script>
@endpush