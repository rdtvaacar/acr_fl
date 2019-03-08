<?php

namespace Acr\Acr_fl\Controllers;

use Acr\Acr_fl\Models\Config;
use Acr\Acr_fl\Models\Acr_files_childs;
use Acr\Acr_fl\Models\Acr_files;
use Image;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Response;

class FlController extends Controller
{
    function files_data(Request $request, $acr_file_id = null)
    {
        $acr_files_model = new Acr_files_childs();
        $acr_file_id     = empty($acr_file_id) ? $request->acr_file_id : $acr_file_id;
        $files           = $acr_files_model->where('acr_file_id', $acr_file_id)->where('user_id', Auth::user()->id)->orderBy('id', 'desc')->get();
        return response()->json([
            'data' => $files,
            'code' => 200
        ]);
    }

    function delete_id(Request $request, $id = null)
    {
        $file_id     = empty($id) ? $request->file_id : $id;
        $child_model = new Acr_files_childs();
        $file        = $child_model->where('id', $file_id)->where('user_id', Auth::user()->id)->first();
        @unlink(storage_path('app/public/acr_files/' . $file->acr_file_id . '/' . $file->file_name));
        @unlink(storage_path('app/public/acr_files/' . $file->acr_file_id . '/thumbs/' . $file->file_name));
        @unlink(storage_path('app/public/acr_files/' . $file->acr_file_id . '/med/' . $file->file_name));
        $child_model->where('id', $file_id)->where('user_id', Auth::user()->id)->delete();
        return response()->json([
            'message' => 'Başarıyla Silindi',
            'code'    => 200
        ]);
    }

    function delete_all(Request $request)
    {
        $acr_file_id = $request->acr_file_id;
        $file_model  = new Acr_files();
        $child_model = new Acr_files_childs();
        $file_model->where('id', $acr_file_id)->delete();
        $files = $child_model->where('acr_file_id', $acr_file_id)->where('user_id', Auth::user()->id)->get();
        foreach ($files as $file) {
            @unlink(storage_path('app/public/acr_files/' . $file->acr_file_id . '/' . $file->file_name));
            @unlink(storage_path('app/public/acr_files/' . $file->acr_file_id . '/thumbs/' . $file->file_name));
            @unlink(storage_path('app/public/acr_files/' . $file->acr_file_id . '/med/' . $file->file_name));
        }
        $child_model->where('acr_file_id', $acr_file_id)->where('user_id', Auth::user()->id)->delete();
        return redirect()->back()->with('msg', $this->basarili());
    }

    function post_file_delete(Request $request)
    {
        $acr_file_id = $request->acr_file_id;
        $this->acr_file_delete($acr_file_id);
    }

    function file_uploader(Request $request)
    {
        $acr_file_id = $request->acr_file_id;
        $file        = $request->file('file');
        $this->acr_file_delete($acr_file_id);
        $file_create = $this->file_create($file, $acr_file_id);
        return $file_create[1];
    }

    function config_update(Request $request)
    {
        $config_model = new Config();
        $data         = [
            'thumbs_w'           => $request->thumbs_w,
            'thumbs_h'           => $request->thumbs_h,
            'med_w'              => $request->med_w,
            'med_h'              => $request->med_h,
            'orginal_w'          => $request->orginal_w,
            'orginal_h'          => $request->orginal_h,
            'crop_x'             => $request->crop_x,
            'crop_y'             => $request->crop_y,
            'crop_position'      => $request->crop_position,
            'crop'               => $request->crop,
            'watermark'          => $request->watermark,
            'watermark_position' => $request->watermark_position,
            'font_size'          => $request->font_size,
            'color'              => $request->color,
        ];
        $config_model->where('id', 1)->update($data);
        return redirect()->back();
    }

    function config()
    {
        $config_model = new Config();
        $config       = $config_model->first();
        return view('Acr_flv::config', compact('config'));
    }

    function views_image($acr_file_id, $file, $loc = '')
    {
        $loc = empty($loc) ? '/zero/' : '/' . $loc . '/';
        return view('Acr_flv::views_image', compact('file', 'acr_file_id', 'loc'));
    }

    function views_galery($acr_file_id, $files = null, $w = null, $type = null) // w = thumb width type = 1 =>yalnızca resimler  2 video ve resimler 3 yalnıca dosyalar 4 =>hepsi
    {
        $acr_files_model = new Acr_files_childs();
        $type            = empty($type) ? 4 : $type;
        if (empty($files)) {
            $files = $acr_files_model->where('acr_file_id', $acr_file_id)->get();
        }
        return view('Acr_flv::views_galery', compact('files', 'acr_file_id', 'w', 'type'));
    }

