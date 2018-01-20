<div id="fl_file_div_{{@$file->id}}" style="position: relative; float: left; margin: 4px;">
    @if(in_array(@$file->mime,['image/jpeg', 'image/png', 'image/gif']))
        <?php $img = '/acr/fl/get_file/' . $acr_file_id . '/' . @$file->file_name . $loc; ?>
    @else
        <?php @$img = Acr_fl::onizleme($file->file_type); ?>
    @endif
    <img class="img-thumbnail" src="{!! @$img !!}"/>
</div>
