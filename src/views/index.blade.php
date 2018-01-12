@extends('index')
@section('header')
    {!! Acr_fl::css() !!}

@stop
@section('title')
    <title>E-okul Çevrimiçi Toplu Resim Düzenleme Programı</title>
@stop
@section('acr_index')

    <div class="col-md-12">
        <div class="box box-primary">
            <div class="alert alert-info">Fotoğrafları <strong>"Dosyaları Seç"</strong> butonundan seçin ve yükleme işlemini bitirin ardından <strong>"Arşivle ve İndir" </strong> butonuna basarak bilgisayarrınıza kaydedin. Kaydetme
                <strong>ZİP
                    arşiv</strong> dosyası olarak gerçekleşir ve yüklediğiniz tüm dosyalar içindedir.
            </div>
            <!-- e-okul-resim -->
            <ins class="adsbygoogle"
                 style="display:block"
                 data-ad-client="ca-pub-9863574640622060"
                 data-ad-slot="4601085682"
                 data-ad-format="auto"></ins>
            <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
            <div style="text-align: right;">
                @if(empty($yan_kesim))
                    Resminizin yan olması durumunda kullanılır. Önce bu butona basın ve ardından resimlerinizi yükleyin. <a href="/?yan_kesim=1" class="btn btn-sm btn-danger">YAN KESİM AÇ</a>
                    <div style="clear:both;"></div>
                    <br>
                @else
                    <a href="/?yan_kesim=0" class="btn btn-sm btn-dark">YAN KESİM KAPAT</a>
                    <div style="clear:both;"></div>
                    <br>
                @endif
            </div>
            <div title="Yüklediğiniz resimleri ve belgeleri indirmek için tıklayınız.">
                Yüklediğiniz resimleri ve belgeleri indirmek için tıklayınız.
                <a href="/acr/acr_fl/arsivle/indir?acr_file_id={{$acr_file_id}}" style="float: right" class="btn btn-warning btn-lg">Arşivle ve İndir</a>
            </div>
            {!! Acr_fl::form() !!}
        </div>
    </div>
    <div class="col-md-12">
        <!-- e-okul esnek 2 -->
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-client="ca-pub-9863574640622060"
             data-ad-slot="5203064377"
             data-ad-format="auto"></ins>
        <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
    </div>
@stop
@section('footer')
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    {!! Acr_fl::js($acr_file_id,0,1,$yan_kesim) !!}
@stop