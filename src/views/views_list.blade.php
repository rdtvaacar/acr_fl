<table class="table table-hover">
    <thead>
    <tr>
        <th></th>
        <th>ID</th>
        <th>İNDİR</th>
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
                <a class="btn btn-info btn-sm" href="/acr/fl/download?token={{csrf_token()}}&file_id={{$file->id}}">İNDİR</a>
            </td>
            <td>{{$file->file_name_org}}</td>
            <td>{{round($file->file_size / 100000,2)}} MB</td>
        </tr>
    @endforeach
    </tbody>
</table>