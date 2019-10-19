<div class="{{$viewClass['form-group']}} {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">
    <label for="{{$id}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>
    <div class="{{$viewClass['field']}}">
        @include('admin::form.error')
        <button type="button" class="btn btn-success" style="cursor: pointer;" id="{{$column}}_upload" data-warp='#{{$column}}_upload_warp'>上传图片</button>
        @if(($column_val = old($column, $value)))
            <?php
                $column_val = is_array($column_val) ? $column_val : [];
            ?>
            <div class="upload_warp" id="{{$column}}_upload_warp" style="opacity: 1; display: block;">
                @foreach($column_val as $upload_id => $upload_url)
                <div class="upload_item">
                    <span class="upload_del_btn" data-filename="{{$upload_url}}" onclick="del_pic(this,true)">删除</span>
                    <img src="{{$upload_url}}?x-oss-process=image/resize,m_fill,w_100,h_100">
                    <input type="hidden" class="Js_upload_input" name="{{$column}}[{{$upload_id}}]" value="{{$upload_url}}">
                </div>
                @endforeach
            </div>
        @else
            <div class="upload_warp" id="{{$column}}_upload_warp">
            </div>
        @endif
        @include('admin::form.help-block')
    </div>
</div>