    function views_list($acr_file_id, $files = null)
    {
        $acr_files_model = new Acr_files_childs();
        if (empty($files)) {
            $files = $acr_files_model->where('acr_file_id', $acr_file_id)->get();
        }
        return view('Acr_flv::views_list', compact('files', 'acr_file_id', 'type'));
    }

    function dont_img_list($acr_file_id, $files = null)
    {
        $acr_files_model = new Acr_files_childs();
        if (empty($files)) {
            $files = $acr_files_model->where('acr_file_id', $acr_file_id)->whereNotIn('mime', [
                'image/jpg',
                'image/gif',
                'image/png',
                'image/web',
                'image/svg+xml',
                'image/jpeg'
            ])->get();
        }
        return view('Acr_flv::dont_img_list', compact('files', 'acr_file_id', 'type'));
    }

    function files_galery($acr_file_id, $files = null)
    {
        $acr_files_model = new Acr_files_childs();
        if (empty($files)) {
            $files = $acr_files_model->where('acr_file_id', $acr_file_id)->get();
        }
        return view('Acr_flv::files_galery', compact('files', 'acr_file_id'));
    }

    function files_list($acr_file_id, $files = null)
    {
        $acr_files_model = new Acr_files_childs();
        if (empty($files)) {
            $files = $acr_files_model->where('acr_file_id', $acr_file_id)->orderBy('id', 'desc')->paginate(10);
        }
        return view('Acr_flv::files_list', compact('files', 'acr_file_id'));
    }

    function onizleme($type)
    {
        $type = strtolower($type);
        switch ($type) {
            case 'doc':
            case 'docx':
                $onizleme = '/icon/doc_2.png';
                break;
            case 'xls':
            case 'xlsx':
                $onizleme = '/icon/excel_2.png';
                break;
            case 'pdf':
                $onizleme = '/icon/pdf_2.png';
                break;
            case 'mpg':
            case 'mp4':
            case 'm4a':
            case 'ram':
            case 'avi':
            case 'asf':
            case 'wma':
            case 'wmv':
            case 'wav':
            case 'mid':
            case 'ogg':
            case 'flv':
                $onizleme = '/icon/ClapBoard.png';
                break;
            case 'rar':
            case 'zip':
            case '7z':
                $onizleme = '/icon/rar.png';
                break;
            case 'pptx':
            case 'pps':
            case 'ppt':
                $onizleme = '/icon/ppt_2.png';
                break;
            case 'mp3':
                $onizleme = '/icon/mp3.png';
                break;
        }
        return @$onizleme;
    }

    function file_delete(Request $request)
    {
        $acr_file_model = new Acr_files();
        $file_model     = new Acr_files_childs();
        $file           = $file_model->where('id', $request->id)->first();
        $acr_file_model->where('id', $file->acr_file_id)->delete();
        @unlink(storage_path('app/public/acr_files/' . $file->acr_file_id . '/' . $file->file_name));
        @unlink(storage_path('app/public/acr_files/' . $file->acr_file_id . '/thumbs/' . $file->file_name));
        @unlink(storage_path('app/public/acr_files/' . $file->acr_file_id . '/med/' . $file->file_name));
        $file_model->where('id', $request->id)->where('user_id', Auth::user()->id)->delete();
    }

    function get_file($acr_file_id, $file_name, $loc = '')
    {
        $loc = $loc == 'zero' ? '' : $loc . '/';

        $path = storage_path('app/public/acr_files/' . $acr_file_id . '/' . $loc . $file_name);
        return response()->file($path);
    }


    function file_header(Request $request, $file_name = null)
    {
        $acr_files_model = new Acr_files_childs();
        if (empty($file_name)) {
            $file_id = $request->file_id;
            $file    = $acr_files_model->where('id', $file_id)->first();
            $name    = $file->file_name;
        } else {
            $exp  = explode('/', $file_name);
            $file = $acr_files_model->where('file_name', $file_name)->where('acr_file_id', $exp[1])->first();
            $name = $file->file_name;
        }
        $path = storage_path('app/public/' . $file->acr_file_id . '/' . $name);
        return response()->file($path);
        return $response;
    }

