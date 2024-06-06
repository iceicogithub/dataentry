@extends('admin.layout.main')
@section('content')
    <div class="breadcrumbs">
        <div class="col-sm-8">
            <div class="page-header float-left">
                <div class="page-title">
                    <h1>Legislation Name: {{ $act->legislation_name }}</h1>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="page-header float-right">
                <div class="page-title">
                    <ol class="breadcrumb text-right">
                        <a href="/edit-main-act/{{ $act->act_id }}"><button class="btn btn-danger">Back</button></a>
                    </ol>

                </div>
            </div>
        </div>

        <div class="col-sm-12">

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->has('error'))
                <div class="alert alert-danger">
                    {{ $errors->first('error') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-success">
                    {{ session('error') }}
                </div>
            @endif
          
            <div class="card p-5">
                @if($otherMainAct)
                <form id="form" action="/update_others_main_act/{{$otherMainAct->other_act_id}}" method="post"
                enctype="multipart/form-data" class="form form-horizontal">
                @else
                <form id="form" action="/create_others_main_act" method="post"
                    enctype="multipart/form-data" class="form form-horizontal">
                @endif    
                    @csrf
                    <div class="row">
                        <input type="hidden" value="{{$act->act_id}}" name="act_id">
                        <div class="col-md-12">
                            <div class="form-group form-default">
                                <label class="float-label">Introduction<span class="text-danger">*</span></label>
                                <textarea name="introduction" class="form-control mb-3" placeholder="Enter Act Description" id="introduction" cols="30" rows="3">{{ $otherMainAct ? $otherMainAct->introduction : '' }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group form-default">
                                <label class="float-label">Effective Date<span class="text-danger">*</span></label>
                                <input type="date" name="effective_date" value="{{ $otherMainAct ? $otherMainAct->effective_date : '' }}" class="form-control mb-3">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group form-default">
                                <label class="float-label">Object/Reasons<span class="text-danger">*</span></label>
                                <textarea name="object_reasons" class="form-control mb-3" placeholder="Enter Act Description" id="object_reasons" cols="30" rows="3">{{ $otherMainAct ? $otherMainAct->object_reasons : '' }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group form-default">
                                <label class="float-label">Legislative History<span class="text-danger">*</span></label>
                                <textarea name="legislative_history" class="form-control mb-3" placeholder="Enter Act Description" id="legislative_history" cols="30" rows="3">{{ $otherMainAct ? $otherMainAct->legislative_history : '' }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group form-default">
                                <label class="float-label">Financial Implication<span class="text-danger">*</span></label>
                                <textarea name="financial_implication" class="form-control mb-3" placeholder="Enter Act Description" id="financial_implication" cols="30" rows="3">{{ $otherMainAct ? $otherMainAct->financial_implication : '' }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12 text-right mt-2">
                            <div class="form-group">
                                <button type="submit" class="btn btn-success">Update Data</button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
@section('script')
<script>
      $(document).ready(function() {
        CKEDITOR.replace('financial_implication', {
            toolbar: 'Full'
        });
        CKEDITOR.replace('legislative_history', {
            toolbar: 'Full'
        });
        CKEDITOR.replace('object_reasons', {
            toolbar: 'Full'
        });
        CKEDITOR.replace('introduction', {
            toolbar: 'Full'
        });
    });
</script>
@endsection