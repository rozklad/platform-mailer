@extends('layouts/default')

{{-- Page title --}}
@section('title')
@parent
{{{ trans("action.{$mode}") }}} {{ trans('sanatorium/mailer::mailtransactions/common.title') }}
@stop

{{-- Queue assets --}}
{{ Asset::queue('validate', 'platform/js/validate.js', 'jquery') }}

{{ Asset::queue('redactor', 'redactor/js/redactor.js', 'jquery') }}
{{-- Asset::queue('redactor-fullscreen', 'redactor/plugins/fullscreen.js', 'redactor') --}}
{{-- Asset::queue('redactor-source', 'redactor/plugins/source.js', 'redactor') --}}
{{ Asset::queue('redactor', 'redactor/css/redactor.css', 'styles') }}

{{ Asset::queue('codemirror', 'sanatorium/codemirror::codemirror/codemirror.css') }}
{{ Asset::queue('codemirror-monokai-sublime', 'sanatorium/codemirror::codemirror/monokai-sublime.css') }}
{{ Asset::queue('codemirror', 'sanatorium/codemirror::codemirror/codemirror.js', 'jquery') }}
{{ Asset::queue('codemirror-xml', 'sanatorium/codemirror::codemirror/mode/xml/xml.js', 'jquery') }}
{{ Asset::queue('codemirror-javascript', 'sanatorium/codemirror::codemirror/mode/javascript/javascript.js', 'jquery') }}
{{ Asset::queue('codemirror-vbscript', 'sanatorium/codemirror::codemirror/mode/vbscript/vbscript.js', 'jquery') }}
{{ Asset::queue('codemirror-css', 'sanatorium/codemirror::codemirror/mode/css/css.js', 'jquery') }}
{{ Asset::queue('codemirror-htmlmixed', 'sanatorium/codemirror::codemirror/mode/htmlmixed/htmlmixed.js', 'jquery') }}

{{-- Inline scripts --}}
@section('scripts')
@parent
<script type="text/javascript">

$(function(){

	// Define an extended mixed-mode that understands vbscript and
    // leaves mustache/handlebars embedded templates in html mode
    var mixedMode = {
      	name: "htmlmixed",
      	scriptTypes: [{matches: /\/x-handlebars-template|\/x-mustache/i,
      		mode: null},
      		{matches: /(text|application)\/(x-)?vb(a|script)/i,
      			mode: "vbscript"}]
      		};

	var editor = CodeMirror.fromTextArea(document.getElementById("template"), {
		lineNumbers: 	true,
		matchBrackets: 	true,
		theme: 			'monokai-sublime',
		indentUnit: 	4,
        indentWithTabs: true,
        mode: 			mixedMode,
	});

	// Init redactor
	$('#redactor-air').redactor({
		air: true,
		clickToEdit: true,
		clickToCancel: '#btn-cancel',
		clickToSave: '#btn-save',
		buttons: ["format","bold","italic","lists","link","file","horizontalrule"],
		//plugins: ["source","fullscreen"],
		callbacks: {
			save: function(html)
			{
				editor.getDoc().setValue(html);
			},
			cancel: function(html)
			{
				editor.getDoc().setValue(html);
			}
		}        
	});
});

	
</script>
@stop

{{-- Inline styles --}}
@section('styles')
@parent
@stop

{{-- Page content --}}
@section('page')