    function acr_file_delete($acr_file_id)
    {
        $file_model     = new Acr_files_childs();
        $acr_file_model = new Acr_files();
        $acr_file_model->where('id', $acr_file_id)->delete();
        $file = $file_model->where('acr_file_id', $acr_file_id)->first();
        @unlink(storage_path('app/public/acr_files/' . $acr_file_id . '/' . $file->file_name));
        @unlink(storage_path('app/public/acr_files/' . $acr_file_id . '/thumbs/' . $file->file_name));
        @unlink(storage_path('app/public/acr_files/' . $acr_file_id . '/med/' . $file->file_name));
        $file_model->where('acr_file_id', $acr_file_id)->where('user_id', Auth::user()->id)->delete();
    }

    function download(Request $request)
    {
        if (session()->token() == $request->token) {
            $acr_files_model = new Acr_files_childs();
            $file_id         = $request->file_id;
            $file            = $acr_files_model->where('id', $file_id)->first();
            $name            = $file->file_name;
            $indirme         = $file->indirme + 1;
            $acr_files_model->where('id', $file_id)->update(['indirme' => $indirme]);
            return response()->download(storage_path('app/public/acr_files/' . $file->acr_file_id . '/' . $name));
        }
    }

    function acr_file_id()
    {
        $file_model = new Acr_files();
        return $file_model->insertGetId(['updated_at' => date('Y-m-d')]);
    }

    function upload(Request $request, $name_random = false)
    {
        $files       = $request->allFiles();
        $acr_file_id = $request->acr_file_id;
        $file_names  = [];
        foreach ($files as $file) {
            $file_create                    = self::file_create($file, $acr_file_id, $name_random);
            $file_names[$file_create[0]]    = $file_create[1];
            $file_name_org[$file_create[0]] = $file_create[2];
        }
        $ups = [];
        foreach ($file_names as $key => $file_name) {
            $ups[] = [
                'name_org' => $file_name_org[$key],
                'name'     => $file_name,
                'thumb'    => 'http://' . $request->getHost() . '/acr/fl/get_file/' . $acr_file_id . '/' . $file_name . '/thumbs',
                'zero'     => 'http://' . $request->getHost() . '/acr/fl/get_file/' . $acr_file_id . '/' . $file_name . '/zero',
                'id'       => $key
            ];

        }

        return response()->json([
            'data' => $ups,
            'code' => 200
        ]);

    }


    function css()
    {
        return view('Acr_flv::css')->render();
    }

    function form()
    {
        return view('Acr_flv::form')->render();
    }

    function galery_css()
    {
        return view('Acr_flv::galery_css')->render();
    }

    function galery_js()
    {
        return view('Acr_flv::galery_js')->render();
    }

    function form_one()
    {
        return view('Acr_flv::form_one')->render();
    }

    function js($data)
    {
        return view('Acr_flv::js', compact('data'))->render();
    }

