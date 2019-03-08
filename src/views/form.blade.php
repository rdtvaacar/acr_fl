<form action="/acr/acr_fl/upload" class="fileinput-button fl_form" enctype="multipart/form-data" method="post">
    <div id="fl_loading" style="display: none" class="alert alert-danger">
        <div style="float: left"> Dosyalar yükleniyor lütfen bekleyiniz</div>
        <div style="clear:both;"></div>
        <div class="progress">
            <div
                    class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                    role="progressbar" aria-valuemin="0" aria-valuemax="100">
            </div>
        </div>
    </div>
    <div style="display: none;" id="fl_complete" class="alert alert-success">Dosyalar başarıyla yüklendi
        <div class="btn btn-xs btn-success" onclick="fl_new()">Yeni dosyalar Ekle</div>
    </div>
    <div id="fl_form">
        <input style="float: left" class="fileinput-button " multiple="multiple" name="files" type="file" id="files"/>
        <button class="btn btn-danger btn-sm" type="submit">KAYDET</button>
    </div>
    <div style="clear:both;"></div>
    <div class="fl_yuklenenler"></div>
</form>