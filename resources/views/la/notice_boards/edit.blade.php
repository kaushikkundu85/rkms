@extends("la.layouts.app")

@section("contentheader_title")
    <a @ajaxload href="{{ url(config('laraadmin.adminRoute') . '/notice_boards') }}">@lang('la_notice_board.notice_boards')</a> :
@endsection
@section("contentheader_description", $notice_board->$view_col)
@section("section", app('translator')->get('la_notice_board.notice_boards'))
@section("section_url", url(config('laraadmin.adminRoute') . '/notice_boards'))
@section("sub_section", app('translator')->get('common.edit'))

@section("htmlheader_title", app('translator')->get('la_notice_board.notice_board_edit')." : ".$notice_board->$view_col)

@section("main-content")

@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="box">
    <div class="box-header">
        
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                {!! Form::model($notice_board, ['route' => [config('laraadmin.adminRoute') . '.notice_boards.update', $notice_board->id ], 'method'=>'PUT', 'id' => 'notice_board-edit-form']) !!}
                    @la_form($module)
                    
                    {{--
                    @la_input($module, 'notice_title')
					@la_input($module, 'notice_date')
					@la_input($module, 'notice_details')
					@la_input($module, 'pdf_notice')
					@la_input($module, 'custom_url')
					@la_input($module, 'news_flash')
					@la_input($module, 'exam_notice')
					@la_input($module, 'status')
					@la_input($module, 'new_news')
                    --}}
                    <br>
                    <div class="form-group">
                        {!! Form::button( app('translator')->get('common.update'), ['class'=>'btn btn-success', 'type'=>'submit']) !!} <a @ajaxload href="{{ url(config('laraadmin.adminRoute') . '/notice_boards') }}" class="btn btn-default pull-right">@lang('common.cancel')</a>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
var submitBtn = null;
var formObj = null;

$(function () {
    @la_access("Notice_boards", "edit")
    // Edit NoticeBoard REST Request
    submitBtn = $('#notice_board-edit-form button[type=submit]');
    formObj = $("#notice_board-edit-form");

    formObj.validate({
        submitHandler: function(form, event) {
            event.preventDefault();
            $.ajax({
                url: formObj.attr('action'),
                method: 'PUT',
                contentType: 'json',
                headers: { 'X-CSRF-Token': '{{ csrf_token() }}' },
                data: getFormDataJSON(formObj),
                beforeSend: function() {
                    submitBtn.html('<i class="fa fa-refresh fa-spin mr5"></i> Updating...');
                    submitBtn.prop('disabled', true);
                },
                success: function( data ) {
                    if(data.status == "success") {
                        show_success("NoticeBoard Update", data);
                    } else {
                        show_failure("NoticeBoard Update", data);
                    }
                    submitBtn.html('Update');
                    submitBtn.prop('disabled', false);
                    if(isset(data.redirect)) {
                        window.location.href = data.redirect;
                    }
                },
                error: function( data ) {
                    show_failure("NoticeBoard Update", data);
                    submitBtn.html('Update');
                    submitBtn.prop('disabled', false);
                    if(isset(data.redirect)) {
                        window.location.href = data.redirect;
                    }
                }
            });
            return false;
        }
    });
    @endla_access
});
</script>
@endpush
