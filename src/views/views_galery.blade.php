<div id="links">
    @foreach($files as $file)
        <div id="fl_file_div_{{$file->id}}" style="position: relative; float: left; margin: 4px;">
            <?php $img = '/acr/fl/get_file/' . $acr_file_id . '/' . $file->file_name . '/thumbs';
            $img_zero = '/acr/fl/get_file/' . $acr_file_id . '/' . $file->file_name . '/zero';
            ?>
            @if(in_array($file->mime,['image/jpeg', 'image/png', 'image/gif']))
                @if (in_array($type,[1,4]))
                    <a href="{{$img_zero}}">
                        <img @if(!empty($w)) width="{{$w}}" @endif class="img-thumbnail" src="{!! $img !!}"/>
                    </a>
                @endif
            @elseif(in_array($file->file_type,['mp4','vma','mpg','flv','mov','avi']))
                @if (in_array($type,[2,4]))
                    <a href="{{$img_zero}}"
                       type="video/{{$file->file_type}}"
                       data-poster="/img/takla.png"
                       data-sources='[{"href": "{{$img}}"}]'>
                        <img @if(!empty($w)) width="{{$w}}" @else  width="180" @endif src="/img/takla.png"/>
                    </a>
                @endif
            @else
                @if (in_array($type,[3,4]))
                    <?php $img = Acr_fl::onizleme($file->file_type); ?>
                    <img @if(!empty($w)) width="{{$w}}" @endif class="img-thumbnail" src="{!! $img !!}"/>
                @endif
            @endif
        </div>
    @endforeach
</div>
<div id="blueimp-gallery" class="blueimp-gallery" data-start-slideshow="true" data-filter=":even">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
</div>