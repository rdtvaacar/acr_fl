<?php

namespace Acr\Acr_fl\Controllers;

use Acr\File\Model\Acr_files;
use Acr\File\Model\Acr_files_childs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Auth;
use Zipper;

class ImgControllers extends Controller
{

    function kaydet($acr_file_id, $session_id)
    {
        $fl_model = new Acr_files();
        $sayi     = $fl_model->where('id', $acr_file_id)->count();
        if ($sayi == 0) {
            if (!empty($acr_file_id)) {
                $data = [
                    'id' => $acr_file_id,
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

    function sil_childs($acr_child_file, $acr_file_id)
    {
        $cf_fl_model = new Acr_files_childs();
        $fl_model    = new Acr_files();
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
    }
}