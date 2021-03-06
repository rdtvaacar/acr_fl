@if(count($files)>0)
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
            <?php  $get_file = '/acr/fl/get_file/' . $acr_file_id . '/' . $file->file_name . '/zero'; ?>
            <tr id="fl_file_div_{{$file->id}}">
                <td><a href=""><img style="width:36px;" class="img-thumbnail" src="{!! $img !!}"/></a></td>
                <td>{{$file->id}}</td>
                <td>
                    <div onclick="fl_file_delete({{$file->id}})" style="font-size: 14pt; cursor: pointer; color:red;" class="glyphicon glyphicon-trash fas fa-trash-alt"></div>
                </td>
                <td><a target="_blank" href="{{$get_file}}"> {{$file->file_name_org}}</a></td>
                <td>{{round($file->file_size / 100000,2)}} MB</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <td colspan="5" style="text-align: center;">{{$files->links()}}</td>
        </tr>
        <tr>
            <td colspan="5" style="text-align: right;">
                <a class="btn btn-xs btn-danger" href="/acr/fl/delete/all?acr_file_id={{$acr_file_id}}">Tüm Dosyaları Sil</a>
            </td>
        </tr>
        </tfoot>
    </table>
@endif