    function file_create($file, $acr_file_id, $name_random = false, $fl_id = null, $fl_path = null, $fl_thumbs = null, $fl_med = null)
    {
        if (!empty($fl_id)) {
            $old_file = Acr_files_childs::where('id', $fl_id)->first();
            @unlink("$fl_path$acr_file_id/$fl_thumbs/$old_file->file_name.$old_file->file_type");
            @unlink("$fl_path$acr_file_id/$fl_med/$old_file->file_name.$old_file->file_type");
            @unlink("$fl_path$acr_file_id/$old_file->file_name.$old_file->file_type");
        }
        $config_model = new Config();
        $config       = $config_model->first();
        if (!empty($file)) {
            $mime = $file->getMimeType();
            if (!in_array($mime, self::allow_type())) {
                return $mime;
            }
            $acr_files_model = new Acr_files_childs();
            $file_name_dot   = $file->getClientOriginalName();
            $dot             = $file->getClientOriginalExtension();
            $file_name_org   = str_replace('.' . $dot, '', $file_name_dot);
            $file_name       = $name_random ? uniqid(rand(100000, 999999)) : self::ingilizceYap($file_name_org);
            $file_size       = $file->getClientSize();
            if (file_exists(storage_path() . '/app/public/acr_files/' . $acr_file_id . '/' . $file_name_dot)) {
                $file_name = $file_name . '_' . uniqid(rand(100000, 999999));
            }
            $path   = empty($fl_path) ? storage_path() . '/app/public/acr_files/' . $acr_file_id . '/' : $fl_path;
            $thumbs = empty($fl_thumbs) ? $path . 'thumbs/' : $path . $fl_thumbs;
            $med    = empty($fl_med) ? $path . 'med/' : $path . $fl_med;
            if (!is_dir(storage_path() . '/app/public/acr_files/')) {
                mkdir(storage_path() . '/app/public/acr_files/');
            }
            if (!is_dir($path)) {
                mkdir($path);
            }
            if (!is_dir($thumbs)) {
                mkdir($thumbs);

            }
            if (!is_dir($med)) {
                mkdir($med);

            }
            $img_mimes = [
                'image/jpeg',
                'image/png',
                'image/gif'
            ];
            if (in_array($mime, $img_mimes)) {

                $img_thumbs = Image::make($file)->resize($config->thumbs_w, $config->thumbs_h, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $img_med    = Image::make($file)->resize($config->med_w, $config->med_h, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $img        = Image::make($file)->resize($config->orginal_w, $config->orginal_h, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                if (!empty($config->watermark)) {
                    foreach ([
                                 $img,
                                 $img_med
                             ] as $item) {
                        $i_w       = $item->width();
                        $i_h       = $item->height();
                        $font_size = $config->font_size;
                        $text      = $config->watermark;
                        $color     = $config->color;
                        $crop_x    = floor(($i_w / 100) * $config->crop_x);
                        $crop_y    = floor(($i_h / 100) * $config->crop_y);
                        $crop_x    = $crop_x == 0 ? 0 : -$crop_x;
                        $crop_y    = $crop_y == 0 ? 0 : -$crop_y;
                        if ($config->crop == 1) {
                            $item->resizeCanvas($crop_x, $crop_y, $config->crop_position, true);
                        }

                        switch ($config->watermark_position) {
                            case 0;
                                $ix  = ($i_w / 2) + ($font_size * 4.8);
                                $i_h = ($i_w / 2) - 12;
                                break;
                            case 1;
                                $ix  = 20 + ($font_size * 4.8);
                                $i_h = 10 + $font_size;
                                break;
                            case 2;
                                $ix  = ($i_w / 100) * 99 - ($font_size);
                                $i_h = 10 + $font_size;
                                break;
                            case 3;
                                $ix  = ($i_w / 100) * 90 - ($font_size);
                                $i_h = ($i_h / 100) * 99;
                                break;
                            case 4;
                                $ix  = 20 + ($font_size * 4.8);
                                $i_h = ($i_h / 100) * 99;
                                break;
                        }
                        $item->text($text, $ix, $i_h, function ($font) use ($font_size, $color) {
                            $font->file(base_path('/public_html') . '/acr/fl/Fonts/Righteous.ttf');
                            $font->size($font_size);
                            $font->color($color);
                            $font->align('right');
                            $font->valign('bottom');
                            $font->angle(0);
                        });
                    }
                }


                $img_thumbs->save($thumbs . $file_name . '.' . $dot);;
                $img_med->save($med . $file_name . '.' . $dot);
                $img->save($path . $file_name . '.' . $dot);
                $file_name = $file_name;

            } else {
                if (empty($fl_id)) {
                    $file_name = self::store_upload($file, $acr_file_id);
                    $file_name = str_replace("acr_files/$acr_file_id/", '', $file_name);
                } else {
                    move_uploaded_file($file->getPathname(), "$path$acr_file_id/$file_name.$dot");
                }


            }
            $data_file = [
                'acr_file_id'   => $acr_file_id,
                'user_id'       => Auth::user()->id,
                'file_name_org' => $file_name_org,
                'file_name'     => "$file_name.$dot",
                'file_size'     => $file_size,
                'file_type'     => $dot,
                'mime'          => $mime
            ];
            if (empty($fl_id)) {
                $id = $acr_files_model->insertGetId($data_file);

            } else {
                $acr_files_model->where('id', $fl_id)->update($data_file);
                $id = $fl_id;
            }
        }
        return [
            $id,
            $file_name,
            $file_name_org
        ];
    }

    function store_upload($file, $acr_file_id)
    {
        return $file->store('acr_files/' . $acr_file_id, 'public');
    }

    function allow_type()
    {
        $data_type = [
            "application/excel",
            "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
            "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
            "application/vnd.openxmlformats-officedocument.presentationml.presentation",
            "application/vnd.ms-powerpoint",
            "application/msword",
            "application/pdf",
            "application/vnd.ms-excel",
            "application/x-gtar",
            "application/x-gunzip",
            "application/x-gzip",
            "application/x-zip-compressed",
            "application/zip",
            "audio/TSP-audio",
            "audio/basic",
            "audio/basic",
            "audio/midi",
            "audio/mpeg",
            "audio/ulaw",
            "audio/x-aiff",
            "audio/x-mpegurl",
            "audio/x-ms-wax",
            "audio/x-ms-wma",
            "audio/x-pn-realaudio-plugin",
            "audio/x-pn-realaudio",
            "audio/x-realaudio",
            "audio/x-wav",
            "image/cmu-raster",
            "image/gif",
            "image/ief",
            "image/tiff",
            "image/jpeg",
            "image/png",
            "image/x-cmu-raster",
            "image/svg+xml",
            "image/x-portable-anymap",
            "image/x-portable-bitmap",
            "image/x-portable-graymap",
            "image/x-portable-pixmap",
            "image/x-rgb",
            "image/x-xbitmap",
            "image/x-xwindowdump",
            "video/dl",
            "video/fli",
            "video/flv",
            "video/gl",
            "video/mpeg",
            "video/mp4",
            "video/mpeg",
            "video/quicktime",
            "video/vnd.vivo",
            "video/x-fli",
            "video/x-ms-asf",
            "video/x-ms-asx",
            "video/x-ms-wmv",
            "video/x-msvideo",
            "video/x-sgi-movie"
        ];
        return $data_type;
    }

    function ingilizceYap($url)
    {
        $url  = trim($url);
        $url  = strtolower($url);
        $find = array(
            '<b>',
            '</b>'
        );
        $url  = str_replace($find, '', $url);
        $url  = preg_replace('/<(\/{0,1})img(.*?)(\/{0,1})\>/', 'image', $url);

        $find = array(
            ' ',
            '&quot;',
            '&amp;',
            '&',
            '\r\n',
            '\n',
            '/',
            '\\',
            '+',
            '<',
            '>'
        );
        $url  = str_replace($find, '-', $url);

        $find = array(
            'é',
            'è',
            'ë',
            'ê',
            'É',
            'È',
            'Ë',
            'Ê'
        );
        $url  = str_replace($find, 'e', $url);

        $find = array(
            'í',
            'ı',
            'ì',
            'î',
            'ï',
            'I',
            'İ',
            'Í',
            'Ì',
            'Î',
            'Ï',
            'İ'
        );
        $url  = str_replace($find, 'i', $url);

        $find = array(
            'ó',
            'ö',
            'Ö',
            'ò',
            'ô',
            'Ó',
            'Ò',
            'Ô'
        );
        $url  = str_replace($find, 'o', $url);

        $find = array(
            'á',
            'ä',
            'â',
            'à',
            'â',
            'Ä',
            'Â',
            'Á',
            'À',
            'Â'
        );
        $url  = str_replace($find, 'a', $url);

        $find = array(
            'ú',
            'ü',
            'Ü',
            'ù',
            'û',
            'Ú',
            'Ù',
            'Û'
        );
        $url  = str_replace($find, 'u', $url);

        $find = array(
            'ç',
            'Ç'
        );
        $url  = str_replace($find, 'c', $url);

        $find = array(
            'ş',
            'Ş'
        );
        $url  = str_replace($find, 's', $url);

        $find = array(
            'ğ',
            'Ğ'
        );
        $url  = str_replace($find, 'g', $url);

        $find = array(
            '/[^a-z0-9\-<>]/',
            '/[\-]+/',
            '/<[^>]*>/'
        );
        $repl = array(
            '',
            '-',
            ''
        );

        $url = preg_replace($find, $repl, $url);
        $url = str_replace('--', '-', $url);

        return $url;
    }

    function kaydet($acr_file_id, $session_id)
    {
        $fl_model = new Acr_files();
        $sayi     = $fl_model->where('id', $acr_file_id)->count();
        if ($sayi == 0) {
            if (!empty($acr_file_id)) {
                $data = [
                    'id'        => $acr_file_id,
                    'parent_id' => $session_id,
                ];
            } else {
                $data = ['parent_id' => $session_id];
            }

            return $fl_model->insertGetId($data);
        } else {
            return $acr_file_id;
        }
    }

    function acr_file_session($acr_file_id)
    {
        $fl_model = new Acr_files();

        @$session_id = $fl_model->find($acr_file_id)->session_id;
        if ($session_id) {
            return $session_id;
        } else
            return null;
    }

    function child_fields_create($data)
    {
        $cf_fl_model = new Acr_files_childs();
        $cf_fl_model->insert($data);
    }

    /* function sil_childs($acr_child_file, $acr_file_id)
     {
         $cf_fl_model            = new Acr_files_childs();
         $fl_model               = new Acr_files();
         $acr_files_childs_sorgu = $cf_fl_model->where('file_name', $acr_child_file)->where('acr_file_id', $acr_file_id);
         $sil                    = $acr_files_childs_sorgu->delete();
         if ($cf_fl_model->where('acr_file_id', $acr_file_id)->count() == 0) {
             $fl_model->where('id', $acr_file_id)->delete();
         }
         if ($sil) {
             return 1;
         } else {
             return 0;
         }
     }*/
}