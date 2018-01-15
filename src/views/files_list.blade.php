<table class="table table-hover">
    <thead>
    <tr>
        <th></th>
        <th>ID</th>
        <th>İŞLEM</th>
        <th>İSİM</th>
        <th>BOYUT</th>

    </tr>
    </thead>
    <tbody>
    @foreach($files as $file)
        @if(in_array($file->mime,['image/jpeg', 'image/png', 'image/gif']))
            <?php $img = '/acr/fl/get_file/' . $acr_file_id . '/' . $file->file_name . '/thumbs'; ?>
        @else
            <?php $img = Acr_fl::onizleme($file->file_type); ?>
        @endif
        <tr id="fl_file_div_{{$file->id}}">
            <td><img style="width:36px;" class="img-thumbnail" src="{!! $img !!}"/></td>
            <td>{{$file->id}}</td>
            <td>
                <div onclick="fl_file_delete({{$file->id}})" style="font-size: 14pt; cursor: pointer; color:red;" class="glyphicon glyphicon-trash"></div>
            </td>
            <td>{{$file->file_name_org}}</td>
            <td>{{round($file->file_size / 100000,2)}} MB</td>
        </tr>
    @endforeach
    </tbody>
</table>