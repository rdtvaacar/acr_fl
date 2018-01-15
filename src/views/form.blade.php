<form id="fl_form_form" action="/acr/acr_fl/upload" class="fileinput-button" enctype="multipart/form-data" method="post">
    <div id="fl_loading" style="display: none" class="alert alert-danger">
        <div style="float: left"> Dosyalar yükleniyor lütfen bekleyiniz</div>
        <div class="loading" style="width: 24px; height: 24px; float: left;"></div>
        <div style="clear:both;"></div>
    </div>
    <div style="display: none;" id="fl_complete" class="alert alert-success">Dosyalar başarıyla yüklendi
        <div class="btn btn-xs btn-success" onclick="fl_new()">Yeni dosyalar Ekle</div>
    </div>
    <div id="fl_form">
        <input class="fileinput-button" multiple="multiple" name="files" type="file" id="files"/>
        <button class="btn btn-danger btn-sm" type="submit">KAYDET</button>
    </div>
    <div id="fl_yuklenenler"></div>
</form>