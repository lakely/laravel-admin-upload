<div class="{{$viewClass['form-group']}} {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">
    <label for="{{$id}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>
    <div class="{{$viewClass['field']}}">
        @include('admin::form.error')
        @if($old_val = old($name, $value))
            <div class="upload_add_btn Js_upload_warp">
                <img data-filename="{{$old_val}}" src="{{$old_val}}?x-oss-process=image/resize,m_fill,w_100,h_100">
                <div class="upload_model" onclick="del_pic(this,false)">删除</div>
                <div class="upload_add_img" id="{{$id}}_upload" style="position: relative; z-index: 1; display: none;">+</div>
                <input type="hidden" class="Js_upload_input" name="{{$name}}" value="{{$old_val}}">
            </div>
        @else
            <div class="upload_add_btn Js_upload_warp">
                <div class="upload_model" onclick="del_pic(this,false)">删除</div>
                <div class="upload_add_img" id="{{$id}}_upload">+</div>
                <input type="hidden" class="Js_upload_input" name="{{$name}}" value="">
            </div>
        @endif
        @include('admin::form.help-block')
    </div>
</div>
