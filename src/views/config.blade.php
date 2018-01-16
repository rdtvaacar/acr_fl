@extends('index')
@section('acr_index')
    <form action="/acr/fl/config/update" method="post">
        {{csrf_field()}}
        <table class="table table-hover">
            <tr>
                <td>Thumbs Width</td>
                <td><input class="form-control" name="thumbs_w" id="thumbs_w" value="{{$config->thumbs_w}}"/></td>
                <td>Thumbs Height</td>
                <td><input class="form-control" name="thumbs_h" id="thumbs_h" value="{{$config->thumbs_h}}"/></td>

                <td>Medium Width</td>
                <td><input class="form-control" name="med_w" id="med_w" value="{{$config->med_w}}"/></td>
                <td>Medium Height</td>
                <td><input class="form-control" name="med_h" id="med_h" value="{{$config->med_h}}"/></td>
                <td>Orginal Width</td>
                <td><input class="form-control" name="orginal_w" id="orginal_w" value="{{$config->orginal_w}}"/></td>
            </tr>
            <tr>
                <td>Cut X</td>
                <td><input class="form-control" name="crop_x" id="crop_x" value="{{$config->crop_x}}"/></td>
                <td>Cut Y</td>
                <td><input class="form-control" name="crop_y" id="crop_y" value="{{$config->crop_y}}"/></td>
                <td>Cut Position</td>
                <td>
                    <?php $pos = [
                        'top-left',
                        'top',
                        'top-right',
                        'left',
                        'center',
                        'right',
                        'bottom-left',
                        'bottom',
                        'bottom-right'
                    ] ?>
                    <select class="form-control" name="crop_position" id="crop_position">
                        <?php $sel = ['ORTA', 'SOL ÜST', 'SAĞ ÜST', 'ALT SAĞ', 'ALT SOL'] ?>
                        @foreach($pos as $po)
                            <option {{$po == $config->crop_position?'selected':''}} value="{{$po}}">{{$po}}</option>
                        @endforeach
                    </select>
                <td>Cut Open</td>
                <td><input class="form-control" name="crop" id="crop" placeholder="1 crop 2 dont crop" value="{{$config->crop}}"/></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Orginal Height</td>
                <td><input class="form-control" name="orginal_h" id="orginal_h" value="{{$config->orginal_h}}"/></td>
                <td>Water Mark</td>
                <td><input class="form-control" name="watermark" id="watermark" value="{{$config->watermark}}"/></td>
                <td>Water Font Size</td>
                <td><input class="form-control" name="font_size" id="font_size" value="{{$config->font_size}}"/></td>
                <td>Water Mark Position</td>
                <td>
                    <select class="form-control" name="watermark_position" id="watermark_position">
                        <?php $sel = ['ORTA', 'SOL ÜST', 'SAĞ ÜST', 'ALT SAĞ', 'ALT SOL'] ?>
                        @for($i =0; $i<5;$i++)
                            <option {{$i == $config->watermark_position?'selected':''}} value="{{$i}}">{{$sel[$i]}}</option>
                        @endfor
                    </select>
                </td>
                <td>Water Font Color</td>
                <td><input class="form-control" name="color" id="color" value="{{$config->color}}"/></td>
            </tr>
        </table>
        <br>
        <button class="btn btn-block btn-primary btn-lg">DEĞİŞİKLİKLERİ KAYDET</button>
    </form>
@stop