<section class="panel panel-default panel-tabs">

	{{-- Form --}}
	<form id="mailer-form" action="{{ request()->fullUrl() }}" role="form" method="post" data-parsley-validate>

		{{-- Form: CSRF Token --}}
		<input type="hidden" name="_token" value="{{ csrf_token() }}">

		<header class="panel-heading">

			<nav class="navbar navbar-default navbar-actions">

				<div class="container-fluid">

					<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#actions">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>

						<a class="btn btn-navbar-cancel navbar-btn pull-left tip" href="{{ route('admin.sanatorium.mailer.mailtransactions.all') }}" data-toggle="tooltip" data-original-title="{{{ trans('action.cancel') }}}">
							<i class="fa fa-reply"></i> <span class="visible-xs-inline">{{{ trans('action.cancel') }}}</span>
						</a>

						<span class="navbar-brand">{{{ trans("action.{$mode}") }}} <small>{{{ $mailtransaction->exists ? $mailtransaction->id : null }}}</small></span>
					</div>

					{{-- Form: Actions --}}
					<div class="collapse navbar-collapse" id="actions">

						<ul class="nav navbar-nav navbar-right">

							@if ($mailtransaction->exists)
							<li>
								<a href="{{ route('admin.sanatorium.mailer.mailtransactions.delete', $mailtransaction->id) }}" class="tip" data-action-delete data-toggle="tooltip" data-original-title="{{{ trans('action.delete') }}}" type="delete">
									<i class="fa fa-trash-o"></i> <span class="visible-xs-inline">{{{ trans('action.delete') }}}</span>
								</a>
							</li>
							@endif

							<li>
								<button class="btn btn-primary navbar-btn" data-toggle="tooltip" data-original-title="{{{ trans('action.save') }}}">
									<i class="fa fa-save"></i> <span class="visible-xs-inline">{{{ trans('action.save') }}}</span>
								</button>
							</li>

						</ul>

					</div>

				</div>

			</nav>

		</header>

		<div class="panel-body">

			<div role="tabpanel">

				{{-- Form: Tabs --}}
				<ul class="nav nav-tabs" role="tablist">
					<li class="active" role="presentation"><a href="#general-tab" aria-controls="general-tab" role="tab" data-toggle="tab">{{{ trans('sanatorium/mailer::mailtransactions/common.tabs.general') }}}</a></li>
					<li role="presentation"><a href="#attributes" aria-controls="attributes" role="tab" data-toggle="tab">{{{ trans('sanatorium/mailer::mailtransactions/common.tabs.attributes') }}}</a></li>
				</ul>

				<div class="tab-content">

					{{-- Tab: General --}}
					<div role="tabpanel" class="tab-pane fade in active" id="general-tab">

						<fieldset>

							<div class="row">

								<div class="form-group{{ Alert::onForm('event', ' has-error') }}">

									<label for="event" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/mailer::mailtransactions/model.general.event_help') }}}"></i>
										{{{ trans('sanatorium/mailer::mailtransactions/model.general.event') }}}
									</label>

									<input type="text" class="form-control" name="event" id="event" placeholder="{{{ trans('sanatorium/mailer::mailtransactions/model.general.event') }}}" value="{{{ input()->old('event', $mailtransaction->event) }}}">

									<span class="help-block">{{{ Alert::onForm('event') }}}</span>

								</div>

								<div class="form-group{{ Alert::onForm('subject', ' has-error') }}">

									<label for="subject" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/mailer::mailtransactions/model.general.subject_help') }}}"></i>
										{{{ trans('sanatorium/mailer::mailtransactions/model.general.subject') }}}
									</label>

									<textarea class="form-control" name="subject" id="subject" placeholder="{{{ trans('sanatorium/mailer::mailtransactions/model.general.subject') }}}">{{{ input()->old('subject', $mailtransaction->subject) }}}</textarea>

									<span class="help-block">{{{ Alert::onForm('subject') }}}</span>

								</div>

								

								<div class="form-group{{ Alert::onForm('template', ' has-error') }}">

									<label for="template" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/mailer::mailtransactions/model.general.template_help') }}}"></i>
										{{{ trans('sanatorium/mailer::mailtransactions/model.general.template') }}}
									</label>
									
									<div class="row">
										<div class="col-sm-6">
											<div id="mail-preview" style="background:#fff;max-width:660px;overflow-x:auto;">
												<div id="redactor-air">@include('sanatorium/mailer::mailtransactions/partials/air')</div>
											</div>

											<p>
												<button id="btn-save" class="btn btn-default" style="display: none;" type="primary" outline>{{ trans('action.save') }}</button>
												<button id="btn-cancel" class="btn btn-default" style="display: none;" outline>{{ trans('action.cancel') }}</button>
											</p>
										</div>
										<div class="col-sm-6">
											<textarea class="form-control" name="template" id="template" placeholder="{{{ trans('sanatorium/mailer::mailtransactions/model.general.template') }}}" rows="40">{!! input()->old('template', $mailtransaction->template) !!}</textarea>

											<span class="help-block">{{{ Alert::onForm('template') }}}</span>
										</div>
									</div>

								</div>

								<div class="form-group{{ Alert::onForm('receivers', ' has-error') }}">

									<label for="receivers" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/mailer::mailtransactions/model.general.receivers_help') }}}"></i>
										{{{ trans('sanatorium/mailer::mailtransactions/model.general.receivers') }}}
									</label>

									<textarea class="form-control" name="receivers" id="receivers" placeholder="{{{ trans('sanatorium/mailer::mailtransactions/model.general.receivers') }}}" rows="4">{{{ input()->old('receivers', $mailtransaction->receivers) }}}</textarea>

									<span class="help-block">{{{ Alert::onForm('receivers') }}}</span>

								</div>

							</div>

						</fieldset>

					</div>

					{{-- Tab: Attributes --}}
					<div role="tabpanel" class="tab-pane fade" id="attributes">
						@attributes($mailtransaction)
					</div>

				</div>

			</div>

		</div>

	</form>

</section>
@stop
