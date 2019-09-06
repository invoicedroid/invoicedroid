@extends('layouts.install')

@section('content')
    <h4 class="id-text-center">{{ trans('install.steps.requirements') }}</h4>
    @foreach( $requirements as $requirement )
    <el-alert
        title="{{$requirement}}"
        class="id-margin"
        type="error"
        description=""
        :closable="false"
        show-icon>
    </el-alert>
    @endforeach
@endsection
