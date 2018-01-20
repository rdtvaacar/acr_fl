<div id="links">
    @foreach($files as $file)
        <div id="fl_file_div_{{$file->id}}" style="position: relative; float: left; margin: 4px;">
            <?php $img = '/acr/fl/get_file/' . $acr_file_id . '/' . $file->file_name . '/thumbs';
            $img_zero = '/acr/fl/get_file/' . $acr_file_id . '/' . $file->file_name . '/zero';
            ?>
            @if(in_array($file->mime,['image/jpeg', 'image/png', 'image/gif']))
                <a href="{{$img_zero}}">
                    <img class="img-thumbnail" src="{!! $img !!}"/>
                </a>
            @elseif(in_array($file->file_type,['mp4','vma','mpg','flv','mov','avi']))
                <a href="{{$img_zero}}"
                   type="video/{{$file->file_type}}"
                   data-poster="/img/takla.png"
                   data-sources='[{"href": "{{$img}}"}]'>
                    <img width="180" src="/img/takla.png"/>
                </a>
            @else
                <?php $img = Acr_fl::onizleme($file->file_type); ?>
                <img class="img-thumbnail" src="{!! $img !!}"/>
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