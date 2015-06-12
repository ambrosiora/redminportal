@extends('redminportal::layouts.master')

@section('content')
    @if($errors->has())
    <div class='alert alert-danger'>
        We encountered the following errors:
        <ul>
            @foreach($errors->all() as $message)
            <li>{{ $message }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{ Form::open(array('files' => TRUE, 'action' => 'Redooor\Redminportal\MediaController@postStore', 'role' => 'form')) }}
        {{ Form::hidden('id', $media->id)}}

    	<div class='row'>
            <div class="col-md-3 col-md-push-9">
                <div class='form-actions text-right'>
                    {{ HTML::link('admin/medias', 'Cancel', array('class' => 'btn btn-default'))}}
                    {{ Form::submit('Save Changes', array('class' => 'btn btn-primary')) }}
                </div>
                <hr>
                <div class='well well-small'>
                    <div class="form-group">
                        <div class="checkbox">
                            <label for="featured-checker">
                                {{ Form::checkbox('featured', $media->featured, $media->featured, array('id' => 'featured-checker')) }} Featured
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="checkbox">
                            <label for="active-checker">
                                {{ Form::checkbox('active', $media->active, $media->active, array('id' => 'active-checker')) }} Active
                            </label>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="panel-title">Category</div>
                    </div>
                    <div class="panel-body">
                        {{ Form::hidden('category_id', $media->category_id, array('id' => 'category_id'))}}
                        <ul class="redooor-hierarchy">
                        @foreach ($categories as $item)
                            <li>{{ $item->printCategory() }}</li>
                        @endforeach
                        </ul>
                    </div>
                </div>
                <div class="form-group">
                    {{ Form::label('sku', 'SKU') }}
                    {{ Form::text('sku', $media->sku, array('class' => 'form-control')) }}
                </div>
                <div class="form-group">
                    {{ Form::label('tags', 'Tags (separated by comma)') }}
                    {{ Form::text('tags', $tagString, array('class' => 'form-control')) }}
                </div>
                <div>
                    <div class="fileupload fileupload-new" data-provides="fileupload">
                      <div class="fileupload-preview thumbnail" style="width: 200px; height: 150px;"></div>
                      <div>
                        <span class="btn btn-default btn-file"><span class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span>{{ Form::file('image') }}</span>
                        <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload">Remove</a>
                      </div>
                    </div>
                </div>
            </div>

            <div class="col-md-9 col-md-pull-3">
                <ul class="nav nav-tabs" id="lang-selector">
                    @foreach(\Config::get('redminportal::translation') as $translation)
                    <li><a href="#lang-{{ $translation['lang'] }}">{{ $translation['name'] }}</a></li>
                    @endforeach
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="lang-en">
                        <div class="form-group">
                            {{ Form::label('name', 'Title') }}
                            {{ Form::text('name', $media->name, array('class' => 'form-control')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('short_description', 'Summary') }}
                            {{ Form::text('short_description', $media->short_description, array('class' => 'form-control')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('long_description', 'Description') }}
                            {{ Form::textarea('long_description', $media->long_description, array('class' => 'form-control')) }}
                        </div>
                    </div>
                    @foreach(\Config::get('redminportal::translation') as $translation)
                        @if($translation['lang'] != 'en')
                        <div class="tab-pane" id="lang-{{ $translation['lang'] }}">
                            <div class="form-group">
                                {{ Form::label($translation['lang'] . '_name', 'Title') }}
                                @if ($translated)
                                {{ Form::text($translation['lang'] . '_name', (array_key_exists($translation['lang'], $translated) ? $translated[$translation['lang']]->name : ''), array('class' => 'form-control')) }}
                                @else
                                {{ Form::text($translation['lang'] . '_name', null, array('class' => 'form-control')) }}
                                @endif
                            </div>

                            <div class="form-group">
                                {{ Form::label($translation['lang'] . '_short_description', 'Summary') }}
                                @if ($translated)
                                {{ Form::text($translation['lang'] . '_short_description', (array_key_exists($translation['lang'], $translated) ? $translated[$translation['lang']]->short_description : ''), array('class' => 'form-control')) }}
                                @else
                                {{ Form::text($translation['lang'] . '_short_description', null, array('class' => 'form-control')) }}
                                @endif
                            </div>

                            <div class="form-group">
                                {{ Form::label($translation['lang'] . '_long_description', 'Description') }}
                                @if ($translated)
                                {{ Form::textarea($translation['lang'] . '_long_description', (array_key_exists($translation['lang'], $translated) ? $translated[$translation['lang']]->long_description : ''), array('class' => 'form-control')) }}
                                @else
                                {{ Form::textarea($translation['lang'] . '_long_description', null, array('class' => 'form-control')) }}
                                @endif
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
                @if(count($media->images) > 0)
                <h4>Uploaded Photos</h4>
                <div class='row'>
                    @foreach( $media->images as $image )
                    <div class='col-md-3'>
                        {{ HTML::image($imageUrl . $image->path, $media->name, array('class' => 'img-thumbnail', 'alt' => $image->path)) }}
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    {{ Form::close() }}
@stop

@section('footer')
    <script src="{{ URL::to('packages/redooor/redminportal/assets/js/bootstrap-fileupload.js') }}"></script>
    <script>
        !function ($) {
            $(function(){
                $('#lang-selector li').first().addClass('active');
                $('#lang-selector a').click(function (e) {
                    e.preventDefault();
                    $(this).tab('show');
                });
                // On load, check if previous category exists for error message
                function checkCategory() {
                    $selected_val = $('#category_id').val();
                    if ($selected_val != '') {
                        $('.redooor-hierarchy a').each(function() {
                            if ($(this).attr('href') == $selected_val) {
                                $(this).addClass('active');
                            }
                        });
                    }
                }
                checkCategory();
                // Change selected category
                $(document).on('click', '.redooor-hierarchy a', function(e) {
                    e.preventDefault();
                    $selected = $(this).attr('href');
                    $('#category_id').val($selected);
                    $('.redooor-hierarchy a.active').removeClass('active');
                    $(this).addClass('active');
                });
            })
        }(window.jQuery);
    </script>
    @include('redminportal::plugins/tinymce')
@stop